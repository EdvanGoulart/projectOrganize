<div class="w-full">
    <div class="w-full bg-base-200 rounded-xl p-3 sm:p-4 lg:p-6 space-y-6">
        <form id="formDiscipline" action="/discipline/create" class="card bg-base-100 border border-base-300 shadow max-w-3xl mx-auto" method="POST">
            <div class="card-body">
                <input type="hidden" id="id" name="id" />
                <div class="flex-col">
                    <div class="">
                        <div class="mb-5">
                            <label for="name" class="block mb-2 text-sm font-medium text-base-content">Nome</label>
                            <input type="text" id="name" name="name" class="input input-bordered w-full" />
                            <div id="error-name" class="mt-1 text-xs text-error "></div>
                        </div>
                    </div>

                    <div class="flex">
                        <div class="mb-5 w-[45%] mr-2">
                            <label for="color" class="block text-sm font-medium mb-2 text-base-content">Cor da etiqueta</label>
                            <input type="color" id="color" name="color" class="p-1 h-10 w-14 block bg-base-100 border border-base-300 cursor-pointer rounded-lg" id="hs-color-input" value="#2563eb" title="Choose your color">
                        </div>
                    </div>

                    <div class="mb-5">
                        <div class=" w-full">

                            <label for="description" class="block text-sm font-medium mb-2 text-base-content">Descrição</label>
                            <textarea id="description" name="description" class="textarea textarea-bordered w-full" rows="3" placeholder="">

                     </textarea>
                        </div>
                    </div>

                    <div class="mb-5 flex justify-center">
                        <!-- <button type="submit" id="btnSalvar" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Registrar</button> -->
                        <div class="flex gap-2">
                            <button type="submit" id="btnSalvar"

                                class="btn btn-primary">
                                Registrar
                            </button>

                            <button type="button" id="btnCancelar"
                                class="btn btn-ghost hidden">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>


        <div class="card bg-base-100 border border-base-300 shadow max-w-7xl mx-auto">
            <div class="card-body p-0 sm:p-2">
                <div class="overflow-x-auto">
                    <table class="table table-zebra table-fixed w-full text-sm text-left">
                        <!-- head -->
                        <thead>
                            <tr>
                                <th class="w-1/4 px-4 py-2">Disciplina</th>
                                <th class="w-32 px-4 py-2">Cor</th>
                                <th class="w-1/2 px-4 py-2">Descrição</th>
                                <th class="w-40 px-4 py-2 text-center">Ação</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($disciplineList as $discipline) : ?>

                                <tr class="border-t border-base-300"
                                    data-id="<?= $discipline->id; ?>"
                                    data-name="<?= htmlspecialchars($discipline->name); ?>"
                                    data-description="<?= htmlspecialchars($discipline->description); ?>"
                                    data-color="<?= htmlspecialchars($discipline->color); ?>">

                                    <td class="px-4 py-2 break-words whitespace-normal">
                                        <?= $discipline->name ?>
                                    </td>

                                    <td class="px-4 py-2">
                                        <div class="flex items-center gap-2">
                                            <span class="w-5 h-5 rounded border"
                                                style="background-color: <?= $discipline->color ?>"></span>
                                            <span><?= $discipline->color ?></span>
                                        </div>
                                    </td>

                                    <td class="px-4 py-2 break-words whitespace-normal max-w-[500px]">
                                        <?= $discipline->description ?>
                                    </td>

                                    <td class="px-4 py-2">
                                        <div class="flex flex-col sm:flex-row gap-2 sm:justify-center">
                                            <button class="btn-edit btn btn-outline btn-info btn-sm"
                                                data-id="<?= $discipline->id; ?>">
                                                Editar
                                            </button>

                                            <button class="btn-delete btn btn-outline btn-error btn-sm"
                                                data-id="<?= $discipline->id; ?>">
                                                Excluir
                                            </button>
                                        </div>
                                    </td>

                                </tr>

                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(function() {

        /* ==============================
           SUBMIT FORM (CRIAR / EDITAR)
        ============================== */

        $('#formDiscipline').on('submit', function(e) {

            e.preventDefault();

            const id = $('#id').val();
            const isEdit = id !== '';
            const url = isEdit ? '/discipline/edit' : '/discipline/create';

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',

                success: function(response) {

                    if (response.success) {

                        Swal.fire({
                            title: 'Sucesso!',
                            text: isEdit ?
                                'Disciplina atualizada com sucesso!' :
                                'Disciplina cadastrada com sucesso!',
                            icon: 'success'
                        });

                        const data = response.data;

                        if (isEdit) {

                            const $row = $(`tr[data-id="${data.id}"]`);

                            $row.data('name', data.name);
                            $row.data('color', data.color);
                            $row.data('description', data.description);

                            $row.find('td:nth-child(1)').text(data.name);

                            $row.find('td:nth-child(2)').html(`
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded border" style="background-color:${data.color}"></span>
                                <span>${data.color}</span>
                            </div>
                        `);

                            $row.find('td:nth-child(3)').text(data.description);

                        } else {

                            const newRow = `
                        <tr class="border-t border-base-300"
                            data-id="${data.id}"
                            data-name="${data.name}"
                            data-description="${data.description}"
                            data-color="${data.color}">

                            <td class="px-4 py-2 break-words whitespace-normal">
                                ${data.name}
                            </td>

                            <td class="px-4 py-2">
                                <div class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded border"
                                    style="background-color:${data.color}"></span>
                                    <span>${data.color}</span>
                                </div>
                            </td>

                            <td class="px-4 py-2 break-words whitespace-normal">
                                ${data.description}
                            </td>

                            <td class="px-4 py-2">
                                <div class="flex flex-col sm:flex-row gap-2 sm:justify-center">

                                    <button
                                        type="button"
                                        class="btn-edit btn btn-outline btn-info btn-sm"
                                        data-id="${data.id}">
                                        Editar
                                    </button>

                                    <button
                                        type="button"
                                        class="btn-delete btn btn-outline btn-error btn-sm"
                                        data-id="${data.id}">
                                        Excluir
                                    </button>

                                </div>
                            </td>

                        </tr>
                        `;

                            $('table tbody').append(newRow);
                        }

                        resetForm();

                    } else {

                        if (response.errors) {

                            if (response.errors['name']) {
                                $('#error-name').text(response.errors['name'][0]);
                            }

                        } else {

                            Swal.fire({
                                title: 'Erro!',
                                html: response.message,
                                icon: 'error'
                            });

                        }
                    }

                },

                error: function() {

                    Swal.fire(
                        'Erro!',
                        'Ocorreu um erro inesperado.',
                        'error'
                    );

                }

            });

        });


        /* ==============================
           BOTÃO EDITAR
        ============================== */

        $(document).on('click', '.btn-edit', function() {

            const row = $(this).closest('tr');

            const id = row.data('id');
            const name = row.data('name');
            const color = row.data('color');
            const description = row.data('description');

            $('#id').val(id);
            $('#name').val(name);
            $('#color').val(color);
            $('#description').val(description);

            $('#btnSalvar').text('Atualizar');
            $('#btnCancelar').removeClass('hidden');

            $('html, body').animate({
                scrollTop: $("#formDiscipline").offset().top - 50
            }, 400);

        });


        /* ==============================
           BOTÃO EXCLUIR
        ============================== */

        $(document).on('click', '.btn-delete', function() {

            const id = $(this).data('id');

            Swal.fire({
                title: 'Tem certeza?',
                text: 'Essa disciplina será removida!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: '/discipline/delete',
                        type: 'POST',
                        data: {
                            id: id
                        },

                        success: function() {

                            $(`tr[data-id="${id}"]`).remove();

                            Swal.fire(
                                'Excluído!',
                                'Disciplina removida com sucesso.',
                                'success'
                            );

                        },

                        error: function() {

                            Swal.fire(
                                'Erro!',
                                'Não foi possível excluir.',
                                'error'
                            );

                        }

                    });

                }

            });

        });


        /* ==============================
           BOTÃO CANCELAR
        ============================== */

        $('#btnCancelar').on('click', function() {
            resetForm();
        });

    });


    /* ==============================
       RESET FORM
    ============================== */

    function resetForm() {

        $('#formDiscipline')[0].reset();
        $('#id').val('');
        $('#btnSalvar').text('Registrar');
        $('#btnCancelar').addClass('hidden');

        $('#error-name').text('');

    }
</script>