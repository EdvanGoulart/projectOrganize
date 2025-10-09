<?php $validacoes = flash()->get('validacoes'); ?>

<div class="grid grid-cols-2">

    <div class="hero min-h-screen flex ml-40">
        <div class="hero-content -mt-20">
            <div>
                <p class="py-2 text-xl">Bem Vindo ao</p>
                <h1 class="text-6xl font-bold">Organize</h1>
                <p class="py-2 pb-4 text-xl">Transforme seus estudos em um processo mais eficiente e organizado.</p>
            </div>
        </div>
    </div>

    <div class="bg-white hero mr-40 min-h-screen text-black">
        <div class="hero-content -mt-20">
            <form method="POST" action="/login">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title text-xl">Faça o seu login</div>

                        <?php require base_path('views/partials/_mensagem.view.php'); ?>

                        <label class="form-control">
                            <div class="label">
                                <span class="label-text text-black">Email</span>
                            </div>

                            <input
                                type="text" name="email"
                                class="input input-primary w-full max-w-xs bg-white "
                                value="<?= old('email') ?>" />

                            <?php if (isset($validacoes['email'])) { ?>
                                <div class="mt-1 text-xs text-error"><?= $validacoes['email'][0] ?></div>
                            <?php } ?>
                        </label>

                        <label class="form-control">
                            <div class="label">
                                <span class="label-text text-black">Senha</span>
                            </div>

                            <input type="password" name="password" class="input input-primary w-full max-w-xs bg-white" />

                            <?php if (isset($validacoes['password'])) { ?>
                                <div class="mt-1 text-xs text-error"><?= $validacoes['password'][0] ?></div>
                            <?php } ?>
                        </label>

                        <div class="card-actions">
                            <button class="btn btn-primary btn-block">Login</button>
                            <a href="/registrar" class="btn btn-link">Quero me registrar</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>