<div class="w-full">
    <div class="flex bg-gray-900 text-white min-h-screen">

        <main class="w-full lg:w-[80%] flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
            <div class="max-w-5xl mx-auto text-white">
                <section class="mb-6 rounded-2xl border border-slate-700/70 bg-gradient-to-br from-slate-800/95 to-slate-900/90 p-5 sm:p-6 shadow-xl shadow-black/20">
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-400 mb-2">Decks de estudo</p>
                    <h1 class="text-2xl sm:text-3xl font-bold mb-2">✏️ Editar deck</h1>
                    <p class="text-sm text-slate-300">Ajuste o título, a disciplina e os cards para manter seu material sempre atualizado.</p>
                </section>

                <form id="formDeckEdit" class="space-y-6 rounded-2xl border border-slate-700 bg-slate-800/70 p-5 sm:p-6">
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
                        <button type="button" id="btnCancelEditDeck" class="bg-slate-700 hover:bg-slate-600 text-white py-2 px-6 rounded-full">
                            Voltar
                        </button>
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