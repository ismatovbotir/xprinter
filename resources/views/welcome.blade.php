@extends('layouts.app')
@section('title', 'Xprinter.uz — O\'zbekistonda rasmiy distribyutor')
@section('description', 'Termoprinterlari Xprinter — chek, etiket va mobil printerlar. O\'zbekistonda rasmiy distribyutor, 12 oy kafolat, Toshkentda servis markaz.')

@section('og_title',       'Xprinter.uz — Rasmiy distribyutor Xprinter O\'zbekistonda')
@section('og_description', 'Termoprinterlari Xprinter O\'zbekistonda — chek, etiket va mobil printerlar. Rasmiy distribyutor, 12 oy kafolat, Toshkentda servis markaz.')
@section('keywords',       'termoprinter toshkent, xprinter uzbekistan, chek printer sotib olish, etiket printer narxi, termoprinter kafolat')

@push('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "Xprinter.uz",
    "url": "{{ url('/') }}",
    "description": "O'zbekistonda Xprinter termoprinterlari rasmiy distribyutori",
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
    "@context": "https://schema.org",
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
            <div class="hero-left">
                <div class="hero-tag">
                    <span class="hero-tag-dot"></span>
                    Rasmiy distribyutor · O'zbekiston
                </div>

                <h1 class="hero-headline">
                    Xprinter<br>
                    <em>termoprinterlar</em><br>
                    O'zbekistonda
                </h1>

                <p class="hero-sub">
                    Chek, etiket va mobil printerlar — rasmiy kafolat, Toshkentda servis markaz, butun respublikaga yetkazib berish.
                </p>

                <div class="hero-attrs">
                    <span class="hero-attr">
                        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        12 oy rasmiy kafolat
                    </span>
                    <span class="hero-attr">
                        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        Toshkentda servis markaz
                    </span>
                    <span class="hero-attr">
                        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        24 soat ichida yetkazib berish
                    </span>
                </div>

                <div class="hero-btns">
                    <a href="{{ route('catalog') }}" class="pub-btn pub-btn-primary" style="padding:13px 26px;font-size:14px">
                        Katalogni ko'rish →
                    </a>
                    <a href="https://t.me/xprinter_uz" target="_blank" class="pub-btn pub-btn-ghost" style="padding:13px 22px;font-size:14px">
                        <svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2"><path d="M21 5L2 12.5l7 1M21 5l-2.5 15L9 13.5M21 5L9 13.5m0 0V19l3.3-3"/></svg>
                        Telegram
                    </a>
                </div>
            </div>

            {{-- Right: spec terminal card --}}
            <div class="hero-spec">
                <div class="hero-spec-bar">
                    <div class="spec-bar-dots">
                        <span style="background:#FF5F56"></span>
                        <span style="background:#FEBC2E"></span>
                        <span style="background:#28C840"></span>
                    </div>
                    <span class="spec-bar-path">xprinter.uz › catalog › product.scan</span>
                </div>

                <div>
                    <div class="spec-row">
                        <span class="spec-key">MODEL</span>
                        <span class="spec-val blue">XP-Q890K</span>
                    </div>
                    <div class="spec-row">
                        <span class="spec-key">TYPE</span>
                        <span class="spec-val">RECEIPT PRINTER</span>
                    </div>
                    <div class="spec-row">
                        <span class="spec-key">WIDTH</span>
                        <span class="spec-val">80 mm</span>
                    </div>
                    <div class="spec-row">
                        <span class="spec-key">SPEED</span>
                        <span class="spec-val">250 mm/s</span>
                    </div>
                    <div class="spec-row">
                        <span class="spec-key">DPI</span>
                        <span class="spec-val">203 dpi</span>
                    </div>
                    <div class="spec-row">
                        <span class="spec-key">INTERFACE</span>
                        <span class="spec-val">USB · LAN · BT</span>
                    </div>
                    <div class="spec-row">
                        <span class="spec-key">STATUS</span>
                        <span class="spec-val green">● IN STOCK</span>
                    </div>
                </div>

                <div class="spec-footer">
                    <span class="spec-footer-label">Boshqa modellar</span>
                    <div class="spec-pills">
                        <span class="spec-pill">XP-428B</span>
                        <span class="spec-pill">XP-365B</span>
                        <span class="spec-pill">DX5</span>
                        <span class="spec-pill">XP-58IIH</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ═══════════════ STATS ═══════════════ --}}
