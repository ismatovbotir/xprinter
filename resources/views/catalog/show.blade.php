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
    : "Xprinter {$product->model_number} — O'zbekistondagi rasmiy distribyutorlardan, 12 oy kafolat")
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

@php
  $productName = $trans?->name ?? $product->model_number;
  $mainPhoto   = $product->photos->first()?->url ?? ($product->photo ? Storage::url($product->photo) : null);
@endphp

<div class="container">
  <div class="prod-detail">

    {{-- LEFT: Image --}}
    <div class="prod-gallery">
      <div class="prod-detail-img" id="prod-main-img">
        @if($mainPhoto)
          <img src="{{ $mainPhoto }}" alt="{{ $productName }}" width="600" height="600" fetchpriority="high" decoding="async">
        @else
          <div class="prod-detail-img-placeholder">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <rect x="4" y="7" width="16" height="12" rx="1"/>
              <path d="M7 7V4h10v3M8 11h8M8 14h8"/>
            </svg>
            <span class="prod-detail-img-model">{{ $product->model_number }}</span>
          </div>
        @endif
      </div>

      @if($product->photos->count() > 1)
      <div class="prod-thumbs">
        @foreach($product->photos as $photo)
        <button type="button" class="prod-thumb {{ $loop->first ? 'active' : '' }}" data-src="{{ $photo->url }}"
                aria-label="{{ __('product.photo_n', ['n' => $loop->iteration]) }}"
                aria-pressed="{{ $loop->first ? 'true' : 'false' }}">
          <img src="{{ $photo->url }}" alt="" width="64" height="64" loading="lazy" decoding="async">
        </button>
        @endforeach
      </div>
      @endif
    </div>

    {{-- RIGHT: Info --}}
    <div class="prod-detail-body">

      {{-- Breadcrumb --}}
      <nav class="prod-breadcrumb" aria-label="{{ __('product.breadcrumb') }}">
        <a href="{{ route('home') }}">{{ __('product.home') }}</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('catalog') }}">{{ __('nav.catalog') }}</a>
        @if($catTrans && $product->category)
        <span aria-hidden="true">/</span>
        <a href="{{ route('catalog.category', $product->category->slug) }}">{{ $catTrans->name }}</a>
        @endif
        <span aria-hidden="true">/</span>
        <span class="prod-breadcrumb-current" aria-current="page">{{ $product->model_number }}</span>
      </nav>

      {{-- Category + model --}}
      @if($catTrans)
      <div class="prod-detail-cat">{{ $catTrans->name }}</div>
      @endif

      <div>
        <h1 class="prod-detail-name">{{ $productName }}</h1>
        <div class="prod-detail-model">{{ $product->model_number }}</div>
      </div>

      {{-- Description --}}
      @if($trans?->description)
      <div class="prod-detail-desc">{{ $trans->description }}</div>
      @endif

      {{-- CTA --}}
      <div class="prod-detail-cta">
        <a href="{{ $siteSettings->telegram_url ?: 'https://t.me/xprinter_admin_bot' }}" target="_blank" rel="noopener" class="pub-btn pub-btn-primary pub-btn-lg">
          {{ __('product.ask_price') }}
        </a>
        @if($siteSettings->phone)
        <a href="tel:{{ preg_replace('/\s+/', '', $siteSettings->phone) }}" class="pub-btn pub-btn-ghost pub-btn-lg">
          {{ __('home.cta_phone_btn') }}
        </a>
        @endif
        @if($product->files->isNotEmpty())
        <a href="#downloads" class="pub-btn pub-btn-ghost pub-btn-lg">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          {{ __('product.downloads') }}
        </a>
        @endif
      </div>

      {{-- Warranty note --}}
      <dl class="prod-facts">
        @foreach([['warranty_val', 'warranty_label'], ['delivery_val', 'delivery_label'], ['parts_val', 'parts_label']] as [$val, $lbl])
        <div class="prod-fact">
          <dt class="prod-fact-label">{{ __("product.$lbl") }}</dt>
          <dd class="prod-fact-val">{{ __("product.$val") }}</dd>
        </div>
        @endforeach
      </dl>

      {{-- Parameters --}}
      @if($paramGroups->isNotEmpty())
      <section class="prod-params" aria-labelledby="prod-specs-title">
        <h2 class="prod-params-title" id="prod-specs-title">{{ __('product.specs') }}</h2>
        <dl>
        @foreach($paramGroups as $parameterId => $values)
        @php
          $param      = $values->first()->parameter;
          $paramName  = $param?->translations->firstWhere('lang', $locale)?->name
                     ?? $param?->translations->firstWhere('lang', 'uz')?->name
                     ?? "#{$parameterId}";
        @endphp
        <div class="prod-param-row">
          <dt class="prod-param-key">{{ $paramName }}</dt>
          <dd class="prod-param-val">
            @foreach($values as $val)
            @php
              $valName = $val->translations->firstWhere('lang', $locale)?->name
                      ?? $val->translations->firstWhere('lang', 'uz')?->name
                      ?? "—";
            @endphp
            <span class="prod-param-tag">{{ $valName }}</span>
            @endforeach
          </dd>
        </div>
        @endforeach
        </dl>
      </section>
      @endif

    </div>
  </div>

  {{-- Downloads --}}
  @php
    $filesByType = $product->files->groupBy(fn($f) => $f->pivot->type);
    $typeLabels  = ['driver'=>'Driver','manual'=>'Manual','spec'=>'Spec','firmware'=>'Firmware','utility'=>'Utility','other'=>'Other'];
    $langLabels  = ['uz'=>'UZ','ru'=>'RU','en'=>'EN'];
  @endphp
  @if($filesByType->isNotEmpty())
  <section class="prod-section" id="downloads" aria-labelledby="downloads-title">
    <h2 class="prod-section-title" id="downloads-title">{{ __('product.downloads') }}</h2>
    <ul class="prod-files">
      @foreach($filesByType as $type => $files)
        @foreach($files as $file)
        <li>
        <a href="{{ $file->url }}" target="_blank" rel="noopener" class="prod-file">
          <div class="prod-file-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
              <line x1="12" y1="18" x2="12" y2="12"/><polyline points="9 15 12 18 15 15"/>
            </svg>
          </div>
          <div class="prod-file-info">
            <div class="prod-file-name">{{ $file->name }}</div>
            <div class="prod-file-meta">
              @if($file->version)<span>v{{ $file->version }}</span>@endif
              {{ $file->size_formatted }}
            </div>
          </div>
          <div class="prod-file-tags">
            <span class="prod-file-type">{{ $typeLabels[$type] ?? strtoupper($type) }}</span>
            <span class="prod-file-lang">{{ $langLabels[$file->pivot->language] ?? strtoupper($file->pivot->language) }}</span>
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
              <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
          </div>
        </a>
        </li>
        @endforeach
      @endforeach
    </ul>
  </section>
  @endif

  {{-- Knowledge base: setup & troubleshooting for this model --}}
  @if(isset($supportArticles) && $supportArticles->isNotEmpty())
  <section class="prod-section" id="support" aria-labelledby="support-title">
    <h2 class="prod-section-title" id="support-title">{{ __('support.product_block_title') }}</h2>
    <div class="kb-product-block">
      @foreach($supportArticles as $article)
        @include('support._card', ['article' => $article])
      @endforeach
    </div>
    <p class="kb-more"><a href="{{ route('support.type', ['type' => 'troubleshooting', 'product' => $product->slug]) }}">{{ __('support.product_block_all') }} →</a></p>
  </section>
  @endif

  {{-- Who sells this model --}}
  <section class="prod-section" aria-labelledby="sellers-title">
    <h2 class="prod-section-title" id="sellers-title">{{ __('partners.sellers_title', ['count' => $sellers->count()]) }}</h2>
    @if($sellers->isEmpty())
      <p class="partner-muted">{{ __('partners.sellers_empty') }} <a href="{{ route('partners.index') }}">{{ __('partners.title') }} →</a></p>
    @else
      <div class="partner-grid">
        @foreach($sellers as $partner)
          @include('partners._card', ['partner' => $partner])
        @endforeach
      </div>
      <p class="partner-sellers-more"><a href="{{ route('partners.index') }}">{{ __('partners.all_partners') }} →</a></p>
    @endif
  </section>

  {{-- B2B: invite dealers / service centers --}}
  <div class="prod-section">
    @include('partials.dealer-cta', ['title' => __('product.dealer_title'), 'text' => __('product.dealer_text')])
  </div>

  {{-- Related products --}}
  @if($related->isNotEmpty())
  <section class="prod-section" aria-labelledby="related-title">
    <h2 class="prod-section-title" id="related-title">{{ __('product.related') }}</h2>
    <div class="prod-grid">
      @foreach($related as $rel)
      @php
        $relTrans   = $rel->translations->firstWhere('lang', $locale) ?? $rel->translations->firstWhere('lang', 'uz');
        $relCatTrans = $rel->category?->translations->firstWhere('lang', $locale) ?? $rel->category?->translations->firstWhere('lang', 'uz');
      @endphp
      <a href="{{ route('products.show', [$rel->category->slug, $rel->slug]) }}" class="prod-card">
        <div class="prod-card-img">
          @if($rel->photo)
            <img src="{{ Storage::url($rel->photo) }}" alt="{{ $relTrans?->name ?? $rel->model_number }}"
                 width="400" height="300" loading="lazy" decoding="async">
          @else
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <rect x="4" y="7" width="16" height="12" rx="1"/><path d="M7 7V4h10v3"/>
            </svg>
          @endif
        </div>
        <div class="prod-card-body">
          @if($relCatTrans)
          <div class="prod-card-cat">{{ $relCatTrans->name }}</div>
          @endif
          <h3 class="prod-card-name">{{ $relTrans?->name ?? $rel->model_number }}</h3>
          <div class="prod-card-model">{{ $rel->model_number }}</div>
        </div>
        <div class="prod-card-footer">
          <span class="prod-card-more">{{ __('home.details') }}</span>
          <div class="prod-card-arrow" aria-hidden="true">→</div>
        </div>
      </a>
      @endforeach
    </div>
  </section>
  @endif

</div>

@push('scripts')
<script>
  // Gallery: swap main image from thumbnails
  document.querySelectorAll('.prod-thumb').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var main = document.querySelector('#prod-main-img img');
      if (main) main.src = btn.dataset.src;
      document.querySelectorAll('.prod-thumb').forEach(function (b) {
        b.classList.toggle('active', b === btn);
        b.setAttribute('aria-pressed', b === btn ? 'true' : 'false');
      });
    });
  });
</script>
@endpush

@endsection
