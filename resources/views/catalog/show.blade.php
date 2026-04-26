@extends('layouts.app')
@php
  $locale = app()->getLocale();
  $trans  = $product->translations->firstWhere('lang', $locale)
         ?? $product->translations->firstWhere('lang', 'uz');
  $catTrans = $product->category?->translations->firstWhere('lang', $locale)
           ?? $product->category?->translations->firstWhere('lang', 'uz');

  $paramGroups = $product->parameterValues->groupBy('parameter_id');
@endphp
@section('title',       $trans?->name ?? $product->model_number)
@section('description', $trans?->description
    ? Str::limit(strip_tags($trans->description), 160)
    : "Xprinter {$product->model_number} — O'zbekistonda rasmiy kafolat bilan sotib oling")
@section('keywords',    "xprinter {$product->model_number}, {$product->model_number} narxi, {$product->model_number} toshkent, termoprinter sotib olish")
@section('og_type',     'product')
@section('og_title',    ($trans?->name ?? $product->model_number) . ' — Xprinter.uz')
@section('og_description', $trans?->description
    ? Str::limit(strip_tags($trans->description), 160)
    : "Xprinter {$product->model_number} — O'zbekistonda rasmiy distribyutordan, 12 oy kafolat")
@section('og_image',    $product->photo ? url(Storage::url($product->photo)) : url('/images/og-default.jpg'))

@push('schema')
@php
    $breadcrumbs = [
        ['@type'=>'ListItem','position'=>1,'name'=>'Bosh sahifa','item'=>url('/')],
        ['@type'=>'ListItem','position'=>2,'name'=>'Katalog','item'=>route('catalog')],
    ];
    if ($product->category) {
        $breadcrumbs[] = ['@type'=>'ListItem','position'=>3,
            'name' => $catTrans?->name ?? $product->category->slug,
            'item' => route('catalog.category', $product->category->slug)];
        $breadcrumbs[] = ['@type'=>'ListItem','position'=>4,
            'name' => $trans?->name ?? $product->model_number,
            'item' => url()->current()];
    } else {
        $breadcrumbs[] = ['@type'=>'ListItem','position'=>3,
            'name' => $trans?->name ?? $product->model_number,
            'item' => url()->current()];
    }

    $productSchema = array_filter([
        '@context'     => 'https://schema.org',
        '@type'        => 'Product',
        'name'         => $trans?->name ?? $product->model_number,
        'description'  => $trans?->description ? strip_tags($trans->description) : null,
        'model'        => $product->model_number,
        'sku'          => $product->model_number,
        'image'        => $product->photo ? url(Storage::url($product->photo)) : null,
        'url'          => url()->current(),
        'brand'        => ['@type'=>'Brand','name'=>'Xprinter'],
        'manufacturer' => ['@type'=>'Organization','name'=>'Xprinter Group','url'=>'https://www.xprintertech.com'],
        'offers'       => [
            '@type'           => 'Offer',
            'availability'    => 'https://schema.org/InStock',
            'url'             => url()->current(),
            'priceCurrency'   => 'UZS',
            'seller'          => ['@type'=>'Organization','name'=>'Xprinter.uz','url'=>url('/')],
            'areaServed'      => ['@type'=>'Country','name'=>'Uzbekistan'],
            'warranty'        => '12 months',
        ],
    ]);
@endphp
<script type="application/ld+json">{{ json_encode($productSchema, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) }}</script>
<script type="application/ld+json">{{ json_encode(['@context'=>'https://schema.org','@type'=>'BreadcrumbList','itemListElement'=>$breadcrumbs], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) }}</script>
@endpush

@section('content')

