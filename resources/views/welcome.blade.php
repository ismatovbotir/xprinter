@extends('layouts.app')
@section('title', "Xprinter.uz — distribyutorlar va dilerlar platformasi")
@section('description', "Termoprinterlari Xprinter — chek, etiket va mobil printerlar. O'zbekistondagi rasmiy distribyutorlar va dilerlar tarmog'i, 12 oy kafolat, servis markazlari.")

@section('og_title',       "Xprinter.uz — distribyutorlar va dilerlar platformasi")
@section('og_description', "Termoprinterlari Xprinter O'zbekistonda — chek, etiket va mobil printerlar. Rasmiy distribyutorlar va dilerlar tarmog'i, 12 oy kafolat.")
@section('keywords',       'termoprinter toshkent, xprinter uzbekistan, chek printer sotib olish, etiket printer narxi, termoprinter kafolat')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "WebSite",
    "name": "Xprinter.uz",
    "url": "{{ url('/') }}",
    "description": "Xprinter termoprinterlari uchun O'zbekistondagi rasmiy distribyutorlar va dilerlar platformasi",
    "potentialAction": {
        "@type": "SearchAction",
        "target": {
            "@type": "EntryPoint",
            "urlTemplate": "{{ route('catalog') }}?search={search_term_string}"
        },
        "query-input": "required name=search_term_string"
    }
}
</script>
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Xprinter.uz — Bosh sahifa",
    "url": "{{ url('/') }}",
    "breadcrumb": {
        "@type": "BreadcrumbList",
        "itemListElement": [
            {"@type": "ListItem", "position": 1, "name": "Bosh sahifa", "item": "{{ url('/') }}"}
        ]
    }
}
</script>
@endpush

@push('styles')
<style>
/* remove footer top gap — CTA already dark */
.pub-footer { margin-top: 0 !important; }
</style>
@endpush

@section('content')

{{-- ═══════════════ HERO ═══════════════ --}}
<section class="hero">
    <div class="hero-dots"></div>
    <div class="hero-glow"></div>
    <div class="hero-glow-r"></div>

    <div class="container">
        <div class="hero-inner">

            {{-- Left --}}
            @php
                $badges = array_filter($content->badges()) ?: [__('home.badge_warranty'), __('home.badge_distributors'), __('home.badge_delivery')];
            @endphp
            <div class="hero-left">
                <div class="hero-tag">
                    <span class="hero-tag-dot"></span>
                    {{ $content->hero_tag ?: __('home.hero_tag') }}
                </div>

                <h1 class="hero-headline">
                    {{ $content->hero_line1 ?: 'Xprinter' }}<br>
                    <em>{{ $content->hero_line2 ?: __('home.hero_line2') }}</em><br>
                    {{ $content->hero_line3 ?: __('home.hero_line3') }}
                </h1>

                <p class="hero-sub">
                    {{ $content->hero_subtitle ?: __('home.hero_subtitle') }}
                </p>

                <div class="hero-attrs">
                    @foreach($badges as $badge)
                    <span class="hero-attr">
                        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ $badge }}
                    </span>
                    @endforeach
                </div>

                <div class="hero-btns">
                    <a href="{{ route('catalog') }}" class="pub-btn pub-btn-primary pub-btn-lg">
                        {{ __('home.cta_catalog') }}
                    </a>
                    @guest
                    <a href="{{ route('register') }}" class="pub-btn pub-btn-ghost pub-btn-lg">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                        {{ __('home.cta_dealer') }}
                    </a>
                    @else
                    <a href="{{ auth()->user()->redirectPath() }}" class="pub-btn pub-btn-ghost pub-btn-lg">{{ __('nav.cabinet') }}</a>
                    @endguest
                </div>
            </div>

            {{-- Right: product carousel (products picked in Admin → Bosh sahifa). Swipe / arrows / pills. --}}
            @if(count($heroSlides))
            <div class="hero-spec" data-hero-carousel aria-roledescription="carousel" aria-label="{{ __('home.hero_models') }}">
                <div class="hero-spec-bar">
                    <div class="spec-bar-dots" aria-hidden="true">
                        <span style="background:#FF5F56"></span>
                        <span style="background:#FEBC2E"></span>
                        <span style="background:#28C840"></span>
                    </div>
                    <span class="spec-bar-path">xprinter.uz › catalog › product.scan</span>
                    @if(count($heroSlides) > 1)
                    <div class="spec-nav">
                        <button type="button" class="spec-nav-btn" data-prev aria-label="‹">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>
                        </button>
                        <button type="button" class="spec-nav-btn" data-next aria-label="›">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                    </div>
                    @endif
                </div>

                <div class="spec-track" data-track tabindex="0">
                    @foreach($heroSlides as $i => $slide)
                    <a href="{{ $slide['url'] }}" class="spec-slide" role="group" aria-roledescription="slide"
                       aria-label="{{ $i + 1 }} / {{ count($heroSlides) }}: {{ $slide['model'] }}">
                        <div class="spec-row">
                            <span class="spec-key">MODEL</span>
                            <span class="spec-val blue">{{ $slide['model'] }}</span>
                        </div>
                        @if($slide['type'])
                        <div class="spec-row">
                            <span class="spec-key">TYPE</span>
                            <span class="spec-val">{{ $slide['type'] }}</span>
                        </div>
                        @endif
                        @foreach($slide['specs'] as $key => $value)
                        <div class="spec-row">
                            <span class="spec-key">{{ $key }}</span>
                            <span class="spec-val">{{ $value }}</span>
                        </div>
                        @endforeach
                        <div class="spec-row">
                            <span class="spec-key">STATUS</span>
                            @if($slide['sellers'])
                            <span class="spec-val green">{{ __('home.hero_in_stock', ['n' => $slide['sellers']]) }}</span>
                            @else
                            <span class="spec-val">{{ __('home.hero_on_request') }}</span>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>

                <div class="spec-footer">
                    <span class="spec-footer-label">{{ __('home.hero_models') }}</span>
                    <div class="spec-pills">
                        @foreach($heroSlides as $i => $slide)
                        <button type="button" class="spec-pill{{ $i === 0 ? ' is-active' : '' }}" data-goto="{{ $i }}"
                                aria-current="{{ $i === 0 ? 'true' : 'false' }}">{{ $slide['model'] }}</button>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</section>

