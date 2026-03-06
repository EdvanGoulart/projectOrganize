<?php
$xpLabels = json_encode($dashboard['xpHistoryLabels'], JSON_UNESCAPED_UNICODE);
$xpValues = json_encode($dashboard['xpHistoryValues'], JSON_UNESCAPED_UNICODE);
?>


<div class="w-full p-6 bg-base-200 min-h-screen">
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-6 mb-8">

        <!-- XP / Nível -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title">Nível</h2>
                <p class="text-3xl font-bold text-primary"><?= $dashboard['level'] ?></p>
                <progress class="progress progress-primary w-full" value="<?= $dashboard['xpCurrentLevel'] ?>" max="<?= $dashboard['xpNextLevel'] ?>"></progress>
                <p class="text-sm text-gray-500"><?= $dashboard['xpCurrentLevel'] ?> / <?= $dashboard['xpNextLevel'] ?> XP</p>
                <p class="text-xs text-gray-400">XP total: <?= $dashboard['xp'] ?></p>
            </div>
        </div>

        <!-- Decks estudados -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title">Decks Estudados</h2>
                <p class="text-3xl font-bold text-secondary"><?= $dashboard['deckReviews'] ?></p>
                <p class="text-sm text-gray-500">Total de revisões realizadas</p>
            </div>
        </div>

        <!-- Cards respondidos -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title">Flashcards</h2>
                <p class="text-3xl font-bold text-accent"><?= $dashboard['cardsAnswered'] ?></p>
                <p class="text-sm text-gray-500">Cards respondidos</p>
            </div>
        </div>

        <!-- Tarefas concluídas -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title">Tarefas</h2>
                <p class="text-3xl font-bold text-success"><?= $dashboard['tasksCompleted'] ?></p>
                <p class="text-sm text-gray-500">Tarefas concluídas</p>
            </div>
        </div>

        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title">Ofensiva de Login</h2>
                <p class="text-3xl font-bold text-warning"><?= $dashboard['currentLoginStreak'] ?> 🔥</p>
                <p class="text-sm text-gray-500">Melhor ofensiva: <?= $dashboard['longestLoginStreak'] ?> dias</p>
            </div>
        </div>

    </div>

    <div class="card bg-base-100 shadow mb-8">
        <div class="card-body">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                <h2 class="card-title">Quadro de Conquistas</h2>
                <a href="/conquistas" class="btn btn-primary btn-sm">Abrir quadro completo</a>
            </div>

            <div class="mb-4">
                <div class="flex justify-between text-sm text-gray-400 mb-2">
                    <span>Desbloqueadas: <?= $dashboard['achievementsSummary']['unlocked'] ?>/<?= $dashboard['achievementsSummary']['total'] ?></span>
                    <span><?= $dashboard['achievementsSummary']['percentage'] ?>%</span>
                </div>
                <progress class="progress progress-success w-full" value="<?= $dashboard['achievementsSummary']['unlocked'] ?>" max="<?= $dashboard['achievementsSummary']['total'] ?>"></progress>
            </div>

            <?php if (!empty($dashboard['latestUnlockedAchievements'])) : ?>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-3">
                    <?php foreach ($dashboard['latestUnlockedAchievements'] as $achievement) : ?>
                        <article class="rounded-xl border border-base-300 bg-base-200 p-4">
                            <p class="text-2xl mb-2"><?= $achievement['icon'] ?></p>
                            <p class="font-semibold leading-tight"><?= htmlspecialchars((string) $achievement['title']) ?></p>
                            <p class="text-xs text-gray-400 mt-2">
                                Desbloqueada em <?= date('d/m/Y H:i', strtotime((string) $achievement['unlockedAt'])) ?>
                            </p>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="text-sm text-gray-400">Você ainda não desbloqueou conquistas. Continue evoluindo para ganhar seus primeiros troféus!</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="card bg-base-100 shadow mb-8">
        <div class="card-body">
            <h2 class="card-title mb-4">Últimos ganhos de XP</h2>

            <ul class="space-y-3">
                <?php if (!empty($dashboard['recentAchievements'])) : ?>
                    <?php foreach ($dashboard['recentAchievements'] as $event) : ?>
                        <li class="flex items-center gap-3">
                            <div class="badge badge-warning">🏆</div>
                            <div>
                                <p class="font-semibold"><?= htmlspecialchars((string) $event['description']) ?></p>
                                <p class="text-sm text-gray-500"><?= date('d/m/Y H:i', strtotime((string) $event['created_at'])) ?></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php else : ?>
                    <li class="text-sm text-gray-500">Ainda sem eventos de XP. Crie uma tarefa ou finalize uma revisão para começar.</li>
                <?php endif; ?>
            </ul>

        </div>
    </div>




    <div class="card bg-base-100 shadow mb-8">
        <div class="card-body">
            <h2 class="card-title mb-4">Evolução de XP (7 dias)</h2>
            <canvas id="xpChart" height="100"></canvas>
        </div>
    </div>


</div>

<script>
    const ctx = document.getElementById('xpChart');

    const labels = <?= $xpLabels ?>;
    const values = <?= $xpValues ?>;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'XP ganho',
                data: values,
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>