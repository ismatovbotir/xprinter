<!DOCTYPE html>
<html lang="{{ auth()->user()?->lang ?? 'uz' }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tasdiq kutilmoqda — Xprinter</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;600;700&family=Manrope:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />
    @vite(['resources/css/admin.css', 'resources/js/app.js'])
    <style>
        body { background: var(--bg-soft); display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 24px; }
        .pending-wrap { width: 100%; max-width: 480px; text-align: center; }
        .pending-icon { width: 80px; height: 80px; border-radius: 24px; background: linear-gradient(135deg, #FFF3CD, #FFE082); margin: 0 auto 24px; display: flex; align-items: center; justify-content: center; }
        .pending-icon svg { width: 40px; height: 40px; stroke: #F59E0B; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
        .pending-title { font-family: 'Unbounded', sans-serif; font-size: 22px; font-weight: 700; letter-spacing: -0.02em; color: var(--ink); margin-bottom: 12px; }
        .pending-text { font-size: 14px; color: var(--muted); line-height: 1.6; margin-bottom: 32px; }
        .info-row { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: var(--bg-soft); border-radius: 10px; margin-bottom: 8px; }
        .info-label { font-size: 12px; color: var(--muted); }
        .info-value { font-size: 13px; font-weight: 600; color: var(--ink); }
        .status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 20px; font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 600; letter-spacing: 0.05em; }
        .status-badge.pending { background: #FFF3CD; color: #B45309; }
        .status-badge.rejected { background: #FFE5E8; color: #C0392B; }
        .pulse { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #F59E0B; animation: pulse 1.5s infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.5; transform: scale(0.8); } }
    </style>
</head>
<body>

<div class="pending-wrap">

    @php $company = auth()->user()->company; @endphp

    @if($company && $company->status === 'rejected')
    {{-- Rejected --}}
    <div class="pending-icon" style="background: linear-gradient(135deg, #FFE5E8, #FFCCD2)">
        <svg viewBox="0 0 24 24" style="stroke:#C0392B"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
    </div>
    <div class="pending-title">Ariza rad etildi</div>
    <div class="pending-text">
        Afsuski, kompaniyangiz tasdiqlanmadi. Admin bilan bog'laning yoki yangi ariza yuboring.
    </div>

    @else
    {{-- Pending --}}
    <div class="pending-icon">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
    <div class="pending-title">Ariza ko'rib chiqilmoqda</div>
    <div class="pending-text">
        Kompaniyangiz ma'lumotlari Admin tomonidan tekshirilmoqda.<br>
        Odatda bu <strong>1–2 ish kuni</strong> davom etadi. Tasdiq haqida email orqali xabar olasiz.
    </div>
    @endif

    @if($company)
    <div class="card" style="margin-bottom:24px;text-align:left">
        <div style="padding:16px">
            <div class="info-row">
                <span class="info-label">Brend</span>
                <span class="info-value">{{ $company->brand ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">INN</span>
                <span class="info-value" style="font-family:'JetBrains Mono',monospace">{{ $company->inn }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Holat</span>
                <span class="status-badge {{ $company->status }}">
                    @if($company->status === 'pending')
                        <span class="pulse"></span> Ko'rib chiqilmoqda
                    @elseif($company->status === 'rejected')
                        Rad etildi
                    @else
                        {{ $company->status }}
                    @endif
                </span>
            </div>
        </div>
    </div>
    @endif

    <div style="display:flex;flex-direction:column;gap:10px;align-items:center">
        <a href="mailto:info@xprinter.uz" style="font-size:13px;color:var(--blue);text-decoration:none">
            info@xprinter.uz — savol yuboring
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background:none;border:none;color:var(--muted);font-size:13px;cursor:pointer;font-family:'Manrope',sans-serif">
                Chiqish
            </button>
        </form>
    </div>
</div>

</body>
</html>
