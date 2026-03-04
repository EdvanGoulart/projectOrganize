<div class="w-full">
    <div class="flex h-screen bg-gray-900 text-white">

        <?php require base_path('views/partials/_menuOptions.view.php') ?>

        <main id="conteudo" class="w-full lg:w-[80%] flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">

            <body>
                <div id="lista" class="max-w-6xl mx-auto">
                    <h1 class="text-2xl sm:text-3xl font-bold mb-2">🧠 Meus Decks</h1>
                    <p class="text-sm text-gray-300 mb-6">Acompanhe sua etapa de revisão e estude no momento certo.</p>

                    <div id="lista" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-5">
                        <?php foreach ($deckList as $d): ?>
                            <div class="deckItem rounded-2xl border border-slate-700/70 bg-slate-800/80 shadow-lg hover:shadow-xl hover:border-slate-500 transition">
                                <div class="card-body p-4 sm:p-5">
                                    <div class="flex items-start gap-3">
                                        <h2 class="card-title flex-1 text-lg sm:text-xl text-white"><?= $d->title ?></h2>
                                        <button class="btn-delete p-2 rounded-md  cursor-pointer" data-id="<?= $d->id; ?>">
                                            <i class=" fa-solid fa-trash text-red-600"></i>
                                        </button>
                                    </div>

                                    <p class="text-sm text-slate-200 min-h-[44px]"><?= htmlspecialchars($d->description) ?></p>

                                    <div class="flex-1 text-xs text-slate-200 bg-slate-900/40 rounded-lg p-3">
                                        <hr class="mt-2 mb-1">
                                        <strong>Última Revisão:</strong> <?= $d->ultima_revisao ? date('d/m/Y', strtotime($d->ultima_revisao)) : 'Sem revisão' ?>
                                        / <strong>Próxima:</strong> <?= $d->proxima_revisao ?>
                                        <div class="mt-1 opacity-80">
                                            <strong>Etapa:</strong> <?= $d->etapa_revisao ?>
                                        </div>
                                    </div>
                                    <div class="card-actions flex-wrap justify-end gap-2 mt-3">
                                        <a class="btn btn-sm btn-outline btn-edit-deck" data-id="<?= $d->id; ?>">Cards</a>
                                        <a href="/deck/practice?id=<?= $d->id ?>" class="btn btn-sm btn-success">Estudar</a>
                                        <?php if (isset($pendMap[$d->id])): ?>
                                            <span class="badge badge-secondary ml-2"><?= $pendMap[$d->id] ?> revisões hoje</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- FORMULÁRIO DE EDIÇÃO -->
                <div id="editDeckSection" class="hidden">
                    <?php require base_path('views/deck/edit.view.php') ?>
                </div>

            </body>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('#formDeck').on('submit', function(e) {
        e.preventDefault();
        const data = $(this).serialize();
        $.post('../router.php?action=create_deck', data, function(res) {
            if (res.ok) location.reload();
        }, 'json');
    });

    $(document).on('click', '.btn-edit-deck', function(e) {
        e.preventDefault();
        const id = $(this).data('id');

        // Faz requisição AJAX para pegar os dados do deck + cards
        $.ajax({
            url: '/deck/edit', // endpoint PHP que retorna os dados
            type: 'POST',
            data: {
                id
            },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    $('#deckId').val(res.data.deck.id);
                    $('#titulo').val(res.data.deck.title);
                    $('#descricao').val(res.data.deck.description);

                    let idDeck = res.data.deck.id;


                    let disciplinaSelecionada = res.data.discipline_check.id

                    let select = $('#discipline');
                    select.empty(); // remove tudo

                    $.each(res.data.disciplineAll, function(index, item) {
                        if (item.id == disciplinaSelecionada) {
                            select.append(
                                `<option value="${item.id}" selected>${item.name}</option>`
                            );
                        } else {
                            select.append(
                                `<option value="${item.id}">${item.name}</option>`
                            );
                        }

                    });

                    // Limpa lista de cards
                    $('#listaCards').empty();
                    let ultimoId = 0;
                    // Renderiza os cards existentes
                    res.data.cards.forEach(card => {
                        $('#listaCards').append(`

                        <div class="cardItem bg-[#2a2f45] p-4 rounded-xl animate__animated animate__fadeIn" data-id="${card.id}" data-iddeck="${idDeck}">
                        <div class="flex space-x-4">
                            <input type="hidden"name="cards[${card.id}][id]" value="${card.id}"/>
                            <input type="text"name="cards[${card.id}][termo]" value="${card.termo}" placeholder="Termo"
                                class="flex-1 bg-[#1e2130] text-white p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400" />
                            <input type="text" name="cards[${card.id}][definicao]" value="${card.definicao}" placeholder="Definição"
                                class="flex-1 bg-[#1e2130] text-white p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400" />
                            <button type="button" class="btnRemover text-red-400 font-bold ml-2">✕</button>
                        </div>
                    </div>
                        

                    `);
                        if (Number(card.id) > ultimoId) {
                            ultimoId = Number(card.id);
                        }
                    });

                    window.ultimoIdCard = ultimoId;
                    window.idDeckGlobal = idDeck;

                    // Oculta lista e mostra o editor
                    $('#lista').addClass('hidden');
                    $('#editDeckSection').removeClass('hidden');
                } else {
                    Swal.fire('Erro', 'Deck não encontrado.', 'error');
                }
            },
            error: function() {
                Swal.fire('Erro', 'Falha ao carregar deck.', 'error');
            }
        });
    });

    $('#btnAddCard').on('click', function() {
        console.log(window.ultimoIdCard++);
        let cardIndex = window.ultimoIdCard++; // garante sequência
        let idDeck = window.idDeckGlobal; // garante sequência

        $('#listaCards').append(`
        <div class="cardItem bg-[#2a2f45] p-4 rounded-xl animate__animated animate__fadeIn" data-id="${cardIndex}" data-iddeck="${idDeck}">
            <div class="flex space-x-4">
                <input type="hidden" name="cards_new[${cardIndex}][id]" value="${cardIndex}"/>
                <input type="text" name="cards_new[${cardIndex}][termo]" placeholder="Termo"
                    class="flex-1 bg-[#1e2130] text-white p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400" />
                <input type="text" name="cards_new[${cardIndex}][definicao]" placeholder="Definição"
                    class="flex-1 bg-[#1e2130] text-white p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400" />
                <button type="button" class="btnRemover text-red-400 font-bold ml-2">✕</button>
            </div>
        </div>
    `);
    });


    // ❌ Remover um cartão
    $(document).on('click', '.btnRemover', function() {

        let card = $(this).closest('.cardItem');
        let id = card.data('id');
        let idDeck = card.data('iddeck');

        if (!id || !idDeck) {
            Swal.fire({
                icon: "error",
                title: "Erro",
                text: "ID do card ou do deck não encontrado.",
            });
            return;
        }

        Swal.fire({
            title: "Excluir cartão?",
            text: "Esta ação não poderá ser desfeita.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sim, excluir",
            cancelButtonText: "Cancelar"
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: '/cards/delete',
                    method: 'POST',
                    data: {
                        id: id,
                        idDeck: idDeck
                    },
                    success: function(response) {

                        if (response.success) {

                            Swal.fire({
                                icon: "success",
                                title: "Excluído!",
                                text: "O cartão foi removido."
                            });

                            card.fadeOut(200, function() {
                                $(this).remove();
                            });

                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Erro",
                                text: "Não foi possível excluir o cartão."
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: "error",
                            title: "Erro",
                            text: "Ocorreu um problema ao excluir. Tente novamente."
                        });
                    }
                });

            }
        });
    });



    $('#formDeckEdit').on('submit', function(e) {
        e.preventDefault();
        const data = $(this).serialize();
        $.post('/deck/update', data, function(res) {
            if (res.success) {
                Swal.fire('Sucesso!', 'Deck atualizado com sucesso!', 'success');

            } else if (res.validacao) {
                Swal.fire({
                    icon: "warning",
                    title: "Atenção",
                    text: "O campo termo e definição devem ser preenchidos !"
                });
            } else {
                Swal.fire('Erro', res.message, 'error');
            }
        }, 'json');
    });


    // Deletar
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const deck = $(this).closest('.deckItem');

        Swal.fire({
            title: 'Tem certeza?',
            text: 'Esta ação irá deletar este Deck e todos os cards !',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, deletar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/deck/delete',
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
                            deck.fadeOut(500, function() {
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
</script>