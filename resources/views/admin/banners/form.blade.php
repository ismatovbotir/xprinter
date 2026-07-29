@extends('layouts.admin')
@section('title', isset($banner) ? 'Bannerni tahrirlash' : "Banner qo'shish")

@section('content')

@php $editing = isset($banner); @endphp

<div class="page-header">
    <div>
        <div class="page-title">{{ $editing ? 'Bannerni tahrirlash' : "Banner qo'shish" }}</div>
        <div class="page-subtitle">Bosh sahifa slider banneri</div>
    </div>
    <a href="{{ route('admin.banners.index') }}" class="btn btn-ghost">← Ro'yxatga qaytish</a>
</div>

<form method="POST"
      action="{{ $editing ? route('admin.banners.update', $banner) : route('admin.banners.store') }}"
      enctype="multipart/form-data"
      style="max-width:640px">
    @csrf
    @if($editing) @method('PUT') @endif

    <div class="card" style="margin-bottom:16px">
        <div class="card-header"><div class="card-title">Matn</div></div>
        <div style="padding:20px;display:flex;flex-direction:column;gap:16px">

            <div class="form-group">
                <label class="form-label">Sarlavha <span style="color:var(--red)">*</span></label>
                <input type="text" name="title" class="form-input {{ $errors->has('title') ? 'is-invalid' : '' }}"
                       value="{{ old('title', $banner->title ?? '') }}" placeholder="Xprinter — rasmiy distribyutor">
                @error('title')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Qo'shimcha matn</label>
                <input type="text" name="subtitle" class="form-input {{ $errors->has('subtitle') ? 'is-invalid' : '' }}"
                       value="{{ old('subtitle', $banner->subtitle ?? '') }}" placeholder="Termoprinterlari, kafolat, servis">
                @error('subtitle')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
            </div>

        </div>
    </div>

    <div class="card" style="margin-bottom:16px">
        <div class="card-header"><div class="card-title">Tugma (ixtiyoriy)</div></div>
        <div style="padding:20px;display:flex;flex-direction:column;gap:16px">

            <div class="form-group">
                <label class="form-label">Tugma matni</label>
                <input type="text" name="button_text" class="form-input"
                       value="{{ old('button_text', $banner->button_text ?? '') }}" placeholder="Katalogga o'tish">
            </div>

            <div class="form-group">
                <label class="form-label">Tugma havolasi</label>
                <input type="text" name="button_link" class="form-input"
                       value="{{ old('button_link', $banner->button_link ?? '') }}" placeholder="/catalog">
                <div style="font-size:11px;color:var(--muted);margin-top:4px">Ichki havola: /catalog, yoki to'liq URL</div>
            </div>

        </div>
    </div>

    <div class="card" style="margin-bottom:16px">
        <div class="card-header"><div class="card-title">Rasm</div></div>
        <div style="padding:20px;display:flex;flex-direction:column;gap:16px">

            @if($editing && $banner->image_url)
            <div>
                <div style="font-size:12px;color:var(--muted);margin-bottom:8px">Hozirgi rasm</div>
                <div style="width:100%;max-width:400px;border-radius:10px;overflow:hidden;border:1px solid var(--line)">
                    <img src="{{ $banner->image_url }}" alt="" style="width:100%;display:block">
                </div>
            </div>
            @endif

            <div class="form-group">
                <label class="form-label">{{ $editing && $banner->image_url ? 'Yangi rasm (ixtiyoriy)' : 'Rasm' }}</label>
                <input type="file" name="image" accept="image/*"
                       class="form-input {{ $errors->has('image') ? 'is-invalid' : '' }}"
                       style="padding:8px"
                       onchange="previewImage(this)">
                @error('image')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                <div style="font-size:11px;color:var(--muted);margin-top:4px">JPG, PNG — maks 4 MB. Tavsiya: 1440×560 px</div>
                <div id="img-preview" style="display:none;margin-top:12px;border-radius:10px;overflow:hidden;border:1px solid var(--line);max-width:400px">
                    <img id="img-preview-src" style="width:100%;display:block">
                </div>
            </div>

        </div>
    </div>

    <div class="card" style="margin-bottom:20px">
        <div class="card-header"><div class="card-title">Sozlamalar</div></div>
        <div style="padding:20px;display:flex;flex-direction:column;gap:16px">

            <div class="form-group">
                <label class="form-label">Tartib raqami</label>
                <input type="number" name="sort_order" class="form-input" style="max-width:120px"
                       value="{{ old('sort_order', $banner->sort_order ?? 0) }}" min="0">
                <div style="font-size:11px;color:var(--muted);margin-top:4px">Kichik raqam — birinchi ko'rinadi</div>
            </div>

            <label style="display:flex;align-items:center;gap:10px;cursor:pointer">
                <input type="checkbox" name="is_active" value="1"
                       {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }}
                       style="width:16px;height:16px;accent-color:var(--blue)">
                <div>
                    <div style="font-size:13px;font-weight:600;color:var(--ink)">Faol</div>
                    <div style="font-size:12px;color:var(--muted)">Belgilanmasa banner slayderde ko'rinmaydi</div>
                </div>
            </label>

        </div>
    </div>

    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;height:46px;font-size:14px">
        {{ $editing ? 'Saqlash' : "Qo'shish" }}
    </button>
</form>

<script>
function previewImage(input) {
    var preview = document.getElementById('img-preview');
    var img = document.getElementById('img-preview-src');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

@endsection
