@extends('layouts.app')
@php
    $locale  = app()->getLocale();
    $catName = $activeCategory
        ? ($activeCategory->translations->firstWhere('lang', $locale)?->name ?? $activeCategory->slug)
        : null;
@endphp
@section('title',       $catName ?? 'Katalog')
@section('description', $catName
    ? "Xprinter {$catName} O'zbekistonda — rasmiy distribyutordan sotib oling. 12 oy kafolat, Toshkentga yetkazib berish."
    : "Xprinter termoprinterlari katalogi — chek, etiket va mobil printerlar. O'zbekistonda rasmiy distribyutor.")
@section('keywords',    $catName
    ? "{$catName} toshkent, xprinter {$catName}, termoprinter narxi"
    : 'termoprinter katalog, chek printer, etiket printer, mobil printer, xprinter uzbekistan')
@section('og_title',    ($catName ? $catName . ' — ' : '') . 'Xprinter.uz Katalog')
@section('og_description', $catName
    ? "Xprinter {$catName} — O'zbekistonda rasmiy distribyutordan. 12 oy kafolat, tez yetkazib berish."
    : "Xprinter termoprinterlari katalogi O'zbekistonda. Rasmiy distribyutor — chek, etiket va mobil printerlar.")

@push('schema')
@php
    $breadcrumbs = [
        ['@type'=>'ListItem','position'=>1,'name'=>'Bosh sahifa','item'=>url('/')],
        ['@type'=>'ListItem','position'=>2,'name'=>'Katalog','item'=>route('catalog')],
    ];
    if ($activeCategory) {
        $breadcrumbs[] = ['@type'=>'ListItem','position'=>3,'name'=>$catName,'item'=>url()->current()];
    }

    $itemList = $products->map(fn($p) => [
        '@type' => 'ListItem',
        'url'   => route('products.show', [$p->category->slug ?? 'catalog', $p->slug]),
        'name'  => ($p->translations->firstWhere('lang', $locale)
                 ?? $p->translations->firstWhere('lang', 'uz'))?->name ?? $p->model_number,
    ])->values()->all();
@endphp
<script type="application/ld+json">{{ json_encode(['@context'=>'https://schema.org','@type'=>'BreadcrumbList','itemListElement'=>$breadcrumbs], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) }}</script>
@if(count($itemList))
<script type="application/ld+json">{{ json_encode(['@context'=>'https://schema.org','@type'=>'ItemList','name'=>$catName ?? 'Katalog','itemListElement'=>$itemList], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) }}</script>
@endif
@endpush

@section('content')

<div class="pub-page-header">
  <div class="container">
    <h1>Mahsulotlar katalogi</h1>
    <p>Xprinter termoprinterlari — chek, etiket va mobil printerlar</p>
  </div>
</div>

<div class="container">
  <div class="cat-layout">

    {{-- Sidebar --}}
    <aside class="cat-sidebar">
      <form method="GET" action="{{ $activeCategory ? route('catalog.category', $activeCategory->slug) : route('catalog') }}">
        <div class="cat-search">
          <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" name="search" value="{{ $search }}" placeholder="Model yoki nom...">
        </div>

        <div class="cat-sidebar-title">Kategoriyalar</div>

        <a href="{{ route('catalog') }}"
           class="cat-filter-link {{ !$activeCategory ? 'active' : '' }}">
          Barchasi
          <span class="count">{{ $categories->sum('products_count') }}</span>
        </a>

        @foreach($categories as $cat)
        @php $catName = $cat->translations->firstWhere('lang', app()->getLocale())?->name ?? $cat->slug; @endphp
        <a href="{{ route('catalog.category', $cat->slug) }}"
           class="cat-filter-link {{ $activeCategory?->id === $cat->id ? 'active' : '' }}">
          {{ $catName }}
          <span class="count">{{ $cat->products_count }}</span>
        </a>
        @endforeach
      </form>
    </aside>

    {{-- Main --}}
    <div>
      @if($search || $activeCategory)
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px">
        <div style="font-size:13px;color:var(--muted)">
          @if($search)«{{ $search }}» bo'yicha — @endif
          @if($activeCategory){{ $activeCategory->translations->firstWhere('lang', app()->getLocale())?->name ?? $activeCategory->slug }} — @endif
          <strong style="color:var(--ink)">{{ $products->total() }}</strong> ta
        </div>
        <a href="{{ $activeCategory ? route('catalog.category', $activeCategory->slug) : route('catalog') }}"
           style="font-size:12px;color:var(--muted);text-decoration:underline">Tozalash</a>
      </div>
      @endif

      @if($products->isEmpty())
      <div class="pub-empty">
        <svg viewBox="0 0 24 24"><rect x="3" y="7" width="18" height="12" rx="1"/><path d="M7 7V4h10v3"/></svg>
        <h3>Mahsulot topilmadi</h3>
        <p>Boshqa kalit so'z yoki kategoriya tanlang</p>
      </div>
      @else
      <div class="prod-grid">
        @foreach($products as $product)
        @php
          $locale   = app()->getLocale();
          $trans    = $product->translations->firstWhere('lang', $locale)
                   ?? $product->translations->firstWhere('lang', 'uz');
          $catTrans = $product->category?->translations->firstWhere('lang', $locale)
                   ?? $product->category?->translations->firstWhere('lang', 'uz');
        @endphp
        <a href="{{ route('products.show', [$product->category->slug, $product->slug]) }}" class="prod-card">
          <div class="prod-card-img">
            @if($product->photo)
              <img src="{{ Storage::url($product->photo) }}" alt="{{ $trans?->name }}">
            @else
              <svg viewBox="0 0 24 24" style="width:56px;height:56px;stroke:var(--line-hi);fill:none;stroke-width:1.2">
                <rect x="4" y="7" width="16" height="12" rx="1"/>
                <path d="M7 7V4h10v3M8 11h8M8 14h8"/>
              </svg>
            @endif
          </div>
          <div class="prod-card-body">
            @if($catTrans)
            <div class="prod-card-cat">{{ $catTrans->name }}</div>
            @endif
            <div class="prod-card-name">{{ $trans?->name ?? $product->model_number }}</div>
            <div class="prod-card-model">{{ $product->model_number }}</div>
          </div>
          <div class="prod-card-footer">
            <span style="font-size:12px;color:var(--muted)">Batafsil →</span>
            <div class="prod-card-arrow">→</div>
          </div>
        </a>
        @endforeach
      </div>

      @if($products->hasPages())
      <div class="pub-pagination">
        {{ $products->links() }}
      </div>
      @endif
      @endif
    </div>

  </div>
</div>

@endsection
