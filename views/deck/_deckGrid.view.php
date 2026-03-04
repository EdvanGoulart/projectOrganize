<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-5">
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
                    <p class="mb-1"> <strong>Última Revisão:</strong> <?= $d->ultima_revisao ? date('d/m/Y', strtotime($d->ultima_revisao)) : 'Sem revisão' ?></p>
                    <p> <strong>Próxima Revisão:</strong> <?= $d->proxima_revisao ?></p>

                    <div class="mt-1">
                        <p><strong>Etapa:</strong> <span class="opacity-80"><?= $d->etapa_revisao ?></span></p>
                    </div>
                    <?php if (!empty($d->aviso_revisao)): ?>
                        <div class="mt-2 rounded-md border border-amber-</span>500/40 bg-amber-900/30 p-2 text-amber-200">
                            ⚠️ <?= htmlspecialchars($d->aviso_revisao) ?>
                        </div>
                    <?php endif; ?>
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
    <?php if (empty($deckList)): ?>
        <div class="col-span-full rounded-xl border border-slate-700 bg-slate-800/60 p-6 text-center text-slate-200">
            Nenhum deck encontrado para esse filtro de revisão.
        </div>
    <?php endif; ?>
</div>