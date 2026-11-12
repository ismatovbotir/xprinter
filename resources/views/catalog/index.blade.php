@extends('layouts.app')
@php
    $locale  = app()->getLocale();
    $catName = $activeCategory
        ? ($activeCategory->translations->firstWhere('lang', $locale)?->name ?? $activeCategory->slug)
        : null;

    $metaTitle = $catName ?? 'Katalog';
    $metaDesc  = $catName
        ? "Xprinter {$catName} O'zbekistonda — rasmiy distribyutorlar tarmog'idan sotib oling. 12 oy kafolat, Toshkentga yetkazib berish."
        : "Xprinter termoprinterlari katalogi — chek, etiket va mobil printerlar. O'zbekistondagi rasmiy distribyutorlar va dilerlar tarmog'i.";
    $metaKeys  = $catName
        ? "{$catName} toshkent, xprinter {$catName}, termoprinter narxi"
        : 'termoprinter katalog, chek printer, etiket printer, mobil printer, xprinter uzbekistan';
    $ogTitle   = ($catName ? $catName . ' — ' : '') . 'Xprinter.uz Katalog';
    $ogDesc    = $catName
        ? "Xprinter {$catName} — O'zbekistondagi rasmiy distribyutorlardan. 12 oy kafolat, tez yetkazib berish."
        : "Xprinter termoprinterlari katalogi O'zbekistonda. Rasmiy distribyutorlar va dilerlar tarmog'i — chek, etiket va mobil printerlar.";
@endphp
@section('title',          $metaTitle)
@section('description',    $metaDesc)
@section('keywords',       $metaKeys)
@section('og_title',       $ogTitle)
@section('og_description', $ogDesc)

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
    <h1>{{ $activeCategory ? $catName : __('catalog.title') }}</h1>
    <p>{{ __('catalog.sub') }}</p>
  </div>
</div>

<div class="container">
  <div class="cat-layout">

    {{-- Sidebar --}}
    <aside class="cat-sidebar">
      <form method="GET" action="{{ $activeCategory ? route('catalog.category', $activeCategory->slug) : route('catalog') }}" role="search">
        <div class="cat-search">
          <label for="cat-search-input" class="sr-only">{{ __('catalog.search_label') }}</label>
          <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="search" id="cat-search-input" name="search" value="{{ $search }}"
                 placeholder="{{ __('catalog.search_placeholder') }}" enterkeyhint="search" autocomplete="off">
        </div>

        <fieldset class="cat-sort">
          <legend class="cat-sidebar-title">{{ __('catalog.sort') }}</legend>
          @foreach(['newest' => __('catalog.sort_newest'), 'name' => __('catalog.sort_name'), 'model' => __('catalog.sort_model')] as $val => $label)
          <label class="cat-sort-option {{ $sort === $val ? 'active' : '' }}">
            <input type="radio" name="sort" value="{{ $val }}" {{ $sort === $val ? 'checked' : '' }} onchange="this.form.submit()">
            {{ $label }}
          </label>
          @endforeach
        </fieldset>
      </form>

      <nav aria-label="{{ __('catalog.categories') }}">
        <div class="cat-sidebar-title">{{ __('catalog.categories') }}</div>
        <div class="cat-filter-list">
          <a href="{{ route('catalog') }}"
             class="cat-filter-link {{ !$activeCategory ? 'active' : '' }}"
             @if(!$activeCategory) aria-current="page" @endif>
            {{ __('common.all') }}
            <span class="count">{{ $categories->sum('products_count') }}</span>
          </a>

          @foreach($categories as $cat)
          @php $sideName = $cat->translations->firstWhere('lang', $locale)?->name ?? $cat->slug; @endphp
          <a href="{{ route('catalog.category', $cat->slug) }}"
             class="cat-filter-link {{ $activeCategory?->id === $cat->id ? 'active' : '' }}"
             @if($activeCategory?->id === $cat->id) aria-current="page" @endif>
            {{ $sideName }}
            <span class="count">{{ $cat->products_count }}</span>
          </a>
          @endforeach
        </div>
      </nav>
    </aside>

    {{-- Main --}}
    <div>
      @if($search || $activeCategory)
      <div class="cat-results-bar">
        <div class="cat-results-count" aria-live="polite">
          @if($search){{ __('catalog.results_for', ['q' => $search]) }} — @endif
          @if($activeCategory){{ $catName }} — @endif
          <strong>{{ __('catalog.found', ['count' => $products->total()]) }}</strong>
        </div>
        @if($search)
        <a href="{{ $activeCategory ? route('catalog.category', $activeCategory->slug) : route('catalog') }}" class="cat-clear">{{ __('catalog.clear') }}</a>
        @endif
      </div>
      @endif

      @if($products->isEmpty())
      <div class="pub-empty">
        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="7" width="18" height="12" rx="1"/><path d="M7 7V4h10v3"/></svg>
        <h2>{{ __('catalog.empty_title') }}</h2>
        <p>{{ __('catalog.empty_text') }}</p>
      </div>
      @else
      <div class="prod-grid">
        @foreach($products as $product)
        @php
          $trans    = $product->translations->firstWhere('lang', $locale)
                   ?? $product->translations->firstWhere('lang', 'uz');
          $catTrans = $product->category?->translations->firstWhere('lang', $locale)
                   ?? $product->category?->translations->firstWhere('lang', 'uz');
        @endphp
        <a href="{{ route('products.show', [$product->category->slug, $product->slug]) }}" class="prod-card">
          <div class="prod-card-img">
            @if($product->photo)
              <img src="{{ Storage::url($product->photo) }}" alt="{{ $trans?->name ?? $product->model_number }}"
                   width="400" height="300" @if($loop->index >= 3) loading="lazy" @endif decoding="async">
            @else
              <svg viewBox="0 0 24 24" aria-hidden="true">
                <rect x="4" y="7" width="16" height="12" rx="1"/>
                <path d="M7 7V4h10v3M8 11h8M8 14h8"/>
              </svg>
            @endif
          </div>
          <div class="prod-card-body">
            @if($catTrans)
            <div class="prod-card-cat">{{ $catTrans->name }}</div>
            @endif
            <h2 class="prod-card-name">{{ $trans?->name ?? $product->model_number }}</h2>
            <div class="prod-card-model">{{ $product->model_number }}</div>
          </div>
          <div class="prod-card-footer">
            <span class="prod-card-more">{{ __('home.details') }}</span>
            <div class="prod-card-arrow" aria-hidden="true">→</div>
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

<div class="container section-end">
  @include('partials.dealer-cta')
</div>

@endsection
