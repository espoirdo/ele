<nav id="main-navbar" x-data="{ authOpen: false, scrolled: false, mobileMenuOpen: false }"
     x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })"
     :class="scrolled ? 'nav-scrolled' : ''">

<style>
#main-navbar {
    position: fixed;
    top: 16px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    background: rgba(204, 0, 0, 0.92);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    padding: 12px 0;
    font-family: 'Poppins', sans-serif;
    border-radius: 50px;
    max-width: 1200px;
    width: calc(100% - 32px);
    box-shadow: 0 8px 32px rgba(204, 0, 0, 0.2);
}
#main-navbar.nav-scrolled {
    background: rgba(204, 0, 0, 0.75);
    padding: 10px 0;
    border-radius: 50px;
    box-shadow: 0 4px 20px rgba(204, 0, 0, 0.15);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}
.nav-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.nav-logo {
    width: 48px; height: 48px;
    border-radius: 50%;
    background: #FFFFFF;
    display: flex; align-items: center; justify-content: center;
    text-decoration: none;
    flex-shrink: 0;
    transition: transform 0.25s ease;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.15);
    border: 1.5px solid rgba(255, 255, 255, 0.3);
}
.nav-logo:hover { transform: scale(1.06); }
.nav-logo img {
    width: 36px; height: 36px;
    object-fit: contain;
    border-radius: 50%;
}
.nav-links {
    display: flex; align-items: center; gap: 36px;
}
.nav-link {
    color: #FFFFFF;
    font-weight: 600; font-size: 14px;
    text-decoration: none;
    letter-spacing: 0.3px;
    position: relative; padding-bottom: 4px;
    transition: all 0.25s ease;
    text-underline-offset: 5px;
}
.nav-link::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0;
    width: 0; height: 2px;
    background: #FFFFFF;
    border-radius: 2px;
    transition: width 0.3s ease;
}
.nav-link:hover::after { width: 100%; }
.nav-link.nav-active {
    text-decoration: underline;
    text-underline-offset: 5px;
}
.nav-link.nav-active::after { width: 100%; }
.nav-link:hover { color: rgba(255, 255, 255, 0.85); }

/* ===== AUTH ZONE ===== */
.nav-auth { position: relative; }
.nav-auth-btn {
    display: flex; align-items: center;
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 50px;
    padding: 6px 16px 6px 6px;
    gap: 10px;
    cursor: pointer;
    transition: all 0.25s ease;
    min-width: 0;
    outline: none;
    -webkit-tap-highlight-color: transparent;
}
.nav-auth-btn:hover { background: rgba(255, 255, 255, 0.22); }
.nav-auth-btn:focus { background: rgba(255, 255, 255, 0.22); box-shadow: none; }
.nav-auth-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #CC0000, #910000);
    display: flex; align-items: center; justify-content: center;
    color: white; font-weight: 700; font-size: 13px;
    overflow: hidden; flex-shrink: 0;
    border: 1.5px solid rgba(255, 255, 255, 0.4);
}
.nav-auth-avatar img { width: 100%; height: 100%; object-fit: cover; }
.nav-auth-name {
    color: white; font-weight: 600; font-size: 13px;
    white-space: nowrap; max-width: 100px;
    overflow: hidden; text-overflow: ellipsis;
}
.nav-auth-chevron {
    color: rgba(255, 255, 255, 0.7);
    transition: transform 0.25s;
    flex-shrink: 0;
}
.nav-auth-btn[aria-expanded="true"] .nav-auth-chevron { transform: rotate(180deg); }

/* ===== DROPDOWN ===== */
.nav-dropdown {
    position: absolute;
    right: 0;
    top: calc(100% + 12px);
    width: 260px;
    background: rgba(255, 255, 255, 0.96);
    backdrop-filter: blur(24px) saturate(180%);
    -webkit-backdrop-filter: blur(24px) saturate(180%);
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
    overflow: hidden;
    z-index: 10000;
    animation: dropIn 0.28s cubic-bezier(0.34, 1.56, 0.64, 1) both;
    transform-origin: top right;
}
@keyframes dropIn {
    from { opacity: 0; transform: scale(0.88) translateY(-10px); }
    to   { opacity: 1; transform: scale(1)    translateY(0); }
}

