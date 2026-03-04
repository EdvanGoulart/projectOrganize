<div class="w-full">
    <div class="flex h-screen bg-gray-900 text-white">

        <?php require base_path('views/partials/_menuOptions.view.php') ?>

        <main id="conteudo" class="w-full lg:w-[80%] flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">

            <div class="max-w-xl mx-auto text-white px-1">

                <!-- TÍTULO -->
                <h1 class="text-3xl font-bold mb-6 text-center">
                    Estudando: <?= $deck->title ?>
                </h1>

                <!-- BARRA DE PROGRESSO -->
                <div class="w-full bg-gray-700 rounded-full h-3 mb-3">
                    <div id="progressBar" class="bg-blue-600 h-3 rounded-full transition-all"></div>
                </div>

                <!-- TEXTO DE PROGRESSO -->
                <p id="progressText" class="text-center mb-6 text-gray-300 text-lg">
                    Card 1 de <?= count($cards) ?>
                </p>

                <!-- FLASHCARD -->
                <div class="perspective w-full flex justify-center">
                    <div id="flashcard" class="relative w-full bg-transparent h-64">

                        <div id="cardInner"
                            class="relative w-full h-full text-center transition-transform duration-500 transform-style-3d">

                            <!-- FRENTE -->
                            <div class="absolute w-full h-full backface-hidden bg-gray-800 rounded-xl shadow-xl p-6">

                                <!-- TOPO -->
                                <div class="flex justify-between items-center text-sm text-gray-300 mb-4">
                                    <span>🧠 Flashcard</span>
                                    <span>⏱️ <span id="timer">0</span>s</span>
                                </div>

                                <!-- CONTEÚDO -->
                                <div class="flex items-center justify-center h-full">
                                    <span id="card-front" class="text-2xl font-semibold"></span>
                                </div>

                            </div>

                            <!-- VERSO -->
                            <div
                                class="absolute w-full h-full backface-hidden bg-gray-700 rounded-xl shadow-xl p-6 rotate-y-180">

                                <!-- TOPO -->
                                <div class="flex justify-between items-center text-sm text-gray-300 mb-4">
                                    <span>📘 Resposta</span>
                                    <span>⏱️ <span id="timer-back">0</span>s</span>
                                </div>

                                <!-- CONTEÚDO -->
                                <div class="flex items-center justify-center h-full">
                                    <span id="card-back" class="text-2xl font-semibold"></span>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <!-- BOTÕES -->
                <div class="mt-6 text-center">

                    <button id="btnShow"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg font-bold text-lg transition">
                        Mostrar resposta (Espaço)
                    </button>

                    <div id="answerButtons" class="hidden mt-4 flex justify-center space-x-4">
                        <button
                            id="btnRight"
                            class="px-6 py-3 bg-green-600 hover:bg-green-700 rounded-lg font-bold text-lg transition">
                            Acertei (→)
                        </button>

                        <button
                            id="btnWrong"
                            class="px-6 py-3 bg-red-600 hover:bg-red-700 rounded-lg font-bold text-lg transition">
                            Errei (←)
                        </button>
                    </div>

                </div>

            </div>

            <div id="reviewStatusMessage" class="hidden mt-4 rounded-lg border px-4 py-3 text-sm"></div>

            <!-- FEEDBACK FINAL -->
            <div id="finalFeedback" class="hidden mt-10 bg-gray-800 rounded-xl p-6 shadow-xl">

                <h2 class="text-2xl font-bold text-center mb-6">
                    📊 Resultado da Revisão
                </h2>

                <div class="space-y-3 text-lg">
                    <p>📚 <strong>Total de cards:</strong> <span id="fbTotal"></span></p>
                    <p>✅ <strong>Acertos:</strong> <span id="fbAcertos" class="text-green-400"></span></p>
                    <p>❌ <strong>Erros:</strong> <span id="fbErros" class="text-red-400"></span></p>
                    <p>⏱️ <strong>Tempo total:</strong> <span id="fbTempo"></span>s</p>
                </div>

            </div>


        </main>
    </div>
</div>


