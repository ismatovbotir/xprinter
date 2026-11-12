@extends($layout)
@section('title', 'Ikki bosqichli himoya')
@section('breadcrumb', 'Ikki bosqichli himoya')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Ikki bosqichli himoyani yoqish</div>
    <div class="page-subtitle">Google Authenticator, Authy yoki 1Password bilan ishlaydi</div>
  </div>
  <a href="{{ route('profile.edit') }}" class="btn btn-ghost">
    <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
    Orqaga
  </a>
</div>

<div class="card" style="max-width:560px">
  <div style="padding:24px">

    <ol style="padding-left:20px;margin:0 0 20px;font-size:14px;color:var(--ink-soft);line-height:1.7">
      <li>Telefoningizga authenticator ilovasini o'rnating.</li>
      <li>Quyidagi QR kodni skanerlang yoki kalitni qo'lda kiriting.</li>
      <li>Ilova ko'rsatgan 6 xonali kodni pastga kiriting.</li>
    </ol>

    <div style="display:flex;gap:20px;align-items:center;flex-wrap:wrap;margin-bottom:20px">
      <div style="border:1px solid var(--line);border-radius:14px;padding:10px;background:#fff;line-height:0">
        {!! $qrSvg !!}
      </div>
      <div style="flex:1;min-width:200px">
        <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:0.1em;font-family:'JetBrains Mono',monospace;margin-bottom:6px">Kalit</div>
        <code style="display:block;font-family:'JetBrains Mono',monospace;font-size:14px;letter-spacing:0.12em;word-break:break-all;background:var(--bg-soft);padding:10px 12px;border-radius:10px;color:var(--ink)">{{ chunk_split($secret, 4, ' ') }}</code>
      </div>
    </div>

    <form method="POST" action="{{ route('two-factor.confirm') }}">
      @csrf
      <div class="form-group" style="margin-bottom:16px">
        <label class="form-label">Ilovadagi kod <span style="color:var(--red)">*</span></label>
        <input type="text" name="code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required autofocus
               autocomplete="one-time-code"
               class="form-input @error('code') error @enderror"
               style="max-width:200px;font-family:'JetBrains Mono',monospace;letter-spacing:0.3em;text-align:center;font-size:18px">
        @error('code') <span class="form-error">{{ $message }}</span> @enderror
      </div>
      <button type="submit" class="btn btn-primary">Yoqish</button>
    </form>

  </div>
</div>

@endsection
