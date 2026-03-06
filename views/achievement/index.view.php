<div class="w-full p-6 bg-base-200 min-h-screen">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold">Quadro de Conquistas</h1>
            <p class="text-sm text-gray-400">Acompanhe seus troféus e descubra quais metas faltam.</p>
        </div>
        <div class="stats shadow bg-base-100">
            <div class="stat">
                <div class="stat-title">Conquistas desbloqueadas</div>
                <div class="stat-value text-primary"><?= $board['unlocked'] ?>/<?= $board['total'] ?></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        <?php foreach ($board['achievements'] as $achievement) : ?>
            <?php
            $badgeClass = $achievement['isUnlocked'] ? 'badge-success' : 'badge-neutral';
            $progressClass = $achievement['isUnlocked'] ? 'progress-success' : 'progress-warning';
            ?>
            <article class="card bg-base-100 shadow-md border border-base-300">
                <div class="card-body">
                    <div class="flex justify-between items-start gap-2">
                        <h2 class="card-title"><?= $achievement['icon'] ?> <?= htmlspecialchars($achievement['title']) ?></h2>
                        <span class="badge <?= $badgeClass ?>"><?= $achievement['isUnlocked'] ? 'Desbloqueada' : 'Em progresso' ?></span>
                    </div>

                    <p class="text-sm text-gray-400"><?= htmlspecialchars($achievement['description']) ?></p>

                    <div>
                        <div class="flex justify-between text-xs text-gray-400 mb-2">
                            <span><?= $achievement['progress'] ?>/<?= $achievement['target'] ?> <?= htmlspecialchars($achievement['metricLabel']) ?></span>
                            <span><?= (int) round(($achievement['progress'] / $achievement['target']) * 100) ?>%</span>
                        </div>
                        <progress class="progress <?= $progressClass ?> w-full" value="<?= $achievement['progress'] ?>" max="<?= $achievement['target'] ?>"></progress>
                    </div>

                    <?php if ($achievement['isUnlocked']) : ?>
                        <p class="text-xs text-success mt-2">
                            Desbloqueada em <?= date('d/m/Y H:i', strtotime((string) $achievement['unlockedAt'])) ?>
                        </p>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</div>