@extends('layouts.app')
@section('title', 'Aloqa')
@section('description', "Xprinter.uz bilan bog'laning — telefon, Telegram, WhatsApp yoki email orqali. Ish vaqti va manzil.")
@section('og_title', "Aloqa — Xprinter.uz")
@section('og_description', "Xprinter.uz bilan bog'laning — telefon, Telegram, WhatsApp yoki email orqali.")

@push('schema')
<script type="application/ld+json">{{ json_encode(['@context'=>'https://schema.org','@type'=>'BreadcrumbList','itemListElement'=>[
    ['@type'=>'ListItem','position'=>1,'name'=>'Bosh sahifa','item'=>url('/')],
    ['@type'=>'ListItem','position'=>2,'name'=>'Aloqa','item'=>url()->current()],
]], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) }}</script>
@endpush

@section('content')

<div class="pub-page-header">
  <div class="container">
    <h1>{{ __('contact_page.heading') }}</h1>
    <p>{{ __('contact_page.sub') }}</p>
  </div>
</div>

<section class="section">
  <div class="container">
    <div class="why-grid">

      @if($siteSettings->phone)
      <a href="tel:{{ preg_replace('/\s+/', '', $siteSettings->phone) }}" class="why-card" style="text-decoration:none">
        <div class="why-icon" style="background:#EAF2FD">
          <svg viewBox="0 0 24 24" style="stroke:var(--blue)"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        </div>
        <div class="why-title">{{ __('contact_page.phone_label') }}</div>
        <div class="why-text">{{ $siteSettings->phone }}</div>
      </a>
      @endif

      <a href="{{ $siteSettings->telegram_url ?: 'https://t.me/xprinter_admin_bot' }}" target="_blank" class="why-card" style="text-decoration:none">
        <div class="why-icon" style="background:#F0FDF8">
          <svg viewBox="0 0 24 24" style="stroke:var(--green)"><path d="M21 5L2 12.5l7 1M21 5l-2.5 15L9 13.5M21 5L9 13.5m0 0V19l3.3-3"/></svg>
        </div>
        <div class="why-title">{{ __('contact_page.telegram_label') }}</div>
        <div class="why-text">{{ '@' . ($siteSettings->telegram ?: 'xprinter_admin_bot') }}</div>
      </a>

      @if($siteSettings->whatsapp_url)
      <a href="{{ $siteSettings->whatsapp_url }}" target="_blank" class="why-card" style="text-decoration:none">
        <div class="why-icon" style="background:#FFF7ED">
          <svg viewBox="0 0 24 24" style="stroke:#F97316"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
        </div>
        <div class="why-title">{{ __('contact_page.whatsapp_label') }}</div>
        <div class="why-text">{{ $siteSettings->whatsapp }}</div>
      </a>
      @endif

      <a href="mailto:{{ $siteSettings->email ?: 'info@xprinter.uz' }}" class="why-card" style="text-decoration:none">
        <div class="why-icon" style="background:#EAF2FD">
          <svg viewBox="0 0 24 24" style="stroke:var(--blue)"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><polyline points="22 6 12 13 2 6"/></svg>
        </div>
        <div class="why-title">{{ __('contact_page.email_label') }}</div>
        <div class="why-text">{{ $siteSettings->email ?: 'info@xprinter.uz' }}</div>
      </a>

      <div class="why-card">
        <div class="why-icon" style="background:#F0FDF8">
          <svg viewBox="0 0 24 24" style="stroke:var(--green)"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        </div>
        <div class="why-title">{{ __('contact_page.address_label') }}</div>
        <div class="why-text">{{ $siteSettings->address ?: __('footer.address_fallback') }}</div>
      </div>

      <div class="why-card">
        <div class="why-icon" style="background:#FFF7ED">
          <svg viewBox="0 0 24 24" style="stroke:#F97316"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="why-title">{{ __('contact_page.hours_label') }}</div>
        <div class="why-text">{{ $siteSettings->work_time_display ?: __('footer.hours_fallback') }}</div>
      </div>

    </div>
  </div>
</section>

@endsection
