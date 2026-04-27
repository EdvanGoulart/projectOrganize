<div class="w-full">
    <div class="flex  bg-gray-900 text-white">



        <main id="conteudo" class="w-full lg:w-[80%] flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">

            <div id="lista" class=" mx-auto">
                <section class="mb-6 rounded-2xl border border-slate-700/70 bg-gradient-to-br from-slate-800/95 to-slate-900/90 p-5 sm:p-6 shadow-xl shadow-black/20">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.25em] text-slate-400 mb-2">Revisão inteligente</p>
                            <h1 class="text-2xl sm:text-3xl font-bold mb-2">🧠 Meus Decks</h1>
                            <p class="text-sm text-slate-300">Acompanhe sua etapa de revisão e estude no momento certo.</p>
                        </div>

                        <a href="/deck/formCreateDeck" class="inline-flex items-center justify-center w-full md:w-auto px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold transition shadow-lg shadow-indigo-900/30">
                            + Novo deck
                        </a>
                    </div>
                </section>

                <form id="filtroEtapaForm" method="GET" class="mb-5 rounded-xl border border-slate-700 bg-slate-800/70 p-4 sm:p-5">
                    <div class="flex flex-col sm:flex-row sm:items-end gap-3">
                        <div class="w-full sm:w-80">
                            <label for="filtro_etapa" class="block text-xs uppercase tracking-wide text-slate-300 mb-1">Filtrar por etapa de revisão</label>
                            <select id="filtro_etapa" name="filtro_etapa" class="select select-bordered w-full bg-slate-900 border-slate-600 text-white">
                                <option value="" <?= empty($filtroEtapaSelecionado) ? 'selected' : '' ?>>Todas as etapas</option>
                                <option value="hoje" <?= ($filtroEtapaSelecionado ?? '') === 'hoje' ? 'selected' : '' ?>>Revisão hoje / atrasados</option>
                                <option value="amanha" <?= ($filtroEtapaSelecionado ?? '') === 'amanha' ? 'selected' : '' ?>>Revisão amanhã</option>
                                <option value="3dias" <?= ($filtroEtapaSelecionado ?? '') === '3dias' ? 'selected' : '' ?>>Próximos 3 dias</option>
                                <option value="7dias" <?= ($filtroEtapaSelecionado ?? '') === '7dias' ? 'selected' : '' ?>>Próximos 7 dias</option>
                                <option value="30dias" <?= ($filtroEtapaSelecionado ?? '') === '30dias' ? 'selected' : '' ?>>Próximos 30 dias</option>
                                <option value="90dias" <?= ($filtroEtapaSelecionado ?? '') === '90dias' ? 'selected' : '' ?>>Ciclo contínuo (90+ dias)</option>
                            </select>
                        </div>

                    </div>
                </form>

                <div id="modalHistorico" class="hidden fixed inset-0 z-50 bg-black/70 p-4">
                    <div class="max-w-5xl mx-auto mt-8 rounded-2xl border border-slate-700 bg-slate-900 shadow-2xl max-h-[85vh] overflow-y-auto">
                        <div class="flex items-center justify-between p-4 border-b border-slate-700">
                            <h2 id="historicoTitulo" class="text-xl font-bold">Histórico do deck</h2>
                            <button type="button" id="fecharHistorico" class="text-slate-300 hover:text-white">✕</button>
                        </div>
                        <div id="historicoConteudo" class="p-4 text-slate-200"></div>
                    </div>
                </div>

                <div id="deckGrid">
                    <?php require base_path('views/deck/_deckGrid.view.php') ?>
                </div>

            </div>

            <!-- FORMULÁRIO DE EDIÇÃO -->
            <div id="editDeckSection" class="hidden">
                <?php require base_path('views/deck/edit.view.php') ?>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function carregarDecksPorEtapa(filtroEtapa = '') {
        $.ajax({
            url: '/deck-list',
            type: 'GET',
            data: {
                filtro_etapa: filtroEtapa
            },
            dataType: 'json',
            success: function(res) {
                if (!res.success) {
                    Swal.fire('Erro', 'Não foi possível aplicar o filtro agora.', 'error');
                    return;
                }

                $('#deckGrid').html(res.html);
            },
            error: function() {
                Swal.fire('Erro', 'Falha ao atualizar os decks por etapa.', 'error');
            }
        });
    }

    $('#filtro_etapa').on('change', function() {
        const filtro = $(this).val();
        carregarDecksPorEtapa(filtro);
    });

    $('#limparFiltroEtapa').on('click', function() {
        $('#filtro_etapa').val('');
        carregarDecksPorEtapa('');
    });

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


                    let disciplinaSelecionada = res.data.discipline_check?.id ?? '';

                    let select = $('#discipline');
                    select.empty();
                    select.append('<option value="">Selecione uma disciplina</option>');

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
                            <input type="text"name="cards[${card.id}][term]" value="${card.term}" placeholder="Termo"
                                class="flex-1 bg-[#1e2130] text-white p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400" />
                            <input type="text" name="cards[${card.id}][definition]" value="${card.definition}" placeholder="Definição"
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

        let cardIndex = window.ultimoIdCard++; // garante sequência
        let idDeck = window.idDeckGlobal;

        $('#listaCards').append(`
        <div class="cardItem bg-[#2a2f45] p-4 rounded-xl animate__animated animate__fadeIn" data-id="${cardIndex}" data-iddeck="${idDeck}" data-new="1">
            <div class="flex space-x-4">
                <input type="hidden" name="cards_new[${cardIndex}][id]" value="${cardIndex}"/>
                <input type="text" name="cards_new[${cardIndex}][term]" placeholder="Termo"
                    class="flex-1 bg-[#1e2130] text-white p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400" />
                <input type="text" name="cards_new[${cardIndex}][definition]" placeholder="Definição"
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

        if (card.data('new') === 1 || card.data('new') === '1') {
            card.fadeOut(150, function() {
                $(this).remove();
            });
            return;
        }

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

        const disciplina = $('#discipline').val();
        if (!disciplina) {
            Swal.fire({
                icon: 'warning',
                title: 'Disciplina obrigatória',
                text: 'Selecione uma disciplina para continuar.'
            });
            return;
        }

        const data = $(this).serialize();
        $.post('/deck/update', data, function(res) {
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: 'Deck atualizado com sucesso!',
                    confirmButtonText: 'Voltar para meus decks'
                }).then(() => {
                    window.location.href = '/deck-list';
                });

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

    $(document).on('click', '#btnCancelEditDeck', function() {
        $('#editDeckSection').addClass('hidden');
        $('#lista').removeClass('hidden');
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

    function formatSecondsToLabel(totalSeconds) {
        const secs = Number(totalSeconds) || 0;
        if (secs < 60) return `${secs}s`;

        const min = Math.floor(secs / 60);
        const rem = secs % 60;

        return `${min}m ${rem}s`;
    }

    function renderHistoricoRevisoes(deck) {
        if (!deck) {
            return '<p class="text-slate-300">Este deck ainda não possui revisões registradas.</p>';
        }

        const cardsRows = (deck.cards || []).map(card => `
            <tr class="border-t border-slate-700/80">
                <td class="py-2 pr-2">${card.card_term || 'Card removido'}</td>
                <td class="py-2 pr-2 text-center">${card.acertos}</td>
                <td class="py-2 pr-2 text-center">${card.erros}</td>
                <td class="py-2 pr-2 text-center">${formatSecondsToLabel(card.tempo_total)}</td>
                <td class="py-2 pr-2 text-center">${formatSecondsToLabel(card.tempo_medio)}</td>
                <td class="py-2 text-center font-semibold text-cyan-200">${card.aproveitamento}%</td>
            </tr>
        `).join('');

        return `
            <section class="mb-5 rounded-xl border border-slate-700 bg-slate-800/60 p-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm mb-3">
                    <div class="rounded-lg bg-slate-900/70 p-2">Acertos: <strong>${deck.acertos}</strong></div>
                    <div class="rounded-lg bg-slate-900/70 p-2">Erros: <strong>${deck.erros}</strong></div>
                    <div class="rounded-lg bg-slate-900/70 p-2">Revisões: <strong>${deck.total_revisoes}</strong></div>
                    <div class="rounded-lg bg-slate-900/70 p-2">Tempo total: <strong>${formatSecondsToLabel(deck.tempo_total)}</strong></div>
                </div>
                <p class="text-sm text-slate-200 mb-3">Aproveitamento geral: <strong class="text-cyan-200">${deck.aproveitamento}%</strong></p>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-slate-300 border-b border-slate-700">
                            <tr>
                                <th class="py-2 pr-2">Card</th>
                                <th class="py-2 pr-2 text-center">Acertos</th>
                                <th class="py-2 pr-2 text-center">Erros</th>
                                <th class="py-2 pr-2 text-center">Tempo total</th>
                                <th class="py-2 pr-2 text-center">Tempo médio</th>
                                <th class="py-2 text-center">Aproveitamento</th>
                            </tr>
                        </thead>
                        <tbody>${cardsRows}</tbody>
                    </table>
                </div>
            </section>
        `;
    }

    $(document).on('click', '.btn-history-deck', function() {
        const idDeck = $(this).data('id');
        const tituloDeck = $(this).data('title') || 'Deck';
        const container = $('#historicoConteudo');

        $('#historicoTitulo').text(`Histórico do deck: ${tituloDeck}`);
        container.html('<p class="text-slate-300">Carregando histórico...</p>');
        $('#modalHistorico').removeClass('hidden');

        $.ajax({
            url: '/deck/review/historico',
            type: 'GET',
            data: {
                id_deck: idDeck
            },
            dataType: 'json',
            success: function(res) {
                if (!res.success) {
                    container.html(`<p class="text-red-300">${res.message || 'Não foi possível carregar o histórico do deck.'}</p>`);
                    return;
                }

                container.html(renderHistoricoRevisoes(res.data));
            },
            error: function() {
                container.html('<p class="text-red-300">Erro de comunicação ao buscar o histórico do deck.</p>');
            }
        });
    });

    $('#fecharHistorico').on('click', function() {
        $('#modalHistorico').addClass('hidden');
    });
</script>