@extends('layouts.admin')
@section('title', 'Savolga javob')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Savolga javob berish</div>
    <div class="page-subtitle">
      {{ $question->user ? trim($question->user->name . ' ' . $question->user->last_name) . ($question->user->phone ? ' · ' . $question->user->phone : '') : 'Mehmon (sayt)' }}
      · {{ strtoupper($question->channel) }} · {{ $question->created_at->format('d.m.Y H:i') }}
    </div>
  </div>
  <a href="{{ route('admin.chatbot.index') }}" class="btn btn-ghost">← Ro'yxatga qaytish</a>
</div>

<form method="POST" action="{{ route('admin.chatbot.questions.answer', $question) }}" style="max-width:800px">
  @csrf

  <div class="card" style="margin-bottom:16px">
    <div class="card-header"><div class="card-title">Mijoz savoli</div></div>
    <div style="padding:20px;white-space:pre-wrap;font-size:14px">{{ $question->question }}</div>
  </div>

  <div class="card" style="margin-bottom:20px">
    <div class="card-header"><div class="card-title">Javob</div></div>
    <div style="padding:20px;display:flex;flex-direction:column;gap:16px">
      <div class="form-group">
        <textarea name="answer" rows="5" required class="form-input @error('answer') error @enderror"
                  placeholder="Javobni {{ $question->lang === 'ru' ? 'rus' : "o'zbek" }} tilida yozing...">{{ old('answer', $question->answer) }}</textarea>
        @error('answer')<div class="form-error">{{ $message }}</div>@enderror
        @if($question->channel === 'telegram' && $question->user?->telegram_chat_id)
          <div style="font-size:12px;color:var(--muted);margin-top:6px">Javob mijozga Telegram orqali yuboriladi.</div>
        @else
          <div style="font-size:12px;color:var(--muted);margin-top:6px">Sayt mehmoni — javob mijozga yetkazilmaydi, faqat botni o'rgatish uchun saqlanadi.</div>
        @endif
      </div>

      <label style="display:flex;align-items:center;gap:8px;cursor:pointer;user-select:none">
        <input type="hidden" name="teach" value="0">
        <input type="checkbox" name="teach" value="1" @checked(old('teach', true))>
        <span>Botni o'rgatish — keyingi safar shunga o'xshash savollarga bot o'zi javob beradi</span>
      </label>

      <div class="form-group">
        <label class="form-label">Bilim uchun savol (ixtiyoriy, umumlashtirilgan holda)</label>
        <input type="text" name="teach_question" class="form-input" value="{{ old('teach_question') }}"
               placeholder="{{ Str::limit($question->question, 80) }}">
      </div>
    </div>
  </div>

  <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;height:46px;font-size:14px">Saqlash</button>
</form>

@endsection
