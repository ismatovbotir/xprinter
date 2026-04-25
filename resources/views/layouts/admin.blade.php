<!DOCTYPE html>
<html lang="{{ auth()->user()?->lang ?? 'uz' }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Dashboard') — Xprinter Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;600;700&family=Manrope:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --bg: #FFFFFF;
            --bg-soft: #F4F8FE;
            --bg-blue: #EAF2FD;
            --surface: #FFFFFF;
            --line: #E3EBF5;
            --line-hi: #C9D6E8;
            --ink: #0A1B3D;
            --ink-soft: #2C3E5C;
            --muted: #6B7B95;
            --faint: #A5B0C4;
            --blue: #0066FF;
            --blue-hi: #1A75FF;
            --blue-deep: #0040B8;
            --blue-soft: #DCE9FB;
            --cyan: #00B6E8;
            --green: #00C896;
            --red: #FF3B5C;
            --orange: #FF8C00;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background: var(--bg-soft);
            color: var(--ink);
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar ─────────────────────────────── */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: var(--surface);
            border-right: 1px solid var(--line);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 40;
            transform: translateX(-100%);
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar.open {
            transform: translateX(0);
        }

        /* ── Overlay ─────────────────────────────── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(10, 27, 61, 0.4);
            backdrop-filter: blur(2px);
            z-index: 39;
        }

        .sidebar-overlay.open {
            display: block;
        }

        .sidebar-logo {
            padding: 20px 24px;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-logo-mark {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--blue), var(--cyan));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-logo-mark svg {
            width: 20px;
            height: 20px;
            fill: #fff;
        }

        .sidebar-logo-text {
            font-family: 'Unbounded', sans-serif;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--ink);
            line-height: 1.2;
        }

        .sidebar-logo-sub {
            font-family: 'JetBrains Mono', monospace;
            font-size: 9px;
            color: var(--muted);
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 16px 12px;
        }

        .nav-section-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 9px;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--faint);
            padding: 0 12px;
            margin: 16px 0 6px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 500;
            color: var(--ink-soft);
            text-decoration: none;
            transition: background 0.15s, color 0.15s;
            cursor: pointer;
            margin-bottom: 2px;
        }

        .nav-item:hover {
            background: var(--bg-soft);
            color: var(--ink);
        }

        .nav-item.active {
            background: var(--blue-soft);
            color: var(--blue-deep);
            font-weight: 600;
        }

        .nav-item .nav-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--bg-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: background 0.15s;
        }

        .nav-item.active .nav-icon {
            background: var(--blue-soft);
        }

        .nav-item svg {
            width: 16px;
            height: 16px;
            stroke: var(--muted);
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .nav-item.active svg {
            stroke: var(--blue);
        }

        .nav-badge {
            margin-left: auto;
            background: var(--blue);
            color: #fff;
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            font-weight: 500;
            padding: 2px 7px;
            border-radius: 20px;
        }

        .nav-badge.orange {
            background: var(--orange);
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid var(--line);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 12px;
            background: var(--bg-soft);
            cursor: pointer;
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--blue), var(--cyan));
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Unbounded', sans-serif;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--ink);
        }

        .user-role {
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            color: var(--muted);
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        /* ── Hamburger ───────────────────────────── */
        .hamburger {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--bg-soft);
            border: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
        }

        .hamburger svg {
            width: 18px;
            height: 18px;
            stroke: var(--ink-soft);
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
        }

        /* ── Main ────────────────────────────────── */
        .main {
            margin-left: 0;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: 100%;
        }

        /* ── Header ──────────────────────────────── */
        .header {
            background: var(--surface);
            border-bottom: 1px solid var(--line);
            padding: 0 16px;
            height: 60px;
            display: flex;
            align-items: center;
            gap: 10px;
            position: sticky;
            top: 0;
            z-index: 30;
        }

        .header-search {
            display: none;
        }

        .breadcrumb {
            flex: 1;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
        }

        .breadcrumb-item {
            font-size: 13px;
            color: var(--muted);
        }

        .breadcrumb-item.current {
            font-weight: 600;
            color: var(--ink);
        }

        .breadcrumb-sep {
            color: var(--faint);
            font-size: 13px;
        }

        .header-search {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--bg-soft);
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 0 14px;
            height: 38px;
            width: 220px;
            transition: border-color 0.15s;
        }

        .header-search:focus-within {
            border-color: var(--blue);
        }

        .header-search svg {
            width: 15px;
            height: 15px;
            stroke: var(--muted);
            fill: none;
            stroke-width: 2;
            flex-shrink: 0;
        }

        .header-search input {
            border: none;
            background: transparent;
            font-family: 'Manrope', sans-serif;
            font-size: 13px;
            color: var(--ink);
            outline: none;
            width: 100%;
        }

        .header-search input::placeholder {
            color: var(--faint);
        }

        .lang-switch {
            display: flex;
            background: var(--bg-soft);
            border: 1px solid var(--line);
            border-radius: 8px;
            overflow: hidden;
        }

        .lang-btn {
            padding: 5px 12px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.05em;
            color: var(--muted);
            cursor: pointer;
            border: none;
            background: transparent;
            transition: background 0.15s, color 0.15s;
        }

        .lang-btn.active {
            background: var(--blue);
            color: #fff;
        }

        .header-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--bg-soft);
            border: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
            transition: border-color 0.15s;
        }

        .header-btn:hover {
            border-color: var(--line-hi);
        }

        .header-btn svg {
            width: 16px;
            height: 16px;
            stroke: var(--ink-soft);
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .notif-dot {
            position: absolute;
            top: 7px;
            right: 7px;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--red);
            border: 2px solid var(--surface);
        }

        /* ── Content ─────────────────────────────── */
        .content {
            padding: 16px;
            flex: 1;
        }

        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .page-title {
            font-family: 'Unbounded', sans-serif;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--ink);
        }

        .page-subtitle {
            font-size: 13.5px;
            color: var(--muted);
            margin-top: 4px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0 20px;
            height: 40px;
            border-radius: 10px;
            font-family: 'Manrope', sans-serif;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.15s;
        }

        .btn-primary {
            background: var(--blue);
            color: #fff;
            box-shadow: 0 6px 20px rgba(0, 102, 255, 0.28);
        }

        .btn-primary:hover {
            background: var(--blue-hi);
            box-shadow: 0 8px 24px rgba(0, 102, 255, 0.36);
        }

        .btn-ghost {
            background: var(--surface);
            color: var(--ink-soft);
            border: 1px solid var(--line);
        }

        .btn-ghost:hover {
            border-color: var(--line-hi);
        }

        .btn svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* ── Stat cards ──────────────────────────── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 20px 22px;
            transition: box-shadow 0.2s, border-color 0.2s;
        }

        .stat-card:hover {
            border-color: var(--line-hi);
            box-shadow: 0 16px 40px rgba(0, 40, 120, 0.07);
        }

        .stat-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 10px;
        }

        .stat-value {
            font-family: 'Unbounded', sans-serif;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--ink);
            margin-bottom: 6px;
        }

        .stat-delta {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .stat-delta.up {
            color: var(--green);
        }

        .stat-delta.pending {
            color: var(--orange);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
        }

        .stat-icon svg {
            width: 20px;
            height: 20px;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
        }

        .stat-icon.blue {
            background: var(--blue-soft);
        }

        .stat-icon.blue svg {
            stroke: var(--blue);
        }

        .stat-icon.green {
            background: #E0FAF3;
        }

        .stat-icon.green svg {
            stroke: var(--green);
        }

        .stat-icon.cyan {
            background: #E0F6FD;
        }

        .stat-icon.cyan svg {
            stroke: var(--cyan);
        }

        .stat-icon.orange {
            background: #FFF3E0;
        }

        .stat-icon.orange svg {
            stroke: var(--orange);
        }

        /* ── Two column layout ───────────────────── */
        .two-col {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        /* ── Table scroll on mobile ──────────────── */
        .card {
            overflow-x: auto;
        }

        /* ── Desktop breakpoint ──────────────────── */
        @media (min-width: 1024px) {
            .sidebar {
                transform: translateX(0);
            }

            .hamburger {
                display: none;
            }

            .main {
                margin-left: 260px;
            }

            .header {
                padding: 0 32px;
                height: 64px;
                gap: 16px;
            }

            .header-search {
                display: flex;
            }

            .content {
                padding: 32px;
            }

            .page-header {
                margin-bottom: 28px;
                flex-wrap: nowrap;
            }

            .stats-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 16px;
                margin-bottom: 28px;
            }

            .two-col {
                grid-template-columns: 1fr 340px;
                gap: 20px;
            }

            .card {
                overflow-x: visible;
            }
        }

        /* ── Table card ──────────────────────────── */
        .card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 16px;
            overflow: hidden;
        }

        .card-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-family: 'Unbounded', sans-serif;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: -0.01em;
            color: var(--ink);
        }

        .card-count {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--muted);
            background: var(--bg-soft);
            border: 1px solid var(--line);
            padding: 3px 10px;
            border-radius: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
            padding: 12px 22px;
            text-align: left;
            background: var(--bg-soft);
            border-bottom: 1px solid var(--line);
        }

        tbody tr {
            border-bottom: 1px solid var(--line);
            transition: background 0.12s;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody tr:hover {
            background: var(--bg-soft);
        }

        tbody td {
            padding: 14px 22px;
            font-size: 13.5px;
            color: var(--ink-soft);
            vertical-align: middle;
        }

        .company-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .company-logo {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--bg-blue);
            border: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Unbounded', sans-serif;
            font-size: 10px;
            font-weight: 700;
            color: var(--blue-deep);
            flex-shrink: 0;
        }

        .company-name {
            font-weight: 600;
            color: var(--ink);
            font-size: 13.5px;
        }

        .company-inn {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--muted);
        }

        /* ── Status badges ───────────────────────── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .badge::before {
            content: '';
            width: 5px;
            height: 5px;
            border-radius: 50%;
        }

        .badge-approved {
            background: #E0FAF3;
            color: #007A5A;
        }

        .badge-approved::before {
            background: var(--green);
        }

        .badge-pending {
            background: #FFF3E0;
            color: #8B5000;
        }

        .badge-pending::before {
            background: var(--orange);
        }

        .badge-rejected {
            background: #FFEBEE;
            color: #B0001E;
        }

        .badge-rejected::before {
            background: var(--red);
        }

        /* ── Type tags ───────────────────────────── */
        .type-tags {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .type-tag {
            padding: 3px 9px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            background: var(--blue-soft);
            color: var(--blue-deep);
        }

        .type-tag.service {
            background: #E0F6FD;
            color: #005F82;
        }

        .type-tag.partner {
            background: #F0EAFF;
            color: #4B00B8;
        }

        /* ── Action buttons ──────────────────────── */
        .action-btn {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            border: 1px solid var(--line);
            background: var(--surface);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: border-color 0.15s, background 0.15s;
        }

        .action-btn:hover {
            border-color: var(--blue);
            background: var(--blue-soft);
        }

        .action-btn:hover svg {
            stroke: var(--blue);
        }

        .action-btn svg {
            width: 13px;
            height: 13px;
            stroke: var(--muted);
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .actions-cell {
            display: flex;
            gap: 5px;
        }

        /* ── Pending list (right col) ─────────────── */
        .pending-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            border-bottom: 1px solid var(--line);
            transition: background 0.12s;
        }

        .pending-item:last-child {
            border-bottom: none;
        }

        .pending-item:hover {
            background: var(--bg-soft);
        }

        .pending-logo {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--bg-blue);
            border: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Unbounded', sans-serif;
            font-size: 11px;
            font-weight: 700;
            color: var(--blue-deep);
            flex-shrink: 0;
        }

        .pending-name {
            font-weight: 600;
            font-size: 13px;
            color: var(--ink);
        }

        .pending-inn {
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            color: var(--muted);
        }

        .pending-actions {
            margin-left: auto;
            display: flex;
            gap: 5px;
        }

        .approve-btn {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            border: none;
            background: #E0FAF3;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s;
        }

        .approve-btn:hover {
            background: var(--green);
        }

        .approve-btn:hover svg {
            stroke: #fff;
        }

        .approve-btn svg {
            width: 13px;
            height: 13px;
            stroke: var(--green);
            fill: none;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .reject-btn {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            border: none;
            background: #FFEBEE;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s;
        }

        .reject-btn:hover {
            background: var(--red);
        }

        .reject-btn:hover svg {
            stroke: #fff;
        }

        .reject-btn svg {
            width: 13px;
            height: 13px;
            stroke: var(--red);
            fill: none;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
    </style>
</head>

<body style="display:flex;min-height:100vh">

    {{-- Overlay (mobile) --}}
    <div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>

    {{-- ═══════════════ SIDEBAR ═══════════════ --}}
    <aside class="sidebar">

        {{-- Logo --}}
        <div class="sidebar-logo">
            <div class="logo-mark">
                <svg viewBox="0 0 24 24">
                    <path d="M5 7h14M5 12h14M5 17h8" />
                </svg>
            </div>
            <div>
                <div class="logo-text">XPRINTER</div>
                <div class="logo-sub">Admin Panel</div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav">

            <span class="nav-section-label">Asosiy</span>

            <a href="{{ route('admin.dashboard') }}" @class([
                'nav-item',
                'active' => request()->routeIs('admin.dashboard'),
            ])>
                <div class="nav-icon">
                    <svg viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1" />
                        <rect x="14" y="3" width="7" height="7" rx="1" />
                        <rect x="3" y="14" width="7" height="7" rx="1" />
                        <rect x="14" y="14" width="7" height="7" rx="1" />
                    </svg>
                </div>
                Dashboard
            </a>

            <span class="nav-section-label">Ma'lumotlar</span>

            <a href="{{ route('admin.countries.index') }}" @class([
                'nav-item',
                'active' => request()->routeIs('admin.countries.*'),
            ])>
                <div class="nav-icon">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M2 12h20M12 2a15 15 0 0 0 0 20M12 2a15 15 0 0 1 0 20" />
                    </svg>
                </div>
                Davlatlar
            </a>

            <a href="{{ route('admin.regions.index') }}" @class([
                'nav-item',
                'active' => request()->routeIs('admin.regions.*'),
            ])>
                <div class="nav-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                    </svg>
                </div>
                Viloyatlar
            </a>

            <a href="{{ route('admin.cities.index') }}" @class(['nav-item', 'active' => request()->routeIs('admin.cities.*')])>
                <div class="nav-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                </div>
                Shaharlar
            </a>

            <span class="nav-section-label">Katalog</span>

            <a href="{{ route('admin.categories.index') }}" @class([
                'nav-item',
                'active' => request()->routeIs('admin.categories.*'),
            ])>
                <div class="nav-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M4 6h16M4 10h16M4 14h8" />
                    </svg>
                </div>
                Kategoriyalar
            </a>

            <a href="{{ route('admin.parameters.index') }}" @class([
                'nav-item',
                'active' => request()->routeIs('admin.parameters.*'),
            ])>
                <div class="nav-icon">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="3" />
                        <path
                            d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83" />
                    </svg>
                </div>
                Parametrlar
            </a>

            <a href="{{ route('admin.products.index') }}" @class([
                'nav-item',
                'active' => request()->routeIs('admin.products.*'),
            ])>
                <div class="nav-icon">
                    <svg viewBox="0 0 24 24">
                        <rect x="2" y="7" width="20" height="14" rx="2" />
                        <path d="M16 7V5a2 2 0 0 0-4 0v2M8 7V5a2 2 0 0 0-4 0v2" />
                    </svg>
                </div>
                Mahsulotlar
            </a>

            <span class="nav-section-label">Kompaniyalar</span>

            <a href="{{ route('admin.companies.index') }}" @class([
                'nav-item',
                'active' => request()->routeIs('admin.companies.index'),
            ])>
                <div class="nav-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg>
                </div>
                Barcha kompaniyalar
                @php($total = \App\Models\Company::count())
                @if ($total)
                    <span class="nav-badge">{{ $total }}</span>
                @endif
            </a>

            <a href="{{ route('admin.companies.pending') }}" @class([
                'nav-item',
                'active' => request()->routeIs('admin.companies.pending'),
            ])>
                <div class="nav-icon">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                </div>
                Kutilayotganlar
                @php($pending = \App\Models\Company::where('status', 'pending')->count())
                @if ($pending)
                    <span class="nav-badge orange">{{ $pending }}</span>
                @endif
            </a>

            <span class="nav-section-label">Foydalanuvchilar</span>

            <a href="{{ route('admin.users.index') }}" @class(['nav-item', 'active' => request()->routeIs('admin.users.*')])>
                <div class="nav-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
                Foydalanuvchilar
            </a>

            <span class="nav-section-label">Sozlamalar</span>

            <a href="{{ route('admin.translations.index') }}" @class([
                'nav-item',
                'active' => request()->routeIs('admin.translations.*'),
            ])>
                <div class="nav-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M5 8l6 6" />
                        <path d="M4 14l6-6 2-3" />
                        <path d="M2 5h12" />
                        <path d="M7 2h1" />
                        <path d="M22 22l-5-10-5 10" />
                        <path d="M14 18h6" />
                    </svg>
                </div>
                Tarjimalar
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
                <svg style="margin-left:auto;width:14px;height:14px;stroke:var(--faint);fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round"
                    viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="1" />
                    <circle cx="12" cy="5" r="1" />
                    <circle cx="12" cy="19" r="1" />
                </svg>
            </a>
        </div>

    </aside>

    {{-- ═══════════════ MAIN ═══════════════════ --}}
    <div class="main">

        {{-- Header --}}
        <header class="header">
            <button class="hamburger" onclick="toggleSidebar()" aria-label="Menu">
                <svg viewBox="0 0 24 24">
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </button>
            <div class="breadcrumb">
                <span class="bc">Admin</span>
                <span class="bc-sep">/</span>
                <span class="bc current">@yield('title', 'Dashboard')</span>
            </div>

            <div class="header-search">
                <svg viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input type="text" placeholder="Qidirish..." />
            </div>

            {{-- Language switcher --}}
            <div class="lang-switch">
                <button class="lang-btn @if (app()->getLocale() === 'uz') active @endif"
                    onclick="window.location='{{ route('lang.switch', 'uz') }}'">UZ</button>
                <button class="lang-btn @if (app()->getLocale() === 'ru') active @endif"
                    onclick="window.location='{{ route('lang.switch', 'ru') }}'">RU</button>
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}" style="display:contents">
                @csrf
                <button type="submit" class="hbtn" title="Chiqish">
                    <svg viewBox="0 0 24 24">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" y1="12" x2="9" y2="12" />
                    </svg>
                </button>
            </form>
        </header>

        {{-- Flash messages --}}
        <div style="padding: 0 32px; padding-top: 24px;">
            @if (session('success'))
                <div class="flash flash-success">
                    <svg style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2.5;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0"
                        viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="flash flash-error">
                    <svg style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2.5;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0"
                        viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="15" y1="9" x2="9" y2="15" />
                        <line x1="9" y1="9" x2="15" y2="15" />
                    </svg>
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
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('open');
            document.getElementById('overlay').classList.toggle('open');
        }
    </script>
</body>

</html>
