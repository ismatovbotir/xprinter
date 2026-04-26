@extends('layouts.admin')
@section('title', isset($product) ? 'Mahsulotni tahrirlash' : 'Yangi mahsulot')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">{{ isset($product) ? 'Mahsulotni tahrirlash' : 'Yangi mahsulot' }}</div>
    <div class="page-subtitle">{{ isset($product) ? $product->model_number : 'Yangi mahsulot qo\'shish' }}</div>
  </div>
  <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">
    <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
    Orqaga
  </a>
</div>

<form method="POST" action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}"
      style="max-width:760px">
  @csrf
  @if(isset($product)) @method('PUT') @endif

  {{-- Main info --}}
  <div class="card" style="margin-bottom:16px">
    <div class="card-header">
      <div class="card-title">Asosiy ma'lumotlar</div>
    </div>
    <div style="padding:20px">
      <div class="form-grid" style="grid-template-columns:1fr 1fr;margin-bottom:16px">
        <div class="form-group">
          <label class="form-label">Model raqami <span style="color:#D32F2F">*</span></label>
          <input type="text" name="model_number" class="form-input {{ $errors->has('model_number') ? 'is-error' : '' }}"
                 value="{{ old('model_number', $product->model_number ?? '') }}"
                 placeholder="XP-Q890K"
                 style="font-family:'JetBrains Mono',monospace;font-weight:600">
          @error('model_number') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Kategoriya <span style="color:#D32F2F">*</span></label>
          <select name="category_id" class="form-input {{ $errors->has('category_id') ? 'is-error' : '' }}">
            <option value="">— Tanlang —</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
              {{ $cat->translations->firstWhere('lang','uz')?->name ?? $cat->slug }}
            </option>
            @endforeach
          </select>
          @error('category_id') <div class="form-error">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Slug <span style="color:#D32F2F">*</span></label>
        <input type="text" name="slug" id="slug_field" class="form-input {{ $errors->has('slug') ? 'is-error' : '' }}"
               value="{{ old('slug', $product->slug ?? '') }}"
               placeholder="xp-q890k"
               pattern="[a-z0-9-]+"
               title="Faqat kichik harflar, raqamlar va defis">
        <div class="form-hint">URL uchun. Masalan: <code>xp-q890k</code>. Avtomatik hosil bo'ladi.</div>
        @error('slug') <div class="form-error">{{ $message }}</div> @enderror
      </div>
    </div>
  </div>

  {{-- Translations --}}
  <div class="card" style="margin-bottom:16px">
    <div class="card-header">
      <div class="card-title">Tarjimalar</div>
    </div>
    <div style="padding:20px">
      <div class="form-grid" style="grid-template-columns:1fr 1fr 1fr;margin-bottom:16px">
        <div class="form-group">
          <label class="form-label">O'zbekcha <span style="color:#D32F2F">*</span></label>
          <input type="text" name="name_uz" class="form-input {{ $errors->has('name_uz') ? 'is-error' : '' }}"
                 value="{{ old('name_uz', $product?->translations?->firstWhere('lang','uz')?->name) }}"
                 placeholder="Chek printeri XP-Q890K">
          @error('name_uz') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Ruscha <span style="color:#D32F2F">*</span></label>
          <input type="text" name="name_ru" class="form-input {{ $errors->has('name_ru') ? 'is-error' : '' }}"
                 value="{{ old('name_ru', $product?->translations?->firstWhere('lang','ru')?->name) }}"
                 placeholder="Принтер чеков XP-Q890K">
          @error('name_ru') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">English <span style="color:#D32F2F">*</span></label>
          <input type="text" name="name_en" class="form-input {{ $errors->has('name_en') ? 'is-error' : '' }}"
                 value="{{ old('name_en', $product?->translations?->firstWhere('lang','en')?->name) }}"
                 placeholder="Receipt printer XP-Q890K">
          @error('name_en') <div class="form-error">{{ $message }}</div> @enderror
        </div>
      </div>
      <div class="form-grid" style="grid-template-columns:1fr 1fr 1fr">
        <div class="form-group">
          <label class="form-label">Tavsif (O'zbekcha)</label>
          <textarea name="description_uz" class="form-input" rows="4"
                    placeholder="Mahsulot tavsifi...">{{ old('description_uz', $product?->translations->firstWhere('lang','uz')?->description) }}</textarea>
          @error('description_uz') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Tavsif (Ruscha)</label>
          <textarea name="description_ru" class="form-input" rows="4"
                    placeholder="Описание товара...">{{ old('description_ru', $product?->translations->firstWhere('lang','ru')?->description) }}</textarea>
          @error('description_ru') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Description (English)</label>
          <textarea name="description_en" class="form-input" rows="4"
                    placeholder="Product description...">{{ old('description_en', $product?->translations->firstWhere('lang','en')?->description) }}</textarea>
          @error('description_en') <div class="form-error">{{ $message }}</div> @enderror
        </div>
      </div>
    </div>
  </div>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary">
      <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
      {{ isset($product) ? 'Saqlash' : 'Qo\'shish' }}
    </button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">Bekor qilish</a>
  </div>
</form>

<script>
@unless(isset($product))
document.querySelector('[name=model_number]').addEventListener('input', function () {
    const slug = this.value.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
    document.getElementById('slug_field').value = slug;
});
@endunless
</script>

@endsection
