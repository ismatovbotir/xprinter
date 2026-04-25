@extends('layouts.auth')

@section('title', "Ro'yxatdan o'tish")

@section('form')
<div class="auth-card">

  <div class="auth-heading">Diler sifatida kiring</div>
  <div class="auth-subheading">Kompaniya akkauntini yarating — admin tasdiqlashidan keyin platforma ochiladi</div>

  <form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="form-group">
      <label for="name" class="form-label">To'liq ism</label>
      <input
        id="name"
        type="text"
        name="name"
        value="{{ old('name') }}"
        placeholder="Ism Familiya"
        class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
        required
        autocomplete="name"
        autofocus
      >
      @error('name')
        <span class="invalid-feedback">{{ $message }}</span>
      @enderror
    </div>

    <div class="form-group">
      <label for="email" class="form-label">Email manzil</label>
      <input
        id="email"
        type="email"
        name="email"
        value="{{ old('email') }}"
        placeholder="example@company.com"
        class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
        required
        autocomplete="email"
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
        placeholder="Kamida 8 ta belgi"
        class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
        required
        autocomplete="new-password"
      >
      @error('password')
        <span class="invalid-feedback">{{ $message }}</span>
      @enderror
    </div>

    <div class="form-group" style="margin-bottom: 28px">
      <label for="password-confirm" class="form-label">Parolni tasdiqlash</label>
      <input
        id="password-confirm"
        type="password"
        name="password_confirmation"
        placeholder="Parolni qayta kiriting"
        class="form-input"
        required
        autocomplete="new-password"
      >
    </div>

    <button type="submit" class="btn-auth">Ro'yxatdan o'tish</button>
  </form>

</div>

<div class="auth-footer">
  Hisobingiz bormi?
  <a href="{{ route('login') }}" class="auth-link">Kirish</a>
</div>
@endsection