{{-- ═══════════════ STATS ═══════════════ --}}
@php
    $stats = collect($content->statList())->filter(fn($s) => !empty($s['value']))->values();
    if ($stats->isEmpty()) {
        $stats = collect([
            ['value' => '5',    'suffix' => '+', 'label' => __('home.stat_years')],
            ['value' => '1000', 'suffix' => '+', 'label' => __('home.stat_installs')],
            ['value' => '24',   'suffix' => 'h', 'label' => __('home.stat_delivery')],
            ['value' => '12',   'suffix' => '',  'label' => __('home.stat_warranty')],
        ]);
    }
@endphp
<div class="stats-strip">
    <div class="container">
        <div class="stats-grid">
            @foreach($stats as $stat)
            <div class="stat-cell">
                <div class="stat-num">{{ $stat['value'] }}<span>{{ $stat['suffix'] }}</span></div>
                <div class="stat-label">{{ $stat['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ═══════════════ FOR DEALERS & SERVICE CENTERS (B2B) ═══════════════ --}}
<section class="section dealers" id="dealers" aria-labelledby="dealers-title">
    <div class="container">
        <div class="dealers-inner">
            <div class="dealers-intro">
                <div class="section-tag">{{ __('home.dealers_tag') }}</div>
                <h2 class="section-title" id="dealers-title">{{ __('home.dealers_title') }}</h2>
                <p class="section-sub">{{ __('home.dealers_sub') }}</p>

                <div class="dealer-benefits">
                    @foreach([
                        ['benefit1', '<rect x="3" y="4" width="18" height="16" rx="2"/><line x1="7" y1="9" x2="17" y2="9"/><line x1="7" y1="13" x2="14" y2="13"/>'],
                        ['benefit2', '<line x1="12" y1="2" x2="12" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>'],
                        ['benefit3', '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>'],
                        ['benefit4', '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/>'],
                    ] as [$key, $icon])
                    <div class="dealer-benefit">
                        <div class="dealer-benefit-icon"><svg viewBox="0 0 24 24" aria-hidden="true">{!! $icon !!}</svg></div>
                        <div>
                            <h3 class="dealer-benefit-title">{{ __("home.{$key}_title") }}</h3>
                            <p class="dealer-benefit-text">{{ __("home.{$key}_text") }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="dealer-steps">
                <h3 class="dealer-steps-title">{{ __('home.steps_title') }}</h3>
                <ol class="dealer-steps-list">
                    @foreach([1, 2, 3] as $n)
                    <li class="dealer-step">
                        <span class="dealer-step-num" aria-hidden="true">0{{ $n }}</span>
                        <div>
                            <div class="dealer-step-title">{{ __("home.step{$n}_title") }}</div>
                            <div class="dealer-step-text">{{ __("home.step{$n}_text") }}</div>
                        </div>
                    </li>
                    @endforeach
                </ol>
                @guest
                <a href="{{ route('register') }}" class="pub-btn pub-btn-primary pub-btn-lg pub-btn-block">{{ __('home.cta_dealer') }} →</a>
                @else
                <a href="{{ auth()->user()->redirectPath() }}" class="pub-btn pub-btn-primary pub-btn-lg pub-btn-block">{{ __('nav.cabinet') }} →</a>
                @endguest
                <a href="{{ $siteSettings->telegram_url ?: 'https://t.me/xprinter_admin_bot' }}" target="_blank" rel="noopener" class="dealer-steps-link">
                    {{ __('home.dealers_question') }}
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════ CATEGORIES ═══════════════ --}}
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-tag">{{ __('home.categories_tag') }}</div>
            <h2 class="section-title">{{ __('home.categories_title') }}</h2>
            <div class="section-sub">{{ __('home.categories_sub') }}</div>
        </div>

        <div class="home-cat-grid">
            @foreach($categories as $cat)
            @php
                $locale  = app()->getLocale();
                $catName = $cat->translations->firstWhere('lang', $locale)?->name
                        ?? $cat->translations->firstWhere('lang', 'uz')?->name
                        ?? $cat->slug;
            @endphp
            <a href="{{ route('catalog.category', $cat->slug) }}" class="home-cat-card">
                <div class="home-cat-icon" aria-hidden="true">
                    @switch($cat->slug)
                        @case('receipt-printers')
                            <svg viewBox="0 0 24 24"><rect x="4" y="6" width="16" height="14" rx="2"/><path d="M8 6V4h8v2"/><line x1="8" y1="11" x2="16" y2="11"/><line x1="8" y1="14" x2="13" y2="14"/><path d="M4 20l2-2 2 2 2-2 2 2 2-2 2 2"/></svg>
                            @break
                        @case('label-printers')
                            <svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg>
                            @break
                        @case('mobile-printers')
                            <svg viewBox="0 0 24 24"><rect x="7" y="2" width="10" height="20" rx="2"/><circle cx="12" cy="18" r="1" fill="currentColor" stroke="none"/><line x1="9" y1="7" x2="15" y2="7"/><line x1="9" y1="10" x2="13" y2="10"/></svg>
                            @break
                        @case('wristband-printers')
                            <svg viewBox="0 0 24 24"><rect x="5" y="8" width="14" height="10" rx="2"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/><path d="M9 18v1a3 3 0 0 0 6 0v-1"/></svg>
                            @break
                        @default
                            <svg viewBox="0 0 24 24"><rect x="3" y="7" width="18" height="12" rx="1"/><path d="M7 7V4h10v3"/></svg>
                    @endswitch
                </div>

                <div class="home-cat-name">{{ $catName }}</div>

                <div class="home-cat-meta">
                    <span class="home-cat-count">{{ __('home.models_count', ['count' => $cat->products_count]) }}</span>
                    <span class="cat-arrow" aria-hidden="true">→</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════ POPULAR PRODUCTS ═══════════════ --}}
