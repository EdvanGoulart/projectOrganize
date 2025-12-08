<div class="navbar bg-base-100 shadow-sm">
    <div class="flex-1">
        <a href="/task" class="btn btn-ghost text-xl">Organize</a>
    </div>
    <div class="flex-1">
        <ul class="menu menu-horizontal px-1">
            <li><a href="/discipline">Disciplinas</a></li>
            <li><a href="/deck-list">Revisões</a></li>
        </ul>
    </div>

    <div class="flex-none">
        <ul class="menu menu-horizontal px-1">
            <li>
                <details>
                    <summary>Olá, <?= auth()->name ?></summary>
                    <ul class="bg-base-100 rounded-t-none p-2">
                        <li><a href="/perfil">Perfil</a></li>
                        <li><a href="/logout">Sair</a></li>
                    </ul>
                </details>
            </li>
        </ul>
    </div>
</div>