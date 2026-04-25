@extends('layouts.auth')

@section('title', 'Kirish')

@section('form')
<div class="auth-card">

  <div class="auth-heading">Xush kelibsiz</div>
  <div class="auth-subheading">Hisobingizga kiring</div>

  <form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group">
      <label for="email" class="form-label">Email manzil</label>
      <input
        id="email"
        type="email"
        name="email"
        value="{{ old('email') }}"
        placeholder="example@mail.com"
        class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
        required
        autocomplete="email"
        autofocus
      >
      @error('email')
        <span class="invalid-feedback">{{ $message }}</span>
      @enderror
    </div>

    <div class="form-group">
      <label for="password" class="form-label">Parol</label>
      <input
        id="password"
        type="password"
        name="password"
        placeholder="••••••••"
        class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
        required
        autocomplete="current-password"
      >
      @error('password')
        <span class="invalid-feedback">{{ $message }}</span>
      @enderror
    </div>

    <div class="form-row-between">
      <label class="form-check">
        <input
          type="checkbox"
          name="remember"
          class="form-check-input"
          {{ old('remember') ? 'checked' : '' }}
        >
        <span class="form-check-label">Eslab qolish</span>
      </label>

      @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="auth-link">Parolni unutdingizmi?</a>
      @endif
    </div>

    <button type="submit" class="btn-auth">Kirish</button>
  </form>

</div>

<div class="auth-footer">
  Hisobingiz yo'qmi?
  <a href="{{ route('register') }}" class="auth-link">Ro'yxatdan o'tish</a>
</div>
@endsection