@if($featured->isNotEmpty())
<section class="section section-soft">
    <div class="container">
        <div class="section-header section-header-row">
            <div>
                <div class="section-tag">{{ __('home.popular_tag') }}</div>
                <h2 class="section-title">{{ __('home.popular_title') }}</h2>
            </div>
            <a href="{{ route('catalog') }}" class="pub-btn pub-btn-ghost">
                {{ __('home.view_all') }}
            </a>
        </div>

        <div class="prod-grid">
            @foreach($featured as $product)
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
                        <img src="{{ Storage::url($product->photo) }}" alt="{{ $trans?->name ?? $product->model_number }}"
                             width="400" height="300" loading="lazy" decoding="async">
                    @else
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <rect x="4" y="7" width="16" height="12" rx="1"/>
                            <path d="M7 7V4h10v3M8 11h8M8 14h5"/>
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
                    <span class="prod-card-more">{{ __('home.details') }}</span>
                    <div class="prod-card-arrow" aria-hidden="true">→</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════ WHY US / ABOUT ═══════════════ --}}
<section class="section" id="about">
    <div class="container">
        @include('partials.about-section', ['content' => $content])
    </div>
</section>

{{-- ═══════════════ SCENARIOS ═══════════════ --}}
<section class="section" style="padding-top:0">
    <div class="container">
        <div class="section-header">
            <div class="section-tag">{{ __('home.scenarios_tag') }}</div>
            <h2 class="section-title">{{ __('home.scenarios_title') }}</h2>
            <div class="section-sub">{{ __('home.scenarios_sub') }}</div>
        </div>

        <div class="scenario-grid">
            <div class="scenario-card">
                <div class="scenario-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <div class="scenario-name">{{ __('home.scenario_shop') }}</div>
                @if(!empty($scenarioModels['shop']))<div class="scenario-models">@foreach($scenarioModels['shop'] as $m)<a href="{{ $m['url'] }}">{{ $m['model'] }}</a>@if(!$loop->last) · @endif @endforeach</div>@endif
            </div>

            <div class="scenario-card">
                <div class="scenario-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
                </div>
                <div class="scenario-name">{{ __('home.scenario_cafe') }}</div>
                @if(!empty($scenarioModels['cafe']))<div class="scenario-models">@foreach($scenarioModels['cafe'] as $m)<a href="{{ $m['url'] }}">{{ $m['model'] }}</a>@if(!$loop->last) · @endif @endforeach</div>@endif
            </div>

            <div class="scenario-card">
                <div class="scenario-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24"><path d="M3 9a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9z"/><path d="M8 9V7a4 4 0 0 1 8 0v2"/><line x1="12" y1="13" x2="12" y2="17"/></svg>
                </div>
                <div class="scenario-name">{{ __('home.scenario_pharmacy') }}</div>
                @if(!empty($scenarioModels['pharmacy']))<div class="scenario-models">@foreach($scenarioModels['pharmacy'] as $m)<a href="{{ $m['url'] }}">{{ $m['model'] }}</a>@if(!$loop->last) · @endif @endforeach</div>@endif
            </div>

            <div class="scenario-card">
                <div class="scenario-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 4v4h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
                <div class="scenario-name">{{ __('home.scenario_marketplace') }}</div>
                @if(!empty($scenarioModels['marketplace']))<div class="scenario-models">@foreach($scenarioModels['marketplace'] as $m)<a href="{{ $m['url'] }}">{{ $m['model'] }}</a>@if(!$loop->last) · @endif @endforeach</div>@endif
            </div>

            <div class="scenario-card">
                <div class="scenario-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                </div>
                <div class="scenario-name">{{ __('home.scenario_medical') }}</div>
                @if(!empty($scenarioModels['medical']))<div class="scenario-models">@foreach($scenarioModels['medical'] as $m)<a href="{{ $m['url'] }}">{{ $m['model'] }}</a>@if(!$loop->last) · @endif @endforeach</div>@endif
            </div>

            <div class="scenario-card">
                <div class="scenario-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div class="scenario-name">{{ __('home.scenario_courier') }}</div>
                @if(!empty($scenarioModels['courier']))<div class="scenario-models">@foreach($scenarioModels['courier'] as $m)<a href="{{ $m['url'] }}">{{ $m['model'] }}</a>@if(!$loop->last) · @endif @endforeach</div>@endif
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════ CERTS ═══════════════ --}}
<div class="certs-bar">
    <div class="container">
        <div class="certs-inner">
            <span class="certs-label">{{ __('home.certs_label') }}</span>
            <span class="cert-pill">ISO 9001:2015</span>
            <span class="cert-pill">CE</span>
            <span class="cert-pill">FCC</span>
            <span class="cert-pill">RoHS</span>
            <span class="cert-pill">Xprinter Group · 2006</span>
            <span class="cert-pill">{{ __('home.certs_countries') }}</span>
        </div>
    </div>
