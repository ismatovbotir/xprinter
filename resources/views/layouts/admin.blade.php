<!DOCTYPE html>
<html lang="{{ auth()->user()?->lang ?? 'uz' }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>@yield('title', 'Dashboard') — Xprinter Admin</title>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;600;700&family=Manrope:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />

  @vite(['resources/css/admin.css', 'resources/js/app.js'])
  @livewireStyles
  @stack('styles')
</head>

<body style="display:flex;min-height:100vh">

{{-- ═══════════════ SIDEBAR ═══════════════ --}}
<aside class="sidebar">

  {{-- Logo --}}
  <div class="sidebar-logo">
    <div class="logo-mark">
      <svg viewBox="0 0 24 24"><path d="M5 7h14M5 12h14M5 17h8"/></svg>
    </div>
    <div>
      <div class="logo-text">XPRINTER</div>
      <div class="logo-sub">Admin Panel</div>
    </div>
  </div>

  {{-- Navigation --}}
  <nav class="sidebar-nav">

    <span class="nav-section-label">Asosiy</span>

    <a href="{{ route('admin.dashboard') }}"
       @class(['nav-item', 'active' => request()->routeIs('admin.dashboard')])>
      <div class="nav-icon">
        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      </div>
      Dashboard
    </a>

    <span class="nav-section-label">Ma'lumotlar</span>

    <a href="{{ route('admin.countries.index') }}"
       @class(['nav-item', 'active' => request()->routeIs('admin.countries.*')])>
      <div class="nav-icon">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15 15 0 0 0 0 20M12 2a15 15 0 0 1 0 20"/></svg>
      </div>
      Davlatlar
    </a>

    <a href="{{ route('admin.regions.index') }}"
       @class(['nav-item', 'active' => request()->routeIs('admin.regions.*')])>
      <div class="nav-icon">
        <svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
      </div>
      Viloyatlar
    </a>

    <a href="{{ route('admin.cities.index') }}"
       @class(['nav-item', 'active' => request()->routeIs('admin.cities.*')])>
      <div class="nav-icon">
        <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
      </div>
      Shaharlar
    </a>

    <span class="nav-section-label">Katalog</span>

    <a href="{{ route('admin.categories.index') }}"
       @class(['nav-item', 'active' => request()->routeIs('admin.categories.*')])>
      <div class="nav-icon">
        <svg viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h8"/></svg>
      </div>
      Kategoriyalar
    </a>

    <a href="{{ route('admin.parameters.index') }}"
       @class(['nav-item', 'active' => request()->routeIs('admin.parameters.*')])>
      <div class="nav-icon">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
      </div>
      Parametrlar
    </a>

    <a href="{{ route('admin.products.index') }}"
       @class(['nav-item', 'active' => request()->routeIs('admin.products.*')])>
      <div class="nav-icon">
        <svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-4 0v2M8 7V5a2 2 0 0 0-4 0v2"/></svg>
      </div>
      Mahsulotlar
    </a>

    <span class="nav-section-label">Kompaniyalar</span>

    <a href="{{ route('admin.companies.index') }}"
       @class(['nav-item', 'active' => request()->routeIs('admin.companies.index')])>
      <div class="nav-icon">
        <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      </div>
      Barcha kompaniyalar
      @php($total = \App\Models\Company::count())
      @if($total)
        <span class="nav-badge">{{ $total }}</span>
      @endif
    </a>

    <a href="{{ route('admin.companies.pending') }}"
       @class(['nav-item', 'active' => request()->routeIs('admin.companies.pending')])>
      <div class="nav-icon">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </div>
      Kutilayotganlar
      @php($pending = \App\Models\Company::where('status','pending')->count())
      @if($pending)
        <span class="nav-badge orange">{{ $pending }}</span>
      @endif
    </a>

    <span class="nav-section-label">Foydalanuvchilar</span>

    <a href="{{ route('admin.users.index') }}"
       @class(['nav-item', 'active' => request()->routeIs('admin.users.*')])>
      <div class="nav-icon">
        <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      </div>
      Foydalanuvchilar
    </a>

  </nav>

  {{-- User footer --}}
  <div class="sidebar-footer">
    <a href="{{ route('admin.profile') }}" class="user-card">
      <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
      <div>
        <div class="user-name">{{ auth()->user()->name }}</div>
        <div class="user-role">Admin</div>
      </div>
      <svg style="margin-left:auto;width:14px;height:14px;stroke:var(--faint);fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
    </a>
  </div>

</aside>

{{-- ═══════════════ MAIN ═══════════════════ --}}
<div class="main">

  {{-- Header --}}
  <header class="header">
    <div class="breadcrumb">
      <span class="bc">Admin</span>
      <span class="bc-sep">/</span>
      <span class="bc current">@yield('title', 'Dashboard')</span>
    </div>

    <div class="header-search">
      <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" placeholder="Qidirish..." />
    </div>

    {{-- Language switcher --}}
    <div class="lang-switch">
      <button class="lang-btn @if(app()->getLocale() === 'uz') active @endif"
              onclick="window.location='{{ route('lang.switch', 'uz') }}'">UZ</button>
      <button class="lang-btn @if(app()->getLocale() === 'ru') active @endif"
              onclick="window.location='{{ route('lang.switch', 'ru') }}'">RU</button>
    </div>

    {{-- Logout --}}
    <form method="POST" action="{{ route('logout') }}" style="display:contents">
      @csrf
      <button type="submit" class="hbtn" title="Chiqish">
        <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      </button>
    </form>
  </header>

  {{-- Flash messages --}}
  <div style="padding: 0 32px; padding-top: 24px;">
    @if(session('success'))
      <div class="flash flash-success">
        <svg style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2.5;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div class="flash flash-error">
        <svg style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2.5;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        {{ session('error') }}
      </div>
    @endif
  </div>

  {{-- Page content --}}
  <main class="content">
    @yield('content')
  </main>

</div>

@livewireScripts
@stack('scripts')
</body>
</html>
