@php
    $aboutCards = collect($content->aboutCardList())->filter(fn($c) => !empty($c['title']))->values();
    if ($aboutCards->isEmpty()) {
        $aboutCards = collect([
            ['title' => "Distribyutorlar va dilerlar tarmog'i", 'text' => "Xprinter Group mahsulotlarini O'zbekiston bo'ylab sotuvchi rasmiy distribyutorlar va reseller kompaniyalar shu platformada birlashgan."],
            ['title' => '12 oy kafolat va servis', 'text' => "Tanlagan hamkoringiz — tarmoqdagi servis markazlari orqali kafolat ta'miri, ehtiyot qismlar va texnik yordam ko'rsatadi."],
            ['title' => 'Tez yetkazib berish', 'text' => "Toshkentda 24 soat ichida, regionlarga 3–7 kun ichida — sizga eng yaqin distribyutor orqali yetkazib beriladi."],
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
    <div class="section-tag">{{ $content->about_tag ?: '// Nega biz' }}</div>
    <div class="section-title">{{ $content->about_title ?: "Xprinter.uz — distribyutorlar tarmog'i" }}</div>
    <div class="section-sub">{{ $content->about_subtitle ?: "Xprinter Group mahsulotlarini O'zbekiston bo'ylab sotadigan rasmiy distribyutorlar va reseller kompaniyalar platformasi." }}</div>
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
