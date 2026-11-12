{{-- Two-factor auth block, included in every role's profile page --}}
<div class="card" style="grid-column:1 / -1">
  <div class="card-header">
    <div class="card-title">Ikki bosqichli himoya (2FA)</div>
  </div>
  <div style="padding:24px;display:flex;justify-content:space-between;align-items:center;gap:20px;flex-wrap:wrap">
    <div style="max-width:520px">
      @if($user->hasTwoFactorEnabled())
        <div style="display:flex;align-items:center;gap:8px;font-weight:700;color:var(--green);margin-bottom:6px">
          <svg viewBox="0 0 24 24" style="width:18px;height:18px;stroke:currentColor;fill:none;stroke-width:2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
          Yoqilgan — {{ $user->two_factor_confirmed_at->format('d.m.Y') }}
        </div>
        <div style="font-size:13px;color:var(--muted);line-height:1.6">
          Har kirishda authenticator ilovasidagi kod so'raladi. O'chirish uchun joriy parolni kiriting.
        </div>
      @else
        <div style="font-weight:700;color:var(--ink);margin-bottom:6px">O'chirilgan</div>
        <div style="font-size:13px;color:var(--muted);line-height:1.6">
          Parol o'g'irlansa ham hisobingizga kirish mumkin bo'lmaydi. Admin, owner va producer hisoblari uchun tavsiya etiladi.
        </div>
      @endif
    </div>

    @if($user->hasTwoFactorEnabled())
      <form method="POST" action="{{ route('two-factor.destroy') }}" style="display:flex;gap:8px;align-items:flex-start">
        @csrf @method('DELETE')
        <div class="form-group">
          <input type="password" name="password" class="form-input @error('password') error @enderror"
                 placeholder="Joriy parol" required autocomplete="current-password" style="min-width:180px">
          @error('password') <span class="form-error">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="btn btn-ghost" style="color:var(--red)">O'chirish</button>
      </form>
    @else
      <a href="{{ route('two-factor.setup') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        Yoqish
      </a>
    @endif
  </div>
</div>
