@extends('layouts.admin')
@section('title', 'Asosiy sozlamalar')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Asosiy sozlamalar</div>
    <div class="page-subtitle">Kontaktlar, ish vaqti va analitika tokenlari</div>
  </div>
</div>

@if(session('success'))
<div class="alert alert-success" style="max-width:680px;margin-bottom:16px">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.settings.update') }}" style="max-width:680px;display:flex;flex-direction:column;gap:20px">
  @csrf
  @method('PUT')

  {{-- Contacts --}}
  <div class="card">
    <div class="card-header">
      <div class="card-title">Kontaktlar va ish vaqti</div>
    </div>
    <div style="padding:24px">

      <div class="form-grid" style="margin-bottom:16px">
        <div class="form-group">
          <label class="form-label">Telefon</label>
          <input type="text" name="phone" class="form-input @error('phone') error @enderror"
                 value="{{ old('phone', $settings->phone) }}" placeholder="+998 XX XXX XX XX">
          @error('phone') <span class="form-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-input @error('email') error @enderror"
                 value="{{ old('email', $settings->email) }}" placeholder="info@xprinter.uz">
          @error('email') <span class="form-error">{{ $message }}</span> @enderror
        </div>
      </div>

      <div class="form-grid" style="margin-bottom:16px">
        <div class="form-group">
          <label class="form-label">Telegram</label>
          <input type="text" name="telegram" class="form-input @error('telegram') error @enderror"
                 value="{{ old('telegram', $settings->telegram) }}" placeholder="xprinter_telegram_bot">
          <span class="form-hint">@ belgisisiz username, masalan: xprinter_telegram_bot</span>
          @error('telegram') <span class="form-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">WhatsApp</label>
          <input type="text" name="whatsapp" class="form-input @error('whatsapp') error @enderror"
                 value="{{ old('whatsapp', $settings->whatsapp) }}" placeholder="+998 XX XXX XX XX">
          @error('whatsapp') <span class="form-error">{{ $message }}</span> @enderror
        </div>
      </div>

      <div class="form-group" style="margin-bottom:16px">
        <label class="form-label">Manzil</label>
        <input type="text" name="address" class="form-input @error('address') error @enderror"
               value="{{ old('address', $settings->address) }}" placeholder="Toshkent, ko'cha, uy raqami">
        @error('address') <span class="form-error">{{ $message }}</span> @enderror
      </div>

      <div class="form-group">
        <label class="form-label">Ish kunlari</label>
        @php($selectedDays = old('work_days', $settings->work_days ?? []))
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:2px">
          @foreach(\App\Models\SiteSetting::WEEK_DAYS as $key => $day)
          <label style="display:flex;align-items:center;gap:6px;cursor:pointer;padding:8px 12px;border:1px solid var(--line);border-radius:8px;transition:border-color .15s"
                 onmouseover="this.style.borderColor='var(--blue)'" onmouseout="this.style.borderColor='var(--line)'">
            <input type="checkbox" name="work_days[]" value="{{ $key }}"
                   {{ in_array($key, $selectedDays) ? 'checked' : '' }}
                   style="width:16px;height:16px;accent-color:var(--blue);cursor:pointer">
            <span style="font-weight:600;color:var(--ink);font-size:13px">{{ $day['uz'] }}</span>
          </label>
          @endforeach
        </div>
        @error('work_days') <span class="form-error">{{ $message }}</span> @enderror
      </div>

      <div class="form-group" style="margin-top:16px">
        <label class="form-label">Ish boshlanishi</label>
        <input type="time" name="work_time_from" class="form-input @error('work_time_from') error @enderror"
               value="{{ old('work_time_from', $settings->work_time_from) }}">
        @error('work_time_from') <span class="form-error">{{ $message }}</span> @enderror
      </div>

      <div class="form-group" style="margin-top:16px">
        <label class="form-label">Ish tugashi</label>
        <input type="time" name="work_time_to" class="form-input @error('work_time_to') error @enderror"
               value="{{ old('work_time_to', $settings->work_time_to) }}">
        @error('work_time_to') <span class="form-error">{{ $message }}</span> @enderror
      </div>

    </div>
  </div>

  {{-- Analytics --}}
  <div class="card">
    <div class="card-header">
      <div class="card-title">Analitika</div>
    </div>
    <div style="padding:24px">

      <div class="form-group" style="margin-bottom:16px">
        <label class="form-label">Google Analytics (GA4) ID</label>
        <input type="text" name="google_analytics_id" class="form-input @error('google_analytics_id') error @enderror"
               value="{{ old('google_analytics_id', $settings->google_analytics_id) }}" placeholder="G-XXXXXXXXXX">
        <span class="form-hint">Google Analytics 4 measurement ID</span>
        @error('google_analytics_id') <span class="form-error">{{ $message }}</span> @enderror
      </div>

      <div class="form-group">
        <label class="form-label">Yandex.Metrica raqami</label>
        <input type="text" name="yandex_metrica_id" class="form-input @error('yandex_metrica_id') error @enderror"
               value="{{ old('yandex_metrica_id', $settings->yandex_metrica_id) }}" placeholder="12345678">
        <span class="form-hint">Yandex.Metrica hisoblagich raqami</span>
        @error('yandex_metrica_id') <span class="form-error">{{ $message }}</span> @enderror
      </div>

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
