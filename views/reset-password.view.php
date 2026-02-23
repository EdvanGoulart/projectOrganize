<?php $validacoes = flash()->get('validacoes_reset_password') ?: []; ?>

<div class="grid grid-cols-2">
    <div class="hero min-h-screen flex ml-40">
        <div class="hero-content -mt-20">
            <div>
                <p class="py-2 text-xl">Quase lá</p>
                <h1 class="text-6xl font-bold">Organize</h1>
                <p class="py-2 pb-4 text-xl">Defina sua nova senha para voltar a acessar sua conta.</p>
            </div>
        </div>
    </div>

    <div class="bg-white hero mr-40 min-h-screen text-black">
        <div class="hero-content -mt-20">
            <form method="POST" action="/reset-password">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>" />

                <div class="card">
                    <div class="card-body">
                        <div class="card-title text-xl">Redefinir senha</div>

                        <?php require base_path('views/partials/_mensagem.view.php'); ?>

                        <label class="form-control">
                            <div class="label"><span class="label-text text-black">Nova senha</span></div>
                            <input type="password" name="password" class="input input-primary w-full max-w-xs bg-white" />
                            <?php if (isset($validacoes['password'])) { ?>
                                <div class="mt-1 text-xs text-error"><?= $validacoes['password'][0] ?></div>
                            <?php } ?>
                        </label>

                        <label class="form-control">
                            <div class="label"><span class="label-text text-black">Confirme a nova senha</span></div>
                            <input type="password" name="password_confirm" class="input input-primary w-full max-w-xs bg-white" />
                        </label>

                        <div class="card-actions">
                            <button class="btn btn-primary btn-block">Atualizar senha</button>
                            <a href="/login" class="btn btn-link">Voltar para login</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>