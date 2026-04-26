@extends('layouts.producer')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">Producer Dashboard</div>
        <div class="page-subtitle">Xprinter Group — umumiy ko'rsatkichlar</div>
    </div>
</div>

{{-- Dealer stats --}}
<div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(180px,1fr));margin-bottom:24px">

    <div class="stat-card">
        <div class="stat-icon blue">
            <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </div>
        <div class="stat-label">Faol dilerlar</div>
        <div class="stat-value">{{ $dealerCount }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
        </div>
        <div class="stat-label">Authorized hamkorlar</div>
        <div class="stat-value">{{ $partnerCount }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="stat-label">Kutilayotgan</div>
        <div class="stat-value">{{ $pendingCount }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon cyan">
            <svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-4 0v2M8 7V5a2 2 0 0 0-4 0v2"/></svg>
        </div>
        <div class="stat-label">Mahsulot modellari</div>
        <div class="stat-value">{{ $productCount }}</div>
    </div>

</div>

{{-- Serial stats --}}
<div class="card" style="margin-bottom:20px">
    <div class="card-header">
        <div class="card-title">Seriya raqamlari</div>
        <a href="{{ route('producer.serials.import') }}" class="btn btn-primary" style="height:34px;font-size:12px;padding:0 14px">
            <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            CSV import
        </a>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));padding:20px;gap:16px">

        <div style="text-align:center">
            <div style="font-family:'Unbounded',sans-serif;font-size:28px;font-weight:700;color:var(--ink)">{{ number_format($serialTotal) }}</div>
            <div style="font-size:12px;color:var(--muted);margin-top:4px">Jami</div>
        </div>

        <div style="text-align:center">
            <div style="font-family:'Unbounded',sans-serif;font-size:28px;font-weight:700;color:var(--blue)">{{ number_format($serialAvailable) }}</div>
            <div style="font-size:12px;color:var(--muted);margin-top:4px">Mavjud</div>
        </div>

        <div style="text-align:center">
            <div style="font-family:'Unbounded',sans-serif;font-size:28px;font-weight:700;color:var(--green)">{{ number_format($serialSold) }}</div>
            <div style="font-size:12px;color:var(--muted);margin-top:4px">Sotilgan</div>
        </div>

        <div style="text-align:center">
            <div style="font-family:'Unbounded',sans-serif;font-size:28px;font-weight:700;color:var(--cyan)">{{ number_format($serialRegistered) }}</div>
            <div style="font-size:12px;color:var(--muted);margin-top:4px">Ro'yxatdan o'tgan</div>
        </div>

    </div>
</div>

{{-- Quick links --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px">
    <a href="{{ route('producer.serials.index') }}" style="display:flex;align-items:center;gap:12px;padding:16px;background:var(--surface);border:1.5px solid var(--line);border-radius:14px;text-decoration:none;color:var(--ink);transition:border-color 0.15s" onmouseover="this.style.borderColor='var(--blue)'" onmouseout="this.style.borderColor='var(--line)'">
        <div style="width:40px;height:40px;border-radius:12px;background:var(--blue-soft);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg viewBox="0 0 24 24" style="width:20px;height:20px;stroke:var(--blue);fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div>
            <div style="font-weight:600;font-size:14px">Seriya raqamlari</div>
            <div style="font-size:12px;color:var(--muted)">Ko'rish va filter</div>
        </div>
    </a>
    <a href="{{ route('producer.partners.index') }}" style="display:flex;align-items:center;gap:12px;padding:16px;background:var(--surface);border:1.5px solid var(--line);border-radius:14px;text-decoration:none;color:var(--ink);transition:border-color 0.15s" onmouseover="this.style.borderColor='var(--blue)'" onmouseout="this.style.borderColor='var(--line)'">
        <div style="width:40px;height:40px;border-radius:12px;background:#E0FAF3;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg viewBox="0 0 24 24" style="width:20px;height:20px;stroke:var(--green);fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <div>
            <div style="font-weight:600;font-size:14px">Hamkorlar</div>
            <div style="font-size:12px;color:var(--muted)">Status boshqaruvi</div>
        </div>
    </a>
</div>

@endsection
