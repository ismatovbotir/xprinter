@extends('layouts.producer')
@section('title', 'CSV Import')
@section('breadcrumb', 'Seriya raqamlari')

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">CSV Import</div>
        <div class="page-subtitle">Seriya raqamlarini CSV orqali yuklash</div>
    </div>
    <a href="{{ route('producer.serials.index') }}" class="btn btn-secondary">
        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        Orqaga
    </a>
</div>

<div style="max-width:540px">
    <div class="card" style="padding:24px">
        <form method="POST" action="{{ route('producer.serials.store') }}" enctype="multipart/form-data"
              style="display:flex;flex-direction:column;gap:16px">
            @csrf

            <div class="form-group">
                <label class="form-label">Mahsulot</label>
                <select name="product_id" class="form-input @error('product_id') is-invalid @enderror">
                    <option value="">Mahsulotni tanlang</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->translation?->name ?? $product->model_number }}
                        ({{ $product->model_number }})
                    </option>
                    @endforeach
                </select>
                @error('product_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">CSV fayl</label>
                <input type="file" name="csv" accept=".csv,.txt"
                       class="form-input @error('csv') is-invalid @enderror">
                @error('csv')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div style="font-size:12px;color:var(--muted);margin-top:6px">
                    Har bir qatorda bitta seriya raqam. Maksimal 2MB.
                </div>
            </div>

            <div style="background:var(--bg-soft);border-radius:10px;padding:14px 16px;font-size:12.5px;color:var(--muted)">
                <div style="font-weight:600;color:var(--ink-soft);margin-bottom:6px">CSV format:</div>
                <code style="font-family:'JetBrains Mono',monospace;font-size:12px;display:block;line-height:1.8">SN123456789<br>SN987654321<br>SN111222333</code>
                <div style="margin-top:8px">Mavjud seriya raqamlar o'tkazib yuboriladi (dublikat yo'q).</div>
            </div>

            <button type="submit" class="btn btn-primary" style="align-self:flex-start">
                <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Yuklash
            </button>
        </form>
    </div>
</div>

@endsection
