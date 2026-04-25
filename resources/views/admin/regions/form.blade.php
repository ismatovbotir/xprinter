@extends('layouts.admin')
@section('title', isset($region) ? 'Viloyatni tahrirlash' : "Viloyat qo'shish")

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">{{ isset($region) ? 'Viloyatni tahrirlash' : "Viloyat qo'shish" }}</div>
    <div class="page-subtitle">
      <a href="{{ route('admin.regions.index') }}" style="color:var(--blue);text-decoration:none">
        ← Viloyatlar ro'yxatiga qaytish
      </a>
    </div>
  </div>
</div>

<div class="card" style="max-width:680px">
  <div class="card-header">
    <div class="card-title">Asosiy ma'lumotlar</div>
  </div>
  <div style="padding:28px">
    <form method="POST" action="{{ isset($region) ? route('admin.regions.update', $region) : route('admin.regions.store') }}">
      @csrf
      @isset($region) @method('PUT') @endisset

      <div class="form-group" style="margin-bottom:24px">
        <label class="form-label">Davlat <span style="color:#D32F2F">*</span></label>
        <select name="country_id" class="form-input @error('country_id') error @enderror">
          <option value="">— Davlatni tanlang —</option>
          @foreach($countries as $country)
            <option value="{{ $country->id }}"
              {{ old('country_id', $region->country_id ?? '') == $country->id ? 'selected' : '' }}>
              {{ $country->translations->firstWhere('lang','uz')?->name ?? $country->code }}
            </option>
          @endforeach
        </select>
        @error('country_id') <span class="form-error">{{ $message }}</span> @enderror
      </div>

      <div class="form-grid" style="margin-bottom:28px">
        <div class="form-group">
          <label class="form-label">Nomi — O'zbekcha <span style="color:#D32F2F">*</span></label>
          <input type="text" name="name_uz"
                 class="form-input @error('name_uz') error @enderror"
                 value="{{ old('name_uz', isset($region) ? ($region->translations->firstWhere('lang','uz')?->name ?? '') : '') }}"
                 placeholder="Toshkent viloyati">
          @error('name_uz') <span class="form-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Nomi — Ruscha <span style="color:#D32F2F">*</span></label>
          <input type="text" name="name_ru"
                 class="form-input @error('name_ru') error @enderror"
                 value="{{ old('name_ru', isset($region) ? ($region->translations->firstWhere('lang','ru')?->name ?? '') : '') }}"
                 placeholder="Ташкентская область">
          @error('name_ru') <span class="form-error">{{ $message }}</span> @enderror
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">
          <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
          {{ isset($region) ? 'Saqlash' : "Qo'shish" }}
        </button>
        <a href="{{ route('admin.regions.index') }}" class="btn btn-ghost">Bekor qilish</a>
      </div>
    </form>
  </div>
</div>

@endsection
