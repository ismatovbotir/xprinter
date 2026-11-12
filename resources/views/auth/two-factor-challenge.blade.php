@extends('layouts.auth')

@section('title', 'Tasdiqlash kodi')

@section('form')
<div class="auth-card">

  <div class="auth-heading">Ikki bosqichli himoya</div>
  <div class="auth-subheading">Authenticator ilovasidagi 6 xonali kodni kiriting</div>

  <form method="POST" action="{{ route('two-factor.verify') }}">
    @csrf

    <div class="form-group">
      <label for="code" class="form-label">Kod</label>
      <input
        id="code"
        type="text"
        name="code"
        inputmode="numeric"
        pattern="[0-9]{6}"
        maxlength="6"
        placeholder="123456"
        class="form-input {{ $errors->has('code') ? 'is-invalid' : '' }}"
        style="font-family:'JetBrains Mono',monospace;letter-spacing:0.3em;text-align:center;font-size:20px"
        required
        autocomplete="one-time-code"
        autofocus
      >
      @error('code')
        <span class="invalid-feedback">{{ $message }}</span>
      @enderror
    </div>

    <button type="submit" class="btn-auth">Tasdiqlash</button>
  </form>

</div>

<div class="auth-footer">
  <form method="POST" action="{{ route('logout') }}" style="display:inline">
    @csrf
    <button type="submit" class="auth-link" style="background:none;border:0;cursor:pointer;font:inherit">Boshqa hisob bilan kirish</button>
  </form>
</div>
@endsection