<div class="stats-strip">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-cell">
                <div class="stat-num">5<span>+</span></div>
                <div class="stat-label">Yil O'zbekiston bozorida</div>
            </div>
            <div class="stat-cell">
                <div class="stat-num">1000<span>+</span></div>
                <div class="stat-label">Muvaffaqiyatli o'rnatish</div>
            </div>
            <div class="stat-cell">
                <div class="stat-num">24<span>h</span></div>
                <div class="stat-label">Toshkentda yetkazib berish</div>
            </div>
            <div class="stat-cell">
                <div class="stat-num">12</div>
                <div class="stat-label">Oy rasmiy kafolat</div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════ CATEGORIES ═══════════════ --}}
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-tag">// Kategoriyalar</div>
            <div class="section-title">Mahsulot turlari</div>
            <div class="section-sub">Chek printerlaridan tortib tibbiy bilakuzuk printerlarigacha — har bir vazifa uchun to'g'ri model.</div>
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
                <div class="home-cat-icon">
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
                    <span class="home-cat-count">{{ $cat->products_count }} ta model</span>
                    <span class="cat-arrow">→</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════ POPULAR PRODUCTS ═══════════════ --}}
@if($featured->isNotEmpty())
<section class="section" style="background:var(--bg-soft);padding:80px 0;border-top:1px solid var(--line);border-bottom:1px solid var(--line)">
    <div class="container">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:44px;flex-wrap:wrap;gap:16px">
            <div>
                <div class="section-tag">// Ommabop modellar</div>
                <div class="section-title">Eng ko'p sotiladi</div>
            </div>
            <a href="{{ route('catalog') }}" class="pub-btn pub-btn-ghost" style="font-size:13px;padding:9px 20px">
                Barcha modellar →
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
                        <img src="{{ Storage::url($product->photo) }}" alt="{{ $trans?->name }}">
                    @else
                        <svg viewBox="0 0 24 24" style="width:52px;height:52px;stroke:var(--line-hi);fill:none;stroke-width:1.2">
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
                    <span style="font-size:12px;color:var(--muted)">Batafsil</span>
                    <div class="prod-card-arrow">→</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════ WHY US ═══════════════ --}}
<section class="section" id="about">
    <div class="container">
        <div class="section-header">
            <div class="section-tag">// Nega biz</div>
            <div class="section-title">Xprinter.uz — rasmiy kanal</div>
            <div class="section-sub">Xprinter Group Xitoy tomonidan vakolatlashtrilgan yagona distribyutor O'zbekistonda.</div>
        </div>

        <div class="why-grid">
            <div class="why-card">
                <div class="why-icon" style="background:#EAF2FD">
                    <svg viewBox="0 0 24 24" style="stroke:var(--blue)">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <div class="why-title">Rasmiy distribyutor</div>
                <div class="why-text">Xprinter Group (Xitoy) tomonidan O'zbekistonda yagona vakolatli distribyutor. Sertifikatlar va hujjatlar bor.</div>
            </div>

            <div class="why-card">
                <div class="why-icon" style="background:#F0FDF8">
                    <svg viewBox="0 0 24 24" style="stroke:var(--green)">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </div>
                <div class="why-title">12 oy kafolat va servis</div>
                <div class="why-text">Toshkentda o'z servis markazimiz. Kafolat ta'miri, ehtiyot qismlar, texnik yordam — bir joyda.</div>
            </div>

            <div class="why-card">
                <div class="why-icon" style="background:#FFF7ED">
                    <svg viewBox="0 0 24 24" style="stroke:#F97316">
                        <path d="M5 17H3a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v3"/>
                        <rect x="9" y="11" width="14" height="10" rx="1"/>
                        <path d="M12 16h3M12 19h2"/>
                    </svg>
                </div>
                <div class="why-title">Tez yetkazib berish</div>
                <div class="why-text">Toshkentda 24 soat ichida kuryerlik. Regionlarga 3–7 kun. Yirik buyurtmalar uchun alohida shartlar.</div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════ SCENARIOS ═══════════════ --}}
