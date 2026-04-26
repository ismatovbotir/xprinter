<!DOCTYPE html>
<html lang="{{ auth()->user()?->lang ?? 'uz' }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Dashboard') — Xprinter Producer</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;600;700&family=Manrope:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/admin.css', 'resources/js/app.js'])
</head>

<body>

    <div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="sidebar-logo-mark">
                <svg viewBox="0 0 24 24"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            </div>
            <div>
                <div class="sidebar-logo-text">Xprinter</div>
                <div class="sidebar-logo-sub">Producer</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Asosiy</div>
            <a href="{{ route('producer.dashboard') }}" class="nav-item {{ request()->routeIs('producer.dashboard') ? 'active' : '' }}">
                <span class="nav-icon"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg></span>
                Dashboard
            </a>

            <div class="nav-section-label">Seriya raqamlari</div>
            <a href="{{ route('producer.serials.index') }}" class="nav-item {{ request()->routeIs('producer.serials.*') ? 'active' : '' }}">
                <span class="nav-icon"><svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></span>
                Seriya raqamlari
            </a>
            <a href="{{ route('producer.serials.import') }}" class="nav-item {{ request()->routeIs('producer.serials.import') ? 'active' : '' }}">
                <span class="nav-icon"><svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg></span>
                CSV import
            </a>

            <div class="nav-section-label">Hamkorlar</div>
            <a href="{{ route('producer.partners.index') }}" class="nav-item {{ request()->routeIs('producer.partners.*') ? 'active' : '' }}">
                <span class="nav-icon"><svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></span>
                Hamkorlar
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('profile.edit') }}" class="user-card" style="text-decoration:none">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div style="flex:1;min-width:0">
                    <div class="user-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ auth()->user()->name }}</div>
                    <div class="user-role">Producer</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" onclick="event.stopPropagation()">
                    @csrf
                    <button type="submit" style="background:none;border:none;cursor:pointer;padding:4px" title="Chiqish">
                        <svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:var(--muted);fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    </button>
                </form>
            </a>
        </div>
    </aside>

    <div class="main">
        <header class="header">
            <button class="hamburger" onclick="toggleSidebar()" aria-label="Menu">
                <svg viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
            <div class="breadcrumb">
                <span class="breadcrumb-item">Xprinter Producer</span>
                <span class="breadcrumb-sep">/</span>
                <span class="breadcrumb-item current">@yield('breadcrumb', 'Dashboard')</span>
            </div>
            <div class="lang-switch">
                <button class="lang-btn @if(app()->getLocale() === 'uz') active @endif" onclick="window.location='{{ route('lang.switch', 'uz') }}'">UZ</button>
                <button class="lang-btn @if(app()->getLocale() === 'ru') active @endif" onclick="window.location='{{ route('lang.switch', 'ru') }}'">RU</button>
                <button class="lang-btn @if(app()->getLocale() === 'en') active @endif" onclick="window.location='{{ route('lang.switch', 'en') }}'">EN</button>
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
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('overlay').classList.toggle('open');
    }
    </script>

</body>
</html>
