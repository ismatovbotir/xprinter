@extends('layouts.admin')
@section('title', isset($user) ? 'Foydalanuvchini tahrirlash' : 'Yangi foydalanuvchi')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">{{ isset($user) ? 'Foydalanuvchini tahrirlash' : 'Yangi foydalanuvchi' }}</div>
    <div class="page-subtitle">{{ isset($user) ? $user->email : 'Yangi hisob yaratish' }}</div>
  </div>
  <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">
    <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
    Orqaga
  </a>
</div>

<div class="card" style="max-width:620px">
  <div style="padding:24px">
    <form method="POST" action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}">
      @csrf
      @if(isset($user)) @method('PUT') @endif

      <div class="form-grid" style="margin-bottom:16px">
        <div class="form-group">
          <label class="form-label">Ism <span style="color:#D32F2F">*</span></label>
          <input type="text" name="name" class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                 value="{{ old('name', $user->name ?? '') }}" placeholder="To'liq ism">
          @error('name') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Email <span style="color:#D32F2F">*</span></label>
          <input type="email" name="email" class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                 value="{{ old('email', $user->email ?? '') }}" placeholder="user@example.com">
          @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="form-grid" style="margin-bottom:16px">
        <div class="form-group">
          <label class="form-label">Rol <span style="color:#D32F2F">*</span></label>
          <select name="role" class="form-input {{ $errors->has('role') ? 'error' : '' }}">
            @foreach(['admin','producer','owner','user','client'] as $r)
            <option value="{{ $r }}" {{ old('role', $user->role ?? 'user') === $r ? 'selected' : '' }}>
              {{ ucfirst($r) }}
            </option>
            @endforeach
          </select>
          @error('role') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Til <span style="color:#D32F2F">*</span></label>
          <select name="lang" class="form-input">
            <option value="uz" {{ old('lang', $user->lang ?? 'uz') === 'uz' ? 'selected' : '' }}>O'zbek</option>
            <option value="ru" {{ old('lang', $user->lang ?? 'uz') === 'ru' ? 'selected' : '' }}>Русский</option>
            <option value="en" {{ old('lang', $user->lang ?? 'uz') === 'en' ? 'selected' : '' }}>English</option>
          </select>
        </div>
      </div>

      <div class="form-group" style="margin-bottom:16px">
        <label class="form-label">Kompaniya</label>
        <select name="company_id" class="form-input">
          <option value="">— Bog'lanmagan —</option>
          @foreach($companies as $company)
          <option value="{{ $company->id }}"
                  {{ old('company_id', $user->company_id ?? '') === $company->id ? 'selected' : '' }}>
            {{ $company->brand ?? $company->name }} @if($company->inn) ({{ $company->inn }}) @endif
          </option>
          @endforeach
        </select>
        <div class="form-hint">Faqat tasdiqlangan kompaniyalar ko'rsatiladi</div>
      </div>

      <div class="form-group" style="margin-bottom:24px">
        <label class="form-label">
          Parol {{ isset($user) ? '(bo\'sh qoldirsa o\'zgarmaydi)' : '' }}
          @unless(isset($user)) <span style="color:#D32F2F">*</span> @endunless
        </label>
        <input type="password" name="password" class="form-input {{ $errors->has('password') ? 'error' : '' }}"
               placeholder="{{ isset($user) ? 'Yangi parol (ixtiyoriy)' : 'Kamida 8 ta belgi' }}"
               autocomplete="new-password">
        @error('password') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">
          <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
          {{ isset($user) ? 'Saqlash' : 'Qo\'shish' }}
        </button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Bekor qilish</a>
      </div>
    </form>
  </div>
</div>

@endsection
