<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo">EL<span style="font-size:8px">edji</span></div>
        <div class="name">Admin Panel</div>
    </div>

    <nav class="sidebar-menu">
        <a href="{{ route('admin.dashboard') }}"
           class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
        </a>

        <a href="{{ route('admin.events.index') }}"
           class="menu-item {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i>
            Événements
            @php
                $pendingCount = \App\Models\Event::where('statut', 'en_attente')->count();
            @endphp
            @if($pendingCount > 0)
                <span class="menu-badge">{{ $pendingCount }}</span>
            @endif
        </a>

        <a href="{{ route('admin.users.index') }}"
           class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            Utilisateurs
        </a>

        <a href="{{ route('admin.categories.index') }}"
           class="menu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i>
            Catégories
        </a>

        <a href="{{ route('admin.payments.index') }}"
           class="menu-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
            <i class="fas fa-credit-card"></i>
            Paiements
        </a>

        <a href="{{ route('admin.bookings.index') }}"
           class="menu-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
            <i class="fas fa-ticket-alt"></i>
            Reservations
        </a>

        <a href="{{ route('admin.comments.index') }}"
           class="menu-item {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
            <i class="fas fa-comments"></i>
            Commentaires
            @php
                $signaledCount = \App\Models\Comment::where('signale', true)->count();
            @endphp
            @if($signaledCount > 0)
                <span class="menu-badge">{{ $signaledCount }}</span>
            @endif
        </a>

        <a href="{{ route('admin.settings.index') }}"
           class="menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i>
            Paramètres
        </a>

        <form action="{{ route('admin.logout') }}" method="POST" style="margin-top: 20px; padding: 0 20px;">
            @csrf
            <button type="submit" class="menu-item" style="width: 100%; border: none; background: none; cursor: pointer;">
                <i class="fas fa-sign-out-alt"></i>
                Déconnexion
            </button>
        </form>
    </nav>
</aside>