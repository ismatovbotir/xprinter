@extends('layouts.app')
@section('title', 'Biz haqimizda')
@section('description', "Xprinter.uz — Xprinter termoprinterlarining O'zbekistondagi rasmiy distribyutorlari va dilerlari platformasi. Sertifikatlangan hamkorlar, kafolat va servis.")
@section('og_title', "Biz haqimizda — Xprinter.uz")
@section('og_description', "Xprinter.uz — Xprinter termoprinterlarining O'zbekistondagi rasmiy distribyutorlari va dilerlari platformasi.")

@push('schema')
<script type="application/ld+json">{{ json_encode(['@context'=>'https://schema.org','@type'=>'BreadcrumbList','itemListElement'=>[
    ['@type'=>'ListItem','position'=>1,'name'=>'Bosh sahifa','item'=>url('/')],
    ['@type'=>'ListItem','position'=>2,'name'=>'Biz haqimizda','item'=>url()->current()],
]], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) }}</script>
@endpush

@section('content')

<div class="pub-page-header">
  <div class="container">
    <h1>{{ __('nav.about') }}</h1>
    <p>{{ __('about_page.page_sub') }}</p>
  </div>
</div>

<section class="section">
  <div class="container">
    @include('partials.about-section', ['content' => $content])
  </div>
</section>

<section class="section" style="padding-top:0">
  <div class="container">
    <div class="section-header">
      <div class="section-tag">{{ __('about_page.how_tag') }}</div>
      <h2 class="section-title">{{ __('about_page.how_title') }}</h2>
      <div class="section-sub">{{ __('about_page.how_sub') }}</div>
    </div>

    <div class="why-grid">
      <div class="why-card">
        <div class="why-icon" style="background:#EAF2FD" aria-hidden="true">
          <svg viewBox="0 0 24 24" style="stroke:var(--blue)"><rect x="4" y="6" width="16" height="14" rx="2"/><path d="M8 6V4h8v2"/></svg>
        </div>
        <h3 class="why-title">{{ __('about_page.step1_title') }}</h3>
        <div class="why-text">{{ __('about_page.step1_text') }}</div>
      </div>
      <div class="why-card">
        <div class="why-icon" style="background:#F0FDF8" aria-hidden="true">
          <svg viewBox="0 0 24 24" style="stroke:var(--green)"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <h3 class="why-title">{{ __('about_page.step2_title') }}</h3>
        <div class="why-text">{{ __('about_page.step2_text') }}</div>
      </div>
      <div class="why-card">
        <div class="why-icon" style="background:#FFF7ED" aria-hidden="true">
          <svg viewBox="0 0 24 24" style="stroke:#F97316"><path d="M20 6L9 17l-5-5"/></svg>
        </div>
        <h3 class="why-title">{{ __('about_page.step3_title') }}</h3>
        <div class="why-text">{{ __('about_page.step3_text') }}</div>
      </div>
    </div>
  </div>
</section>

<section class="section" style="padding-top:0">
  <div class="container">
    @include('partials.dealer-cta')
  </div>
</section>

@endsection
