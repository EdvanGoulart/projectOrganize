<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Mail\PasswordResetMailer;
use App\Models\User;
use Core\Database;
use Core\Validacao;

class ForgotPasswordController
{
    public function requestForm()
    {
        return view('forgot-password', template: 'guest');
    }

    public function sendEmail()
    {
        $validacao = Validacao::validar([
            'email' => ['required', 'email'],
        ], request()->all());

        if ($validacao->naoPassou('forgot_password')) {
            return view('forgot-password', template: 'guest');
        }

        $email = request()->post('email');
        $database = new Database(config('database'));

        $user = $database->query(
            query: 'select * from user where email = :email',
            class: User::class,
            params: ['email' => $email]
        )->fetch();

        if ($user) {
            $token = $this->generateResetToken($email);
            $resetLink = $this->buildResetLink($token);

            $resultado = (new PasswordResetMailer())->send($email, $user->name, $resetLink);

            //Debug caso de erro ao enviar email
            // echo '<pre>';
            // var_dump($resultado);
            // echo '</pre>';
            // die();

            if (! $resultado['success'] && $this->isLocalDebugEnabled()) {
                flash()->push('mensagem', '⚠️ Falha SMTP local: ' . ($resultado['error'] ?? 'erro desconhecido') . ' | Use o link temporário: ' . $resetLink);

                return redirect('/forgot-password');
            }
        }

        flash()->push('mensagem', 'Se o e-mail existir, enviamos um link para redefinição de senha.');

        return redirect('/forgot-password');
    }

    public function resetForm()
    {
        $token = (string) request()->get('token', '');

        if (! $this->isValidToken($token)) {
            flash()->push('mensagem', 'Link inválido ou expirado. Solicite um novo e-mail.');

            return redirect('/forgot-password');
        }

        return view('reset-password', ['token' => $token], 'guest');
    }

    public function updatePassword()
    {
        $token = (string) request()->post('token', '');

        if (! $this->isValidToken($token)) {
            flash()->push('mensagem', 'Link inválido ou expirado. Solicite um novo e-mail.');

            return redirect('/forgot-password');
        }

        $validacao = Validacao::validar([
            'password' => ['required', 'confirmed', 'strong'],
        ], request()->all());

        if ($validacao->naoPassou('reset_password')) {
            return view('reset-password', ['token' => $token], 'guest');
        }

        $email = $this->extractEmailFromToken($token);

        if (! $email) {
            flash()->push('mensagem', 'Não foi possível validar o e-mail do token.');

            return redirect('/forgot-password');
        }

        $database = new Database(config('database'));

        $database->query(
            query: 'update user set password = :password where email = :email',
            params: [
                'password' => password_hash(request()->post('password'), PASSWORD_DEFAULT),
                'email' => $email,
            ]
        );

        flash()->push('mensagem', 'Senha redefinida com sucesso. Faça login novamente.');

        return redirect('/login');
    }

    private function generateResetToken(string $email): string
    {
        $payload = [
            'email' => $email,
            'exp' => time() + (60 * 30),
        ];

        $encodedPayload = base64_encode(json_encode($payload));
        $signature = hash_hmac('sha256', $encodedPayload, $this->tokenSecret());

        return $encodedPayload . '.' . $signature;
    }

    private function isValidToken(string $token): bool
    {
        $tokenParts = explode('.', $token);

        if (count($tokenParts) !== 2) {
            return false;
        }

        [$encodedPayload, $signature] = $tokenParts;

        $expectedSignature = hash_hmac('sha256', $encodedPayload, $this->tokenSecret());

        if (! hash_equals($expectedSignature, $signature)) {
            return false;
        }

        $payload = json_decode((string) base64_decode($encodedPayload), true);

        if (! is_array($payload)) {
            return false;
        }

        if (! isset($payload['email'], $payload['exp'])) {
            return false;
        }

        return (int) $payload['exp'] >= time();
    }

    private function extractEmailFromToken(string $token): ?string
    {
        if (! $this->isValidToken($token)) {
            return null;
        }

        [$encodedPayload] = explode('.', $token);
        $payload = json_decode((string) base64_decode($encodedPayload), true);

        if (! is_array($payload) || ! isset($payload['email'])) {
            return null;
        }

        return (string) $payload['email'];
    }

    private function isLocalDebugEnabled(): bool
    {
        return env('APP_ENV', 'production') === 'local'
            && env('MAIL_DEBUG_SHOW_RESET_LINK', '0') === '1';
    }

    private function tokenSecret(): string
    {
        return (string) env('PASSWORD_RESET_SECRET', (string) config('security.first_key'));
    }

    private function buildResetLink(string $token): string
    {
        $scheme = ! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        return sprintf('%s://%s/reset-password?token=%s', $scheme, $host, urlencode($token));
    }
}
