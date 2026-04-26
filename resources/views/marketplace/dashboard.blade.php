@extends('layouts.marketplace')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">Xush kelibsiz, {{ auth()->user()->name }}</div>
        <div class="page-subtitle">{{ $company->brand ?? $company->name }} — diler kabineti</div>
    </div>
</div>

{{-- Status banner if recently approved --}}
@if(session('company_approved'))
<div style="background:linear-gradient(135deg,#E8F5E9,#F1FFF8);border:1.5px solid #A5D6A7;border-radius:14px;padding:16px 20px;margin-bottom:20px;display:flex;align-items:center;gap:14px">
    <div style="width:40px;height:40px;border-radius:12px;background:#00C896;display:flex;align-items:center;justify-content:center;flex-shrink:0">
        <svg viewBox="0 0 24 24" style="width:20px;height:20px;stroke:#fff;fill:none;stroke-width:2.5;stroke-linecap:round;stroke-linejoin:round"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <div>
        <div style="font-weight:600;color:#1B5E20;font-size:14px">Kompaniyangiz tasdiqlandi!</div>
        <div style="font-size:13px;color:#388E3C">Endi barcha imkoniyatlardan foydalanishingiz mumkin.</div>
    </div>
</div>
@endif

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px">

    <div class="card" style="padding:20px">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
            <div style="font-size:13px;color:var(--muted);font-weight:500">Mahsulotlar</div>
            <div style="width:36px;height:36px;border-radius:10px;background:var(--blue-soft);display:flex;align-items:center;justify-content:center">
                <svg viewBox="0 0 24 24" style="width:18px;height:18px;stroke:var(--blue);fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            </div>
        </div>
        <div style="font-family:'Unbounded',sans-serif;font-size:28px;font-weight:700;color:var(--ink)">
            {{ $company->companyProducts->count() }}
        </div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px">assortimentdagi mahsulot</div>
    </div>

    <div class="card" style="padding:20px">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
            <div style="font-size:13px;color:var(--muted);font-weight:500">Jamoa</div>
            <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#E8F0FF,#D0E4FF);display:flex;align-items:center;justify-content:center">
                <svg viewBox="0 0 24 24" style="width:18px;height:18px;stroke:#3D6ACC;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
        </div>
        <div style="font-family:'Unbounded',sans-serif;font-size:28px;font-weight:700;color:var(--ink)">
            {{ $company->users->count() }}
        </div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px">foydalanuvchi</div>
    </div>

    <div class="card" style="padding:20px">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
            <div style="font-size:13px;color:var(--muted);font-weight:500">NDS holati</div>
            <div style="width:36px;height:36px;border-radius:10px;background:{{ $company->vat_status === 'payer' ? 'linear-gradient(135deg,#E8F5E9,#C8E6C9)' : 'var(--bg-soft)' }};display:flex;align-items:center;justify-content:center">
                <svg viewBox="0 0 24 24" style="width:18px;height:18px;stroke:{{ $company->vat_status === 'payer' ? '#388E3C' : 'var(--muted)' }};fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
        </div>
        <div style="font-family:'JetBrains Mono',monospace;font-size:14px;font-weight:700;color:var(--ink);text-transform:uppercase;letter-spacing:0.05em">
            {{ $company->vat_status === 'payer' ? 'NDS to\'lovchi' : 'NDS to\'lovchi emas' }}
        </div>
        @if($company->vat_status === 'non_payer')
        <div style="margin-top:8px">
            <a href="#" style="font-size:12px;color:var(--blue);text-decoration:none;font-weight:500">
                Tekshiruv so'rash →
            </a>
        </div>
        @endif
    </div>

    <div class="card" style="padding:20px">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
            <div style="font-size:13px;color:var(--muted);font-weight:500">Status</div>
            <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#E8F5E9,#C8E6C9);display:flex;align-items:center;justify-content:center">
                <svg viewBox="0 0 24 24" style="width:18px;height:18px;stroke:#388E3C;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
        </div>
        <div style="font-family:'JetBrains Mono',monospace;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:#388E3C">
            Tasdiqlangan
        </div>
        @if($company->manufacturer_status !== 'none')
        <div style="margin-top:6px;display:inline-flex;align-items:center;gap:6px;padding:3px 10px;background:linear-gradient(135deg,var(--blue),var(--cyan));border-radius:20px">
            <span style="font-family:'JetBrains Mono',monospace;font-size:10px;font-weight:600;color:#fff;letter-spacing:0.05em;text-transform:uppercase">
                {{ $company->manufacturer_status === 'authorized_partner' ? 'Auth. Partner' : 'Auth. Distributor' }}
            </span>
        </div>
        @endif
    </div>

</div>

{{-- Quick actions --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">Tezkor harakatlar</div>
    </div>
    <div style="padding:20px;display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px">
        <a href="#" style="display:flex;align-items:center;gap:12px;padding:14px 16px;border:1.5px solid var(--line);border-radius:12px;text-decoration:none;color:var(--ink);transition:border-color 0.15s,background 0.15s" onmouseover="this.style.borderColor='var(--blue)';this.style.background='var(--bg-blue)'" onmouseout="this.style.borderColor='var(--line)';this.style.background=''">
            <svg viewBox="0 0 24 24" style="width:20px;height:20px;stroke:var(--blue);fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span style="font-size:13.5px;font-weight:500">Mahsulot qo'shish</span>
        </a>
        @if(auth()->user()->isOwner())
        <a href="{{ route('marketplace.team.create') }}" style="display:flex;align-items:center;gap:12px;padding:14px 16px;border:1.5px solid var(--line);border-radius:12px;text-decoration:none;color:var(--ink);transition:border-color 0.15s,background 0.15s" onmouseover="this.style.borderColor='var(--blue)';this.style.background='var(--bg-blue)'" onmouseout="this.style.borderColor='var(--line)';this.style.background=''">
            <svg viewBox="0 0 24 24" style="width:20px;height:20px;stroke:var(--blue);fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
            <span style="font-size:13.5px;font-weight:500">Xodim qo'shish</span>
        </a>
        @endif
        <a href="#" style="display:flex;align-items:center;gap:12px;padding:14px 16px;border:1.5px solid var(--line);border-radius:12px;text-decoration:none;color:var(--ink);transition:border-color 0.15s,background 0.15s" onmouseover="this.style.borderColor='var(--blue)';this.style.background='var(--bg-blue)'" onmouseout="this.style.borderColor='var(--line)';this.style.background=''">
            <svg viewBox="0 0 24 24" style="width:20px;height:20px;stroke:var(--blue);fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span style="font-size:13.5px;font-weight:500">Kompaniya profili</span>
        </a>
    </div>
</div>

@endsection
