@extends('layouts.marketplace')
@section('title', 'Mahsulot qo\'shish')
@section('breadcrumb', 'Assortiment')

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">Mahsulot qo'shish</div>
        <div class="page-subtitle">Katalogdan tanlang va narxini belgilang</div>
    </div>
    <a href="{{ route('marketplace.assortiment.index') }}" class="btn btn-secondary">
        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        Orqaga
    </a>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start">

    {{-- Left: catalog browse --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        @forelse($categories as $category)
        @php $catName = $category->translations->firstWhere('lang', 'uz')?->name ?? $category->translations->first()?->name; @endphp
        <div class="card">
            <div class="card-header">
                <div class="card-title">{{ $catName }}</div>
            </div>
            <div style="padding:0 20px 20px">
                @foreach($category->products as $product)
                @php $pName = $product->translation?->name ?? $product->model_number; @endphp
                <label style="display:flex;align-items:center;gap:14px;padding:12px 0;border-bottom:1px solid var(--line);cursor:pointer" id="label-{{ $product->id }}">
                    <input type="radio" name="product_id" value="{{ $product->id }}" form="add-form"
                           style="width:16px;height:16px;accent-color:var(--blue);flex-shrink:0"
                           onchange="selectProduct({{ $product->id }}, '{{ addslashes($pName) }}', '{{ $product->model_number }}')">
                    <div style="flex:1;min-width:0">
                        <div style="font-weight:600;color:var(--ink);font-size:14px">{{ $pName }}</div>
                        <div style="font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--muted);margin-top:2px">{{ $product->model_number }}</div>
                    </div>
                </label>
                @endforeach
            </div>
        </div>
        @empty
        <div class="card" style="padding:40px;text-align:center;color:var(--muted)">
            Barcha mavjud mahsulotlar allaqachon assortimentda
        </div>
        @endforelse
    </div>

    {{-- Right: price form --}}
    <div style="position:sticky;top:20px">
        <form method="POST" action="{{ route('marketplace.assortiment.store') }}" id="add-form"
              style="display:flex;flex-direction:column;gap:16px">
            @csrf
            <input type="hidden" name="product_id" id="selected_product_id" value="{{ old('product_id') }}">

            <div class="card" style="padding:20px">
                <div id="selected-label" style="padding:12px 0 16px;border-bottom:1px solid var(--line);margin-bottom:16px">
                    <div style="font-size:13px;color:var(--muted);text-align:center">
                        Chap tarafdan mahsulot tanlang
                    </div>
                </div>

                <div style="display:flex;flex-direction:column;gap:14px">
                    <div class="form-group" style="margin:0">
                        <label class="form-label">Valyuta</label>
                        <select name="currency" class="form-input">
                            <option value="uzs" {{ old('currency', 'uzs') === 'uzs' ? 'selected' : '' }}>UZS — So'm</option>
                            <option value="usd" {{ old('currency') === 'usd' ? 'selected' : '' }}>USD — Dollar</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin:0">
                        <label class="form-label">Chakana narx</label>
                        <input type="number" name="price_retail" class="form-input @error('price_retail') is-invalid @enderror"
                               value="{{ old('price_retail') }}" placeholder="0" min="1" step="1000">
                        @error('price_retail')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group" style="margin:0">
                        <label class="form-label">Ulgurji narx</label>
                        <input type="number" name="price_wholesale" class="form-input @error('price_wholesale') is-invalid @enderror"
                               value="{{ old('price_wholesale') }}" placeholder="0" min="1" step="1000">
                        @error('price_wholesale')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-top:1px solid var(--line)">
                        <div>
                            <div style="font-size:13px;font-weight:500;color:var(--ink)">Mavjud</div>
                            <div style="font-size:11px;color:var(--muted)">Mahsulot sotuvda bormi</div>
                        </div>
                        <label style="position:relative;display:inline-block;width:42px;height:24px;cursor:pointer">
                            <input type="checkbox" name="is_available" value="1" checked style="opacity:0;width:0;height:0">
                            <span style="position:absolute;inset:0;background:var(--blue);border-radius:24px;transition:.2s"></span>
                            <span style="position:absolute;height:18px;width:18px;left:3px;bottom:3px;background:#fff;border-radius:50%;transition:.2s"></span>
                        </label>
                    </div>

                    <div class="form-group" style="margin:0">
                        <label class="form-label">Miqdor (ixtiyoriy)</label>
                        <input type="number" name="quantity" class="form-input"
                               value="{{ old('quantity') }}" placeholder="Belgilamasangiz ham bo'ladi" min="0">
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%">
                        Assortimentga qo'shish
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function selectProduct(id, name, model) {
    document.getElementById('selected_product_id').value = id;
    document.getElementById('selected-label').innerHTML =
        '<div style="font-weight:600;color:var(--ink);font-size:15px">' + name + '</div>' +
        '<div style="font-family:\'JetBrains Mono\',monospace;font-size:11px;color:var(--muted);margin-top:3px">' + model + '</div>';
}
</script>

@endsection