<section class="section" style="padding-top:0">
    <div class="container">
        <div class="section-header">
            <div class="section-tag">// Qo'llanish sohalari</div>
            <div class="section-title">Kim uchun?</div>
            <div class="section-sub">Xprinter printerlari Uzbekiston bo'ylab turli sohalarda ishlatiladi.</div>
        </div>

        <div class="scenario-grid">
            <div class="scenario-card">
                <div class="scenario-icon">
                    <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <div class="scenario-name">Do'kon / Supermarket</div>
                <div class="scenario-models">XP-Q890K · XP-365B</div>
            </div>

            <div class="scenario-card">
                <div class="scenario-icon">
                    <svg viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
                </div>
                <div class="scenario-name">Kafe / Restoran</div>
                <div class="scenario-models">XP-58IIH (kassa) · XP-Q809K (oshxona)</div>
            </div>

            <div class="scenario-card">
                <div class="scenario-icon">
                    <svg viewBox="0 0 24 24"><path d="M3 9a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9z"/><path d="M8 9V7a4 4 0 0 1 8 0v2"/><line x1="12" y1="13" x2="12" y2="17"/></svg>
                </div>
                <div class="scenario-name">Dorixona</div>
                <div class="scenario-models">XP-Q890K · XP-428B</div>
            </div>

            <div class="scenario-card">
                <div class="scenario-icon">
                    <svg viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 4v4h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
                <div class="scenario-name">Marketplace sotuvchi</div>
                <div class="scenario-models">XP-428B · XP-490B</div>
            </div>

            <div class="scenario-card">
                <div class="scenario-icon">
                    <svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                </div>
                <div class="scenario-name">Tibbiyot / Klinika</div>
                <div class="scenario-models">XP-D281B · XP-365B</div>
            </div>

            <div class="scenario-card">
                <div class="scenario-icon">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div class="scenario-name">Kuryer yetkazib berish</div>
                <div class="scenario-models">DX5 · MP3 · XP-P816</div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════ CERTS ═══════════════ --}}
<div class="certs-bar">
    <div class="container">
        <div class="certs-inner">
            <span class="certs-label">Sertifikatlar:</span>
            <span class="cert-pill">ISO 9001:2015</span>
            <span class="cert-pill">CE</span>
            <span class="cert-pill">FCC</span>
            <span class="cert-pill">RoHS</span>
            <span class="cert-pill">Xprinter Group · 2006</span>
            <span class="cert-pill">200+ mamlakat</span>
        </div>
    </div>
</div>

{{-- ═══════════════ CTA ═══════════════ --}}
<section class="home-cta">
    <div class="container">
        <div class="home-cta-inner">
            <div class="home-cta-tag">// Aloqa</div>
            <h2 class="home-cta-title">
                Savol yoki<br>buyurtma?
            </h2>
            <p class="home-cta-sub">
                Menejerimiz siz uchun to'g'ri modelni tanlashga, narx va yetkazib berish shartlarini kelishishga yordam beradi.
            </p>

            <div class="home-cta-btns">
                <a href="https://t.me/xprinter_uz" target="_blank" class="cta-tg">
                    <svg viewBox="0 0 24 24" style="width:18px;height:18px;stroke:currentColor;fill:none;stroke-width:2">
                        <path d="M21 5L2 12.5l7 1M21 5l-2.5 15L9 13.5M21 5L9 13.5m0 0V19l3.3-3"/>
                    </svg>
                    Telegram orqali yozing
                </a>
                <a href="tel:+998901234567" class="cta-phone">
                    <svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.72a16 16 0 0 0 6.06 6.06l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/>
                    </svg>
                    Qo'ng'iroq qilish
                </a>
            </div>

            <div class="cta-contacts">
                <span class="cta-contact">
                    <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    info@xprinter.uz
                </span>
                <span class="cta-contact">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Dush–Shan 9:00–18:00
                </span>
                <span class="cta-contact">
                    <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    Toshkent, O'zbekiston
                </span>
            </div>
        </div>
    </div>
</section>

@endsection
