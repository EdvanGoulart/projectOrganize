<?php

declare(strict_types=1);

namespace App\Mail;


use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class PasswordResetMailer
{
    public function send(string $toEmail, string $toName, string $resetLink): array
    {
        if (! class_exists(PHPMailer::class)) {
            return [
                'success' => false,
                'error' => 'PHPMailer não encontrado. Execute: composer require phpmailer/phpmailer:^6.9',
            ];
        }

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = (string) config('mail.smtp_host');
            $mail->Port = (int) config('mail.smtp_port');
            $mail->SMTPAuth = true;
            $mail->Username = (string) config('mail.smtp_user');
            $mail->Password = (string) config('mail.smtp_pass');
            $mail->CharSet = 'UTF-8';


            $secure = strtolower((string) config('mail.smtp_secure'));
            // echo '<pre>';
            // var_dump(config('mail.from_email'));
            // echo '</pre>';
            // die();
            if ($secure === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif ($secure === 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            $mail->setFrom((string) config('mail.from_email'), (string) config('mail.from_name'));
            $mail->addAddress($toEmail, $toName);


            $mail->isHTML(true);
            $mail->Subject = 'Redefinição de senha - Organize';
            $mail->Body = $this->buildMessage($toName, $resetLink);
            $mail->AltBody = "Recebemos uma solicitação para redefinir sua senha.\n\nUse este link: {$resetLink}\n\nEsse link expira em 30 minutos.";

            // Somente para debugar
            // $mail->SMTPDebug = 2;
            // $mail->Debugoutput = 'html';

            $mail->send();

            return ['success' => true, 'error' => null];
        } catch (Exception $exception) {
            return [
                'success' => false,
                'error' => $exception->getMessage(),
            ];
        }
    }

    private function buildMessage(string $toName, string $resetLink): string
    {
        $safeName = htmlspecialchars($toName, ENT_QUOTES, 'UTF-8');
        $safeLink = htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8');

        return "
            <html>
                <body style=\"font-family: Arial, sans-serif; color: #111\">
                    <h2>Olá, {$safeName}!</h2>
                    <p>Recebemos uma solicitação para redefinir sua senha.</p>
                    <p>
                        <a href=\"{$safeLink}\" style=\"display:inline-block;padding:10px 16px;background:#4f46e5;color:#fff;text-decoration:none;border-radius:6px;\">Redefinir senha</a>
                    </p>
                    <p>Se o botão não funcionar, copie e cole no navegador:</p>
                    <p>{$safeLink}</p>
                    <p>Esse link expira em 30 minutos.</p>
                </body>
            </html>
        ";
    }
}
