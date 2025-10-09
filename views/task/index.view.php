<main class="bg-gray-300 w-full">

    <div class=" mx-auto max-w-7xl min-h-screen px-2 sm:px-6 lg:px-8 flex justify-between rounded-xl bg-white border border-gray-200 shadow-2xs rounded-xl p-4">
        <div class="flex flex-col w-1/4 m-2 bg-gray-100 border border-gray-900 shadow-2xs rounded-xl">
            <div class="p-3 md:p-3 flex flex-col h-full">
                <h3 class="text-lg font-bold text-gray-800 mb-5">
                    Pendente
                </h3>
                <div id="pendingList">

                </div>
            </div>
            <div class="text-center">
                <label for="crud-modal" class="btn btn-neutral w-1/2 mb-2 cursor-pointer">
                    Nova Tarefa
                </label>
            </div>
        </div>
        <div class="flex flex-col w-1/4 m-2 bg-gray-100 border border-gray-900 shadow-2xs rounded-xl ">
            <div class="p-3 md:p-3 flex flex-col h-full">
                <h3 class="text-lg font-bold text-gray-800 mb-5">
                    Andamento
                </h3>
                <div id="progressList" class="mb-3">

                </div>
            </div>
            <div class="text-center">
                <label for="crud-modal" class="btn btn-neutral w-1/2 mb-2 cursor-pointer">
                    Nova Tarefa
                </label>
            </div>
        </div>
        <div class="flex flex-col w-1/4 m-2 bg-gray-100 border border-gray-900 shadow-2xs rounded-xl ">
            <div class="p-3 md:p-3 flex flex-col h-full">
                <h3 class="text-lg font-bold text-gray-800 mb-5">
                    Revisão
                </h3>
                <div id="reviewList">

                </div>
            </div>

            <div class="text-center">
                <label for="crud-modal" class="btn btn-neutral w-1/2 mb-2 cursor-pointer">
                    Nova Tarefa
                </label>
            </div>

        </div>
        <div class="flex flex-col w-1/4 m-2 bg-gray-100 border border-gray-900 shadow-2xs rounded-xl ">
            <div class="p-3 md:p-3 flex flex-col h-full">
                <h3 class="text-lg font-bold text-gray-800 mb-5">
                    Concluído
                </h3>
                <div id="completedList">

                </div>

            </div>
            <div class="text-center">
                <label for="crud-modal" class="btn btn-neutral w-1/2 mb-2 cursor-pointer">
                    Nova Tarefa
                </label>
            </div>
        </div>
    </div>


    <div class="grafico mx-auto max-w-7xl px-2 sm:px-6 lg:px-8 bg-amber-200">
        <div>
            filtro
        </div>
        <div>
            Gráfico
        </div>
    </div>

</main>

<!-- Modal de nova tarefa -->

<input type="checkbox" id="crud-modal" class="modal-toggle" />
<div class="modal" role="dialog">
    <div class="modal-box max-w-2xl">
        <div class="flex items-center justify-between border-b pb-2 mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Criar nova tarefa
            </h3>
            <!-- Botão de fechar -->
            <label for="crud-modal" class="btn btn-sm btn-circle btn-ghost">✕</label>
        </div>

        <!-- Modal body -->
        <form id="form-task-register" class="space-y-4">
            <div>
                <label for="name" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
                    Nome
                </label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    class="input input-bordered w-full"
                    required />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="status" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
                        Status
                    </label>
                    <select
                        id="status"
                        name="status"
                        class="select select-bordered w-full"
                        required>
                        <option value="">Selecione</option>
                        <option value="pending">Pendente</option>
                        <option value="inprogress">Andamento</option>
                        <option value="review">Revisão</option>
                        <option value="completed">Concluído</option>
                    </select>
                </div>

                <div>
                    <label for="priority" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
                        Prioridade
                    </label>
                    <select
                        id="priority"
                        name="priority"
                        class="select select-bordered w-full"
                        required>
                        <option value="">Selecione</option>
                        <option>Alta</option>
                        <option>Média</option>
                        <option>Baixa</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="endDate" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
                    Data entrega
                </label>
                <input
                    type="date"
                    name="endDate"
                    id="endDate"
                    class="input input-bordered w-full"
                    required />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="discipline" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
                        Disciplina
                    </label>
                    <select
                        id="discipline"
                        name="discipline"
                        class="select select-bordered w-full">
                        <option value="">Selecione</option>
                        <?php foreach ($disciplineList as $discipline) : ?>
                            <option value="<?= $discipline->getIdDiscipline() ?>">
                                <?= $discipline->getName() ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="flex items-end justify-end">
                    <a
                        href="/discipline"
                        target="_blank"
                        class="btn btn-outline w-full">
                        Gerenciar Disciplinas
                    </a>
                </div>
            </div>

            <div>
                <label for="description" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
                    Descrição
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="textarea textarea-bordered w-full"></textarea>
            </div>

            <div class="modal-action">
                <button
                    type="submit"
                    class="btn btn-primary inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    Adicionar nova tarefa
                </button>
            </div>
        </form>
    </div>
</div>