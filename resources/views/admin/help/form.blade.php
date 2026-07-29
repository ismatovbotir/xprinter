@extends('layouts.admin')
@section('title', isset($article) ? 'Maqolani tahrirlash' : 'Yangi maqola')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">{{ isset($article) ? 'Maqolani tahrirlash' : 'Yangi maqola' }}</div>
    <div class="page-subtitle">Yordam va qo'llanmalar</div>
  </div>
  <a href="{{ route('admin.help.index') }}" class="btn btn-ghost">← Ro'yxatga qaytish</a>
</div>

<form method="POST"
      action="{{ isset($article) ? route('admin.help.update', $article) : route('admin.help.store') }}"
      style="max-width:800px">
    @csrf
    @if(isset($article)) @method('PUT') @endif

    <div class="card" style="margin-bottom:16px">
        <div class="card-header"><div class="card-title">Asosiy ma'lumotlar</div></div>
        <div style="padding:20px;display:flex;flex-direction:column;gap:16px">

            @isset($article)
            <div class="form-group">
                <label class="form-label">Slug</label>
                <input type="text" class="form-input" value="{{ $article->slug }}" disabled
                       style="background:var(--bg-soft);color:var(--muted);cursor:not-allowed">
                <div style="font-size:11px;color:var(--muted);margin-top:4px">Avtomatik yaratilgan, o'zgartirib bo'lmaydi</div>
            </div>
            @else
            <div class="form-group">
                <label class="form-label">Slug</label>
                <div style="font-size:11px;color:var(--muted)">English sarlavhadan avtomatik yaratiladi</div>
            </div>
            @endisset

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="form-group">
                    <label class="form-label">Bo'lim <span style="color:var(--red)">*</span></label>
                    <select name="section" class="form-input {{ $errors->has('section') ? 'is-invalid' : '' }}" required>
                        <option value="">— Tanlang —</option>
                        <optgroup label="Marketplace">
                            <option value="marketplace.dashboard" @selected(old('section', $article->section ?? '') === 'marketplace.dashboard')>Bosh sahifa (Dashboard)</option>
                            <option value="marketplace.assortiment" @selected(old('section', $article->section ?? '') === 'marketplace.assortiment')>Assortiment</option>
                            <option value="marketplace.team" @selected(old('section', $article->section ?? '') === 'marketplace.team')>Jamoa</option>
                            <option value="marketplace.company" @selected(old('section', $article->section ?? '') === 'marketplace.company')>Kompaniya</option>
                        </optgroup>
                        <optgroup label="Admin">
                            <option value="admin.products" @selected(old('section', $article->section ?? '') === 'admin.products')>Mahsulotlar</option>
                            <option value="admin.companies" @selected(old('section', $article->section ?? '') === 'admin.companies')>Kompaniyalar</option>
                            <option value="admin.files" @selected(old('section', $article->section ?? '') === 'admin.files')>Fayllar</option>
                            <option value="admin.banners" @selected(old('section', $article->section ?? '') === 'admin.banners')>Bannerlar</option>
                        </optgroup>
                    </select>
                    @error('section')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Joylashish <span style="color:var(--red)">*</span></label>
                    <select name="placement" class="form-input {{ $errors->has('placement') ? 'is-invalid' : '' }}" required>
                        <option value="icon" @selected(old('placement', $article->placement ?? 'icon') === 'icon')>Icon (?))</option>
                        <option value="tooltip" @selected(old('placement', $article->placement ?? 'icon') === 'tooltip')>Tooltip</option>
                        <option value="modal" @selected(old('placement', $article->placement ?? 'icon') === 'modal')>Modal</option>
                        <option value="sidebar" @selected(old('placement', $article->placement ?? 'icon') === 'sidebar')>Sidebar</option>
                    </select>
                    @error('placement')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="form-group">
                    <label class="form-label">Saralash tartibi</label>
                    <input type="number" name="sort_order" class="form-input" value="{{ old('sort_order', $article->sort_order ?? 0) }}" min="0">
                </div>

                <div class="form-group" style="display:flex;align-items:flex-end">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;user-select:none">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $article->is_active ?? false))>
                        <span>Faol</span>
                    </label>
                </div>
            </div>

        </div>
    </div>

    <div class="card" style="margin-bottom:20px">
        <div class="card-header"><div class="card-title">Mazmuni (3 tilda)</div></div>
        <div style="padding:20px;display:flex;flex-direction:column;gap:24px">

            @foreach(['uz' => "O'zbek", 'ru' => 'Ruscha', 'en' => 'Ingliz'] as $lang => $langName)
                <div style="padding-bottom:20px;border-bottom:1px solid var(--line)" @last('style=border-bottom:none;padding-bottom:0'))>
                    <div style="font-weight:600;color:var(--blue);margin-bottom:12px;font-size:12px;text-transform:uppercase">{{ $langName }}</div>

                    <div class="form-group" style="margin-bottom:12px">
                        <label class="form-label">Sarlavha <span style="color:var(--red)">*</span></label>
                        <input type="text" name="title_{{ $lang }}" class="form-input {{ $errors->has("title_$lang") ? 'is-invalid' : '' }}"
                               value="{{ old("title_$lang", ($article ?? null)?->translations->firstWhere('lang', $lang)?->title ?? '') }}"
                               placeholder="Yordam sarlavhasi" required>
                        @error("title_$lang")<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mazmun <span style="color:var(--red)">*</span></label>
                        <textarea name="content_{{ $lang }}" rows="5" class="form-input {{ $errors->has("content_$lang") ? 'is-invalid' : '' }}"
                                  placeholder="Batafsil yordam matni..."
                                  required>{{ old("content_$lang", ($article ?? null)?->translations->firstWhere('lang', $lang)?->content ?? '') }}</textarea>
                        @error("content_$lang")<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                        <div style="font-size:11px;color:var(--muted);margin-top:4px">HTML taglar qo'llashga ruxsat beriladi: &lt;b&gt;, &lt;i&gt;, &lt;br&gt;, &lt;a href&gt;</div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;height:46px;font-size:14px">
        {{ isset($article) ? 'Saqlash' : 'Qo\'shish' }}
    </button>
</form>

@endsection
