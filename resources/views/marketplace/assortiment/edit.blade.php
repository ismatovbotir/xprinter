@extends('layouts.marketplace')
@section('title', 'Narxni o\'zgartirish')
@section('breadcrumb', 'Assortiment')

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">{{ $companyProduct->product->translation?->name ?? $companyProduct->product->model_number }}</div>
        <div class="page-subtitle">Narxlar va mavjudlikni tahrirlash</div>
    </div>
    <a href="{{ route('marketplace.assortiment.index') }}" class="btn btn-secondary">
        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        Orqaga
    </a>
</div>

@php
    $retail    = $companyProduct->prices->firstWhere('type', 'retail');
    $wholesale = $companyProduct->prices->firstWhere('type', 'wholesale');
@endphp

<div style="max-width:520px">
    <form method="POST" action="{{ route('marketplace.assortiment.update', $companyProduct) }}"
          style="display:flex;flex-direction:column;gap:16px">
        @csrf @method('PUT')

        <div class="card" style="padding:20px;display:flex;flex-direction:column;gap:16px">

            <div class="form-group" style="margin:0">
                <label class="form-label">Valyuta</label>
                <select name="currency" class="form-input">
                    <option value="uzs" {{ old('currency', $retail?->currency ?? 'uzs') === 'uzs' ? 'selected' : '' }}>UZS — So'm</option>
                    <option value="usd" {{ old('currency', $retail?->currency) === 'usd' ? 'selected' : '' }}>USD — Dollar</option>
                </select>
            </div>

            <div class="form-group" style="margin:0">
                <label class="form-label">Chakana narx</label>
                <input type="number" name="price_retail" class="form-input @error('price_retail') is-invalid @enderror"
                       value="{{ old('price_retail', $retail?->value) }}" min="1" step="1000">
                @error('price_retail')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group" style="margin:0">
                <label class="form-label">Ulgurji narx</label>
                <input type="number" name="price_wholesale" class="form-input @error('price_wholesale') is-invalid @enderror"
                       value="{{ old('price_wholesale', $wholesale?->value) }}" min="1" step="1000">
                @error('price_wholesale')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-top:1px solid var(--line)">
                <div>
                    <div style="font-size:13px;font-weight:500;color:var(--ink)">Mavjud</div>
                    <div style="font-size:11px;color:var(--muted)">Mahsulot sotuvda bormi</div>
                </div>
                <label style="cursor:pointer;display:flex;align-items:center;gap:8px">
                    <input type="checkbox" name="is_available" value="1"
                           {{ old('is_available', $companyProduct->is_available) ? 'checked' : '' }}>
                    <span style="font-size:13px;color:var(--ink-soft)">Ha, mavjud</span>
                </label>
            </div>

            <div class="form-group" style="margin:0">
                <label class="form-label">Miqdor (ixtiyoriy)</label>
                <input type="number" name="quantity" class="form-input"
                       value="{{ old('quantity', $companyProduct->quantity) }}" min="0">
            </div>
        </div>

        <div style="display:flex;gap:12px">
            <button type="submit" class="btn btn-primary">Saqlash</button>
            <a href="{{ route('marketplace.assortiment.index') }}" class="btn btn-secondary">Bekor qilish</a>
        </div>
    </form>
</div>

@endsection
