@extends('layouts.marketplace')
@section('title', 'Profil')
@section('breadcrumb', 'Profil')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Mening profilim</div>
    <div class="page-subtitle">Shaxsiy ma'lumotlar va interfeys sozlamalari</div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:900px">

  {{-- Profile form --}}
  <div class="card">
    <div class="card-header">
      <div class="card-title">Ma'lumotlarni tahrirlash</div>
    </div>
    <div style="padding:24px">
      <form method="POST" action="{{ route('profile.update') }}">
        @csrf @method('PUT')

        <div class="form-grid" style="margin-bottom:16px">
          <div class="form-group">
            <label class="form-label">Ism <span style="color:#D32F2F">*</span></label>
            <input type="text" name="name"
                   class="form-input @error('name') error @enderror"
                   value="{{ old('name', $user->name) }}" required>
            @error('name') <span class="form-error">{{ $message }}</span> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Familiya</label>
            <input type="text" name="last_name"
                   class="form-input @error('last_name') error @enderror"
                   value="{{ old('last_name', $user->last_name) }}"
                   placeholder="Ixtiyoriy">
            @error('last_name') <span class="form-error">{{ $message }}</span> @enderror
          </div>
        </div>

        <div class="form-group" style="margin-bottom:16px">
          <label class="form-label">Email</label>
          <input type="email" class="form-input"
                 value="{{ $user->email }}" disabled
                 style="background:var(--bg-soft);color:var(--muted);cursor:not-allowed">
          <span class="form-hint">Email o'zgartirilmaydi</span>
        </div>

        <div class="form-group" style="margin-bottom:16px">
          <label class="form-label">Telefon</label>
          <input type="text" name="phone"
                 class="form-input @error('phone') error @enderror"
                 value="{{ old('phone', $user->phone) }}"
                 placeholder="+998901234567">
          @error('phone') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group" style="margin-bottom:24px">
          <label class="form-label">Interfeys tili</label>
          <select name="lang" class="form-input">
            <option value="uz" {{ old('lang', $user->lang) === 'uz' ? 'selected' : '' }}>O'zbekcha</option>
            <option value="ru" {{ old('lang', $user->lang) === 'ru' ? 'selected' : '' }}>Русский</option>
            <option value="en" {{ old('lang', $user->lang) === 'en' ? 'selected' : '' }}>English</option>
          </select>
        </div>

        <div style="display:flex;gap:10px">
          <button type="submit" class="btn btn-primary">
            <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            Saqlash
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- Account info --}}
  <div class="card" style="align-self:start">
    <div class="card-header">
      <div class="card-title">Akkaunt</div>
    </div>
    <div style="padding:24px">
      <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px">
        <div class="user-avatar" style="width:52px;height:52px;font-size:18px;border-radius:14px">
          {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div>
          <div style="font-weight:700;color:var(--ink);font-size:15px">
            {{ $user->name }}{{ $user->last_name ? ' ' . $user->last_name : '' }}
          </div>
          <div style="font-size:13px;color:var(--muted)">{{ $user->email }}</div>
          @if($user->company)
          <div style="font-size:12px;color:var(--muted);margin-top:2px">{{ $user->company->brand ?? $user->company->name }}</div>
          @endif
        </div>
      </div>

      <div style="display:flex;flex-direction:column;gap:10px">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 12px;background:var(--bg-soft);border-radius:10px">
          <span style="font-size:13px;color:var(--muted)">Rol</span>
          <span style="font-family:'JetBrains Mono',monospace;font-size:11px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:var(--blue-deep);background:var(--blue-soft);padding:3px 10px;border-radius:20px">
            {{ $user->isOwner() ? 'OWNER' : 'OPERATOR' }}
          </span>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 12px;background:var(--bg-soft);border-radius:10px">
          <span style="font-size:13px;color:var(--muted)">Til</span>
          <span style="font-family:'JetBrains Mono',monospace;font-size:11px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:var(--ink)">
            {{ strtoupper($user->lang) }}
          </span>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 12px;background:var(--bg-soft);border-radius:10px">
          <span style="font-size:13px;color:var(--muted)">Ro'yxatdan o'tgan</span>
          <span style="font-size:13px;color:var(--ink)">{{ $user->created_at->format('d.m.Y') }}</span>
        </div>
      </div>
    </div>
  </div>

</div>

@endsection
