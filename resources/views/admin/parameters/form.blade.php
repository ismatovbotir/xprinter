@extends('layouts.admin')
@section('title', isset($parameter) ? 'Parametrni tahrirlash' : 'Yangi parametr')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">{{ isset($parameter) ? 'Parametrni tahrirlash' : 'Yangi parametr' }}</div>
    <div class="page-subtitle">{{ isset($parameter) ? $parameter->translations->firstWhere('lang','uz')?->name : 'Yangi parametr qo\'shish' }}</div>
  </div>
  <a href="{{ route('admin.parameters.index') }}" class="btn btn-ghost">
    <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
    Orqaga
  </a>
</div>

<div class="card" style="max-width:680px">
  <div style="padding:24px">
    <form method="POST" action="{{ isset($parameter) ? route('admin.parameters.update', $parameter) : route('admin.parameters.store') }}">
      @csrf
      @if(isset($parameter)) @method('PUT') @endif

      <div class="form-grid" style="grid-template-columns:1fr 1fr 1fr">
        <div class="form-group">
          <label class="form-label">O'zbekcha <span style="color:#D32F2F">*</span></label>
          <input type="text" name="name_uz" class="form-input {{ $errors->has('name_uz') ? 'is-error' : '' }}"
                 value="{{ old('name_uz', $parameter?->translations->firstWhere('lang','uz')?->name) }}"
                 placeholder="Bosib chiqarish tezligi">
          @error('name_uz') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Ruscha <span style="color:#D32F2F">*</span></label>
          <input type="text" name="name_ru" class="form-input {{ $errors->has('name_ru') ? 'is-error' : '' }}"
                 value="{{ old('name_ru', $parameter?->translations->firstWhere('lang','ru')?->name) }}"
                 placeholder="Скорость печати">
          @error('name_ru') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">English <span style="color:#D32F2F">*</span></label>
          <input type="text" name="name_en" class="form-input {{ $errors->has('name_en') ? 'is-error' : '' }}"
                 value="{{ old('name_en', $parameter?->translations->firstWhere('lang','en')?->name) }}"
                 placeholder="Print speed">
          @error('name_en') <div class="form-error">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="form-group" style="margin-top:20px">
        <label class="form-label">Kategoriyalar</label>
        <div class="form-hint" style="margin-bottom:8px">Bu parametr qaysi kategoriyalarga tegishli</div>
        <div style="display:flex;flex-direction:column;gap:8px">
          @foreach($categories as $cat)
          @php $checked = isset($parameter) && $parameter->categories->contains($cat->id) @endphp
          <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:8px 12px;border:1px solid var(--line);border-radius:8px;transition:border-color .15s"
                 onmouseover="this.style.borderColor='var(--blue)'" onmouseout="this.style.borderColor='var(--line)'">
            <input type="checkbox" name="category_ids[]" value="{{ $cat->id }}"
                   {{ $checked || in_array($cat->id, old('category_ids', [])) ? 'checked' : '' }}
                   style="width:16px;height:16px;accent-color:var(--blue);cursor:pointer">
            <span style="font-weight:600;color:var(--ink)">
              {{ $cat->translations->firstWhere('lang','uz')?->name ?? $cat->slug }}
            </span>
            <span style="color:var(--muted);font-size:13px">
              / {{ $cat->translations->firstWhere('lang','ru')?->name ?? '' }}
            </span>
          </label>
          @endforeach
        </div>
        @error('category_ids') <div class="form-error" style="margin-top:6px">{{ $message }}</div> @enderror
      </div>

      <div class="form-actions" style="margin-top:24px">
        <button type="submit" class="btn btn-primary">
          <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
          {{ isset($parameter) ? 'Saqlash' : 'Qo\'shish' }}
        </button>
        <a href="{{ route('admin.parameters.index') }}" class="btn btn-ghost">Bekor qilish</a>
      </div>
    </form>
  </div>
</div>

@endsection
