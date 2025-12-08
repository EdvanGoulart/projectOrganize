<div class="w-full">
    <div class="flex h-screen bg-gray-900 text-white">

        <main class="flex-1 flex justify-center items-start pt-10">
            <div class="w-4/5 text-white">
                <h1 class="text-2xl font-bold mb-4">Editar lista de cartões</h1>

                <form id="formDeckEdit" class="space-y-6">
                    <input type="hidden" name="id" id="deckId" />

                    <!-- Campo título -->
                    <input
                        type="text"
                        name="titulo"
                        id="titulo"
                        placeholder="Título"
                        class="w-full bg-[#323644] text-white p-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400" />

                    <!-- Disciplina -->
                    <div>
                        <select id="discipline" name="discipline" class="select select-bordered w-full bg-[#323644] text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400">
                            <option value="">Selecione uma disciplina</option>
                        </select>
                    </div>

                    <!-- Campo descrição -->
                    <textarea
                        name="descricao"
                        id="descricao"
                        placeholder="Descrição"
                        class="w-full bg-[#323644] text-white p-4 rounded-lg h-24 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400"></textarea>

                    <!-- Lista de cartões -->
                    <div id="listaCards" class="space-y-4"></div>

                    <!-- Botão adicionar cartão -->
                    <div class="flex justify-center pt-4">
                        <button id="btnAddCard"
                            type="button"
                            class="bg-[#3b3f53] hover:bg-[#4a4f66] py-2 px-6 rounded-full text-sm font-semibold transition">
                            ➕ Adicionar cartão
                        </button>
                    </div>

                    <!-- Botões finais -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-full">
                            Salvar alterações
                        </button>
                    </div>
                </form>
            </div>
        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
</script>