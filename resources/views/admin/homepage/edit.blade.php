@extends('layouts.admin')
@section('title', 'Bosh sahifa')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Bosh sahifa</div>
    <div class="page-subtitle">Sayt bosh sahifasidagi matnlar (hero, "Biz haqimizda" va statistika)</div>
  </div>
  <a href="{{ route('home') }}" target="_blank" class="btn btn-ghost">
    <svg viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
    Saytni ko'rish
  </a>
</div>

@if(session('success'))
<div class="alert alert-success" style="max-width:680px;margin-bottom:16px">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.homepage.update') }}" style="max-width:680px;display:flex;flex-direction:column;gap:20px">
  @csrf
  @method('PUT')

  {{-- Hero --}}
  <div class="card">
    <div class="card-header">
      <div class="card-title">Hero blok</div>
    </div>
    <div style="padding:24px">

      <div class="form-group" style="margin-bottom:16px">
        <label class="form-label">Yorliq (tag)</label>
        <input type="text" name="hero_tag" class="form-input"
               value="{{ old('hero_tag', $content->hero_tag) }}" placeholder="Rasmiy distribyutor · O'zbekiston">
      </div>

      <div class="form-grid" style="grid-template-columns:1fr 1fr 1fr;margin-bottom:16px">
        <div class="form-group">
          <label class="form-label">Sarlavha 1-qator</label>
          <input type="text" name="hero_line1" class="form-input"
                 value="{{ old('hero_line1', $content->hero_line1) }}" placeholder="Xprinter">
        </div>
        <div class="form-group">
          <label class="form-label">2-qator (ajratilgan)</label>
          <input type="text" name="hero_line2" class="form-input"
                 value="{{ old('hero_line2', $content->hero_line2) }}" placeholder="termoprinterlar">
        </div>
        <div class="form-group">
          <label class="form-label">3-qator</label>
          <input type="text" name="hero_line3" class="form-input"
                 value="{{ old('hero_line3', $content->hero_line3) }}" placeholder="O'zbekistonda">
        </div>
      </div>

      <div class="form-group" style="margin-bottom:16px">
        <label class="form-label">Subtitle</label>
        <textarea name="hero_subtitle" rows="3" class="form-input">{{ old('hero_subtitle', $content->hero_subtitle) }}</textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Ishonch belgilari (3 ta)</label>
        <div style="display:flex;flex-direction:column;gap:8px;margin-top:4px">
          @php
            $badges = old('badges', $content->badges());
          @endphp
          @foreach($badges as $i => $badge)
          <input type="text" name="badges[]" class="form-input" value="{{ $badge }}"
                 placeholder="Masalan: 12 oy rasmiy kafolat">
          @endforeach
        </div>
      </div>

    </div>
  </div>

  {{-- About / Why us --}}
  <div class="card">
    <div class="card-header">
      <div class="card-title">"Biz haqimizda" bo'limi</div>
      <div style="font-size:12px;color:var(--muted)">Bosh sahifada va /about sahifasida ishlatiladi</div>
    </div>
    <div style="padding:24px">

      <div class="form-group" style="margin-bottom:16px">
        <label class="form-label">Yorliq (tag)</label>
        <input type="text" name="about_tag" class="form-input"
               value="{{ old('about_tag', $content->about_tag) }}" placeholder="// Nega biz">
      </div>

      <div class="form-grid" style="margin-bottom:16px">
        <div class="form-group">
          <label class="form-label">Sarlavha</label>
          <input type="text" name="about_title" class="form-input"
                 value="{{ old('about_title', $content->about_title) }}" placeholder="Xprinter.uz — distribyutorlar tarmog'i">
        </div>
        <div class="form-group">
          <label class="form-label">Subtitle</label>
          <input type="text" name="about_subtitle" class="form-input"
                 value="{{ old('about_subtitle', $content->about_subtitle) }}" placeholder="Qisqacha izoh">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Kartalar (3 ta)</label>
        <div style="display:flex;flex-direction:column;gap:10px;margin-top:4px">
          @php
            $aboutCards = old('card_titles')
                ? collect(old('card_titles'))->map(fn($t, $i) => ['title' => $t, 'text' => old('card_texts')[$i] ?? ''])->all()
                : $content->aboutCardList();
          @endphp
          @foreach($aboutCards as $card)
          <div style="display:flex;gap:8px">
            <input type="text" name="card_titles[]" class="form-input" style="flex:1" value="{{ $card['title'] }}" placeholder="Karta sarlavhasi">
            <input type="text" name="card_texts[]" class="form-input" style="flex:2" value="{{ $card['text'] }}" placeholder="Karta matni">
          </div>
          @endforeach
        </div>
      </div>

    </div>
  </div>

  {{-- Stats --}}
  <div class="card">
    <div class="card-header">
      <div class="card-title">Statistika paneli</div>
      <div style="font-size:12px;color:var(--muted)">4 ta ko'rsatkich</div>
    </div>
    <div style="padding:24px;display:flex;flex-direction:column;gap:16px">
      @php
        $stats = old('stat_values')
            ? collect(old('stat_values'))->map(fn($v, $i) => [
                'value'  => $v,
                'suffix' => old('stat_suffixes')[$i] ?? '',
                'label'  => old('stat_labels')[$i] ?? '',
            ])->all()
            : $content->statList();
      @endphp

      @foreach($stats as $i => $stat)
      <div class="form-grid" style="grid-template-columns:80px 80px 1fr">
        <div class="form-group" style="margin:0">
          <label class="form-label">Son</label>
          <input type="text" name="stat_values[]" class="form-input" value="{{ $stat['value'] }}" placeholder="5">
        </div>
        <div class="form-group" style="margin:0">
          <label class="form-label">Belgi</label>
          <input type="text" name="stat_suffixes[]" class="form-input" value="{{ $stat['suffix'] }}" placeholder="+">
        </div>
        <div class="form-group" style="margin:0">
          <label class="form-label">Izoh</label>
          <input type="text" name="stat_labels[]" class="form-input" value="{{ $stat['label'] }}" placeholder="Yil O'zbekiston bozorida">
        </div>
      </div>
      @endforeach
    </div>
  </div>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary">
      <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
      Saqlash
    </button>
  </div>

</form>

@endsection