<style>
    .perspective {
        perspective: 1000px;
    }

    .backface-hidden {
        backface-visibility: hidden;
    }

    .rotate-y-180 {
        transform: rotateY(180deg);
    }

    .transform-style-3d {
        transform-style: preserve-3d;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        let cards = <?= json_encode($cards) ?>;
        let index = 0;
        let flipped = false;

        // TIMER
        let startTime = null;
        let timerInterval = null;
        let elapsed = 0;

        let totalAcertos = 0;
        let totalErros = 0;
        let tempoTotal = 0;

        function startTimer() {
            startTime = Date.now();
            elapsed = 0;

            $("#timer").text("0");
            $("#timer-back").text("0");

            clearInterval(timerInterval);
            timerInterval = setInterval(() => {
                elapsed = Math.floor((Date.now() - startTime) / 1000);
                $("#timer").text(elapsed);
                $("#timer-back").text(elapsed);
            }, 1000);
        }


        function stopTimer() {
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }
            return elapsed;
        }


        function flipCard() {
            if (!flipped) {
                $("#cardInner").css("transform", "rotateY(180deg)");
                flipped = true;
                $("#btnShow").hide();
                $("#answerButtons").show();
            }
        }

        function unflipCard() {
            $("#cardInner").css("transform", "rotateY(0deg)");
            flipped = false;
        }

        function renderCard() {

            const percent = (index / cards.length) * 100;
            $("#progressBar").css("width", percent + "%");
            $("#progressText").text(`Card ${Math.min(index + 1, cards.length)} de ${cards.length}`);

            // 🔚 FIM DO DECK
            if (index >= cards.length) {

                stopTimer();
                $("#timer").text("0");
                $("#timer-back").text("0");


                // Esconde área de estudo
                $("#flashcard").hide();
                $("#btnShow").hide();
                $("#answerButtons").hide();
                $("#progressText").text("Revisão finalizada");

                // Preenche feedback
                $("#fbTotal").text(cards.length);
                $("#fbAcertos").text(totalAcertos);
                $("#fbErros").text(totalErros);
                $("#fbTempo").text(tempoTotal);

                // Mostra feedback
                $("#finalFeedback").removeClass("hidden");

                // Envia resumo do deck
                $.post("/deck/revisao/finalizar", {
                    id_deck: <?= $deck->id ?>,
                    total_cards: cards.length,
                    total_acertos: totalAcertos,
                    total_erros: totalErros,
                    tempo_gasto: tempoTotal

                }).done(function(res) {
                    const box = $("#reviewStatusMessage");
                    box.removeClass("hidden border-green-500/40 bg-green-500/10 text-green-100 border-yellow-500/40 bg-yellow-500/10 text-yellow-100 border-red-500/40 bg-red-500/10 text-red-100");

                    if (res && res.success) {
                        box.addClass("border-green-500/40 bg-green-500/10 text-green-100");
                        box.html(`✅ ${res.data?.message ?? 'Revisão registrada com sucesso.'} <br><strong>Próxima revisão:</strong> ${res.data?.proxima_revisao ?? '-'}`);
                    } else if (res && res.data) {
                        box.addClass("border-yellow-500/40 bg-yellow-500/10 text-yellow-100");
                        box.html(`ℹ️ ${res.data.message ?? 'Sua revisão de hoje já foi contabilizada.'} <br><strong>Próxima revisão:</strong> ${res.data.proxima_revisao ?? '-'} | <strong>Etapa:</strong> ${res.data.etapa_revisao ?? '-'}`);
                    } else {
                        box.addClass("border-red-500/40 bg-red-500/10 text-red-100");
                        box.text("❌ Não foi possível atualizar o agendamento da revisão agora.");
                    }
                }).fail(function() {
                    const box = $("#reviewStatusMessage");
                    box.removeClass("hidden").addClass("border-red-500/40 bg-red-500/10 text-red-100");
                    box.text("❌ Falha de comunicação ao salvar sua revisão. Tente novamente.");
                });

                return;
            }


            // 🃏 CARD ATUAL
            $("#card-front").text(cards[index].termo);
            $("#card-back").text(cards[index].definicao);

            $("#btnShow").show();
            $("#answerButtons").hide();

            unflipCard();
            startTimer();
        }


        // BOTÕES DE RESPOSTA (MOUSE)
        $("#btnRight").on("click", function() {
            if (flipped) answerCard('right');
        });

        $("#btnWrong").on("click", function() {
            if (flipped) answerCard('wrong');
        });


        function answerCard(type) {

            if (!flipped) return; // segurança extra

            const tempoGasto = stopTimer();
            tempoTotal += tempoGasto;

            if (type === 'right') {
                totalAcertos++;
            } else {
                totalErros++;
            }

            // Envia card individual
            $.post("/deck/revisao/card", {
                id_deck: <?= $deck->id ?>,
                id_card: cards[index].id,
                resultado: type === 'right' ? 'acerto' : 'erro',
                tempo_gasto: tempoGasto
            });

            // 🔄 Primeiro desvira o card
            unflipCard();

            // ⏳ Aguarda a animação antes de trocar o conteúdo
            setTimeout(() => {
                index++;
                renderCard();
            }, 500); // MESMO tempo da animação CSS
        }



        // BOTÃO MOSTRAR
        $("#btnShow").on("click", flipCard);

        // TECLADO
        document.addEventListener("keydown", function(e) {

            if (e.code === "Space") {
                e.preventDefault();
                if (!flipped) flipCard();
            }

            if (e.code === "ArrowRight" && flipped) {
                answerCard('right');
            }

            if (e.code === "ArrowLeft" && flipped) {
                answerCard('wrong');
            }
        });

        // 🚀 INICIA O PRIMEIRO CARD
        renderCard();
    });
</script>