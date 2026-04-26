@extends('layouts.marketplace')
@section('title', "Kompaniyani ro'yxatdan o'tkazish")
@section('breadcrumb', "Ro'yxatdan o'tish")

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">Kompaniyani ro'yxatdan o'tkazish</div>
        <div class="page-subtitle">Ma'lumotlarni to'ldiring — Admin tekshirib, tasdiq beradi</div>
    </div>
</div>

{{-- Progress steps --}}
<div style="display:flex;align-items:center;gap:0;max-width:500px;margin-bottom:28px">
    @foreach([
        ['num' => '✓', 'label' => 'Ro\'yxatdan o\'tish', 'state' => 'done'],
        ['num' => '2',  'label' => 'Kompaniya ma\'lumotlari', 'state' => 'active'],
        ['num' => '3',  'label' => 'Admin tasdiq',  'state' => 'wait'],
    ] as $i => $step)
    @if($i > 0)
    <div style="flex:1;height:2px;background:{{ $step['state'] !== 'wait' ? 'var(--blue)' : 'var(--line)' }}"></div>
    @endif
    <div style="display:flex;flex-direction:column;align-items:center;gap:6px">
        <div style="width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'JetBrains Mono',monospace;font-size:12px;font-weight:600;
            {{ $step['state'] === 'done'   ? 'background:var(--green);color:#fff' : '' }}
            {{ $step['state'] === 'active' ? 'background:var(--blue);color:#fff' : '' }}
            {{ $step['state'] === 'wait'   ? 'background:var(--line);color:var(--muted)' : '' }}">
            {{ $step['num'] }}
        </div>
        <div style="font-size:10px;font-family:'JetBrains Mono',monospace;letter-spacing:0.05em;color:var(--muted);text-align:center;white-space:nowrap">
            {{ $step['label'] }}
        </div>
    </div>
    @endforeach
</div>

<form method="POST" action="{{ route('marketplace.onboarding.store') }}" style="max-width:500px">
    @csrf

    <div class="card" style="margin-bottom:16px">
        <div class="card-header"><div class="card-title">Kompaniya ma'lumotlari</div></div>
        <div style="padding:20px;display:flex;flex-direction:column;gap:16px">

            <div class="form-group">
                <label class="form-label">Kompaniya brendi <span style="color:var(--red)">*</span></label>
                <input type="text" name="brand"
                       class="form-input {{ $errors->has('brand') ? 'is-invalid' : '' }}"
                       value="{{ old('brand') }}" placeholder="Masalan: TechTrade LLC">
                @error('brand')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                <div style="font-size:11px;color:var(--muted);margin-top:4px">Savdo nomi yoki brendingizning nomi</div>
            </div>

            <div class="form-group">
                <label class="form-label">INN (Soliq raqami) <span style="color:var(--red)">*</span></label>
                <input type="text" name="inn"
                       class="form-input {{ $errors->has('inn') ? 'is-invalid' : '' }}"
                       value="{{ old('inn') }}" placeholder="123456789"
                       style="font-family:'JetBrains Mono',monospace;letter-spacing:0.05em">
                @error('inn')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                <div style="font-size:11px;color:var(--muted);margin-top:4px">Admin shu raqam orqali kompaniyangizni tekshiradi</div>
            </div>

            <div class="form-group">
                <label class="form-label">Aloqa raqami <span style="color:var(--red)">*</span></label>
                <input type="text" name="phone"
                       class="form-input {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                       value="{{ old('phone') }}" placeholder="+998 90 123 45 67">
                @error('phone')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
            </div>

        </div>
    </div>

    <div class="card" style="margin-bottom:20px">
        <div class="card-header"><div class="card-title">Faoliyat turi</div></div>
        <div style="padding:16px;display:flex;flex-direction:column;gap:10px">
            @error('types')<div class="invalid-feedback" style="display:block;margin-bottom:8px">{{ $message }}</div>@enderror

            @foreach([
                ['value' => 'retail',  'label' => 'Chakana savdo (Retail)',   'desc' => "Do'kon, showroom, online-savdo"],
                ['value' => 'partner', 'label' => 'Hamkorlik (Partner)',       'desc' => 'Integratsiya, ERP/POS yechimlari'],
                ['value' => 'service', 'label' => 'Servis markazi (Service)',  'desc' => "Ta'mirlash, texnik xizmat"],
            ] as $type)
            @php $checked = in_array($type['value'], old('types', [])); @endphp
            <label style="display:flex;align-items:center;gap:12px;padding:12px 14px;border:1.5px solid {{ $checked ? 'var(--blue)' : 'var(--line)' }};border-radius:12px;cursor:pointer;transition:all .15s;background:{{ $checked ? 'var(--bg-blue)' : 'var(--surface)' }}">
                <input type="checkbox" name="types[]" value="{{ $type['value'] }}"
                       {{ $checked ? 'checked' : '' }}
                       style="width:16px;height:16px;accent-color:var(--blue);flex-shrink:0"
                       onchange="this.closest('label').style.borderColor=this.checked?'var(--blue)':'var(--line)';this.closest('label').style.background=this.checked?'var(--bg-blue)':'var(--surface)'">
                <div>
                    <div style="font-size:13px;font-weight:600;color:var(--ink)">{{ $type['label'] }}</div>
                    <div style="font-size:12px;color:var(--muted)">{{ $type['desc'] }}</div>
                </div>
            </label>
            @endforeach
        </div>
    </div>

    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;height:46px;font-size:14px">
        Yuborish — tasdiq kutish
    </button>
</form>

@endsection
