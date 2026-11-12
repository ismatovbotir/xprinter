@extends('layouts.app')
@php
    $locale = app()->getLocale();
    $name   = $partner->displayName();
    $cities = $partner->cityLabels();
    $phones = collect([$partner->phone])->merge($partner->contacts->pluck('number'))->filter()->unique()->values();
    $products = $partner->companyProducts->pluck('product')->filter();
@endphp
@section('title',       $name . ' — ' . __('partners.title'))
@section('description', __('partners.show_meta', ['name' => $name, 'city' => $cities[0] ?? "O'zbekiston"]))

@push('schema')
<script type="application/ld+json">{!! json_encode(array_filter([
    '@context'  => 'https://schema.org',
    '@type'     => 'LocalBusiness',
    'name'      => $name,
    'url'       => url()->current(),
    'telephone' => $phones->first(),
    'address'   => $partner->addresses->first() ? array_filter([
        '@type'           => 'PostalAddress',
        'streetAddress'   => $partner->addresses->first()->name,
        'addressLocality' => $cities[0] ?? null,
        'addressCountry'  => 'UZ',
    ]) : null,
]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')

<div class="pub-page-header">
  <div class="container">
    <nav class="partner-breadcrumb" aria-label="Breadcrumb">
      <a href="{{ route('partners.index') }}">← {{ __('partners.title') }}</a>
    </nav>
    <h1>{{ $name }}</h1>
    <div class="partner-head-badges">
      @if($partner->isAuthorized())
        <span class="partner-badge partner-badge-auth">✓ {{ __('partners.status_' . $partner->manufacturer_status) }}</span>
      @else
        <span class="partner-badge">{{ __('partners.verified') }}</span>
      @endif
      @foreach($partner->types ?? [] as $t)
        <span class="partner-type partner-type-{{ $t }}">{{ __('partners.type_' . $t) }}</span>
      @endforeach
    </div>
  </div>
</div>

<div class="container">
  <div class="partner-detail">
    <aside class="partner-contact-card" aria-labelledby="partner-contacts-title">
      <h2 id="partner-contacts-title" class="prod-section-title">{{ __('partners.contacts') }}</h2>

      @if($phones->isNotEmpty())
        <ul class="partner-contact-list">
          @foreach($phones as $phone)
            <li><a href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}" class="pub-btn pub-btn-primary partner-call">📞 {{ $phone }}</a></li>
          @endforeach
        </ul>
      @endif

      @if($partner->addresses->isNotEmpty())
        <h3 class="partner-subtitle">{{ __('partners.addresses') }}</h3>
        <ul class="partner-address-list">
          @foreach($partner->addresses as $address)
            @php
              $cityName = $address->city?->translations->firstWhere('lang', $locale)?->name;
            @endphp
            <li>
              <strong>{{ $cityName }}</strong>
              @if($address->name)<span>{{ $address->name }}</span>@endif
              @if($address->description)<span class="partner-muted">{{ $address->description }}</span>@endif
              @if($address->location)
                <a href="https://yandex.uz/maps/?text={{ urlencode($address->location) }}" target="_blank" rel="noopener">{{ __('partners.on_map') }}</a>
              @endif
            </li>
          @endforeach
        </ul>
      @endif

      <p class="partner-note">{{ __('partners.note') }}</p>
    </aside>

    <section aria-labelledby="partner-products-title">
      <h2 id="partner-products-title" class="prod-section-title">{{ __('partners.products', ['count' => $products->count()]) }}</h2>

      @if($products->isEmpty())
        <div class="pub-empty"><p>{{ __('partners.no_products') }}</p></div>
      @else
        <div class="prod-grid">
          @foreach($products as $product)
            @php
              $trans    = $product->translations->firstWhere('lang', $locale) ?? $product->translations->first();
              $catTrans = $product->category?->translations->firstWhere('lang', $locale);
            @endphp
            <a href="{{ route('products.show', [$product->category->slug, $product->slug]) }}" class="prod-card">
              <div class="prod-card-img">
                @if($product->photo)
                  <img src="{{ Storage::url($product->photo) }}" alt="{{ $trans?->name ?? $product->model_number }}" width="400" height="300" loading="lazy" decoding="async">
                @endif
              </div>
              <div class="prod-card-body">
                @if($catTrans)<div class="prod-card-cat">{{ $catTrans->name }}</div>@endif
                <h3 class="prod-card-name">{{ $trans?->name ?? $product->model_number }}</h3>
                <div class="prod-card-model">{{ $product->model_number }}</div>
              </div>
              <div class="prod-card-footer">
                <span class="prod-card-more">{{ __('home.details') }}</span>
                <div class="prod-card-arrow" aria-hidden="true">→</div>
              </div>
            </a>
          @endforeach
        </div>
      @endif
    </section>
  </div>
</div>

<div class="container section-end">
  @include('partials.dealer-cta')
</div>

@endsection