/* Header dropdown */
.dd-header {
    padding: 18px 16px 14px;
    background: #F9F9F9;
    border-bottom: 0.5px solid rgba(0, 0, 0, 0.07);
    display: flex;
    align-items: center;
    gap: 12px;
}
.dd-avatar {
    width: 44px; height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, #CC0000, #910000);
    display: flex; align-items: center; justify-content: center;
    color: white; font-weight: 700; font-size: 15px;
    flex-shrink: 0;
    box-shadow: 0 3px 10px rgba(204, 0, 0, 0.25);
    overflow: hidden;
}
.dd-avatar img { width: 100%; height: 100%; object-fit: cover; }
.dd-user-info { flex: 1; min-width: 0; }
.dd-name {
    font-size: 15px; font-weight: 700;
    color: #1a1a1a; white-space: nowrap;
    overflow: hidden; text-overflow: ellipsis;
    line-height: 1.3;
}
.dd-role {
    font-size: 12px; color: #666;
    margin-top: 2px; font-weight: 400;
}
.dd-status {
    display: flex; align-items: center; gap: 5px;
    margin-top: 5px;
}
.dd-status-dot {
    width: 7px; height: 7px;
    border-radius: 50%; background: #34C759;
}
.dd-status-text { font-size: 11px; color: #666; }

/* Groups */
.dd-group {
    padding: 5px 0;
    border-bottom: 0.5px solid rgba(0, 0, 0, 0.06);
}
.dd-group:last-child { border-bottom: none; }

/* Items */
.dd-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    font-size: 13px;
    font-weight: 500;
    color: #1a1a1a;
    text-decoration: none;
    transition: all 0.25s ease;
    cursor: pointer;
    width: 100%;
    background: transparent;
    border: none;
    text-align: left;
    font-family: 'Poppins', sans-serif;
    outline: none;
    -webkit-tap-highlight-color: transparent;
}
.dd-item:hover { background: rgba(204, 0, 0, 0.06); }
.dd-item:active { background: rgba(204, 0, 0, 0.12); transform: scale(0.99); }
.dd-item:focus { box-shadow: none; background: rgba(204, 0, 0, 0.06); }
.dd-item-icon {
    width: 34px; height: 34px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.dd-item-label { flex: 1; }
.dd-item-chevron {
    color: rgba(0, 0, 0, 0.25);
    flex-shrink: 0;
}
.dd-item.danger { color: #CC0000; }
.dd-item.danger:hover { background: rgba(204, 0, 0, 0.06); }

/* Non connecté header */
.dd-guest-header {
    padding: 18px 16px 14px;
    background: #F9F9F9;
    border-bottom: 0.5px solid rgba(0, 0, 0, 0.07);
}
.dd-guest-avatar {
    width: 44px; height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, #666, #444);
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 10px;
}
.dd-guest-title {
    font-size: 15px; font-weight: 700; color: #1a1a1a; line-height: 1.3;
}
.dd-guest-sub {
    font-size: 12px; color: #666; margin-top: 3px; line-height: 1.4;
}

/* ===== HAMBURGER MENU ===== */
.nav-hamburger {
    display: none;
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 8px;
    margin-right: 8px;
    outline: none;
    -webkit-tap-highlight-color: transparent;
}
.nav-hamburger svg {
    width: 24px;
    height: 24px;
    color: white;
}

/* Mobile Dropdown Menu */
.nav-mobile-menu {
    display: none;
    position: fixed;
    top: 80px;
    left: 16px;
    right: 16px;
    background: rgba(204, 0, 0, 0.92);
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
    padding: 8px 0;
    z-index: 9998;
    animation: dropIn 0.28s cubic-bezier(0.34, 1.56, 0.64, 1) both;
}
.nav-mobile-menu.show { display: block; }
.nav-mobile-link {
    display: flex;
    align-items: center;
    color: #FFFFFF;
    font-weight: 600; font-size: 15px;
    text-decoration: none;
    padding: 14px 24px;
    transition: all 0.25s ease;
    letter-spacing: 0.3px;
    min-height: 44px;
}
.nav-mobile-link:hover {
    background: rgba(255, 255, 255, 0.1);
}
.nav-mobile-link.nav-active {
    background: rgba(255, 255, 255, 0.15);
    text-decoration: underline;
    text-underline-offset: 5px;
}

/* Responsive */
@media (max-width: 900px) {
    .nav-links { display: none; }
    .nav-hamburger { display: block; }
    .nav-auth-name { display: none; }
    .nav-auth-btn { padding: 6px; }
}
</style>

<div class="nav-inner">

    {{-- Logo --}}
    <a href="{{ route('home') }}" class="nav-logo">
        <img src="{{ setting('logo_navbar') ? Storage::url(setting('logo_navbar')) : asset('images/logo.png') }}" alt="Eledji logo" loading="lazy">
    </a>

    {{-- Hamburger (mobile) --}}
    <button class="nav-hamburger" @click="mobileMenuOpen = !mobileMenuOpen" :aria-expanded="mobileMenuOpen">
        <svg x-show="!mobileMenuOpen" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <svg x-show="mobileMenuOpen" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    {{-- Liens (desktop) --}}
    <div class="nav-links">
        <a href="{{ route('home') }}"
           class="nav-link {{ request()->routeIs('home') ? 'nav-active' : '' }}">
            ACCUEIL
        </a>
        <a href="{{ route('events.index') }}"
           class="nav-link {{ request()->routeIs('events.*') ? 'nav-active' : '' }}">
            EVENEMENTS
        </a>
        <a href="{{ route('news') }}"
           class="nav-link {{ request()->routeIs('news') ? 'nav-active' : '' }}">
            NEWS
        </a>
        <a href="{{ route('contact') }}"
           class="nav-link {{ request()->routeIs('contact') ? 'nav-active' : '' }}">
            CONTACT
        </a>

        {{-- Créer un événement --}}
        @auth
            <a href="{{ route('events.create') }}"
               class="nav-link {{ request()->routeIs('events.create') ? 'nav-active' : '' }}">
                CREER UN EVENEMENT
            </a>
        @endauth
        @guest
            <a href="{{ route('login') }}" class="nav-link">
                CREER UN EVENEMENT
            </a>
        @endguest
    </div>

    {{-- Mobile Menu Dropdown --}}
    <div class="nav-mobile-menu" :class="mobileMenuOpen ? 'show' : ''" x-show="mobileMenuOpen" style="display:none">
        <a href="{{ route('home') }}" class="nav-mobile-link {{ request()->routeIs('home') ? 'nav-active' : '' }}">ACCUEIL</a>
        <a href="{{ route('events.index') }}" class="nav-mobile-link {{ request()->routeIs('events.*') ? 'nav-active' : '' }}">EVENEMENTS</a>
        <a href="{{ route('news') }}" class="nav-mobile-link {{ request()->routeIs('news') ? 'nav-active' : '' }}">NEWS</a>
        <a href="{{ route('contact') }}" class="nav-mobile-link {{ request()->routeIs('contact') ? 'nav-active' : '' }}">CONTACT</a>
        @auth
            <a href="{{ route('events.create') }}" class="nav-mobile-link {{ request()->routeIs('events.create') ? 'nav-active' : '' }}">CREER UN EVENEMENT</a>
        @endauth
        @guest
            <a href="{{ route('login') }}" class="nav-mobile-link">CREER UN EVENEMENT</a>
        @endguest
    </div>

    {{-- Auth --}}
    <div class="nav-auth">

        @auth
            {{-- Bouton connecté --}}
            <button class="nav-auth-btn"
                    @click="authOpen = !authOpen"
                    @click.outside="authOpen = false"
                    :aria-expanded="authOpen"
                    style="outline: none;">
                <div class="nav-auth-avatar">
                    @if(auth()->user()->avatar)
                        <img src="{{ Storage::url(auth()->user()->avatar) }}">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    @endif
                </div>
                <span class="nav-auth-name">{{ auth()->user()->name }}</span>
                <svg class="nav-auth-chevron" width="14" height="14" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            {{-- Dropdown connecté --}}
            <div x-show="authOpen"
                 x-transition:enter="transition duration-50"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="nav-dropdown"
                 style="display:none">

                {{-- Header utilisateur --}}
                <div class="dd-header">
                    <div class="dd-avatar">
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        @endif
                    </div>
                    <div class="dd-user-info">
                        <div class="dd-name">{{ auth()->user()->name }}</div>
                        <div class="dd-role">
                            {{ auth()->user()->role === 'admin' ? 'Administrateur' : 'Membre' }}
                        </div>
                        <div class="dd-status">
                            <div class="dd-status-dot"></div>
                            <span class="dd-status-text">Connecte</span>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="dd-group">
                    @if(isset(auth()->user()->role) && auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="dd-item">
                            <div class="dd-item-icon" style="background: rgba(204, 0, 0, 0.1)">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#CC0000" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </div>
                            <span class="dd-item-label">Panel Admin</span>
                            <svg class="dd-item-chevron" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endif

                    <a href="{{ route('events.create') }}" class="dd-item">
                        <div class="dd-item-icon" style="background: rgba(52, 199, 89, 0.12)">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#34C759" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <span class="dd-item-label">Creer un evenement</span>
                        <svg class="dd-item-chevron" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    <a href="#" class="dd-item">
                        <div class="dd-item-icon" style="background: rgba(0, 122, 255, 0.1)">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#007AFF" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <span class="dd-item-label">Mon profil</span>
                        <svg class="dd-item-chevron" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    <a href="#" class="dd-item">
                        <div class="dd-item-icon" style="background: rgba(255, 149, 0, 0.1)">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#FF9500" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                        </div>
                        <span class="dd-item-label">Mes billets</span>
                        <svg class="dd-item-chevron" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                {{-- Déconnexion --}}
                <div class="dd-group">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dd-item danger">
                            <div class="dd-item-icon" style="background: rgba(204, 0, 0, 0.1)">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#CC0000" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                            <span class="dd-item-label">Deconnexion</span>
                        </button>
                    </form>
                </div>
            </div>

        @else
            {{-- Non connecté --}}
            <button class="nav-auth-btn"
                    @click="authOpen = !authOpen"
                    @click.outside="authOpen = false"
                    style="outline: none;">
                <div class="nav-auth-avatar" style="background: rgba(255, 255, 255, 0.25)">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <span class="nav-auth-name" style="color: rgba(255, 255, 255, 0.9)">Compte</span>
                <svg class="nav-auth-chevron" width="14" height="14" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="authOpen"
                 x-transition:enter="transition duration-50"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="nav-dropdown"
                 style="display:none">

                <div class="dd-guest-header">
                    <div class="dd-guest-avatar">
                        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="dd-guest-title">Bienvenue</div>
                    <div class="dd-guest-sub">Connectez-vous pour acceder a votre compte</div>
                </div>

                <div class="dd-group">
                    <a href="{{ route('login') }}" class="dd-item">
                        <div class="dd-item-icon" style="background: rgba(0, 122, 255, 0.1)">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#007AFF" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                        </div>
                        <span class="dd-item-label" style="font-weight: 600">Connexion</span>
                        <svg class="dd-item-chevron" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                <div class="dd-group">
                    <a href="{{ route('register') }}" class="dd-item" style="color: #CC0000">
                        <div class="dd-item-icon" style="background: rgba(204, 0, 0, 0.1)">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#CC0000" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <span class="dd-item-label" style="font-weight: 600; color: #CC0000">Creer un compte</span>
                        <svg class="dd-item-chevron" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#CC0000" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        @endauth
    </div>
</div>
</nav>