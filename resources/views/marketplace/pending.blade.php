@extends('layouts.marketplace')
@section('title', 'Tasdiq kutilmoqda')
@section('breadcrumb', 'Ariza holati')

@section('content')

@php $company = auth()->user()->company; @endphp

<div style="max-width:480px">

    @if($company && $company->status === 'rejected')
    {{-- ── Rejected ── --}}
    <div style="display:flex;align-items:center;gap:16px;padding:24px;background:#FFF1F2;border:1px solid #FECDD3;border-radius:16px;margin-bottom:24px">
        <div style="width:52px;height:52px;border-radius:14px;background:#FFE4E6;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg viewBox="0 0 24 24" style="width:28px;height:28px;stroke:#9F1239;fill:none;stroke-width:2;stroke-linecap:round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
        </div>
        <div>
            <div style="font-family:'Unbounded',sans-serif;font-size:16px;font-weight:600;color:#9F1239;margin-bottom:4px">Ariza rad etildi</div>
            <div style="font-size:13px;color:#9F1239;opacity:0.8">Admin bilan bog'laning yoki yangi ariza yuboring</div>
        </div>
    </div>

    @else
    {{-- ── Pending ── --}}
    <div style="display:flex;align-items:center;gap:16px;padding:24px;background:#FFFBEB;border:1px solid #FDE68A;border-radius:16px;margin-bottom:24px">
        <div style="width:52px;height:52px;border-radius:14px;background:#FEF3C7;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg viewBox="0 0 24 24" style="width:28px;height:28px;stroke:#92400E;fill:none;stroke-width:2;stroke-linecap:round">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
        </div>
        <div>
            <div style="font-family:'Unbounded',sans-serif;font-size:16px;font-weight:600;color:#92400E;margin-bottom:4px">Ko'rib chiqilmoqda</div>
            <div style="font-size:13px;color:#92400E;opacity:0.8">
                Odatda <strong>1–2 ish kuni</strong> davom etadi. Tasdiq haqida email orqali xabar olasiz.
            </div>
        </div>
    </div>
    @endif

    {{-- Admin note (reason for rejection or any note) --}}
    @if($company?->admin_note)
    <div style="background:#FFF1F2;border:1px solid #FECDD3;border-radius:14px;padding:16px 20px;margin-bottom:20px">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
            <svg viewBox="0 0 24 24" style="width:14px;height:14px;stroke:#9F1239;fill:none;stroke-width:2;flex-shrink:0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span style="font-family:'JetBrains Mono',monospace;font-size:10px;letter-spacing:0.1em;text-transform:uppercase;color:#9F1239;font-weight:600">Admin izohi</span>
        </div>
        <div style="font-size:14px;color:var(--ink-soft);line-height:1.6">{{ $company->admin_note }}</div>
    </div>
    @endif

    {{-- Company info card --}}
    @if($company)
    <div class="card" style="margin-bottom:20px">
        <div class="card-header"><div class="card-title">Ariza ma'lumotlari</div></div>
        <div style="padding:8px 0">
            @foreach([
                ['Brend', $company->brand ?? '—'],
                ['INN', $company->inn],
                ['Telefon', $company->phone ?? '—'],
            ] as [$lbl, $val])
            <div style="display:flex;justify-content:space-between;padding:11px 20px;border-bottom:1px solid var(--line)">
                <span style="font-size:13px;color:var(--muted)">{{ $lbl }}</span>
                <span style="font-size:13px;font-weight:600;color:var(--ink);font-family:'JetBrains Mono',monospace">{{ $val }}</span>
            </div>
            @endforeach
            <div style="display:flex;justify-content:space-between;align-items:center;padding:11px 20px">
                <span style="font-size:13px;color:var(--muted)">Holat</span>
                @if($company->status === 'pending')
                <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:20px;background:#FEF3C7;color:#92400E;font-family:'JetBrains Mono',monospace;font-size:11px;font-weight:600">
                    <span style="width:6px;height:6px;border-radius:50%;background:#F59E0B;animation:pulse-dot 1.5s infinite;display:inline-block"></span>
                    Ko'rib chiqilmoqda
                </span>
                @elseif($company->status === 'rejected')
                <span style="padding:4px 12px;border-radius:20px;background:#FFE4E6;color:#9F1239;font-family:'JetBrains Mono',monospace;font-size:11px;font-weight:600">
                    Rad etildi
                </span>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Progress steps --}}
    <div style="display:flex;align-items:center;gap:0;margin-bottom:24px">
        @foreach([
            ['num' => '✓', 'label' => "Ro'yxatdan o'tish", 'done' => true],
            ['num' => '✓', 'label' => 'Ariza yuborildi',   'done' => true],
            ['num' => '3', 'label' => 'Admin tasdiq',      'done' => false],
        ] as $i => $step)
        @if($i > 0)
        <div style="flex:1;height:2px;background:{{ ($i === 1 || $step['done']) ? 'var(--green)' : 'var(--line)' }}"></div>
        @endif
        <div style="display:flex;flex-direction:column;align-items:center;gap:6px">
            <div style="width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'JetBrains Mono',monospace;font-size:12px;font-weight:600;
                background:{{ $step['done'] ? 'var(--green)' : 'var(--line)' }};
                color:{{ $step['done'] ? '#fff' : 'var(--muted)' }}">
                {{ $step['num'] }}
            </div>
            <div style="font-size:10px;font-family:'JetBrains Mono',monospace;letter-spacing:0.05em;color:var(--muted);text-align:center;white-space:nowrap">
                {{ $step['label'] }}
            </div>
        </div>
        @endforeach
    </div>

    <a href="mailto:info@xprinter.uz"
       style="display:flex;align-items:center;gap:10px;padding:14px 18px;background:var(--bg-soft);border:1px solid var(--line);border-radius:12px;font-size:13px;color:var(--ink-soft);text-decoration:none;transition:border-color .15s"
       onmouseover="this.style.borderColor='var(--blue)'"
       onmouseout="this.style.borderColor='var(--line)'">
        <svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:var(--blue);fill:none;stroke-width:2;flex-shrink:0"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        <span>Savolingiz bormi? <strong style="color:var(--blue)">info@xprinter.uz</strong></span>
    </a>

</div>

<style>
@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: 0.5; transform: scale(0.8); }
}
</style>

@endsection
