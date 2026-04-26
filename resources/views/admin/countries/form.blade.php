@extends('layouts.admin')
@section('title', isset($country) ? 'Davlatni tahrirlash' : "Davlat qo'shish")

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">{{ isset($country) ? 'Davlatni tahrirlash' : "Davlat qo'shish" }}</div>
    <div class="page-subtitle">
      <a href="{{ route('admin.countries.index') }}" style="color:var(--blue);text-decoration:none">
        ← Davlatlar ro'yxatiga qaytish
      </a>
    </div>
  </div>
</div>

<div class="card" style="max-width:780px">
  <div class="card-header">
    <div class="card-title">Asosiy ma'lumotlar</div>
  </div>
  <div style="padding:28px">
    <form method="POST" action="{{ isset($country) ? route('admin.countries.update', $country) : route('admin.countries.store') }}">
      @csrf
      @isset($country) @method('PUT') @endisset

      <div class="form-group" style="margin-bottom:24px">
        <label class="form-label">ISO kodi <span style="color:var(--muted);font-weight:400">(ixtiyoriy)</span></label>
        <input type="text" name="code"
               class="form-input @error('code') error @enderror"
               value="{{ old('code', $country->code ?? '') }}"
               placeholder="UZ" maxlength="2"
               style="text-transform:uppercase;width:100px">
        <span class="form-hint">ISO 3166-1 alpha-2 — 2 lotin harfi</span>
        @error('code') <span class="form-error">{{ $message }}</span> @enderror
      </div>

      <div class="form-grid" style="grid-template-columns:1fr 1fr 1fr;margin-bottom:28px">
        <div class="form-group">
          <label class="form-label">O'zbekcha <span style="color:#D32F2F">*</span></label>
          <input type="text" name="name_uz"
                 class="form-input @error('name_uz') error @enderror"
                 value="{{ old('name_uz', isset($country) ? ($country->translations->firstWhere('lang','uz')?->name ?? '') : '') }}"
                 placeholder="O'zbekiston">
          @error('name_uz') <span class="form-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Ruscha <span style="color:#D32F2F">*</span></label>
          <input type="text" name="name_ru"
                 class="form-input @error('name_ru') error @enderror"
                 value="{{ old('name_ru', isset($country) ? ($country->translations->firstWhere('lang','ru')?->name ?? '') : '') }}"
                 placeholder="Узбекистан">
          @error('name_ru') <span class="form-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">English <span style="color:#D32F2F">*</span></label>
          <input type="text" name="name_en"
                 class="form-input @error('name_en') error @enderror"
                 value="{{ old('name_en', isset($country) ? ($country->translations->firstWhere('lang','en')?->name ?? '') : '') }}"
                 placeholder="Uzbekistan">
          @error('name_en') <span class="form-error">{{ $message }}</span> @enderror
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">
          <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
          {{ isset($country) ? 'Saqlash' : "Qo'shish" }}
        </button>
        <a href="{{ route('admin.countries.index') }}" class="btn btn-ghost">Bekor qilish</a>
      </div>
    </form>
  </div>
</div>

@endsection
