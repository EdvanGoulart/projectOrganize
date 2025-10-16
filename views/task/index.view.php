<main class="bg-gray-300 w-full">

    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-5 flex justify-between rounded-xl bg-[#eae9ea] border border-gray-200 shadow-2xs rounded-xl p-4">
        <div class="flex flex-col w-1/4 max-h-[88vh]  m-2 bg-[#101204] border border-gray-900 shadow-2xs rounded-xl">
            <div class="p-3 md:p-3 flex flex-col h-full">
                <h3 class="text-lg font-bold text-[#eae9ea] mb-5">
                    Pendente
                </h3>
                <div id="pendingList" class="min-h-[30vh] max-h-[70vh] overflow-auto pr-1 custom-scroll">

                </div>
            </div>
            <div class="text-center">
                <label for="crud-modal" class="btn btn-outline w-1/2 mb-2 cursor-pointer">
                    Nova Tarefa
                </label>
            </div>
        </div>
        <div class="flex flex-col w-1/4 max-h-[88vh] m-2 bg-[#101204] border border-gray-900 shadow-2xs rounded-xl ">
            <div class="p-3 md:p-3 flex flex-col h-full">
                <h3 class="text-lg font-bold text-[#eae9ea] mb-5">
                    Andamento
                </h3>
                <div id="progressList" class="min-h-[30vh] max-h-[70vh] overflow-auto pr-1 custom-scroll">

                </div>
            </div>
            <div class="text-center">
                <label for="crud-modal" class="btn btn-outline w-1/2 mb-2 cursor-pointer">
                    Nova Tarefa
                </label>
            </div>
        </div>
        <div class="flex flex-col w-1/4 max-h-[88vh] m-2 bg-[#101204] border border-gray-900 shadow-2xs rounded-xl ">
            <div class="p-3 md:p-3 flex flex-col h-full">
                <h3 class="text-lg font-bold text-[#eae9ea] mb-5">
                    Revisão
                </h3>
                <div id="reviewList" class="min-h-[30vh] max-h-[70vh] overflow-auto pr-1 custom-scroll">

                </div>
            </div>

            <div class="text-center">
                <label for="crud-modal" class="btn btn-outline w-1/2 mb-2 cursor-pointer">
                    Nova Tarefa
                </label>
            </div>

        </div>
        <div class="flex flex-col w-1/4 max-h-[88vh] m-2 bg-[#101204] border border-gray-900 shadow-2xs rounded-xl ">
            <div class="p-3 md:p-3 flex flex-col h-full">
                <h3 class="text-lg font-bold text-[#eae9ea] mb-5">
                    Concluído
                </h3>
                <div id="completedList" class="min-h-[30vh] max-h-[70vh] overflow-auto pr-1 custom-scroll">

                </div>

            </div>
            <div class="text-center">
                <label for="crud-modal" class="btn btn-outline w-1/2 mb-2 cursor-pointer">
                    Nova Tarefa
                </label>
            </div>
        </div>
    </div>


    <div class="grafico mx-auto max-w-7xl h-100 px-2 sm:px-6 lg:px-8 bg-amber-200">
        <div>
            filtro
        </div>
        <div>
            Gráfico
        </div>
    </div>

</main>

<!-- Modal de nova tarefa -->

<!-- Checkbox para controlar o modal -->
<input type="checkbox" id="crud-modal" class="modal-toggle" />

<!-- Modal -->
<div class="modal">
    <div class="modal-box max-w-2xl relative">
        <!-- Botão de fechar -->
        <label for="crud-modal" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</label>

        <!-- Cabeçalho -->
        <h3 class="text-lg font-semibold text-gray-900 dark:text-[#eae9ea] mb-4">
            Criar nova tarefa
        </h3>

        <!-- Formulário -->
        <form id="form-task" class="space-y-4">
            <div>
                <label for="name" class="block mb-1 text-sm font-medium text-gray-900 dark:text-[#eae9ea]">
                    Nome
                </label>
                <input type="text" name="name" id="name" class="input input-bordered w-full" required />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="status" class="block mb-1 text-sm font-medium text-gray-900 dark:text-[#eae9ea]">
                        Status
                    </label>
                    <select id="status" name="status" class="select select-bordered w-full" required>
                        <option value="">Selecione</option>
                        <option value="pending">Pendente</option>
                        <option value="inprogress">Andamento</option>
                        <option value="review">Revisão</option>
                        <option value="completed">Concluído</option>
                    </select>
                </div>

                <div>
                    <label for="priority" class="block mb-1 text-sm font-medium text-gray-900 dark:text-[#eae9ea]">
                        Prioridade
                    </label>
                    <select id="priority" name="priority" class="select select-bordered w-full" required>
                        <option value="">Selecione</option>
                        <option>Alta</option>
                        <option>Média</option>
                        <option>Baixa</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="endDate" class="block mb-1 text-sm font-medium text-gray-900 dark:text-[#eae9ea]">
                    Data entrega
                </label>
                <input type="date" name="endDate" id="endDate" class="input input-bordered w-full" required />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="discipline" class="block mb-1 text-sm font-medium text-gray-900 dark:text-[#eae9ea]">
                        Disciplina
                    </label>
                    <select id="discipline" name="discipline" class="select select-bordered w-full">
                        <option value="">Selecione</option>
                        <?php foreach ($disciplineList as $discipline) : ?>
                            <option value="<?= $discipline->id ?>">
                                <?= $discipline->name ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="flex items-end justify-end">
                    <a href="/discipline" target="_blank" class="btn btn-outline w-full">
                        Gerenciar Disciplinas
                    </a>
                </div>
            </div>

            <div>
                <label for="description" class="block mb-1 text-sm font-medium text-gray-900 dark:text-[#eae9ea]">
                    Descrição
                </label>
                <textarea id="description" name="description" rows="4" class="textarea textarea-bordered w-full"></textarea>
            </div>

            <!-- Botão de ação -->
            <div class="modal-action justify-end">
                <button type="submit" id="btn-submit" class="btn btn-primary inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    Adicionar nova tarefa
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-pap...SeuHashAqui..." crossorigin="anonymous" referrerpolicy="no-referrer" />
<script>
    $('#form-task').submit(function(e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
            url: 'task/create',
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#form-task')[0].reset();
                $('#crud-modal').prop('checked', false);
                carregarTarefas();
            },
            error: function(xhr, status, error) {
                console.error('Erro:', error);
            }
        });
    });

    // Função para carregar tarefas
    function carregarTarefas() {
        $.ajax({
            url: '/task/list',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (!response.success) return;

                // Objeto para armazenar HTML por status
                const htmlByStatus = {
                    pending: '',
                    inprogress: '',
                    review: '',
                    completed: ''
                };


                function gerarHTMLTask(task) {
                    console.log(task);
                    return `
                        <div id="task_${task.id}" class="group w-full bg-[#242528] flex p-0 border border-transparent rounded-xl mb-2 relative transition hover:shadow-md">
                            <!-- Faixa de cor da disciplina -->
                            <div style="background-color: ${task.disciplineColor}" class="w-[5%] rounded-l-xl"></div>
                            

                            <!-- Conteúdo -->
                            <div class="w-[95%] p-3">
                                <div class="flex justify-between items-start h-5">
                                    <h3 class="font-bold break-words ">${task.name}</h3>
                                    

                                    <!-- Botão de opções (aparece no hover) -->
                                    <div class="relative hidden group-hover:block">
                                        <button type="button" class="btn btn-sm btn-circle btn-active" onclick="toggleOptions(this)">
                                            <i class="fa-solid fa-ellipsis"></i>
                                        </button>

                                        <!-- Menu de opções -->
                                        <div class="absolute right-0 mt-2 w-32 bg-[#242528] rounded-lg shadow-lg border border-transparent hidden z-50">
                                            <ul class="text-sm text-gray-700">
                                                <li>
                                                    <button type="button" class="w-full text-left text-white px-4 py-2 hover:bg-[#101204] hover:rounded-lg"
                                                        onclick="editar(${task.id})">
                                                        <i class="fa-solid fa-pen mr-2"></i> Editar
                                                    </button>
                                                </li>
                                                <li>
                                                    <button type="button" class="w-full text-left text-white px-4 py-2 hover:bg-[#101204] hover:rounded-lg"
                                                        onclick="excluir(${task.id})">
                                                        <i class="fa-solid fa-trash mr-2 text-red-600"></i> Excluir
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <p class="mt-2 break-words text-[#bfc1c4]">${task.description}</p>
                            </div>
                        </div>
                     `;
                }

                // Loop nas tarefas
                response.tasks.forEach(task => {
                    let statusKey = task.status; // 'pending', 'inprogress', 'review', 'completed'

                    // Garante que só vamos preencher os status válidos
                    if (htmlByStatus[statusKey] !== undefined) {
                        htmlByStatus[statusKey] += gerarHTMLTask(task);
                    }
                });

                // Inserir no DOM
                $('#pendingList').html(htmlByStatus.pending);
                $('#progressList').html(htmlByStatus.inprogress);
                $('#reviewList').html(htmlByStatus.review);
                $('#completedList').html(htmlByStatus.completed);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    // Abre/fecha o menu de opções individualmente
    function toggleOptions(button) {
        const menu = button.nextElementSibling;
        const isOpen = !menu.classList.contains('hidden');

        // Fecha todos os outros menus abertos
        document.querySelectorAll('.task-options-menu').forEach(m => m.classList.add('hidden'));

        // Alterna o atual
        if (!isOpen) {
            menu.classList.remove('hidden');
        } else {
            menu.classList.add('hidden');
        }
    }

    function excluir(id) {
        const divTask = $("#task_" + id);

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
                    url: '/task/delete',
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
                            divTask.fadeOut(500, function() {
                                divTask.remove();
                            });
                        }
                    },
                    error: function() {
                        Swal.fire('Erro!', 'Ocorreu um erro inesperado.', 'error');
                    }
                });
            }
        });

    }


    // Fecha o menu se clicar fora
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.relative')) {
            document.querySelectorAll('.task-options-menu').forEach(m => m.classList.add('hidden'));
        }
    });

    $(document).ready(function() {
        carregarTarefas();
    });
</script>