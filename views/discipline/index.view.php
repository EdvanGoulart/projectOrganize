<?php $validacoes = flash()->get('validacoes');  ?>

<div class="w-full">

    <form id="formDiscipline" action="/discipline/create" class="max-w-3xl mx-auto mt-5" method="POST">
        <input type="hidden" id="id" name="id" />
        <div class="flex-col">
            <div class="">
                <div class="mb-5">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome</label>
                    <input type="text" id="name" name="name" <?= isset($disciplineList) ? "value='{$disciplineList->name}'" : '' ?> class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light" />
                    <?php if (isset($validacoes['name'])) { ?>
                        <div class="mt-1 text-xs text-error"><?= $validacoes['name'][0] ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="flex">
                <div class="mb-5 w-[45%] mr-2">
                    <label for="color" class="block text-sm font-medium mb-2 dark:text-white">Cor da etiqueta</label>
                    <input type="color" id="color" name="color" <?= isset($disciplineList) ? "value='{$disciplineList->color}'" : '' ?> class="p-1 h-10 w-14 block bg-white border border-gray-200 cursor-pointer rounded-lg disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700" id="hs-color-input" value="#2563eb" title="Choose your color">
                </div>
            </div>

            <div class="mb-5">
                <div class=" w-full">
                    <label for="description" class="block text-sm font-medium mb-2 dark:text-white">Descrição</label>
                    <textarea id="description" name="description" class="py-2 px-3 sm:py-3 sm:px-4 block w-full border-gray-200 rounded-lg sm:text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" rows="3" placeholder="">
                        <?= isset($disciplineList) ? $disciplineList->description : '' ?>
                    </textarea>
                    <?php if (isset($validacoes['description'])) { ?>
                        <div class="mt-1 text-xs text-error"><?= $validacoes['description'][0] ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="mb-5 flex justify-center">
                <!-- <button type="submit" id="btnSalvar" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Registrar</button> -->
                <div class="flex gap-2">
                    <button type="submit" id="btnSalvar"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Registrar
                    </button>

                    <button type="button" id="btnCancelar"
                        class="text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center hidden">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </form>



    <div class="relative mx-auto max-w-7xl px-2 sm:px-6 lg:px-8 overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Disciplina
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Color
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Descrição
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Ação
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($disciplineList as $discipline) : ?>
                    <?php  ?>
                    <tr class="bg-white border-b dark:bg-gray-200 dark:border-gray-700 border-gray-200"
                        data-id="<?= $discipline->id; ?>"
                        data-name="<?= htmlspecialchars($discipline->name); ?>"
                        data-description="<?= htmlspecialchars($discipline->description); ?>"
                        data-color="<?= htmlspecialchars($discipline->color); ?>">
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            <?= $discipline->name ?>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            <?= $discipline->color ?>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            <?= $discipline->description ?>
                        </td>

                        <td class="px-6 py-4 flex justify-evenly">
                            <button class="btn-edit text-blue-600" data-id="<?= $discipline->id; ?>">Editar</button>
                            <button class="btn-delete text-red-600" data-id="<?= $discipline->id; ?>">Excluir</button>

                        </td>
                    </tr>
                <?php endforeach ?>

            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(function() {

        $('#formDiscipline').on('submit', function(e) {
            e.preventDefault();

            const id = $('#id').val();
            const isEdit = id !== ''; // true = edição, false = criação
            const url = isEdit ? '/discipline/edit' : '/discipline/create';

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {

                        // Mensagem dinâmica
                        Swal.fire({
                            title: 'Sucesso!',
                            text: isEdit ? 'Disciplina atualizada com sucesso!' : 'Disciplina cadastrada com sucesso!',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            timer: 2000,
                            timerProgressBar: true
                        });

                        // Limpa os campos e volta para modo "novo"
                        $('#formDiscipline')[0].reset();
                        $('#id').val('');
                        $('#btnSalvar').text('Registrar');

                        const data = response.data;

                        if (isEdit) {
                            // Atualiza a linha existente
                            const $row = $(`tr[data-id="${data.id}"]`);
                            $row.data('name', data.name);
                            $row.data('color', data.color);
                            $row.data('description', data.description);

                            $row.find('td:nth-child(1)').text(data.name);
                            $row.find('td:nth-child(2)').text(data.color);
                            $row.find('td:nth-child(3)').text(data.description);

                            resetForm();

                        } else {
                            // Cria uma nova linha na tabela (modo criação)
                            const newRow = `
                        <tr class="bg-white border-b dark:bg-gray-200 dark:border-gray-700 border-gray-200"
                            data-id="${data.id}"
                            data-name="${data.name}"
                            data-description="${data.description}"
                            data-color="${data.color}"
                        >
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">${data.name}</td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">${data.color}</td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">${data.description}</td>
                            <td class="px-6 py-4 flex justify-evenly">
                                <button type="button" class="btn-edit text-blue-600">Editar</button>
                                <button type="button" class="btn-delete text-red-600" data-id="${data.id}">Excluir</button>
                            </td>
                        </tr>
                    `;
                            $('table tbody').append(newRow);
                        }

                    } else {
                        // Exibe erro vindo do back-end
                        Swal.fire({
                            title: 'Erro!',
                            html: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    Swal.fire({
                        title: 'Erro!',
                        text: 'Ocorreu um erro no cadastro.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });


        //Editar 
        $(document).on('click', '.btn-edit', function(e) {
            e.preventDefault();

            const $tr = $(this).closest('tr');

            const id = $tr.data('id');
            const name = $tr.data('name');
            const description = $tr.data('description');
            const color = $tr.data('color');

            Swal.fire({
                title: 'Editar disciplina?',
                text: 'Você deseja carregar esses dados para edição?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, editar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Preenche o formulário com os dados da linha
                    $('#id').val(id);
                    $('#name').val(name);
                    $('#description').val(description);
                    $('#color').val(color);

                    // Altera texto do botão (opcional)
                    $('#btnSalvar').text('Atualizar');
                    $('#btnCancelar').removeClass('hidden');

                    // Scroll suave até o formulário (opcional)
                    $('html, body').animate({
                        scrollTop: $('#formDiscipline').offset().top - 20
                    }, 500);
                }
            });
        });



        // Deletar
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const $row = $(this).closest('tr');

            Swal.fire({
                title: 'Tem certeza?',
                text: 'Esta ação não poderá ser desfeita!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, deletar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/discipline/delete',
                        type: 'POST',
                        data: {
                            id: id
                        },
                        dataType: 'json',
                        success: function(response) {
                            Swal.fire({
                                title: response.success ? 'Sucesso!' : 'Erro!',
                                text: response.message,
                                icon: response.success ? 'success' : 'error',
                                confirmButtonText: 'OK',
                                timer: response.success ? 2000 : undefined,
                                timerProgressBar: response.success
                            });

                            if (response.success) {
                                $row.fadeOut(500, function() {
                                    $(this).remove();
                                });
                            }
                        },
                        error: function() {
                            Swal.fire('Erro!', 'Ocorreu um erro inesperado.', 'error');
                        }
                    });
                }
            });
        });

    });

    // Função para resetar o formulário e voltar ao modo "criar"
    function resetForm() {
        $('#formDiscipline')[0].reset();
        $('#id').val('');
        $('#btnSalvar').text('Registrar');
        $('#btnCancelar').addClass('hidden');
    }

    // Botão de cancelar
    $('#btnCancelar').on('click', function() {
        resetForm();
    });
</script>

</script>