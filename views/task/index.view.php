<main class="bg-gray-300 w-full">

    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-5 flex justify-between items-start gap-4 rounded-xl bg-[#eae9ea] border border-gray-200 shadow-2xs p-4">

        <!-- Coluna base -->
        <div class="flex flex-col flex-1 h-[80vh] bg-[#101204] border border-gray-900 shadow-2xs rounded-xl">
            <div class="flex flex-col flex-1 p-3 overflow-hidden">
                <h3 class="text-lg font-bold text-[#eae9ea] mb-5">Pendente</h3>
                <div id="pendingList" class="task-column flex-1 overflow-auto pr-1 custom-scroll">
                    <!-- Cards -->
                </div>
            </div>
            <div class="text-center p-2">
                <label class="btn btn-outline w-1/2 mb-2 cursor-pointer" onclick="abrirModalCriarTarefa()">
                    Nova Tarefa
                </label>
            </div>
        </div>

        <!-- Copie as próximas colunas com a mesma estrutura -->
        <div class="flex flex-col flex-1 h-[80vh] bg-[#101204] border border-gray-900 shadow-2xs rounded-xl">
            <div class="flex flex-col flex-1 p-3 overflow-hidden">
                <h3 class="text-lg font-bold text-[#eae9ea] mb-5">Andamento</h3>
                <div id="progressList" class="task-column flex-1 overflow-auto pr-1 custom-scroll"></div>
            </div>
            <div class="text-center p-2">
                <label class="btn btn-outline w-1/2 mb-2 cursor-pointer" onclick="abrirModalCriarTarefa()">
                    Nova Tarefa
                </label>
            </div>
        </div>

        <div class="flex flex-col flex-1 h-[80vh] bg-[#101204] border border-gray-900 shadow-2xs rounded-xl">
            <div class="flex flex-col flex-1 p-3 overflow-hidden">
                <h3 class="text-lg font-bold text-[#eae9ea] mb-5">Revisão</h3>
                <div id="reviewList" class="task-column flex-1 overflow-auto pr-1 custom-scroll"></div>
            </div>
            <div class="text-center p-2">
                <label class="btn btn-outline w-1/2 mb-2 cursor-pointer" onclick="abrirModalCriarTarefa()">
                    Nova Tarefa
                </label>
            </div>
        </div>

        <div class="flex flex-col flex-1 h-[80vh] bg-[#101204] border border-gray-900 shadow-2xs rounded-xl">
            <div class="flex flex-col flex-1 p-3 overflow-hidden">
                <h3 class="text-lg font-bold text-[#eae9ea] mb-5">Concluído</h3>
                <div id="completedList" class="task-column flex-1 overflow-auto pr-1 custom-scroll"></div>
            </div>
            <div class="text-center p-2">
                <label class="btn btn-outline w-1/2 mb-2 cursor-pointer" onclick="abrirModalCriarTarefa()">
                    Nova Tarefa
                </label>
            </div>
        </div>

    </div>



    <div class="p-4 bg-[#1e1f23]  shadow-lg flex flex-col items-center">
        <div class="w-[500px] h-[500px]">
            <canvas id="chartStatus"></canvas>
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
        <h3 id="modal-title" class="text-lg font-semibold text-gray-900 dark:text-[#eae9ea] mb-4">
            Criar nova tarefa
        </h3>

        <!-- Formulário -->
        <form id="form-task" class="space-y-4">
            <input type="hidden" name="id" id="task_id">
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
                        <option value="alta">Alta</option>
                        <option value="media">Média</option>
                        <option value="baixa">Baixa</option>
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
        const id = $('#task_id').val(); // verifica se há ID preenchido

        const url = id ? '/task/update' : '/task/create';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                Swal.fire({
                    title: response.success ? 'Sucesso!' : 'Erro!',
                    text: response.message,
                    icon: response.success ? 'success' : 'error',
                    timer: response.success ? 2000 : undefined,
                    timerProgressBar: response.success
                });

                if (response.success) {
                    $('#form-task')[0].reset();
                    $('#task_id').val('');
                    $('#modal-title').text('Criar nova tarefa');
                    $('#btn-submit').html(`
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    Criar
                `);
                    $('#crud-modal').prop('checked', false);
                    carregarTarefas();
                    carregarGraficoStatus();
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro:', error);
            }
        });
    });

    function editar(id) {
        $.ajax({
            url: '/task/findTask', // rota fixa
            type: 'GET',
            data: {
                id: id
            }, // envia o ID como query string
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const task = response.task;

                    // Preenche o formulário
                    $('#task_id').val(task.id);
                    $('#name').val(task.name);
                    $('#description').val(task.description);
                    $('#status').val(task.status);
                    $('#priority').val(task.priority);
                    $('#discipline').val(task.idDiscipline);
                    $('#endDate').val(task.endDate.split(' ')[0]);

                    $('#modal-title').text('Editar tarefa');
                    $('#btn-submit').html(`
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" clip-rule="evenodd"></path>
                        <path fill-rule="evenodd" d="M5 15a1 1 0 011-1h9a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    Editar
                `);

                    // Abre o modal
                    $('#crud-modal').prop('checked', true);
                } else {
                    Swal.fire('Erro!', response.message || 'Tarefa não encontrada', 'error');
                }
            },
            error: function() {
                Swal.fire('Erro!', 'Não foi possível carregar os dados da tarefa.', 'error');
            }
        });
    }



    function carregarTarefas() {
        $.ajax({
            url: '/task/list',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (!response.success) return;

                const htmlByStatus = {
                    pending: '',
                    inprogress: '',
                    review: '',
                    completed: ''
                };


                function gerarHTMLTask(task) {
                    console.log(task);
                    return `
                        <div id="task_${task.id}" data-id="${task.id}" class=" task-card group w-[14vw] bg-[#242528] flex p-0 border border-transparent rounded-xl mb-2 relative transition hover:shadow-md">
                            <!-- Faixa de cor da disciplina -->
                            <div style="background-color: ${task.disciplineColor}" class="w-[5%] rounded-l-xl"></div>
                            

                            <!-- Conteúdo -->
                            <div class="w-[95%] p-3">
                                <div class="flex justify-between items-start h-auto">
                                    <h3 class="font-bold break-words break-all h-auto pb-2">${task.name}</h3>
                                    

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

                response.tasks.forEach(task => {
                    let statusKey = task.status; // 'pending', 'inprogress', 'review', 'completed'

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

    function abrirModalCriarTarefa() {
        $('#form-task')[0].reset();

        $('#task_id').val('');
        $('#status').val('');
        $('#priority').val('');
        $('#discipline').val('');

        $('#modal-title').text('Criar nova tarefa');
        $('#btn-submit').html(`
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
        </svg>
        Adicionar nova tarefa
    `);

        $('#crud-modal').prop('checked', true);
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

    function carregarGraficoStatus() {
        $.ajax({
            url: '/task/status-chart',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (!response.success) return;

                const labels = response.data.map(item => item.status);
                const valores = response.data.map(item => item.total);

                gerarGraficoPizza(labels, valores);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    let chartStatus = null;

    function gerarGraficoPizza(labels, valores) {
        const ctx = document.getElementById('chartStatus').getContext('2d');

        if (chartStatus) chartStatus.destroy(); // evita sobreposição

        chartStatus = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels.map(l => l.charAt(0).toUpperCase() + l.slice(1)), // Ex: "pending" → "Pending"
                datasets: [{
                    data: valores,
                    backgroundColor: [
                        '#f87171', // pendente
                        '#60a5fa', // em andamento
                        '#facc15', // revisão
                        '#34d399' // concluído
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#fff'
                        }
                    },
                    title: {
                        display: true,
                        text: 'Tarefas por Status',
                        color: '#fff'
                    }
                }
            }
        });
    }



    $(function() {
        // Torna todas as colunas "sortables" (ordenáveis)
        $('.task-column').sortable({
            connectWith: '.task-column', // permite arrastar entre colunas
            placeholder: 'task-placeholder', // estilo visual do espaço durante o arrasto
            items: '.task-card', // elementos arrastáveis
            cursor: 'move',
            revert: true,
            start: function(event, ui) {
                ui.item.addClass('opacity-50');
            },
            stop: function(event, ui) {
                ui.item.removeClass('opacity-50');
            },
            update: function(event, ui) {
                if (this === ui.item.parent()[0]) {
                    const taskId = ui.item.data('id');
                    const newColumnId = $(this).attr('id');

                    // Mapeia coluna → status
                    let newStatus = '';
                    switch (newColumnId) {
                        case 'pendingList':
                            newStatus = 'pending';
                            break;
                        case 'progressList':
                            newStatus = 'inprogress';
                            break;
                        case 'reviewList':
                            newStatus = 'review';
                            break;
                        case 'completedList':
                            newStatus = 'completed';
                            break;
                    }

                    // Envia posição nova + status (caso tenha mudado de coluna)
                    const newPosition = ui.item.index();

                    $.ajax({
                        url: '/task/update-order',
                        type: 'POST',
                        data: {
                            id: taskId,
                            status: newStatus,
                            position: newPosition
                        },
                        success: function(response) {
                            console.log('Tarefa atualizada:', response);
                            carregarGraficoStatus();
                        },
                        error: function(xhr, status, error) {
                            console.error('Erro ao atualizar ordem/status:', error);
                        }
                    });
                }
            }
        }).disableSelection();
    });



    // Fecha o menu se clicar fora
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.relative')) {
            document.querySelectorAll('.task-options-menu').forEach(m => m.classList.add('hidden'));
        }
    });

    $(document).ready(function() {
        carregarTarefas();
        carregarGraficoStatus();
    });
</script>