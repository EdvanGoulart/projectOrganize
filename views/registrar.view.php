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
            <form method="POST" action="/registrar">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title text-xl">Crie a sua conta</div>
                        <div class="flex gap-4">
                            <label class="form-control flex-1">
                                <div class="label">
                                    <span class="label-text text-black">Nome</span>
                                </div>

                                <input name="name" type="text" class="input input-primary w-full max-w-xs bg-white" value="<?= old('name') ?>" />

                                <?php if (isset($validacoes['name'])) { ?>
                                    <div class="mt-1 text-xs text-error"><?= $validacoes['name'][0] ?></div>
                                <?php } ?>
                            </label>

                            <label class="form-control flex-1">
                                <div class="label">
                                    <span class="label-text text-black">Matrícula</span>
                                </div>
                                <input name="enrollment" type="text" class="input input-primary w-full max-w-xs bg-white" value="<?= old('enrollment') ?>" />
                                <?php if (isset($validacoes['enrollment'])) { ?>
                                    <div class="mt-1 text-xs text-error"><?= $validacoes['enrollment'][0] ?></div>
                                <?php } ?>
                            </label>
                        </div>

                        <div class="flex  gap-4">
                            <label class="form-control flex-1">
                                <div class="label">
                                    <span class="label-text text-black">E-mail</span>
                                </div>

                                <input name="email" type="email" class="input input-primary w-full max-w-xs bg-white" value="<?= old('email') ?>" />

                                <?php if (isset($validacoes['email'])) { ?>
                                    <div class="mt-1 text-xs text-error"><?= $validacoes['email'][0] ?></div>
                                <?php } ?>
                            </label>

                            <label class="form-control flex-1">
                                <div class="label">
                                    <span class="label-text text-black">Telefone</span>
                                </div>

                                <input name="phone" value="<?= old('phone') ?>" type="tel" class="input input-primary w-full max-w-xs bg-white" />

                                <?php if (isset($validacoes['phone'])) { ?>
                                    <div class="mt-1 text-xs text-error"><?= $validacoes['phone'][0] ?></div>
                                <?php } ?>
                            </label>

                        </div>

                        <div class="flex  gap-4">
                            <label class="form-control flex-1">
                                <div class="label">
                                    <span class="label-text text-black">Endereço</span>
                                </div>
                                <input name="address" type="text" value="<?= old('address') ?>" class="input input-primary w-full max-w-xs bg-white" />
                                <?php if (isset($validacoes['address'])) { ?>
                                    <div class="mt-1 text-xs text-error"><?= $validacoes['address'][0] ?></div>
                                <?php } ?>
                            </label>

                            <label class="form-control flex-1">
                                <div class="label">
                                    <span class="label-text text-black">Cidade</span>
                                </div>
                                <input name="city" value="<?= old('city') ?>" class="input input-primary w-full max-w-xs bg-white" />
                                <?php if (isset($validacoes['city'])) { ?>
                                    <div class="mt-1 text-xs text-error"><?= $validacoes['city'][0] ?></div>
                                <?php } ?>
                            </label>
                        </div>

                        <div class="flex  gap-4">
                            <label class="form-control flex-1">
                                <div class="label">
                                    <span class="label-text text-black">Bairro</span>
                                </div>
                                <input name="district" value="<?= old('district') ?>" type="text" class="input input-primary w-full max-w-xs bg-white" />
                                <?php if (isset($validacoes['district'])) { ?>
                                    <div class="mt-1 text-xs text-error"><?= $validacoes['district'][0] ?></div>
                                <?php } ?>
                            </label>

                            <label class="form-control flex-1">
                                <div class="label">
                                    <span class="label-text text-black">CEP</span>
                                </div>
                                <input name="zipcode" value="<?= old('zipcode') ?>" type="text" class="input input-primary w-full max-w-xs bg-white" />
                                <?php if (isset($validacoes['zipcode'])) { ?>
                                    <div class="mt-1 text-xs text-error"><?= $validacoes['zipcode'][0] ?></div>
                                <?php } ?>
                            </label>
                        </div>

                        <div class="flex  gap-4">
                            <label class="form-control flex-1">
                                <div class="label">
                                    <span class="label-text text-black">Senha</span>
                                </div>
                                <input name="password" type="password" class="input input-primary w-full max-w-xs bg-white" />
                                <?php if (isset($validacoes['password'])) { ?>
                                    <div class="mt-1 text-xs text-error"><?= $validacoes['password'][0] ?></div>
                                <?php } ?>
                            </label>

                            <label class="form-control flex-1">
                                <div class="label">
                                    <span class="label-text text-black">Confirme sua senha</span>
                                </div>
                                <input name="password_confirm" type="password" class="input input-primary w-full max-w-xs bg-white" />
                            </label>
                        </div>

                        <div class="card-actions">
                            <button class="btn btn-primary btn-block">Registrar</button>
                            <a href="/login" class="btn btn-link">Já tenho uma conta</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>