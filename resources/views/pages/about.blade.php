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
    <h1>Biz haqimizda</h1>
    <p>Xprinter termoprinterlari uchun O'zbekistondagi distribyutorlar va dilerlar platformasi</p>
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
      <div class="section-tag">// Qanday ishlaydi</div>
      <div class="section-title">Platforma qanday tuzilgan</div>
      <div class="section-sub">Xprinter.uz — bitta kompaniya emas, balki bir nechta tekshirilgan distribyutor va diler kompaniyalar joylashgan bozor maydonchasi.</div>
    </div>

    <div class="why-grid">
      <div class="why-card">
        <div class="why-icon" style="background:#EAF2FD">
          <svg viewBox="0 0 24 24" style="stroke:var(--blue)"><rect x="4" y="6" width="16" height="14" rx="2"/><path d="M8 6V4h8v2"/></svg>
        </div>
        <div class="why-title">Katalogdan tanlang</div>
        <div class="why-text">Barcha Xprinter modellari — texnik xususiyatlari va narxlari bilan bitta katalogda.</div>
      </div>
      <div class="why-card">
        <div class="why-icon" style="background:#F0FDF8">
          <svg viewBox="0 0 24 24" style="stroke:var(--green)"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div class="why-title">Distribyutorni tanlang</div>
        <div class="why-text">Har bir mahsulot narxi va mavjudligi — sizga eng yaqin tasdiqlangan hamkor tomonidan belgilanadi.</div>
      </div>
      <div class="why-card">
        <div class="why-icon" style="background:#FFF7ED">
          <svg viewBox="0 0 24 24" style="stroke:#F97316"><path d="M20 6L9 17l-5-5"/></svg>
        </div>
        <div class="why-title">Kafolat bilan xarid qiling</div>
        <div class="why-text">Har bir buyurtma — rasmiy kafolat, servis va o'rnatilgan aloqa kanallari orqali amalga oshiriladi.</div>
      </div>
    </div>
  </div>
</section>

@endsection
