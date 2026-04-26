<!DOCTYPE html>
<html lang="{{ auth()->user()?->lang ?? 'uz' }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Kabinet') — Xprinter</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;600;700&family=Manrope:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/admin.css', 'resources/js/app.js'])
</head>

<body>

{{-- Sidebar --}}
<div class="sidebar-overlay" id="overlay" onclick="closeSidebar()"></div>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-mark">
            <svg viewBox="0 0 24 24"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        </div>
        <div>
            <div class="sidebar-logo-text">Xprinter</div>
            <div class="sidebar-logo-sub">Kabinet</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Asosiy</div>
        <a href="{{ route('marketplace.dashboard') }}"
           class="nav-item {{ request()->routeIs('marketplace.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            </span>
            Dashboard
        </a>

        @if(auth()->user()->isOwner())
        <div class="nav-section-label">Kompaniya</div>
        <a href="#" class="nav-item {{ request()->routeIs('marketplace.company.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </span>
            Kompaniya profili
        </a>
        <a href="{{ route('marketplace.team.index') }}" class="nav-item {{ request()->routeIs('marketplace.team.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </span>
            Jamoa
        </a>
        @endif

        <div class="nav-section-label">Assortiment</div>
        <a href="#" class="nav-item {{ request()->routeIs('marketplace.products.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            </span>
            Mahsulotlar
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div style="flex:1;min-width:0">
                <div class="user-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                    {{ auth()->user()->name }}
                </div>
                <div class="user-role">{{ auth()->user()->company?->brand ?? auth()->user()->role }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background:none;border:none;cursor:pointer;padding:4px" title="Chiqish">
                    <svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:var(--muted);fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- Main --}}
<div class="main" style="margin-left:0">
    <header class="header">
        <button class="hamburger" onclick="openSidebar()" aria-label="Menu">
            <svg viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>

        <div class="breadcrumb">
            <span class="breadcrumb-item">Xprinter</span>
            <span class="breadcrumb-sep">/</span>
            <span class="breadcrumb-item current">@yield('breadcrumb', 'Kabinet')</span>
        </div>

        <div class="lang-switch">
            <button class="lang-btn @if(app()->getLocale() === 'uz') active @endif"
                    onclick="window.location='{{ route('lang.switch', 'uz') }}'">UZ</button>
            <button class="lang-btn @if(app()->getLocale() === 'ru') active @endif"
                    onclick="window.location='{{ route('lang.switch', 'ru') }}'">RU</button>
            <button class="lang-btn @if(app()->getLocale() === 'en') active @endif"
                    onclick="window.location='{{ route('lang.switch', 'en') }}'">EN</button>
        </div>
    </header>

    <div class="content">
        @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:16px">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger" style="margin-bottom:16px">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

<script>
function openSidebar()  { document.getElementById('sidebar').classList.add('open'); document.getElementById('overlay').classList.add('open'); }
function closeSidebar() { document.getElementById('sidebar').classList.remove('open'); document.getElementById('overlay').classList.remove('open'); }

window.matchMedia('(min-width: 1024px)').addEventListener('change', e => {
    if (e.matches) { document.getElementById('sidebar').style.transform = 'translateX(0)'; document.getElementById('overlay').classList.remove('open'); }
    else { document.getElementById('sidebar').style.transform = ''; }
});
if (window.innerWidth >= 1024) document.getElementById('sidebar').style.transform = 'translateX(0)';
document.getElementById('sidebar').style.marginLeft = window.innerWidth >= 1024 ? '260px' : '0';
</script>

<style>
@media (min-width: 1024px) {
    .sidebar { transform: translateX(0) !important; }
    .main { margin-left: 260px !important; }
    .hamburger { display: none; }
}
</style>

</body>
</html>
