<!DOCTYPE html>
<html lang="{{ auth()->user()?->lang ?? 'uz' }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kompaniyani ro'yxatdan o'tkazish — Xprinter</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;600;700&family=Manrope:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />
    @vite(['resources/css/admin.css', 'resources/js/app.js'])
    <style>
        body { background: var(--bg-soft); display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 24px; }
        .onboard-wrap { width: 100%; max-width: 520px; }
        .onboard-logo { display: flex; align-items: center; gap: 12px; margin-bottom: 32px; }
        .onboard-logo-mark { width: 44px; height: 44px; background: linear-gradient(135deg, var(--blue), var(--cyan)); border-radius: 12px; display: flex; align-items: center; justify-content: center; }
        .onboard-logo-mark svg { width: 24px; height: 24px; fill: #fff; }
        .onboard-logo-text { font-family: 'Unbounded', sans-serif; font-size: 16px; font-weight: 700; color: var(--ink); }
        .onboard-logo-sub { font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.1em; }
        .step-indicator { display: flex; align-items: center; gap: 8px; margin-bottom: 28px; }
        .step { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 600; }
        .step.active { background: var(--blue); color: #fff; }
        .step.done { background: var(--green); color: #fff; }
        .step.pending { background: var(--line); color: var(--muted); }
        .step-line { flex: 1; height: 2px; background: var(--line); border-radius: 2px; }
        .step-line.done { background: var(--green); }
        .check-group { display: flex; flex-direction: column; gap: 10px; }
        .check-item { display: flex; align-items: center; gap: 12px; padding: 14px 16px; border: 1.5px solid var(--line); border-radius: 12px; cursor: pointer; transition: border-color 0.15s, background 0.15s; }
        .check-item:hover { border-color: var(--blue); background: var(--bg-blue); }
        .check-item input[type=checkbox] { width: 18px; height: 18px; accent-color: var(--blue); cursor: pointer; flex-shrink: 0; }
        .check-item input[type=checkbox]:checked + .check-label { color: var(--blue-deep); font-weight: 600; }
        .check-label { font-size: 14px; color: var(--ink-soft); }
        .check-desc { font-size: 12px; color: var(--muted); margin-top: 2px; }
    </style>
</head>
<body>

<div class="onboard-wrap">
    <div class="onboard-logo">
        <div class="onboard-logo-mark">
            <svg viewBox="0 0 24 24"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        </div>
        <div>
            <div class="onboard-logo-text">Xprinter</div>
            <div class="onboard-logo-sub">Diler kabineti</div>
        </div>
    </div>

    <div class="step-indicator">
        <div class="step done">✓</div>
        <div class="step-line done"></div>
        <div class="step active">2</div>
        <div class="step-line pending"></div>
        <div class="step pending">3</div>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Kompaniyani ro'yxatdan o'tkazish</div>
                <div style="font-size:13px;color:var(--muted);margin-top:4px">
                    Ma'lumotlarni to'ldiring — Admin tekshirib, tasdiq beradi
                </div>
            </div>
        </div>

        <div style="padding: 24px">
            <form method="POST" action="{{ route('marketplace.onboarding.store') }}">
                @csrf

                {{-- Brand --}}
                <div class="form-group" style="margin-bottom:16px">
                    <label class="form-label">Kompaniya brendi <span style="color:var(--red)">*</span></label>
                    <input type="text" name="brand" class="form-input @error('brand') error @enderror"
                           value="{{ old('brand') }}" placeholder="Misol: TechTrade LLC"
                           style="width:100%" required>
                    @error('brand')
                    <span class="form-error">{{ $message }}</span>
                    @enderror
                    <div style="font-size:12px;color:var(--muted);margin-top:4px">
                        Savdo nomi yoki brendingizning nomi
                    </div>
                </div>

                {{-- INN --}}
                <div class="form-group" style="margin-bottom:16px">
                    <label class="form-label">INN (Soliq raqami) <span style="color:var(--red)">*</span></label>
                    <input type="text" name="inn" class="form-input @error('inn') error @enderror"
                           value="{{ old('inn') }}" placeholder="123456789"
                           style="width:100%" required>
                    @error('inn')
                    <span class="form-error">{{ $message }}</span>
                    @enderror
                    <div style="font-size:12px;color:var(--muted);margin-top:4px">
                        Admin shu raqam orqali kompaniyangizni tekshiradi
                    </div>
                </div>

                {{-- Phone --}}
                <div class="form-group" style="margin-bottom:20px">
                    <label class="form-label">Aloqa raqami <span style="color:var(--red)">*</span></label>
                    <input type="text" name="phone" class="form-input @error('phone') error @enderror"
                           value="{{ old('phone') }}" placeholder="+998 90 123 45 67"
                           style="width:100%" required>
                    @error('phone')
                    <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Types --}}
                <div class="form-group" style="margin-bottom:24px">
                    <label class="form-label" style="margin-bottom:10px">
                        Faoliyat turi <span style="color:var(--red)">*</span>
                    </label>
                    @error('types')
                    <div class="form-error" style="margin-bottom:8px">{{ $message }}</div>
                    @enderror

                    <div class="check-group">
                        <label class="check-item" style="{{ in_array('retail', old('types', [])) ? 'border-color:var(--blue);background:var(--bg-blue)' : '' }}">
                            <input type="checkbox" name="types[]" value="retail"
                                   {{ in_array('retail', old('types', [])) ? 'checked' : '' }}>
                            <div>
                                <div class="check-label">Chakana savdo (Retail)</div>
                                <div class="check-desc">Do'kon, showroom, online-savdo</div>
                            </div>
                        </label>
                        <label class="check-item" style="{{ in_array('partner', old('types', [])) ? 'border-color:var(--blue);background:var(--bg-blue)' : '' }}">
                            <input type="checkbox" name="types[]" value="partner"
                                   {{ in_array('partner', old('types', [])) ? 'checked' : '' }}>
                            <div>
                                <div class="check-label">Hamkorlik (Partner)</div>
                                <div class="check-desc">Integratsiya, ERP/POS yechimlari</div>
                            </div>
                        </label>
                        <label class="check-item" style="{{ in_array('service', old('types', [])) ? 'border-color:var(--blue);background:var(--bg-blue)' : '' }}">
                            <input type="checkbox" name="types[]" value="service"
                                   {{ in_array('service', old('types', [])) ? 'checked' : '' }}>
                            <div>
                                <div class="check-label">Servis markazi (Service)</div>
                                <div class="check-desc">Ta'mirlash, texnik xizmat</div>
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;height:46px;font-size:15px">
                    Yuborish — tasdiq kutish
                </button>
            </form>
        </div>
    </div>

    <div style="text-align:center;margin-top:16px">
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
