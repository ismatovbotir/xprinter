@extends('layouts.marketplace')
@section('title', "Operator qo'shish")
@section('breadcrumb', "Operator qo'shish")

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">Operator qo'shish</div>
        <div class="page-subtitle">{{ $company->brand ?? $company->name }} jamoasiga yangi xodim</div>
    </div>
    <a href="{{ route('marketplace.team.index') }}" class="btn btn-ghost">
        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        Orqaga
    </a>
</div>

<div class="card" style="max-width:520px">
    <div style="padding:24px">
        <form method="POST" action="{{ route('marketplace.team.store') }}">
            @csrf

            <div class="form-group" style="margin-bottom:16px">
                <label class="form-label">Ism <span style="color:var(--red)">*</span></label>
                <input type="text" name="name" class="form-input @error('name') error @enderror"
                       value="{{ old('name') }}" style="width:100%" required>
                @error('name') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom:16px">
                <label class="form-label">Email <span style="color:var(--red)">*</span></label>
                <input type="email" name="email" class="form-input @error('email') error @enderror"
                       value="{{ old('email') }}" style="width:100%" required>
                @error('email') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
                <div class="form-group">
                    <label class="form-label">Parol <span style="color:var(--red)">*</span></label>
                    <input type="password" name="password" class="form-input @error('password') error @enderror"
                           style="width:100%" required>
                    @error('password') <span class="form-error">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Parolni tasdiqlash <span style="color:var(--red)">*</span></label>
                    <input type="password" name="password_confirmation" class="form-input" style="width:100%" required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom:24px">
                <label class="form-label">Interfeys tili <span style="color:var(--red)">*</span></label>
                <select name="lang" class="form-input" style="width:100%">
                    <option value="uz" {{ old('lang','uz') === 'uz' ? 'selected' : '' }}>O'zbekcha</option>
                    <option value="ru" {{ old('lang') === 'ru' ? 'selected' : '' }}>Русский</option>
                    <option value="en" {{ old('lang') === 'en' ? 'selected' : '' }}>English</option>
                </select>
                @error('lang') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div style="background:var(--bg-soft);border-radius:10px;padding:12px 14px;margin-bottom:20px;font-size:13px;color:var(--muted);line-height:1.5">
                Operator <strong style="color:var(--ink)">{{ $company->brand ?? $company->name }}</strong> kompaniyasi nomidan ishlaydi.
                U mahsulotlar va narxlarni boshqara oladi, lekin kompaniya sozlamalarini o'zgartira olmaydi.
            </div>

            <div style="display:flex;gap:10px">
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                    Qo'shish
                </button>
                <a href="{{ route('marketplace.team.index') }}" class="btn btn-ghost">Bekor qilish</a>
            </div>
        </form>
    </div>
</div>

@endsection