<div class="container">
  <div class="prod-detail">

    {{-- LEFT: Image --}}
    <div>
      <div class="prod-detail-img">
        @if($product->photo)
          <img src="{{ Storage::url($product->photo) }}" alt="{{ $trans?->name }}">
        @else
          <div class="prod-detail-img-placeholder">
            <svg viewBox="0 0 24 24">
              <rect x="4" y="7" width="16" height="12" rx="1"/>
              <path d="M7 7V4h10v3M8 11h8M8 14h8"/>
            </svg>
            <span style="font-size:12px;font-family:'JetBrains Mono',monospace;letter-spacing:0.05em">{{ $product->model_number }}</span>
          </div>
        @endif
      </div>
    </div>

    {{-- RIGHT: Info --}}
    <div class="prod-detail-body">

      {{-- Breadcrumb --}}
      <div class="prod-breadcrumb">
        <a href="{{ route('home') }}">Bosh sahifa</a>
        <span>/</span>
        <a href="{{ route('catalog') }}">Katalog</a>
        @if($catTrans && $product->category)
        <span>/</span>
        <a href="{{ route('catalog.category', $product->category->slug) }}">{{ $catTrans->name }}</a>
        @endif
        <span>/</span>
        <span style="color:var(--ink-soft)">{{ $product->model_number }}</span>
      </div>

      {{-- Category + model --}}
      @if($catTrans)
      <div class="prod-detail-cat">{{ $catTrans->name }}</div>
      @endif

      <div>
        <div class="prod-detail-name">{{ $trans?->name ?? $product->model_number }}</div>
        <div class="prod-detail-model" style="margin-top:6px">{{ $product->model_number }}</div>
      </div>

      {{-- Description --}}
      @if($trans?->description)
      <div class="prod-detail-desc">{{ $trans->description }}</div>
      @endif

      {{-- Parameters --}}
      @if($paramGroups->isNotEmpty())
      <div class="prod-params">
        <div class="prod-params-title">Texnik xususiyatlar</div>
        @foreach($paramGroups as $parameterId => $values)
        @php
          $param      = $values->first()->parameter;
          $paramName  = $param?->translations->firstWhere('lang', $locale)?->name
                     ?? $param?->translations->firstWhere('lang', 'uz')?->name
                     ?? "Parametr #{$parameterId}";
        @endphp
        <div class="prod-param-row">
          <div class="prod-param-key">{{ $paramName }}</div>
          <div class="prod-param-val">
            @foreach($values as $val)
            @php
              $valName = $val->translations->firstWhere('lang', $locale)?->name
                      ?? $val->translations->firstWhere('lang', 'uz')?->name
                      ?? "—";
            @endphp
            <span class="prod-param-tag">{{ $valName }}</span>
            @endforeach
          </div>
        </div>
        @endforeach
      </div>
      @endif

      {{-- CTA --}}
      <div class="prod-detail-cta">
        <a href="https://t.me/xprinter_uz" target="_blank" class="pub-btn pub-btn-primary">
          Narx so'rash (Telegram)
        </a>
        <a href="tel:+998000000000" class="pub-btn pub-btn-ghost">
          Qo'ng'iroq qilish
        </a>
      </div>

      {{-- Warranty note --}}
      <div style="display:flex;gap:24px;flex-wrap:wrap">
        @foreach([['12 oy', "Rasmiy kafolat O'zbekistonda"], ['1 kun', "Toshkent bo'yicha yetkazish"], ['Mavjud', 'Ehtiyot qismlar']] as [$val, $lbl])
        <div style="display:flex;flex-direction:column;gap:2px">
          <div style="font-family:'JetBrains Mono',monospace;font-size:16px;font-weight:600;color:var(--ink)">{{ $val }}</div>
          <div style="font-size:11px;color:var(--muted)">{{ $lbl }}</div>
        </div>
        @endforeach
      </div>

    </div>
  </div>

  {{-- Related products --}}
  @if($related->isNotEmpty())
  <div style="padding-bottom:64px">
    <div style="font-family:'JetBrains Mono',monospace;font-size:10px;letter-spacing:0.12em;text-transform:uppercase;color:var(--muted);font-weight:600;margin-bottom:20px">
      O'xshash mahsulotlar
    </div>
    <div class="prod-grid">
      @foreach($related as $rel)
      @php
        $relTrans   = $rel->translations->firstWhere('lang', $locale) ?? $rel->translations->firstWhere('lang', 'uz');
        $relCatTrans = $rel->category?->translations->firstWhere('lang', $locale) ?? $rel->category?->translations->firstWhere('lang', 'uz');
      @endphp
      <a href="{{ route('products.show', [$rel->category->slug, $rel->slug]) }}" class="prod-card">
        <div class="prod-card-img">
          @if($rel->photo)
            <img src="{{ Storage::url($rel->photo) }}" alt="{{ $relTrans?->name }}">
          @else
            <svg viewBox="0 0 24 24" style="width:48px;height:48px;stroke:var(--line-hi);fill:none;stroke-width:1.2">
              <rect x="4" y="7" width="16" height="12" rx="1"/><path d="M7 7V4h10v3"/>
            </svg>
          @endif
        </div>
        <div class="prod-card-body">
          @if($relCatTrans)
          <div class="prod-card-cat">{{ $relCatTrans->name }}</div>
          @endif
          <div class="prod-card-name">{{ $relTrans?->name ?? $rel->model_number }}</div>
          <div class="prod-card-model">{{ $rel->model_number }}</div>
        </div>
        <div class="prod-card-footer">
          <span style="font-size:12px;color:var(--muted)">Batafsil →</span>
          <div class="prod-card-arrow">→</div>
        </div>
      </a>
      @endforeach
    </div>
  </div>
  @endif

</div>

@endsection
