@extends('layouts.admin')
@section('title', isset($product) ? 'Mahsulotni tahrirlash' : 'Yangi mahsulot')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">{{ isset($product) ? 'Mahsulotni tahrirlash' : 'Yangi mahsulot' }}</div>
    <div class="page-subtitle">{{ isset($product) ? $product->model_number : "Yangi mahsulot qo'shish" }}</div>
  </div>
  <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">
    <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
    Orqaga
  </a>
</div>

<livewire:admin.product-form :product="$product ?? null" />

@endsection
