<div class="w-full">
    <div class="flex bg-gray-900 text-white min-h-screen">
        <main class="w-full lg:w-[80%] flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
            <div class="max-w-5xl mx-auto">
                <section class="mb-6 rounded-2xl border border-slate-700/70 bg-gradient-to-br from-slate-800/95 to-slate-900/90 p-5 sm:p-6 shadow-xl shadow-black/20">
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-400 mb-2">Decks de estudo</p>
                    <h1 class="text-2xl sm:text-3xl font-bold mb-2">➕ Criar novo deck</h1>
                    <p class="text-sm text-slate-300">Adicione o conteúdo do seu deck e organize seus cards para revisar com frequência.</p>
                </section>

                <form id="formDeck" class="space-y-6 rounded-2xl border border-slate-700 bg-slate-800/70 p-5 sm:p-6">
                    <input
                        type="text"
                        name="titulo"
                        placeholder="Título"
                        class="w-full bg-[#323644] text-white p-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400" />

                    <div>
                        <select id="discipline" name="discipline" class="select select-bordered w-full bg-[#323644] text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400">
                            <option value="">Selecione uma disciplina</option>
                            <?php foreach ($disciplineList as $discipline) : ?>
                                <option value="<?= $discipline->id ?>">
                                    <?= $discipline->name ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <!-- Campo descrição -->
                    <textarea
                        name="descricao"
                        placeholder="Descrição"
                        class="w-full bg-[#323644] text-white p-4 rounded-lg h-24 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400"></textarea>

                    <!-- Lista de cartões -->
                    <div id="listaCards" class="space-y-4">
                        <div class="cardItem bg-[#2a2f45] p-4 rounded-xl">
                            <div class="flex space-x-4">
                                <input
                                    type="text"
                                    name="cards[0][term]"
                                    placeholder="Termo"
                                    class="flex-1 bg-[#1e2130] text-white p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400" />
                                <input
                                    type="text"
                                    name="cards[0][definition]"
                                    placeholder="Definição"
                                    class="flex-1 bg-[#1e2130] text-white p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400" />
                            </div>
                        </div>
                    </div>

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
                            Criar deck
                        </button>
                    </div>
                </form>
            </div>
        </main>


    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(function() {
        let cardIndex = $('#listaCards .cardItem').length;

        // ➕ Adicionar novo cartão
        $('#btnAddCard').on('click', function() {
            const novoCard = `
        <div class="cardItem bg-[#2a2f45] p-4 rounded-xl animate__animated animate__fadeIn">
            <div class="flex space-x-4">
                <input type="text" name="cards[${cardIndex}][term]" placeholder="term"
                    class="flex-1 bg-[#1e2130] text-white p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400" />
                <input type="text" name="cards[${cardIndex}][definition]" placeholder="Definição"
                    class="flex-1 bg-[#1e2130] text-white p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400" />
                <button type="button" class="btnRemover text-red-400 font-bold ml-2">✕</button>
            </div>
        </div>`;
            $('#listaCards').append(novoCard);
            cardIndex++;
        });


        $(document).on('click', '.btnRemover', function() {
            $(this).closest('.cardItem').fadeOut(200, function() {
                $(this).remove();
            });
        });


        $('#formDeck').on('submit', function(e) {
            e.preventDefault();

            const titulo = $('input[name="titulo"]').val().trim();
            const disciplina = $('select[name="discipline"]').val();

            if (!titulo) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo obrigatório',
                    text: 'Digite um título para o deck antes de salvar.'
                });
                return;
            }

            if (!disciplina) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Disciplina obrigatória',
                    text: 'Selecione uma disciplina para continuar.'
                });
                return;
            }

            $.ajax({
                url: '/deck/create',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function() {
                    Swal.fire({
                        title: 'Salvando...',
                        text: 'Aguarde um momento enquanto o deck é criado.',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                },
                success: function(res) {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso!',
                            text: 'Deck criado com sucesso 🎉',
                            confirmButtonText: 'Voltar para meus decks'
                        }).then(() => {
                            window.location.href = '/deck-list';
                        });


                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro ao salvar',
                            text: res.message || 'Não foi possível criar o deck.'
                        });
                    }
                },
                error: function(err) {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro inesperado',
                        text: 'Ocorreu um erro na comunicação com o servidor.'
                    });
                }
            });
        });
    });
</script>