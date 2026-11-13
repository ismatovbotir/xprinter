@extends('layouts.admin')
@section('title', isset($article) ? 'Maqolani tahrirlash' : 'Yangi maqola')
@section('breadcrumb', 'Bilimlar bazasi')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">{{ isset($article) ? 'Maqolani tahrirlash' : 'Yangi maqola' }}</div>
    <div class="page-subtitle">Bilimlar bazasi — /support sahifasida va chatbot javoblarida ishlatiladi</div>
  </div>
  <a href="{{ route('admin.kb.index') }}" class="btn btn-ghost">← Ro'yxatga qaytish</a>
</div>

@php
  $old = fn ($key, $default = null) => old($key, $default);
  $tr  = fn ($lang, $field) => isset($article) ? ($article->translations->firstWhere('lang', $lang)?->{$field}) : null;
  $selectedProducts = collect(old('products', isset($article) ? $article->products->pluck('id')->all() : []))->map(fn ($v) => (int) $v)->all();
  $selectedFiles    = collect(old('files',    isset($article) ? $article->files->pluck('id')->all()    : []))->map(fn ($v) => (int) $v)->all();
@endphp

<form method="POST"
      action="{{ isset($article) ? route('admin.kb.update', $article) : route('admin.kb.store') }}"
      style="max-width:960px">
  @csrf
  @if(isset($article)) @method('PUT') @endif

  <div class="card" style="margin-bottom:16px">
    <div class="card-header"><div class="card-title">Asosiy</div></div>
    <div style="padding:20px;display:flex;flex-direction:column;gap:16px">

      @isset($article)
        <div class="form-group">
          <label class="form-label">Manzil</label>
          <input type="text" class="form-input" value="{{ route('support.show', $article->slug) }}" disabled
                 style="background:var(--bg-soft);color:var(--muted);cursor:not-allowed">
        </div>
      @else
        <div style="font-size:12px;color:var(--muted)">Slug English sarlavhadan avtomatik yaratiladi.</div>
      @endisset

      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px">
        <div class="form-group">
          <label class="form-label">Turi <span style="color:var(--red)">*</span></label>
          <select name="type" class="form-input" required>
            @foreach($types as $key => $label)
              <option value="{{ $key }}" @selected($old('type', $article->type ?? 'troubleshooting') === $key)>{{ $label }}</option>
            @endforeach
          </select>
          @error('type')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
          <label class="form-label">Qiyinlik</label>
          <select name="difficulty" class="form-input">
            @foreach($difficulties as $d)
              <option value="{{ $d }}" @selected($old('difficulty', $article->difficulty ?? 'easy') === $d)>{{ ['easy' => 'Oson (har kim)', 'medium' => "O'rta (operator)", 'pro' => 'Murakkab (texnik)'][$d] }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Butun kategoriyaga tegishli</label>
          <select name="category_id" class="form-input">
            <option value="">— Yo'q —</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" @selected((int) $old('category_id', $article->category_id ?? 0) === $cat->id)>
                {{ $cat->translations->firstWhere('lang', 'uz')?->name ?? $cat->slug }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Kalit so'zlar / simptomlar</label>
        <input type="text" name="keywords" class="form-input" value="{{ $old('keywords', $article->keywords ?? '') }}"
               placeholder="bo'sh chek, пустой чек, blank print, qog'oz teskari, light print...">
        <span class="form-hint">Har qanday tilda, vergul bilan. Qidiruv va chatbot shu so'zlar bo'yicha topadi.</span>
        @error('keywords')<span class="form-error">{{ $message }}</span>@enderror
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label class="form-label">Saralash tartibi</label>
          <input type="number" name="sort_order" class="form-input" value="{{ $old('sort_order', $article->sort_order ?? 0) }}" min="0">
        </div>
        <div class="form-group" style="display:flex;align-items:flex-end">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;user-select:none">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" value="1" @checked($old('is_published', $article->is_published ?? false))>
            <span style="font-size:13.5px;color:var(--ink)">Nashr qilingan (saytda ko'rinadi)</span>
          </label>
        </div>
      </div>
    </div>
  </div>

  {{-- Translations --}}
  @foreach(['uz' => "O'zbekcha", 'ru' => 'Русский', 'en' => 'English'] as $lang => $langName)
  <div class="card" style="margin-bottom:16px">
    <div class="card-header">
      <div class="card-title">{{ $langName }} <span style="font-family:'JetBrains Mono',monospace;font-size:10px;color:var(--muted);margin-left:6px">{{ strtoupper($lang) }}</span></div>
    </div>
    <div style="padding:20px;display:flex;flex-direction:column;gap:14px">
      <div class="form-group">
        <label class="form-label">Sarlavha <span style="color:var(--red)">*</span></label>
        <input type="text" name="title_{{ $lang }}" class="form-input @error("title_$lang") error @enderror"
               value="{{ $old("title_$lang", $tr($lang, 'title')) }}" required>
        @error("title_$lang")<span class="form-error">{{ $message }}</span>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">Qisqa javob (excerpt)</label>
        <textarea name="excerpt_{{ $lang }}" class="form-input" rows="2"
                  placeholder="1–2 gap: chatbot shu matnni javob sifatida yuboradi">{{ $old("excerpt_$lang", $tr($lang, 'excerpt')) }}</textarea>
        @error("excerpt_$lang")<span class="form-error">{{ $message }}</span>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">Matn (HTML: p, ol, ul, li, strong, code, a, h3) <span style="color:var(--red)">*</span></label>
        <textarea name="body_{{ $lang }}" class="form-input @error("body_$lang") error @enderror" rows="12"
                  style="font-family:'JetBrains Mono',monospace;font-size:12.5px" required>{{ $old("body_$lang", $tr($lang, 'body')) }}</textarea>
        @error("body_$lang")<span class="form-error">{{ $message }}</span>@enderror
      </div>
    </div>
  </div>
  @endforeach

  {{-- Products --}}
  <div class="card" style="margin-bottom:16px">
    <div class="card-header"><div class="card-title">Qaysi modellarga tegishli</div></div>
    <div style="padding:20px;display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:18px">
      @foreach($categories as $cat)
        @if($cat->products->isNotEmpty())
        <div>
          <div style="font-family:'JetBrains Mono',monospace;font-size:10px;letter-spacing:0.1em;text-transform:uppercase;color:var(--muted);margin-bottom:8px">
            {{ $cat->translations->firstWhere('lang', 'uz')?->name ?? $cat->slug }}
          </div>
          @foreach($cat->products as $p)
            <label style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--ink-soft);padding:3px 0;cursor:pointer">
              <input type="checkbox" name="products[]" value="{{ $p->id }}" @checked(in_array($p->id, $selectedProducts, true))>
              {{ $p->model_number }}
            </label>
          @endforeach
        </div>
        @endif
      @endforeach
    </div>
  </div>

  {{-- Files --}}
  @if($fileOptions->isNotEmpty())
  <div class="card" style="margin-bottom:16px">
    <div class="card-header"><div class="card-title">Yuklab olish uchun fayllar (drayver, utilita, qo'llanma)</div></div>
    <div style="padding:20px;display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:6px">
      @foreach($fileOptions as $f)
        <label style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--ink-soft);padding:3px 0;cursor:pointer">
          <input type="checkbox" name="files[]" value="{{ $f->id }}" @checked(in_array($f->id, $selectedFiles, true))>
          {{ $f->name }}@if($f->version) <span style="color:var(--muted)">v{{ $f->version }}</span>@endif
        </label>
      @endforeach
    </div>
  </div>
  @endif

  <div style="display:flex;gap:10px">
    <button type="submit" class="btn btn-primary">
      <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
      Saqlash
    </button>
    <a href="{{ route('admin.kb.index') }}" class="btn btn-ghost">Bekor qilish</a>
  </div>
</form>

@endsection
