@extends('layouts.admin')
@section('title', isset($category) ? 'Kategoriyani tahrirlash' : 'Yangi kategoriya')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">{{ isset($category) ? 'Kategoriyani tahrirlash' : 'Yangi kategoriya' }}</div>
    <div class="page-subtitle">{{ isset($category) ? $category->translations->firstWhere('lang','uz')?->name : "Yangi kategoriya qo'shish" }}</div>
  </div>
  <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost">
    <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
    Orqaga
  </a>
</div>

@if(session('success'))
<div class="alert alert-success" style="max-width:680px;margin-bottom:16px">{{ session('success') }}</div>
@endif

{{-- Main info --}}
<div class="card" style="max-width:680px;margin-bottom:20px">
  <div style="padding:24px">
    <form method="POST" action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
      @csrf
      @if(isset($category)) @method('PUT') @endif

      <div class="form-group">
        <label class="form-label">Slug <span style="color:var(--red)">*</span></label>
        <input type="text" name="slug" class="form-input {{ $errors->has('slug') ? 'is-invalid' : '' }}"
               value="{{ old('slug', $category->slug ?? '') }}"
               placeholder="receipt-printers"
               pattern="[a-z0-9-]+"
               title="Faqat kichik harflar, raqamlar va defis">
        <div class="form-hint">Faqat kichik lotin harflari, raqamlar va defis. Masalan: <code>receipt-printers</code></div>
        @error('slug') <div class="invalid-feedback" style="display:block">{{ $message }}</div> @enderror
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-top:16px">
        <div class="form-group">
          <label class="form-label">O'zbekcha <span style="color:var(--red)">*</span></label>
          <input type="text" name="name_uz" class="form-input {{ $errors->has('name_uz') ? 'is-invalid' : '' }}"
                 value="{{ old('name_uz', $category?->translations->firstWhere('lang','uz')?->name) }}"
                 placeholder="Chek printerlari">
          @error('name_uz') <div class="invalid-feedback" style="display:block">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Ruscha <span style="color:var(--red)">*</span></label>
          <input type="text" name="name_ru" class="form-input {{ $errors->has('name_ru') ? 'is-invalid' : '' }}"
                 value="{{ old('name_ru', $category?->translations->firstWhere('lang','ru')?->name) }}"
                 placeholder="Принтеры чеков">
          @error('name_ru') <div class="invalid-feedback" style="display:block">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">English <span style="color:var(--red)">*</span></label>
          <input type="text" name="name_en" class="form-input {{ $errors->has('name_en') ? 'is-invalid' : '' }}"
                 value="{{ old('name_en', $category?->translations->firstWhere('lang','en')?->name) }}"
                 placeholder="Receipt printers">
          @error('name_en') <div class="invalid-feedback" style="display:block">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="form-actions" style="margin-top:24px">
        <button type="submit" class="btn btn-primary">
          <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
          {{ isset($category) ? 'Saqlash' : "Qo'shish" }}
        </button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost">Bekor qilish</a>
      </div>
    </form>
  </div>
</div>

{{-- Parameters table — only on edit --}}
@isset($category)
<div style="max-width:680px">
  <div class="card-header" style="margin-bottom:12px">
    <div class="card-title">Parametrlar</div>
    <div style="font-size:12px;color:var(--muted)">Bu kategoriyaga biriktirilgan parametrlar</div>
  </div>

  {{-- Attached parameters --}}
  @if($category->parameters->isEmpty())
  <div class="card" style="padding:32px;text-align:center;color:var(--muted);margin-bottom:16px">
    Hech qanday parametr biriktirilmagan
  </div>
  @else
  <div class="card" style="margin-bottom:16px">
    <table>
      <thead>
        <tr>
          <th>Parametr</th>
          <th style="width:120px;text-align:center">Tartib</th>
          <th style="width:80px"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($category->parameters as $param)
        <tr>
          <td>
            <div style="font-weight:600;font-size:13px">
              {{ $param->translations->firstWhere('lang','uz')?->name ?? '—' }}
            </div>
            <div style="font-size:11px;color:var(--muted)">
              ru: {{ $param->translations->firstWhere('lang','ru')?->name ?? '—' }}
              &nbsp;·&nbsp;
              en: {{ $param->translations->firstWhere('lang','en')?->name ?? '—' }}
            </div>
          </td>
          <td style="text-align:center">
            <span style="font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--muted)">
              {{ $param->pivot->sort_order }}
            </span>
          </td>
          <td style="text-align:right">
            <form method="POST"
                  action="{{ route('admin.categories.parameters.detach', [$category, $param]) }}"
                  onsubmit="return confirm('Parametrni olib tashlaysizmi?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-ghost"
                      style="padding:4px 10px;font-size:12px;color:var(--red);border-color:var(--red)">
                Olib tashlash
              </button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif

  {{-- Attach new parameter --}}
  @php
    $attachedIds = $category->parameters->pluck('id');
    $available   = $allParameters->whereNotIn('id', $attachedIds);
  @endphp

  @if($available->isNotEmpty())
  <div class="card" style="padding:20px">
    <div style="font-size:12px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:var(--muted);margin-bottom:12px">
      Parametr biriktirish
    </div>
    <form method="POST" action="{{ route('admin.categories.parameters.attach', $category) }}"
          style="display:flex;gap:12px;align-items:flex-end">
      @csrf
      <div class="form-group" style="flex:1;margin:0">
        <label class="form-label">Parametr</label>
        <select name="parameter_id" class="form-input" required>
          <option value="">— Tanlang —</option>
          @foreach($available as $param)
          <option value="{{ $param->id }}">
            {{ $param->translations->firstWhere('lang','uz')?->name ?? "Parameter #{$param->id}" }}
          </option>
          @endforeach
        </select>
      </div>
      <div class="form-group" style="width:100px;margin:0">
        <label class="form-label">Tartib</label>
        <input type="number" name="sort_order" class="form-input" value="{{ $category->parameters->count() }}" min="0">
      </div>
      <button type="submit" class="btn btn-primary" style="margin-bottom:0;flex-shrink:0">
        <svg viewBox="0 0 24 24" style="width:16px;height:16px"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Biriktirish
      </button>
    </form>
  </div>
  @endif
</div>
@endisset

@endsection