</div>

{{-- ═══════════════ CTA ═══════════════ --}}
<section class="home-cta">
    <div class="container">
        <div class="home-cta-inner">
            <div class="home-cta-tag">{{ __('home.cta_tag') }}</div>
            <h2 class="home-cta-title">
                {{ __('home.cta_title_line1') }}<br>{{ __('home.cta_title_line2') }}
            </h2>
            <p class="home-cta-sub">
                {{ __('home.cta_sub') }}
            </p>

            <div class="home-cta-btns">
                <a href="{{ $siteSettings->telegram_url ?: 'https://t.me/xprinter_admin_bot' }}" target="_blank" rel="noopener" class="cta-tg">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M21 5L2 12.5l7 1M21 5l-2.5 15L9 13.5M21 5L9 13.5m0 0V19l3.3-3"/>
                    </svg>
                    {{ __('home.cta_telegram_btn') }}
                </a>
                @if($siteSettings->phone)
                <a href="tel:{{ preg_replace('/\s+/', '', $siteSettings->phone) }}" class="cta-phone">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.72a16 16 0 0 0 6.06 6.06l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/>
                    </svg>
                    {{ __('home.cta_phone_btn') }}
                </a>
                @endif
                @guest
                <a href="{{ route('register') }}" class="cta-phone">{{ __('home.cta_dealer') }} →</a>
                @endguest
            </div>

            <div class="cta-contacts">
                <a class="cta-contact" href="mailto:{{ $siteSettings->email ?: 'info@xprinter.uz' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    {{ $siteSettings->email ?: 'info@xprinter.uz' }}
                </a>
                <span class="cta-contact">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    {{ $siteSettings->work_time_display ?: __('footer.hours_fallback') }}
                </span>
                <span class="cta-contact">
                    <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    {{ $siteSettings->address ?: __('footer.address_fallback') }}
                </span>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// Hero product carousel: native scroll-snap swipe + arrows + pills + gentle autoplay.
