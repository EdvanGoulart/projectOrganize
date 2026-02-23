<?php
$validacoes = flash()->get('validacoes_perfil') ?: [];
$user = auth();
?>

<div class="w-full max-w-4xl mx-auto">
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title text-2xl">Atualizar dados do usuário</h2>
            <p class="text-sm opacity-80">Edite suas informações e salve para manter seu cadastro atualizado.</p>

            <form method="POST" action="/perfil" class="space-y-4 mt-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="form-control w-full">
                        <div class="label"><span class="label-text">Nome</span></div>
                        <input name="name" type="text" class="input input-primary w-full" value="<?= old('name') ?: $user->name ?>" />
                        <?php if (isset($validacoes['name'])) { ?>
                            <div class="mt-1 text-xs text-error"><?= $validacoes['name'][0] ?></div>
                        <?php } ?>
                    </label>

                    <label class="form-control w-full">
                        <div class="label"><span class="label-text">Matrícula</span></div>
                        <input name="enrollment" type="text" class="input input-primary w-full" value="<?= old('enrollment') ?: $user->enrollment ?>" />
                        <?php if (isset($validacoes['enrollment'])) { ?>
                            <div class="mt-1 text-xs text-error"><?= $validacoes['enrollment'][0] ?></div>
                        <?php } ?>
                    </label>

                    <label class="form-control w-full">
                        <div class="label"><span class="label-text">E-mail</span></div>
                        <input name="email" type="email" class="input input-primary w-full" value="<?= old('email') ?: $user->email ?>" />
                        <?php if (isset($validacoes['email'])) { ?>
                            <div class="mt-1 text-xs text-error"><?= $validacoes['email'][0] ?></div>
                        <?php } ?>
                    </label>

                    <label class="form-control w-full">
                        <div class="label"><span class="label-text">Telefone</span></div>
                        <input name="phone" type="tel" class="input input-primary w-full" value="<?= old('phone') ?: $user->phone ?>" />
                        <?php if (isset($validacoes['phone'])) { ?>
                            <div class="mt-1 text-xs text-error"><?= $validacoes['phone'][0] ?></div>
                        <?php } ?>
                    </label>

                    <label class="form-control w-full">
                        <div class="label"><span class="label-text">Endereço</span></div>
                        <input name="address" type="text" class="input input-primary w-full" value="<?= old('address') ?: $user->address ?>" />
                        <?php if (isset($validacoes['address'])) { ?>
                            <div class="mt-1 text-xs text-error"><?= $validacoes['address'][0] ?></div>
                        <?php } ?>
                    </label>

                    <label class="form-control w-full">
                        <div class="label"><span class="label-text">Cidade</span></div>
                        <input name="city" type="text" class="input input-primary w-full" value="<?= old('city') ?: $user->city ?>" />
                        <?php if (isset($validacoes['city'])) { ?>
                            <div class="mt-1 text-xs text-error"><?= $validacoes['city'][0] ?></div>
                        <?php } ?>
                    </label>

                    <label class="form-control w-full">
                        <div class="label"><span class="label-text">Bairro</span></div>
                        <input name="district" type="text" class="input input-primary w-full" value="<?= old('district') ?: $user->district ?>" />
                        <?php if (isset($validacoes['district'])) { ?>
                            <div class="mt-1 text-xs text-error"><?= $validacoes['district'][0] ?></div>
                        <?php } ?>
                    </label>

                    <label class="form-control w-full">
                        <div class="label"><span class="label-text">CEP</span></div>
                        <input name="zipcode" type="text" class="input input-primary w-full" value="<?= old('zipcode') ?: $user->zipcode ?>" />
                        <?php if (isset($validacoes['zipcode'])) { ?>
                            <div class="mt-1 text-xs text-error"><?= $validacoes['zipcode'][0] ?></div>
                        <?php } ?>
                    </label>
                </div>

                <div class="divider my-2">Alterar senha (opcional)</div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="form-control w-full">
                        <div class="label"><span class="label-text">Nova senha</span></div>
                        <input name="password" type="password" class="input input-primary w-full" />
                        <?php if (isset($validacoes['password'])) { ?>
                            <div class="mt-1 text-xs text-error"><?= $validacoes['password'][0] ?></div>
                        <?php } ?>
                    </label>

                    <label class="form-control w-full">
                        <div class="label"><span class="label-text">Confirmar nova senha</span></div>
                        <input name="password_confirm" type="password" class="input input-primary w-full" />
                    </label>
                </div>

                <div class="card-actions justify-end mt-2">
                    <button class="btn btn-primary">Salvar alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>