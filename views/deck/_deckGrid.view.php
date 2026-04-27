<div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 gap-4 sm:gap-5">
    <?php foreach ($deckList as $d): ?>
        <article class="deckItem rounded-2xl border border-slate-700/70 bg-gradient-to-br from-slate-800/95 to-slate-900/90 shadow-lg shadow-black/20 hover:shadow-2xl hover:shadow-indigo-900/20 hover:border-slate-500 transition duration-200">
            <div class="p-4 sm:p-5">
                <div class="flex items-start gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-[11px] uppercase tracking-[0.22em] text-slate-400 mb-1">Deck de estudo</p>
                        <h2 class="text-lg sm:text-xl font-semibold text-white break-words leading-tight">
                            <?= htmlspecialchars($d->title) ?>
                        </h2>
                    </div>
                    <button class="btn-delete inline-flex items-center justify-center w-9 h-9 rounded-lg border border-rose-400/30 bg-rose-500/10 hover:bg-rose-500/20 transition cursor-pointer" data-id="<?= $d->id; ?>" title="Excluir deck">
                        <i class="fa-solid fa-trash text-rose-300 text-sm"></i>
                    </button>
                </div>

                <p class="mt-3 text-sm text-slate-300 min-h-[42px] break-words">
                    <?= htmlspecialchars($d->description ?: 'Sem descrição cadastrada para este deck.') ?>
                </p>

                <div class="mt-4 rounded-xl border border-slate-700 bg-slate-900/50 p-3 space-y-2 text-xs sm:text-sm text-slate-200">
                    <p>
                        <span class="text-slate-400">Última revisão:</span>
                        <strong class="font-semibold text-slate-100"><?= $d->ultima_revisao ? date('d/m/Y', strtotime($d->ultima_revisao)) : 'Sem revisão' ?></strong>
                    </p>
                    <p>
                        <span class="text-slate-400">Próxima revisão:</span>
                        <strong class="font-semibold text-cyan-200"><?= htmlspecialchars($d->proxima_revisao) ?></strong>
                    </p>
                    <p>
                        <span class="text-slate-400">Etapa:</span>
                        <span class="inline-flex items-center rounded-md border border-indigo-400/30 bg-indigo-500/10 px-2 py-0.5 text-indigo-200 font-medium">
                            <?= htmlspecialchars($d->etapa_revisao) ?>
                        </span>
                    </p>

                    <?php if (!empty($d->aviso_revisao)): ?>
                        <div class="mt-2 rounded-md border border-amber-500/40 bg-amber-900/30 p-2 text-amber-200">
                            ⚠️ <?= htmlspecialchars($d->aviso_revisao) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <?php if (isset($pendMap[$d->id])): ?>
                        <span class="inline-flex items-center w-fit rounded-full border border-violet-400/30 bg-violet-500/15 px-3 py-1 text-xs text-violet-200">
                            <?= $pendMap[$d->id] ?> revisão(ões) hoje
                        </span>
                    <?php endif; ?>

                    <div class="flex w-full sm:w-auto flex-col sm:flex-row gap-2 sm:justify-end">
                        <a class="btn-edit-deck inline-flex justify-center items-center px-4 py-2 rounded-lg border border-slate-500 text-slate-100 hover:bg-slate-700/70 text-sm font-medium transition" data-id="<?= $d->id; ?>">Cards</a>
                        <button type="button" class="btn-history-deck inline-flex justify-center items-center px-4 py-2 rounded-lg border border-cyan-400/40 bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-100 text-sm font-semibold transition" data-id="<?= $d->id; ?>" data-title="<?= htmlspecialchars($d->title) ?>">Histórico</button>
                        <a href="/deck/practice?id=<?= $d->id ?>" class="inline-flex justify-center items-center px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold transition">Estudar</a>
                    </div>
                </div>
        </article>
    <?php endforeach; ?>
    <?php if (empty($deckList)): ?>
        <div class="col-span-full rounded-2xl border border-slate-700 bg-slate-800/60 p-8 text-center text-slate-200">
            <p class="text-base sm:text-lg font-semibold mb-1">Nenhum deck encontrado</p>
            <p class="text-sm text-slate-400">Tente mudar o filtro de revisão para visualizar outros decks.</p>
        </div>
    <?php endif; ?>
</div>