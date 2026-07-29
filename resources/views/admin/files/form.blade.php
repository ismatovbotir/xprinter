@extends('layouts.admin')
@section('title', isset($file) ? 'Faylni tahrirlash' : "Fayl qo'shish")

@section('content')

@php $editing = isset($file); @endphp

<div class="page-header">
    <div>
        <div class="page-title">{{ $editing ? 'Faylni tahrirlash' : "Fayl qo'shish" }}</div>
        <div class="page-subtitle">Drayver yoki manuallar kutubxonasi</div>
    </div>
    <a href="{{ route('admin.files.index') }}" class="btn btn-ghost">← Ro'yxatga qaytish</a>
</div>

<form method="POST"
      action="{{ $editing ? route('admin.files.update', $file) : route('admin.files.store') }}"
      enctype="multipart/form-data"
      style="max-width:640px">
    @csrf
    @if($editing) @method('PUT') @endif

    <div class="card" style="margin-bottom:16px">
        <div class="card-header"><div class="card-title">Ma'lumotlar</div></div>
        <div style="padding:20px;display:flex;flex-direction:column;gap:16px">

            <div class="form-group">
                <label class="form-label">Fayl nomi <span style="color:var(--red)">*</span></label>
                <input type="text" name="name" class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                       value="{{ old('name', $file->name ?? '') }}" placeholder="XP-Q890K Windows driver 64-bit">
                @error('name')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Tavsifi</label>
                <textarea name="description" rows="3" class="form-input" placeholder="Masalan: Ubuntu/Linux uchun KDE plazma yadro...">{{ old('description', $file->description ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Versiya (ixtiyoriy)</label>
                <input type="text" name="version" class="form-input"
                       value="{{ old('version', $file->version ?? '') }}" placeholder="v3.2.1 yoki 2024.01.15">
            </div>

        </div>
    </div>

    <div class="card" style="margin-bottom:20px">
        <div class="card-header"><div class="card-title">Fayl</div></div>
        <div style="padding:20px;display:flex;flex-direction:column;gap:16px">

            @if($editing && $file->file_path)
            <div>
                <div style="font-size:12px;color:var(--muted);margin-bottom:8px">Hozirgi fayl</div>
                <a href="{{ $file->url }}" target="_blank" style="display:inline-flex;align-items:center;gap:8px;padding:8px 12px;background:var(--bg-soft);border:1px solid var(--line);border-radius:8px;text-decoration:none;font-size:13px;color:var(--blue)">
                    <svg viewBox="0 0 24 24" style="width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    {{ $file->file_name }} ({{ $file->size_formatted }})
                </a>
            </div>
            @endif

            <div class="form-group">
                <label class="form-label">{{ $editing && $file->file_path ? 'Yangi fayl (ixtiyoriy)' : 'Fayl' }} <span style="color:var(--red)">{{ !$editing ? '*' : '' }}</span></label>
                <input type="file" name="file"
                       class="form-input {{ $errors->has('file') ? 'is-invalid' : '' }}"
                       style="padding:8px"
                       @if(!$editing) required @endif>
                @error('file')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                <div style="font-size:11px;color:var(--muted);margin-top:4px">Maksimal 50 MB. PDF, ZIP, EXE, MSI va boshqa formatlar.</div>
            </div>

        </div>
    </div>

    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;height:46px;font-size:14px">
        {{ $editing ? 'Saqlash' : "Qo'shish" }}
    </button>
</form>

@endsection
