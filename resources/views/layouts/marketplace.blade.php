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

@php
    $authUser  = auth()->user();
    $company   = $authUser?->company;
    $approved  = $company?->status === 'approved';
    $pending   = $company && $company->status === 'pending';
    $rejected  = $company && $company->status === 'rejected';
    $noCompany = !$company;
    $locked    = !$approved;          // everything except onboarding/pending is locked
@endphp

{{-- Sidebar --}}
<div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-mark">
            <svg viewBox="0 0 24 24"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        </div>
        <div>
            <div class="sidebar-logo-text">Xprinter</div>
            <div class="sidebar-logo-sub">{{ $company?->brand ?? 'Kabinet' }}</div>
        </div>
    </div>

    <nav class="sidebar-nav">

        {{-- ── Onboarding step (visible only when not approved) ── --}}
        @if($locked)
        <div class="nav-section-label">Boshlash</div>

        @if($noCompany)
        {{-- Step 1: fill in company info --}}
        <a href="{{ route('marketplace.onboarding') }}"
           class="nav-item {{ request()->routeIs('marketplace.onboarding') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </span>
            Kompaniyani ro'yxatdan o'tkazish
        </a>
        @else
        {{-- Step 2: waiting for approval --}}
        <a href="{{ route('marketplace.pending') }}"
           class="nav-item {{ request()->routeIs('marketplace.pending') ? 'active' : '' }}"
           style="{{ $rejected ? 'color:var(--red)' : '' }}">
            <span class="nav-icon">
                @if($rejected)
                <svg viewBox="0 0 24 24" style="stroke:var(--red)"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                @else
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                @endif
            </span>
            {{ $rejected ? 'Ariza rad etildi' : "Tasdiq kutilmoqda" }}
            @if($pending)
            <span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:#F59E0B;margin-left:auto;animation:pulse-dot 1.5s infinite;flex-shrink:0"></span>
            @endif
        </a>
        @endif

        <div style="height:1px;background:var(--line);margin:12px 0"></div>
        <div style="padding:0 12px;font-size:11px;color:var(--faint);display:flex;align-items:center;gap:6px;margin-bottom:6px">
            <svg viewBox="0 0 24 24" style="width:12px;height:12px;stroke:var(--faint);fill:none;stroke-width:2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Tasdiqdan keyin ochiladi
        </div>
        @endif

        {{-- ── Dashboard ── --}}
        <div class="nav-section-label">Asosiy</div>
        @if($approved)
        <a href="{{ route('marketplace.dashboard') }}"
           class="nav-item {{ request()->routeIs('marketplace.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            </span>
            Dashboard
        </a>
        @else
        @include('layouts._nav-locked', ['label' => 'Dashboard', 'icon' => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>'])
        @endif

        {{-- ── Company & Team (owner only) ── --}}
        @if($authUser?->isOwner())
        <div class="nav-section-label">Kompaniya</div>
        @if($approved)
        <a href="{{ route('marketplace.company.show') }}"
           class="nav-item {{ request()->routeIs('marketplace.company.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </span>
            Kompaniya profili
        </a>
        <a href="{{ route('marketplace.team.index') }}"
           class="nav-item {{ request()->routeIs('marketplace.team.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </span>
            Jamoa
        </a>
        @else
        @include('layouts._nav-locked', ['label' => 'Kompaniya profili', 'icon' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>'])
        @include('layouts._nav-locked', ['label' => 'Jamoa', 'icon' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>'])
        @endif
        @endif

        {{-- ── Assortiment ── --}}
        <div class="nav-section-label">Assortiment</div>
        @if($approved)
        <a href="{{ route('marketplace.assortiment.index') }}"
           class="nav-item {{ request()->routeIs('marketplace.assortiment.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            </span>
            Mahsulotlar
        </a>
        @else
        @include('layouts._nav-locked', ['label' => 'Mahsulotlar', 'icon' => '<line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>'])
        @endif

    </nav>

    <div class="sidebar-footer">
        <a href="{{ route('profile.edit') }}" class="user-card" style="text-decoration:none">
            <div class="user-avatar">{{ strtoupper(substr($authUser?->name ?? 'U', 0, 1)) }}</div>
            <div style="flex:1;min-width:0">
                <div class="user-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                    {{ $authUser?->name }}
                </div>
                <div class="user-role">{{ $company?->brand ?? $authUser?->role }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" onclick="event.stopPropagation()">
                @csrf
                <button type="submit" style="background:none;border:none;cursor:pointer;padding:4px" title="Chiqish">
                    <svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:var(--muted);fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </button>
            </form>
        </a>
    </div>
</aside>

{{-- Main --}}
<div class="main">
    <header class="header">
        <button class="hamburger" onclick="toggleSidebar()" aria-label="Menu">
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

    {{-- ── Status banner ── --}}
    @if($pending)
    <div style="background:#FFFBEB;border-bottom:1px solid #FDE68A;padding:10px 24px;display:flex;align-items:center;gap:10px;font-size:13px;color:#92400E">
        <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#F59E0B;animation:pulse-dot 1.5s infinite;flex-shrink:0"></span>
        <strong>Ariza ko'rib chiqilmoqda.</strong>
        Kompaniyangiz tasdiqlangach barcha bo'limlar ochiladi. Odatda 1–2 ish kuni.
        <a href="{{ route('marketplace.pending') }}" style="color:#92400E;text-decoration:underline;margin-left:auto;white-space:nowrap">Batafsil →</a>
    </div>
    @endif

    @if($rejected)
    <div style="background:#FFF1F2;border-bottom:1px solid #FECDD3;padding:10px 24px;display:flex;align-items:center;gap:10px;font-size:13px;color:#9F1239">
        <svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:#9F1239;fill:none;stroke-width:2;flex-shrink:0"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        <strong>Ariza rad etildi.</strong>
        Admin bilan bog'laning yoki yangi ariza yuboring.
        <a href="{{ route('marketplace.pending') }}" style="color:#9F1239;text-decoration:underline;margin-left:auto;white-space:nowrap">Batafsil →</a>
    </div>
    @endif

    @if($noCompany)
    <div style="background:#EFF6FF;border-bottom:1px solid #BFDBFE;padding:10px 24px;display:flex;align-items:center;gap:10px;font-size:13px;color:#1E40AF">
        <svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:#1E40AF;fill:none;stroke-width:2;flex-shrink:0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        Kabinetdan foydalanish uchun <strong>kompaniyangizni ro'yxatdan o'tkazing.</strong>
        <a href="{{ route('marketplace.onboarding') }}" style="color:#1E40AF;text-decoration:underline;margin-left:auto;white-space:nowrap">Boshlash →</a>
    </div>
    @endif

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

<style>
@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: 0.5; transform: scale(0.8); }
}
.nav-item-locked {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    border-radius: 10px;
    font-size: 13.5px;
    font-weight: 500;
    color: var(--faint);
    cursor: not-allowed;
    user-select: none;
    opacity: 0.75;
}
.nav-item-locked .nav-icon {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.nav-item-locked .nav-icon svg {
    width: 16px; height: 16px;
    stroke: var(--faint);
    fill: none;
    stroke-width: 1.8;
    stroke-linecap: round;
    stroke-linejoin: round;
}
.lock-icon {
    width: 12px; height: 12px;
    stroke: var(--faint);
    fill: none;
    stroke-width: 2;
    flex-shrink: 0;
    margin-left: auto;
}
</style>

</body>
</html>
