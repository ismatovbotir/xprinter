@extends('layouts.marketplace')
@section('title', 'Kompaniya profili')
@section('breadcrumb', 'Kompaniya profili')

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">Kompaniya profili</div>
        <div class="page-subtitle">{{ $company->brand ?? $company->name }}</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start">

    {{-- LEFT COLUMN --}}
    <div style="display:flex;flex-direction:column;gap:20px">

        {{-- Editable info --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Asosiy ma'lumotlar</div>
            </div>
            <form method="POST" action="{{ route('marketplace.company.update') }}" style="padding:20px;display:flex;flex-direction:column;gap:16px">
                @csrf @method('PUT')

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div class="form-group">
                        <label class="form-label">Brend nomi</label>
                        <input type="text" name="brand" class="form-input @error('brand') is-invalid @enderror"
                               value="{{ old('brand', $company->brand) }}" placeholder="Brend nomini kiriting">
                        @error('brand')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Telefon</label>
                        <input type="text" name="phone" class="form-input @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $company->phone) }}" placeholder="+998 XX XXX XX XX">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Faoliyat turi</label>
                    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:4px">
                        @foreach(['retail' => 'Chakana savdo', 'partner' => 'Hamkorlik', 'service' => 'Servis markaz'] as $val => $label)
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:8px 14px;border:1.5px solid var(--line);border-radius:10px;transition:border-color 0.15s,background 0.15s;{{ in_array($val, $company->types ?? []) ? 'border-color:var(--blue);background:var(--bg-blue)' : '' }}">
                            <input type="checkbox" name="types[]" value="{{ $val }}"
                                   {{ in_array($val, $company->types ?? []) ? 'checked' : '' }}
                                   style="width:15px;height:15px;accent-color:var(--blue)">
                            <span style="font-size:13.5px;font-weight:500;color:var(--ink)">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end">
                    <button type="submit" class="btn btn-primary">Saqlash</button>
                </div>
            </form>
        </div>

        {{-- Addresses --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Manzillar</div>
            </div>

            @if($company->addresses->isNotEmpty())
            <div style="padding:0 20px">
                @foreach($company->addresses as $address)
                <div style="display:flex;align-items:start;gap:12px;padding:14px 0;border-bottom:1px solid var(--line)">
                    <div style="width:36px;height:36px;border-radius:10px;background:var(--bg-soft);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px">
                        <svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:var(--muted);fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-weight:600;color:var(--ink);font-size:14px">{{ $address->name }}</div>
                        <div style="font-size:13px;color:var(--muted);margin-top:2px">
                            {{ $address->city->translations->where('lang', app()->getLocale())->first()?->name ?? $address->city->translations->first()?->name }}
                            @if($address->description)
                            — {{ $address->description }}
                            @endif
                        </div>
                        @if($address->postal_code)
                        <div style="font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--faint);margin-top:2px">{{ $address->postal_code }}</div>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('marketplace.company.address.destroy', $address) }}"
                          data-confirm="Manzilni o'chirasizmi?">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-btn danger" title="O'chirish">
                            <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Add address form --}}
            <div style="padding:20px;border-top:{{ $company->addresses->isNotEmpty() ? '1px solid var(--line)' : 'none' }}">
                <div style="font-size:13px;font-weight:600;color:var(--ink-soft);margin-bottom:12px">
                    {{ $company->addresses->isNotEmpty() ? "Manzil qo'shish" : "Birinchi manzilni qo'shing" }}
                </div>
                <form method="POST" action="{{ route('marketplace.company.address.store') }}"
                      style="display:flex;flex-direction:column;gap:12px">
                    @csrf

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                        <div class="form-group" style="margin:0">
                            <label class="form-label">Viloyat</label>
                            <select id="region_select" class="form-input" onchange="filterCities(this.value)">
                                <option value="">Viloyatni tanlang</option>
                                @foreach($regions as $region)
                                <option value="{{ $region->id }}">
                                    {{ $region->translations->where('lang', 'uz')->first()?->name ?? $region->translations->first()?->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" style="margin:0">
                            <label class="form-label">Shahar / tuman</label>
                            <select name="city_id" id="city_select" class="form-input @error('city_id') is-invalid @enderror">
                                <option value="">Avval viloyatni tanlang</option>
                            </select>
                            @error('city_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:2fr 1fr;gap:12px">
                        <div class="form-group" style="margin:0">
                            <label class="form-label">Manzil nomi (ko'cha, uy)</label>
                            <input type="text" name="name" class="form-input @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="Ko'cha nomi, uy raqami">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group" style="margin:0">
                            <label class="form-label">Pochta indeksi</label>
                            <input type="text" name="postal_code" class="form-input"
                                   value="{{ old('postal_code') }}" placeholder="100000">
                        </div>
                    </div>

                    <div class="form-group" style="margin:0">
                        <label class="form-label">Qo'shimcha ma'lumot</label>
                        <input type="text" name="description" class="form-input"
                               value="{{ old('description') }}" placeholder="5-qavat, 12-xona va h.k.">
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary">
                            <svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Manzil qo'shish
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- RIGHT COLUMN --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        {{-- Official info (read-only) --}}
        <div class="card" style="padding:20px">
            <div style="font-size:12px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:var(--muted);margin-bottom:16px">
                Rasmiy ma'lumotlar
            </div>
            <div style="display:flex;flex-direction:column;gap:12px">
                <div>
                    <div style="font-size:11px;color:var(--faint);font-weight:500;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:3px">Kompaniya nomi</div>
                    <div style="font-size:14px;font-weight:600;color:var(--ink)">{{ $company->name ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--faint);font-weight:500;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:3px">INN</div>
                    <div style="font-family:'JetBrains Mono',monospace;font-size:14px;font-weight:600;color:var(--ink)">{{ $company->inn ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--faint);font-weight:500;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:3px">Yuridik shakli</div>
                    <div style="font-size:14px;color:var(--ink-soft)">{{ $company->legal_form ?? '—' }}</div>
                </div>
                <div style="padding-top:8px;border-top:1px solid var(--line)">
                    <div style="font-size:11px;color:var(--faint);font-weight:500;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px">NDS holati</div>
                    @if($company->vat_status === 'payer')
                    <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;background:linear-gradient(135deg,#E8F5E9,#C8E6C9);border-radius:20px">
                        <span style="width:6px;height:6px;border-radius:50%;background:#388E3C;display:inline-block"></span>
                        <span style="font-size:12px;font-weight:600;color:#1B5E20">NDS to'lovchi</span>
                    </span>
                    @else
                    <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;background:var(--bg-soft);border-radius:20px">
                        <span style="width:6px;height:6px;border-radius:50%;background:var(--faint);display:inline-block"></span>
                        <span style="font-size:12px;font-weight:600;color:var(--muted)">NDS to'lovchi emas</span>
                    </span>
                    <div style="margin-top:8px">
                        <a href="#" style="font-size:12px;color:var(--blue);text-decoration:none;font-weight:500;display:inline-flex;align-items:center;gap:4px">
                            <svg viewBox="0 0 24 24" style="width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            Tekshiruv so'rash
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Manufacturer status --}}
        @if($company->manufacturer_status !== 'none')
        <div class="card" style="padding:20px;background:linear-gradient(135deg,var(--blue),var(--cyan));border:none">
            <div style="display:flex;align-items:center;gap:10px">
                <svg viewBox="0 0 24 24" style="width:24px;height:24px;stroke:#fff;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <polyline points="9 12 11 14 15 10"/>
                </svg>
                <div>
                    <div style="font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.7)">Xprinter Group tomonidan</div>
                    <div style="font-size:14px;font-weight:700;color:#fff;margin-top:2px">
                        {{ $company->manufacturer_status === 'authorized_partner' ? 'Authorized Partner' : 'Authorized Distributor' }}
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Quick links --}}
        <div class="card" style="padding:16px">
            <div style="display:flex;flex-direction:column;gap:8px">
                <a href="{{ route('marketplace.team.index') }}" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;text-decoration:none;color:var(--ink);transition:background 0.15s" onmouseover="this.style.background='var(--bg-soft)'" onmouseout="this.style.background=''">
                    <svg viewBox="0 0 24 24" style="width:18px;height:18px;stroke:var(--blue);fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span style="font-size:13.5px;font-weight:500">Jamoa boshqaruvi</span>
                    <svg viewBox="0 0 24 24" style="width:14px;height:14px;stroke:var(--faint);fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;margin-left:auto"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
                <a href="{{ route('profile.edit') }}" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;text-decoration:none;color:var(--ink);transition:background 0.15s" onmouseover="this.style.background='var(--bg-soft)'" onmouseout="this.style.background=''">
                    <svg viewBox="0 0 24 24" style="width:18px;height:18px;stroke:var(--blue);fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span style="font-size:13.5px;font-weight:500">Mening profilim</span>
                    <svg viewBox="0 0 24 24" style="width:14px;height:14px;stroke:var(--faint);fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;margin-left:auto"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            </div>
        </div>

    </div>
</div>

<script>
const citiesByRegion = @json($regions->map(fn($r) => [
    'id' => $r->id,
    'cities' => $r->cities->map(fn($c) => [
        'id' => $c->id,
        'name' => $c->translations->where('lang', 'uz')->first()?->name ?? $c->translations->first()?->name,
    ])->values(),
])->keyBy('id'));

function filterCities(regionId) {
    const select = document.getElementById('city_select');
    select.innerHTML = '<option value="">Shaharni tanlang</option>';
    if (!regionId || !citiesByRegion[regionId]) return;
    citiesByRegion[regionId].cities.forEach(city => {
        const opt = document.createElement('option');
        opt.value = city.id;
        opt.textContent = city.name;
        select.appendChild(opt);
    });
}
</script>

@endsection
