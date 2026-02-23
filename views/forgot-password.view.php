<?php $validacoes = flash()->get('validacoes_forgot_password') ?: []; ?>

<div class="grid grid-cols-2">
    <div class="hero min-h-screen flex ml-40">
        <div class="hero-content -mt-20">
            <div>
                <p class="py-2 text-xl">Recuperação de acesso</p>
                <h1 class="text-6xl font-bold">Organize</h1>
                <p class="py-2 pb-4 text-xl">Informe seu e-mail para receber o link de redefinição de senha.</p>
            </div>
        </div>
    </div>

    <div class="bg-white hero mr-40 min-h-screen text-black">
        <div class="hero-content -mt-20">
            <form method="POST" action="/forgot-password">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title text-xl">Esqueci minha senha</div>

                        <?php require base_path('views/partials/_mensagem.view.php'); ?>

                        <label class="form-control">
                            <div class="label"><span class="label-text text-black">E-mail cadastrado</span></div>
                            <input type="email" name="email" class="input input-primary w-full max-w-xs bg-white" value="<?= old('email') ?>" />
                            <?php if (isset($validacoes['email'])) { ?>
                                <div class="mt-1 text-xs text-error"><?= $validacoes['email'][0] ?></div>
                            <?php } ?>
                        </label>

                        <div class="card-actions">
                            <button class="btn btn-primary btn-block">Enviar link de recuperação</button>
                            <a href="/login" class="btn btn-link">Voltar para login</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>