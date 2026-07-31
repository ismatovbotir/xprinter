<!DOCTYPE html>
<html lang="uz">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Xprinter katalogi</title>
<script src="https://telegram.org/js/telegram-web-app.js"></script>
<style>
    :root {
        --ink: #0A1B3D;
        --muted: #6B7B95;
        --line: #E3EBF5;
        --blue: #0066FF;
        --bg-soft: #F4F8FE;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Manrope', sans-serif;
        background: var(--tg-theme-bg-color, #fff);
        color: var(--tg-theme-text-color, var(--ink));
        padding: 16px;
        padding-bottom: 40px;
    }
    h1 { font-size: 20px; font-weight: 700; margin-bottom: 4px; }
    .sub { color: var(--tg-theme-hint-color, var(--muted)); font-size: 13px; margin-bottom: 20px; }
    .cats { display: flex; gap: 8px; overflow-x: auto; padding-bottom: 4px; margin-bottom: 18px; }
    .cat-pill {
        flex-shrink: 0;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        background: var(--tg-theme-secondary-bg-color, var(--bg-soft));
        color: var(--tg-theme-text-color, var(--ink));
        text-decoration: none;
        white-space: nowrap;
        border: 1px solid var(--line);
    }
    .cat-pill.active {
        background: var(--tg-theme-button-color, var(--blue));
        color: var(--tg-theme-button-text-color, #fff);
        border-color: transparent;
    }
    .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .card {
        display: block;
        border: 1px solid var(--line);
        border-radius: 14px;
        padding: 12px;
        text-decoration: none;
        color: inherit;
        background: var(--tg-theme-secondary-bg-color, #fff);
    }
    .card img, .card .no-photo {
        width: 100%;
        aspect-ratio: 1;
        object-fit: contain;
        border-radius: 8px;
        background: var(--bg-soft);
        margin-bottom: 8px;
    }
    .card-name { font-size: 13px; font-weight: 600; line-height: 1.3; margin-bottom: 2px; }
    .card-model { font-size: 11px; color: var(--tg-theme-hint-color, var(--muted)); font-family: monospace; }
    .empty { text-align: center; color: var(--tg-theme-hint-color, var(--muted)); padding: 40px 0; font-size: 13px; }
</style>
</head>
<body>

<h1 id="greeting">{{ $activeCategory ? ($activeCategory->translations->firstWhere('lang','uz')?->name ?? $activeCategory->slug) : 'Xprinter katalogi' }}</h1>
<div class="sub">Distribyutorlar va dilerlar platformasi</div>

<div class="cats">
    <a href="{{ route('telegram.app') }}" class="cat-pill {{ !$activeCategory ? 'active' : '' }}">Barchasi</a>
    @foreach($categories as $cat)
    <a href="{{ route('telegram.app', ['category' => $cat->slug]) }}"
       class="cat-pill {{ $activeCategory?->id === $cat->id ? 'active' : '' }}">
        {{ $cat->translations->firstWhere('lang','uz')?->name ?? $cat->slug }}
        ({{ $cat->products_count }})
    </a>
    @endforeach
</div>

@if($activeCategory)
    @if($products->isEmpty())
    <div class="empty">Bu kategoriyada mahsulotlar yo'q</div>
    @else
    <div class="grid">
        @foreach($products as $p)
        @php $pName = $p->translations->firstWhere('lang','uz')?->name ?? $p->model_number; @endphp
        <a href="{{ route('products.show', [$activeCategory->slug, $p->slug]) }}" class="card" target="_blank">
            @if($p->photos->isNotEmpty())
                <img src="{{ $p->photos->first()->url }}" alt="{{ $pName }}">
            @elseif($p->photo)
                <img src="{{ Storage::url($p->photo) }}" alt="{{ $pName }}">
            @else
                <div class="no-photo"></div>
            @endif
            <div class="card-name">{{ $pName }}</div>
            <div class="card-model">{{ $p->model_number }}</div>
        </a>
        @endforeach
    </div>
    @endif
@else
    <div class="empty">Kategoriyani tanlang</div>
@endif

<script>
    const tg = window.Telegram?.WebApp;
    if (tg) {
        tg.ready();
        tg.expand();

        const user = tg.initDataUnsafe?.user;
        if (user?.first_name && !@json((bool) $activeCategory)) {
            document.getElementById('greeting').textContent = 'Salom, ' + user.first_name + '!';
        }
    }
</script>
</body>
</html>
