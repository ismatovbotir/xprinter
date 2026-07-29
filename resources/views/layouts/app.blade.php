<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    {{-- ── Primary ── --}}
    <title>@yield('title', 'Xprinter.uz') — Xprinter distribyutorlari va dilerlari O'zbekistonda</title>
    <meta name="description" content="@yield('description', 'Termoprinterlari Xprinter O\'zbekistonda — chek, etiket va mobil printerlar. Rasmiy distribyutorlar va dilerlar tarmog\'i, 12 oy kafolat, Toshkentda servis markaz.')">
    <meta name="keywords" content="@yield('keywords', 'termoprinter toshkent, chek printer, etiket printer, xprinter uzbekistan, termoprinter narxi, receipt printer uzbekistan')">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- ── Open Graph ── --}}
    <meta property="og:site_name" content="Xprinter.uz">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('og_title', 'Xprinter.uz — distribyutorlar va dilerlar platformasi')">
    <meta property="og:description" content="@yield('og_description', 'Termoprinterlari Xprinter O\'zbekistonda — chek, etiket va mobil printerlar. Rasmiy distribyutorlar va dilerlar tarmog\'i, 12 oy kafolat.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', url('/images/og-default.jpg'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="@yield('og_title', 'Xprinter.uz')">
    <meta property="og:locale" content="{{ ['uz'=>'uz_UZ','ru'=>'ru_RU','en'=>'en_US'][app()->getLocale()] ?? 'uz_UZ' }}">

    {{-- ── Twitter Card ── --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@xprinter_uz">
    <meta name="twitter:title" content="@yield('og_title', 'Xprinter.uz')">
    <meta name="twitter:description" content="@yield('og_description', 'Termoprinterlari Xprinter O\'zbekistonda.')">
    <meta name="twitter:image" content="@yield('og_image', url('/images/og-default.jpg'))">

    {{-- ── Fonts (non-blocking) ── --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;500;600;700&family=Manrope:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" />
    <link rel="stylesheet" media="print" onload="this.media='all'" href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;500;600;700&family=Manrope:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" />
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;500;600;700&family=Manrope:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" /></noscript>

    @vite(['resources/css/public.css'])
    @stack('styles')

    {{-- ── LocalBusiness (all pages) ── --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "Xprinter.uz",
        "description": "Xprinter termoprinterlari uchun O'zbekistondagi rasmiy distribyutorlar va dilerlar platformasi. Chek, etiket va mobil printerlar.",
        "url": "{{ url('/') }}",
        "telephone": "{{ $siteSettings->phone ?: '+998901234567' }}",
        "email": "{{ $siteSettings->email ?: 'info@xprinter.uz' }}",
        "logo": "{{ url('/images/logo.png') }}",
        "image": "{{ url('/images/og-default.jpg') }}",
        "priceRange": "$$",
        "currenciesAccepted": "UZS",
        "paymentAccepted": "Cash, Credit Card, Bank Transfer",
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "UZ",
            "addressLocality": "Toshkent",
            "addressRegion": "Toshkent shahri"
            @if($siteSettings->address)
            , "streetAddress": {!! json_encode($siteSettings->address) !!}
            @endif
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": "41.2995",
            "longitude": "69.2401"
        },
        "openingHoursSpecification": {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": {!! json_encode($siteSettings->work_days_schema ?: ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']) !!},
            "opens": "{{ $siteSettings->work_time_from ?: '09:00' }}",
            "closes": "{{ $siteSettings->work_time_to ?: '18:00' }}"
        },
        "sameAs": ["{{ $siteSettings->telegram_url ?: 'https://t.me/xprinter_uz' }}"]
    }
    </script>

    {{-- ── Page-specific schemas ── --}}
    @stack('schema')

    {{-- ── Analytics ── --}}
    @if($siteSettings->google_analytics_id)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $siteSettings->google_analytics_id }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $siteSettings->google_analytics_id }}');
    </script>
    @endif

    @if($siteSettings->yandex_metrica_id)
    <script type="text/javascript">
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();
        for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
        k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym({{ (int) $siteSettings->yandex_metrica_id }}, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true
        });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/{{ (int) $siteSettings->yandex_metrica_id }}" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    @endif
</head>
<body>

@php
    // On the homepage these scroll to the in-page sections; everywhere else they
    // link to the dedicated pages, since those sections don't exist on other routes.
    $isHome      = request()->routeIs('home');
    $aboutHref   = $isHome ? '#about' : route('about');
    $contactHref = $isHome ? '#contact' : route('contact');
@endphp