document.querySelectorAll('[data-hero-carousel]').forEach(function (root) {
    var track  = root.querySelector('[data-track]');
    var slides = track.children;
    var pills  = root.querySelectorAll('[data-goto]');
    var index  = 0, timer = null;
    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function go(i) {
        index = (i + slides.length) % slides.length;
        track.scrollTo({ left: index * track.clientWidth, behavior: reduce ? 'auto' : 'smooth' });
        mark();
    }
    function mark() {
        pills.forEach(function (p, i) {
            p.classList.toggle('is-active', i === index);
            p.setAttribute('aria-current', i === index ? 'true' : 'false');
        });
    }
    function stop() { clearInterval(timer); timer = null; }
    function start() { if (!reduce && slides.length > 1 && !timer) timer = setInterval(function () { go(index + 1); }, 5000); }

    // Keep the active pill in sync when the user swipes.
    var settle;
    track.addEventListener('scroll', function () {
        clearTimeout(settle);
        settle = setTimeout(function () {
            var i = Math.min(slides.length - 1, Math.round(track.scrollLeft / track.clientWidth));
            if (i !== index) { index = i; mark(); }
        }, 120);
    }, { passive: true });

    pills.forEach(function (p) { p.addEventListener('click', function () { stop(); go(+p.dataset.goto); }); });
    var prev = root.querySelector('[data-prev]'), next = root.querySelector('[data-next]');
    prev && prev.addEventListener('click', function () { stop(); go(index - 1); });
    next && next.addEventListener('click', function () { stop(); go(index + 1); });
    track.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowLeft')  { e.preventDefault(); stop(); go(index - 1); }
        if (e.key === 'ArrowRight') { e.preventDefault(); stop(); go(index + 1); }
    });

    ['mouseenter', 'focusin', 'touchstart'].forEach(function (ev) { root.addEventListener(ev, stop, { passive: true }); });
    root.addEventListener('mouseleave', start);
    start();
});
</script>
@endpush
