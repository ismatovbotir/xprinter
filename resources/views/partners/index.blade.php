@extends('layouts.app')
@section('title',       __('partners.title'))
@section('description', __('partners.meta_description'))
@section('og_title',    __('partners.title') . ' — Xprinter.uz')
@section('og_description', __('partners.meta_description'))

@section('content')

<div class="pub-page-header">
  <div class="container">
    <h1>{{ __('partners.title') }}</h1>
    <p>{{ __('partners.sub') }}</p>
  </div>
</div>

<div class="container">
  <form method="GET" action="{{ route('partners.index') }}" class="partner-filters" role="search">
    <div class="partner-filter-types" role="group" aria-label="{{ __('partners.types_label') }}">
      <a href="{{ route('partners.index', array_filter(['region' => $regionId, 'search' => $search])) }}"
         @class(['partner-chip', 'active' => !$type]) @if(!$type) aria-current="true" @endif>{{ __('common.all') }}</a>
      @foreach(\App\Http\Controllers\PartnerDirectoryController::TYPES as $t)
        <a href="{{ route('partners.index', array_filter(['type' => $t, 'region' => $regionId, 'search' => $search])) }}"
           @class(['partner-chip', 'active' => $type === $t]) @if($type === $t) aria-current="true" @endif>{{ __('partners.type_' . $t) }}</a>
      @endforeach
    </div>

    <div class="partner-filter-row">
      @if($type)<input type="hidden" name="type" value="{{ $type }}">@endif
      <label for="partner-region" class="sr-only">{{ __('partners.region') }}</label>
      <select id="partner-region" name="region" class="partner-select" onchange="this.form.submit()">
        <option value="">{{ __('partners.all_regions') }}</option>
        @foreach($regions as $region)
          <option value="{{ $region->id }}" @selected($regionId === $region->id)>
            {{ $region->translations->firstWhere('lang', app()->getLocale())?->name ?? $region->translations->first()?->name }}
          </option>
        @endforeach
      </select>

      <div class="cat-search partner-search">
        <label for="partner-search-input" class="sr-only">{{ __('partners.search_label') }}</label>
        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="search" id="partner-search-input" name="search" value="{{ $search }}"
               placeholder="{{ __('partners.search_placeholder') }}" enterkeyhint="search" autocomplete="off">
      </div>
    </div>
  </form>

  <div class="cat-results-count partner-count" aria-live="polite">
    <strong>{{ __('partners.found', ['count' => $partners->total()]) }}</strong>
    @if($type || $regionId || $search)
      · <a href="{{ route('partners.index') }}" class="cat-clear">{{ __('catalog.clear') }}</a>
    @endif
  </div>

  @if($partners->isEmpty())
    <div class="pub-empty">
      <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
      <h2>{{ __('partners.empty_title') }}</h2>
      <p>{{ __('partners.empty_text') }}</p>
    </div>
  @else
    <div class="partner-grid">
      @foreach($partners as $partner)
        @include('partners._card', ['partner' => $partner])
      @endforeach
    </div>

    @if($partners->hasPages())
      <div class="pub-pagination">{{ $partners->links() }}</div>
    @endif
  @endif
</div>

<div class="container section-end">
  @include('partials.dealer-cta', ['title' => __('partners.cta_title'), 'text' => __('partners.cta_text')])
</div>

@endsection
