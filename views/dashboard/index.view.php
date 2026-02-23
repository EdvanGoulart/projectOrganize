<div class="w-full p-6 bg-base-200 min-h-screen">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <!-- XP / Nível -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title">Nível</h2>
                <p class="text-3xl font-bold text-primary">4</p>
                <progress class="progress progress-primary w-full" value="320" max="400"></progress>
                <p class="text-sm text-gray-500">320 / 400 XP</p>
            </div>
        </div>

        <!-- Decks estudados -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title">Decks Estudados</h2>
                <p class="text-3xl font-bold text-secondary">12</p>
                <p class="text-sm text-gray-500">Total de revisões realizadas</p>
            </div>
        </div>

        <!-- Cards respondidos -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title">Flashcards</h2>
                <p class="text-3xl font-bold text-accent">240</p>
                <p class="text-sm text-gray-500">Cards respondidos</p>
            </div>
        </div>

        <!-- Tarefas concluídas -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title">Tarefas</h2>
                <p class="text-3xl font-bold text-success">18</p>
                <p class="text-sm text-gray-500">Tarefas concluídas</p>
            </div>
        </div>

    </div>

    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <h2 class="card-title mb-4">Últimas Conquistas</h2>

            <ul class="space-y-3">
                <li class="flex items-center gap-3">
                    <div class="badge badge-warning">🏆</div>
                    <div>
                        <p class="font-semibold">Estudioso</p>
                        <p class="text-sm text-gray-500">Hoje</p>
                    </div>
                </li>

                <li class="flex items-center gap-3">
                    <div class="badge badge-warning">🏆</div>
                    <div>
                        <p class="font-semibold">Primeiro Passo</p>
                        <p class="text-sm text-gray-500">Ontem</p>
                    </div>
                </li>
            </ul>

            <div class="card-actions justify-end mt-4">
                <a href="/conquistas" class="btn btn-sm btn-outline">
                    Ver todas
                </a>
            </div>
        </div>
    </div>

    <br>

    <div class="card bg-base-100 shadow mb-8">
        <div class="card-body">
            <h2 class="card-title mb-4">Evolução de XP</h2>
            <canvas id="xpChart" height="100"></canvas>
        </div>
    </div>


</div>

<script>
    const ctx = document.getElementById('xpChart');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
            datasets: [{
                label: 'XP ganho',
                data: [40, 60, 20, 80, 50, 30, 90],
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