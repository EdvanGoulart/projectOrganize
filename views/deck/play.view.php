<div class="w-full">
    <div class="flex h-screen bg-gray-900 text-white">

        <main id="conteudo" class="w-full lg:w-[80%] flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">

            <div class="max-w-4xl mx-auto text-white px-1">

                <!-- TÍTULO -->
                <div class="mb-6 rounded-2xl border border-slate-700/60 bg-slate-800/60 p-4 sm:p-6 shadow-xl shadow-black/20 backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-300 mb-2">Modo de revisão</p>
                    <h1 class="text-2xl sm:text-3xl font-bold text-center sm:text-left break-words">
                        Estudando: <?= $deck->title ?>
                    </h1>
                </div>

                <!-- BARRA DE PROGRESSO -->
                <div class="w-full bg-slate-700/70 rounded-full h-3 mb-3 overflow-hidden">
                    <div id="progressBar" class="h-3 rounded-full bg-gradient-to-r from-indigo-500 via-sky-500 to-cyan-400 transition-all duration-500"></div>
                </div>

                <!-- TEXTO DE PROGRESSO -->
                <p id="progressText" class="text-center mb-6 text-slate-300 text-base sm:text-lg">
                    Card 1 de <?= count($cards) ?>
                </p>

                <!-- FLASHCARD -->
                <div class="perspective w-full flex justify-center">
                    <div id="flashcard" class="relative w-full bg-transparent h-[21rem] sm:h-80 lg:h-[22rem]">

                        <div id="cardInner"
                            class="relative w-full h-full text-center transition-transform duration-500 transform-style-3d">

                            <!-- FRENTE -->
                            <div class="absolute w-full h-full backface-hidden rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 shadow-xl p-5 sm:p-6">

                                <!-- TOPO -->
                                <div class="flex justify-between items-center text-xs sm:text-sm text-slate-300 mb-4">
                                    <span>🧠 Flashcard</span>
                                    <span>⏱️ <span id="timer">0</span>s</span>
                                </div>

                                <!-- CONTEÚDO -->
                                <div class="flex items-center justify-center h-[calc(100%-2rem)] sm:h-[calc(100%-2.5rem)] px-2">
                                    <span id="card-front" class="text-xl sm:text-2xl lg:text-3xl font-semibold leading-relaxed"></span>
                                </div>

                            </div>

                            <!-- VERSO -->
                            <div
                                class="absolute w-full h-full backface-hidden rounded-2xl border border-indigo-400/20 bg-gradient-to-br from-slate-700 to-slate-800 shadow-xl p-5 sm:p-6 rotate-y-180">

                                <!-- TOPO -->
                                <div class="flex justify-between items-center text-xs sm:text-sm text-slate-300 mb-4">
                                    <span>📘 Resposta</span>
                                    <span>⏱️ <span id="timer-back">0</span>s</span>
                                </div>

                                <!-- CONTEÚDO -->
                                <div class="flex items-center justify-center h-[calc(100%-2rem)] sm:h-[calc(100%-2.5rem)] px-2">
                                    <span id="card-back" class="text-xl sm:text-2xl lg:text-3xl font-semibold leading-relaxed text-cyan-100"></span>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <!-- BOTÕES -->
                <div class="mt-6 text-center">

                    <button id="btnShow"
                        class="w-full sm:w-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-500 rounded-xl font-semibold text-base sm:text-lg transition border border-indigo-400/40 shadow-lg shadow-indigo-900/30">
                        Mostrar resposta (Espaço)
                    </button>

                    <div id="answerButtons" class="hidden mt-4 flex flex-col sm:flex-row justify-center gap-3 sm:gap-4">
                        <button
                            id="btnRight"
                            class="w-full sm:w-auto px-6 py-3 bg-emerald-600 hover:bg-emerald-500 rounded-xl font-semibold text-base sm:text-lg transition border border-emerald-300/30">
                            Acertei (→)
                        </button>

                        <button
                            id="btnWrong"
                            class="w-full sm:w-auto px-6 py-3 bg-emerald-600 hover:bg-emerald-500 rounded-xl font-semibold text-base sm:text-lg transition border border-emerald-300/30">
                            Errei (←)
                        </button>
                    </div>

                </div>

            </div>

            <div id="reviewStatusMessage" class="hidden mt-4 rounded-xl border px-4 py-3 text-sm"></div>

            <!-- FEEDBACK FINAL -->
            <div id="finalFeedback" class="hidden mt-10 rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-800 via-slate-800 to-slate-900 p-5 sm:p-8 shadow-2xl shadow-black/30">

                <h2 class="text-2xl sm:text-3xl font-bold text-center mb-6">
                    📊 Resultado da Revisão
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                    <div class="rounded-xl border border-slate-700 bg-slate-900/60 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-400 mb-2">Resumo</p>
                        <div class="space-y-2 text-sm sm:text-base">
                            <p>📚 <strong>Total de cards:</strong> <span id="fbTotal"></span></p>
                            <p>✅ <strong>Acertos:</strong> <span id="fbAcertos" class="text-emerald-400"></span></p>
                            <p>❌ <strong>Erros:</strong> <span id="fbErros" class="text-rose-400"></span></p>
                            <p>⏱️ <strong>Tempo total:</strong> <span id="fbTempo"></span>s</p>
                        </div>
                    </div>

                    <div class="rounded-xl border border-indigo-400/20 bg-indigo-500/10 p-4 flex flex-col justify-center items-center text-center">
                        <p class="text-xs uppercase tracking-wide text-slate-300 mb-2">Aproveitamento</p>
                        <p id="fbScore" class="text-4xl sm:text-5xl font-bold text-cyan-300">0%</p>
                        <p id="fbMessage" class="text-sm sm:text-base text-slate-200 mt-2"></p>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <a href="/deck-list" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold transition shadow-lg shadow-indigo-900/30">
                        Voltar para meus decks
                    </a>
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

                const score = cards.length ? Math.round((totalAcertos / cards.length) * 100) : 0;
                $("#fbScore").text(`${score}%`);

                let scoreMessage = "Bom começo! Continue revisando para consolidar melhor.";
                if (score >= 90) {
                    scoreMessage = "Excelente desempenho! Seu domínio desse deck está muito forte. 🚀";
                } else if (score >= 70) {
                    scoreMessage = "Ótimo resultado! Você está no caminho certo. 💪";
                } else if (score >= 50) {
                    scoreMessage = "Boa evolução! Mais algumas revisões e você sobe de nível. ✨";
                }
                $("#fbMessage").text(scoreMessage);

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