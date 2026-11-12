@extends('layouts.admin')
@section('title', $entry->exists ? 'Bilimni tahrirlash' : "Bilim qo'shish")

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">{{ $entry->exists ? 'Bilimni tahrirlash' : "Bilim qo'shish" }}</div>
    <div class="page-subtitle">Bot bu javobni katalog ma'lumotlaridan ustun qo'yadi</div>
  </div>
  <a href="{{ route('admin.chatbot.index', ['tab' => 'knowledge']) }}" class="btn btn-ghost">← Ro'yxatga qaytish</a>
</div>

<form method="POST" style="max-width:800px"
      action="{{ $entry->exists ? route('admin.chatbot.knowledge.update', $entry) : route('admin.chatbot.knowledge.store') }}">
  @csrf
  @if($entry->exists) @method('PUT') @endif

  <div class="card" style="margin-bottom:20px">
    <div style="padding:20px;display:flex;flex-direction:column;gap:16px">
      <div class="form-group">
        <label class="form-label">Savol <span style="color:var(--red)">*</span></label>
        <textarea name="question" rows="2" required class="form-input @error('question') error @enderror"
                  placeholder="Masalan: Yetkazib berish qancha vaqt oladi? / Сколько длится доставка?">{{ old('question', $entry->question) }}</textarea>
        @error('question')<div class="form-error">{{ $message }}</div>@enderror
        <div style="font-size:11px;color:var(--muted);margin-top:4px">Savolni ikkala tilda yozsangiz, bot uni har ikki tilda topadi.</div>
      </div>

      <div class="form-group">
        <label class="form-label">Javob <span style="color:var(--red)">*</span></label>
        <textarea name="answer" rows="6" required class="form-input @error('answer') error @enderror">{{ old('answer', $entry->answer) }}</textarea>
        @error('answer')<div class="form-error">{{ $message }}</div>@enderror
        <div style="font-size:11px;color:var(--muted);margin-top:4px">Bot javobni mijoz tiliga moslab beradi.</div>
      </div>

      <div style="display:flex;gap:16px;align-items:flex-end">
        <div class="form-group" style="flex:1">
          <label class="form-label">Til</label>
          <select name="lang" class="form-input">
            <option value="" @selected(old('lang', $entry->lang) === null)>Har ikkisi (uz/ru)</option>
            <option value="uz" @selected(old('lang', $entry->lang) === 'uz')>Faqat o'zbek</option>
            <option value="ru" @selected(old('lang', $entry->lang) === 'ru')>Faqat rus</option>
          </select>
        </div>
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;user-select:none;padding-bottom:10px">
          <input type="hidden" name="is_active" value="0">
          <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $entry->is_active))>
          <span>Faol</span>
        </label>
      </div>
    </div>
  </div>

  <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;height:46px;font-size:14px">
    {{ $entry->exists ? 'Saqlash' : "Qo'shish" }}
  </button>
</form>

@endsection
