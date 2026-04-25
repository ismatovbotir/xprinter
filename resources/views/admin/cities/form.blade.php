@extends('layouts.admin')
@section('title', isset($city) ? 'Shahalni tahrirlash' : "Shahar qo'shish")

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">{{ isset($city) ? 'Shahalni tahrirlash' : "Shahar qo'shish" }}</div>
    <div class="page-subtitle">
      <a href="{{ route('admin.cities.index') }}" style="color:var(--blue);text-decoration:none">
        ← Shaharlar ro'yxatiga qaytish
      </a>
    </div>
  </div>
</div>

<div class="card" style="max-width:680px">
  <div class="card-header">
    <div class="card-title">Asosiy ma'lumotlar</div>
  </div>
  <div style="padding:28px">
    <form method="POST" action="{{ isset($city) ? route('admin.cities.update', $city) : route('admin.cities.store') }}">
      @csrf
      @isset($city) @method('PUT') @endisset

      {{-- Country (for cascading only, not submitted) --}}
      <div class="form-group" style="margin-bottom:20px">
        <label class="form-label">Davlat</label>
        <select id="country_select" class="form-input" onchange="filterRegions(this.value)">
          <option value="">— Davlatni tanlang —</option>
          @foreach($countries as $country)
            <option value="{{ $country->id }}"
              {{ old('_country_id', isset($city) ? $city->region->country_id : '') == $country->id ? 'selected' : '' }}>
              {{ $country->translations->firstWhere('lang','uz')?->name ?? $country->code }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Region --}}
      <div class="form-group" style="margin-bottom:24px">
        <label class="form-label">Viloyat <span style="color:#D32F2F">*</span></label>
        <select name="region_id" id="region_select"
                class="form-input @error('region_id') error @enderror">
          <option value="">— Viloyatni tanlang —</option>
          @foreach($regions as $region)
            <option value="{{ $region->id }}"
                    data-country="{{ $region->country_id }}"
                    {{ old('region_id', $city->region_id ?? '') == $region->id ? 'selected' : '' }}>
              {{ $region->translations->firstWhere('lang','uz')?->name ?? '—' }}
            </option>
          @endforeach
        </select>
        @error('region_id') <span class="form-error">{{ $message }}</span> @enderror
      </div>

      <div class="form-grid" style="margin-bottom:28px">
        <div class="form-group">
          <label class="form-label">Nomi — O'zbekcha <span style="color:#D32F2F">*</span></label>
          <input type="text" name="name_uz"
                 class="form-input @error('name_uz') error @enderror"
                 value="{{ old('name_uz', isset($city) ? ($city->translations->firstWhere('lang','uz')?->name ?? '') : '') }}"
                 placeholder="Toshkent">
          @error('name_uz') <span class="form-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Nomi — Ruscha <span style="color:#D32F2F">*</span></label>
          <input type="text" name="name_ru"
                 class="form-input @error('name_ru') error @enderror"
                 value="{{ old('name_ru', isset($city) ? ($city->translations->firstWhere('lang','ru')?->name ?? '') : '') }}"
                 placeholder="Ташкент">
          @error('name_ru') <span class="form-error">{{ $message }}</span> @enderror
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">
          <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
          {{ isset($city) ? 'Saqlash' : "Qo'shish" }}
        </button>
        <a href="{{ route('admin.cities.index') }}" class="btn btn-ghost">Bekor qilish</a>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
function filterRegions(countryId) {
    document.querySelectorAll('#region_select option').forEach(opt => {
        if (!opt.value) return;
        opt.hidden = opt.dataset.country !== countryId;
    });
    const sel = document.getElementById('region_select');
    if (sel.value && document.querySelector(`#region_select option[value="${sel.value}"]`)?.hidden) {
        sel.value = '';
    }
}
const cs = document.getElementById('country_select');
if (cs?.value) filterRegions(cs.value);
</script>
@endpush

@endsection