{{-- NAV --}}
<nav class="pub-nav">
    <div class="container">
        <div class="pub-nav-inner">
            <a href="{{ route('home') }}" class="pub-logo">
                <div class="pub-logo-mark"></div>
                <span>XPRINTER<span style="color:var(--blue)">.UZ</span></span>
            </a>

            <div class="pub-nav-links" style="display:flex;gap:4px">
                <a href="{{ route('catalog') }}"
                   class="pub-nav-link {{ request()->routeIs('catalog*','product*') ? 'active' : '' }}">
                    Katalog
                </a>
                <a href="{{ $aboutHref }}" class="pub-nav-link {{ request()->routeIs('about') ? 'active' : '' }}">Biz haqimizda</a>
                <a href="{{ $contactHref }}" class="pub-nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Aloqa</a>
            </div>

            <div class="pub-nav-actions">
                {{-- Language switcher --}}
                <div style="display:flex;gap:2px">
                    @foreach(['uz','ru','en'] as $lang)
                    <a href="{{ route('lang.switch', $lang) }}"
                       style="padding:4px 9px;border-radius:6px;font-family:'JetBrains Mono',monospace;font-size:10px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;transition:all .12s;
                              {{ app()->getLocale() === $lang ? 'background:var(--blue);color:#fff' : 'color:var(--muted)' }}">
                        {{ strtoupper($lang) }}
                    </a>
                    @endforeach
                </div>

                @auth
                <a href="{{ auth()->user()->redirectPath() }}" class="pub-btn pub-btn-ghost" style="font-size:12px;padding:6px 14px">
                    Kabinet
                </a>
                @else
                <a href="{{ route('login') }}" class="pub-btn pub-btn-ghost" style="font-size:12px;padding:6px 14px">Kirish</a>
                @endauth

                <a href="{{ $siteSettings->telegram_url ?: 'https://t.me/xprinter_uz' }}" target="_blank" class="pub-btn pub-btn-primary" style="font-size:12px;padding:6px 16px">
                    Telegram →
                </a>
            </div>
        </div>
    </div>
</nav>

{{-- CONTENT --}}
@yield('content')

{{-- FOOTER --}}
<footer class="pub-footer" id="contact">
    <div class="container">
        <div class="pub-foot-grid">
            <div class="pub-foot-brand">
                <a href="{{ route('home') }}" class="pub-logo">
                    <div class="pub-logo-mark"></div>
                    <span>XPRINTER<span style="color:var(--blue)">.UZ</span></span>
                </a>
                <p>Платформа официальных дистрибьюторов и дилеров Xprinter в Узбекистане. Гарантия 12 месяцев, доставка по всей стране, сервисные центры у наших партнёров.</p>
            </div>
            <div class="pub-foot-col">
                <h5>Katalog</h5>
                <ul>
                    <li><a href="{{ route('catalog') }}">Barcha mahsulotlar</a></li>
                    <li><a href="{{ route('catalog.category', 'receipt-printers') }}">Chek printerlari</a></li>
                    <li><a href="{{ route('catalog.category', 'label-printers') }}">Etiket printerlari</a></li>
                    <li><a href="{{ route('catalog.category', 'mobile-printers') }}">Mobil printerlar</a></li>
                </ul>
            </div>
            <div class="pub-foot-col">
                <h5>Kompaniya</h5>
                <ul>
                    <li><a href="{{ $aboutHref }}">Biz haqimizda</a></li>
                    <li><a href="{{ $contactHref }}">Aloqa</a></li>
                    <li><a href="{{ route('login') }}">Dilerlar uchun</a></li>
                </ul>
            </div>
            <div class="pub-foot-col">
                <h5>Aloqa</h5>
                <ul>
                    @if($siteSettings->phone)
                    <li><a href="tel:{{ preg_replace('/\s+/', '', $siteSettings->phone) }}">{{ $siteSettings->phone }}</a></li>
                    @endif
                    <li><a href="{{ $siteSettings->telegram_url ?: 'https://t.me/xprinter_uz' }}">Telegram: {{ '@' . ($siteSettings->telegram ?: 'xprinter_uz') }}</a></li>
                    @if($siteSettings->whatsapp_url)
                    <li><a href="{{ $siteSettings->whatsapp_url }}">WhatsApp: {{ $siteSettings->whatsapp }}</a></li>
                    @endif
                    <li><a href="mailto:{{ $siteSettings->email ?: 'info@xprinter.uz' }}">{{ $siteSettings->email ?: 'info@xprinter.uz' }}</a></li>
                    <li style="color:var(--muted);font-size:13px">{{ $siteSettings->address ?: "Toshkent, O'zbekiston" }}</li>
                    <li style="color:var(--muted);font-size:13px">{{ $siteSettings->work_time_display ?: 'Dush–Shan 9:00–18:00' }}</li>
                </ul>
            </div>
        </div>
        <div class="pub-foot-bottom">
            <div>© {{ date('Y') }} Xprinter.uz — платформа дистрибьюторов и дилеров Xprinter Group в Узбекистане</div>
            <div style="text-transform:uppercase">ISO 9001:2015 · CE · FCC · RoHS</div>
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>
