@extends('layouts.admin')
@section('title', 'Xabar')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">
      {{ trim($user->name . ' ' . ($user->last_name ?? '')) }}
      <a href="{{ route('admin.messages.index') }}" style="font-size:12px;font-weight:500;color:var(--muted);margin-left:10px;text-decoration:none">← Barcha xabarlar</a>
    </div>
    <div class="page-subtitle">{{ $user->phone ?? '—' }}</div>
  </div>
</div>

@if(session('success'))
<div class="alert alert-success" style="max-width:680px;margin-bottom:16px">{{ session('success') }}</div>
@endif

<div class="card" style="max-width:680px">
  <div style="padding:24px;display:flex;flex-direction:column;gap:14px;max-height:520px;overflow-y:auto">
    @forelse($messages as $msg)
      @php($isAdmin = $msg->sender === 'admin')
      <div style="display:flex;{{ $isAdmin ? 'justify-content:flex-end' : 'justify-content:flex-start' }}">
        <div style="max-width:75%;padding:10px 14px;border-radius:14px;font-size:13.5px;line-height:1.5;
                    background:{{ $isAdmin ? 'var(--blue)' : 'var(--bg-soft)' }};
                    color:{{ $isAdmin ? '#fff' : 'var(--ink)' }};">
          <div style="white-space:pre-wrap">{{ $msg->body }}</div>
          <div style="font-size:10.5px;margin-top:4px;opacity:.7">{{ $msg->created_at->format('d.m.Y H:i') }}</div>
        </div>
      </div>
    @empty
      <div style="text-align:center;color:var(--muted);padding:24px">Hali xabarlar yo'q</div>
    @endforelse
  </div>

  <form method="POST" action="{{ route('admin.messages.reply', $user) }}" style="padding:16px 24px;border-top:1px solid var(--line);display:flex;gap:10px">
    @csrf
    <textarea name="body" rows="2" required class="form-input @error('body') error @enderror"
              placeholder="Javob yozing..." style="flex:1;resize:vertical">{{ old('body') }}</textarea>
    <button type="submit" class="btn btn-primary" style="align-self:flex-end">Yuborish</button>
  </form>
  @error('body') <div class="form-error" style="padding:0 24px 16px">{{ $message }}</div> @enderror

  @unless($user->telegram_chat_id)
  <div style="padding:0 24px 16px;font-size:12px;color:var(--muted)">
    Ogohlantirish: bu mijoz Telegram orqali ulanmagan — javob faqat shu yerda saqlanadi, botga yuborilmaydi.
  </div>
  @endunless
</div>

@endsection
