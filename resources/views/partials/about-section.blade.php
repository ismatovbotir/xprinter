@php
    $aboutCards = collect($content->aboutCardList())->filter(fn($c) => !empty($c['title']))->values();
    if ($aboutCards->isEmpty()) {
        $aboutCards = collect([
            ['title' => __('about_page.card1_title'), 'text' => __('about_page.card1_text')],
            ['title' => __('about_page.card2_title'), 'text' => __('about_page.card2_text')],
            ['title' => __('about_page.card3_title'), 'text' => __('about_page.card3_text')],
        ]);
    }

    $icons = [
        '<svg viewBox="0 0 24 24" style="stroke:var(--blue)"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        '<svg viewBox="0 0 24 24" style="stroke:var(--green)"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
        '<svg viewBox="0 0 24 24" style="stroke:#F97316"><path d="M5 17H3a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v3"/><rect x="9" y="11" width="14" height="10" rx="1"/><path d="M12 16h3M12 19h2"/></svg>',
    ];
    $bgColors = ['#EAF2FD', '#F0FDF8', '#FFF7ED'];
@endphp

<div class="section-header">
    <div class="section-tag">{{ $content->about_tag ?: __('about_page.why_tag') }}</div>
    <div class="section-title">{{ $content->about_title ?: __('about_page.why_title') }}</div>
    <div class="section-sub">{{ $content->about_subtitle ?: __('about_page.why_subtitle') }}</div>
</div>

<div class="why-grid">
    @foreach($aboutCards as $i => $card)
    <div class="why-card">
        <div class="why-icon" style="background:{{ $bgColors[$i] ?? '#EAF2FD' }}">
            {!! $icons[$i] ?? $icons[0] !!}
        </div>
        <div class="why-title">{{ $card['title'] }}</div>
        <div class="why-text">{{ $card['text'] }}</div>
    </div>
    @endforeach
</div>
