<div class="navbar bg-base-100 shadow-sm rounded-box px-2">
    <div class="navbar-start gap-2">
        <div class="dropdown lg:hidden">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </div>
            <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-50 mt-3 w-56 p-2 shadow border border-base-300">
                <li><a href="/dashboard">Dashboard</a></li>
                <li><a href="/task">Tarefas</a></li>
                <li><a href="/discipline">Disciplinas</a></li>
                <li><a href="/deck-list">Revisões</a></li>
                <li><a href="/deck-list">Conquistas</a></li>
            </ul>
        </div>
        <a href="/dashboard" class="btn btn-ghost text-xl">Organize</a>
    </div>

    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1">
            <li><a href="/dashboard">Dashboard</a></li>
            <li><a href="/task">Tarefas</a></li>
            <li><a href="/discipline">Disciplinas</a></li>
            <li><a href="/deck-list">Revisões</a></li>
            <li><a href="/conquistas">Conquistas</a></li>
        </ul>
    </div>

    <div class="navbar-end">
        <ul class="menu menu-horizontal px-1">
            <li>
                <details>
                    <summary>Olá, <?= auth()->name ?></summary>
                    <ul class="bg-base-100 rounded-t-none p-2 right-0">
                        <li><a href="/perfil">Perfil</a></li>
                        <li><a href="/logout">Sair</a></li>
                    </ul>
                </details>
            </li>
        </ul>
    </div>
</div>