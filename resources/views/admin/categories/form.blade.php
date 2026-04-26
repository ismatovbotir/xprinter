@extends('layouts.admin')
@section('title', isset($category) ? 'Kategoriyani tahrirlash' : 'Yangi kategoriya')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">{{ isset($category) ? 'Kategoriyani tahrirlash' : 'Yangi kategoriya' }}</div>
    <div class="page-subtitle">{{ isset($category) ? $category->translations->firstWhere('lang','uz')?->name : 'Yangi kategoriya qo\'shish' }}</div>
  </div>
  <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost">
    <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
    Orqaga
  </a>
</div>

<div class="card" style="max-width:680px">
  <div style="padding:24px">
    <form method="POST" action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
      @csrf
      @if(isset($category)) @method('PUT') @endif

      <div class="form-group">
        <label class="form-label">Slug <span style="color:#D32F2F">*</span></label>
        <input type="text" name="slug" class="form-input {{ $errors->has('slug') ? 'is-error' : '' }}"
               value="{{ old('slug', $category->slug ?? '') }}"
               placeholder="receipt-printers"
               pattern="[a-z0-9-]+"
               title="Faqat kichik harflar, raqamlar va defis">
        <div class="form-hint">Faqat kichik lotin harflari, raqamlar va defis. Masalan: <code>receipt-printers</code></div>
        @error('slug') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-grid" style="grid-template-columns:1fr 1fr 1fr;margin-top:16px">
        <div class="form-group">
          <label class="form-label">O'zbekcha <span style="color:#D32F2F">*</span></label>
          <input type="text" name="name_uz" class="form-input {{ $errors->has('name_uz') ? 'is-error' : '' }}"
                 value="{{ old('name_uz', $category?->translations->firstWhere('lang','uz')?->name) }}"
                 placeholder="Chek printerlari">
          @error('name_uz') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Ruscha <span style="color:#D32F2F">*</span></label>
          <input type="text" name="name_ru" class="form-input {{ $errors->has('name_ru') ? 'is-error' : '' }}"
                 value="{{ old('name_ru', $category?->translations->firstWhere('lang','ru')?->name) }}"
                 placeholder="Принтеры чеков">
          @error('name_ru') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">English <span style="color:#D32F2F">*</span></label>
          <input type="text" name="name_en" class="form-input {{ $errors->has('name_en') ? 'is-error' : '' }}"
                 value="{{ old('name_en', $category?->translations->firstWhere('lang','en')?->name) }}"
                 placeholder="Receipt printers">
          @error('name_en') <div class="form-error">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="form-actions" style="margin-top:24px">
        <button type="submit" class="btn btn-primary">
          <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
          {{ isset($category) ? 'Saqlash' : 'Qo\'shish' }}
        </button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost">Bekor qilish</a>
      </div>
    </form>
  </div>
</div>

@endsection
