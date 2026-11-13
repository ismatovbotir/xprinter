@extends('layouts.app')
@php $t = $article->t(); @endphp
@section('title', $t?->title . ' — ' . __('support.title'))
@section('description', Str::limit($t?->excerpt ?: ($t?->plainText() ?? ''), 160))
@section('og_title', $t?->title . ' — Xprinter.uz')
@section('og_description', Str::limit($t?->excerpt ?: ($t?->plainText() ?? ''), 160))
@section('og_type', 'article')

@push('schema')
<script type="application/ld+json">{{ json_encode([
    '@context' => 'https://schema.org',
    '@type' => $article->type === 'troubleshooting' || $article->type === 'settings' ? 'HowTo' : 'TechArticle',
    'name' => $t?->title,
    'description' => Str::limit($t?->excerpt ?: ($t?->plainText() ?? ''), 200),
    'inLanguage' => app()->getLocale(),
    'dateModified' => $article->updated_at?->toDateString(),
    'about' => $article->products->map(fn ($p) => ['@type' => 'Product', 'name' => 'Xprinter ' . $p->model_number])->values()->all(),
], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) }}</script>
@endpush

@section('content')

<div class="pub-page-header kb-article-header">
  <div class="container">
    <nav class="kb-breadcrumb" aria-label="{{ __('product.breadcrumb') }}">
      <a href="{{ route('home') }}">{{ __('product.home') }}</a> ›
      <a href="{{ route('support.index') }}">{{ __('support.title') }}</a> ›
      <a href="{{ route('support.type', $article->type) }}">{{ __('support.type_' . $article->type) }}</a>
    </nav>
    <div class="kb-card-top">
      <span class="kb-type kb-type-{{ $article->type }}">{{ __('support.type_' . $article->type) }}</span>
      <span class="kb-difficulty">{{ __('support.difficulty_' . $article->difficulty) }}</span>
    </div>
    <h1>{{ $t?->title }}</h1>
    @if($t?->excerpt)<p class="kb-lead">{{ $t->excerpt }}</p>@endif
  </div>
</div>

<section class="section">
  <div class="container kb-layout">

    <article class="kb-article">
      <div class="kb-body">
        {!! $t?->safeBody() !!}
      </div>

      @if($article->files->isNotEmpty())
        <h2 class="kb-h3">{{ __('support.downloads') }}</h2>
        <ul class="prod-files">
          @foreach($article->files as $file)
            <li>
              <a href="{{ $file->url }}" target="_blank" rel="noopener" class="prod-file">
                <div class="prod-file-icon" aria-hidden="true">
                  <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9 15 12 18 15 15"/></svg>
                </div>
                <div class="prod-file-info">
                  <div class="prod-file-name">{{ $file->name }}</div>
                  <div class="prod-file-meta">@if($file->version)<span>v{{ $file->version }}</span>@endif {{ $file->size_formatted }}</div>
                </div>
              </a>
            </li>
          @endforeach
        </ul>
      @endif

      <div class="kb-cta kb-cta-compact">
        <div>
          <h3>{{ __('support.not_helped_title') }}</h3>
          <p>{{ __('support.not_helped_text') }}</p>
        </div>
        <div class="kb-cta-actions">
          <a href="{{ route('partners.index', ['type' => 'service']) }}" class="pub-btn pub-btn-primary pub-btn-sm">{{ __('support.find_service') }}</a>
        </div>
      </div>
    </article>

    <aside class="kb-aside">
      @if($article->products->isNotEmpty())
        <div class="kb-aside-block">
          <h4>{{ __('support.applies_to') }}</h4>
          <ul class="kb-models">
            @foreach($article->products as $p)
              <li>
                <a href="{{ $p->category ? route('products.show', [$p->category->slug, $p->slug]) : route('catalog') }}">{{ $p->model_number }}</a>
              </li>
            @endforeach
          </ul>
        </div>
      @elseif($article->category)
        <div class="kb-aside-block">
          <h4>{{ __('support.applies_to') }}</h4>
          <p><a href="{{ route('catalog.category', $article->category->slug) }}">{{ $article->category->translations->firstWhere('lang', app()->getLocale())?->name ?? $article->category->slug }}</a></p>
        </div>
      @endif

      @if($serviceCenters->isNotEmpty())
        <div class="kb-aside-block">
          <h4>{{ __('support.service_centers') }}</h4>
          <ul class="kb-service">
            @foreach($serviceCenters as $c)
              <li>
                <a href="{{ route('partners.show', $c->slug) }}">{{ $c->displayName() }}</a>
                @if($c->phone)<span>{{ $c->phone }}</span>@endif
              </li>
            @endforeach
          </ul>
          <a href="{{ route('partners.index', ['type' => 'service']) }}" class="kb-aside-link">{{ __('partners.all_partners') }} →</a>
        </div>
      @endif

      @if($related->isNotEmpty())
        <div class="kb-aside-block">
          <h4>{{ __('support.related') }}</h4>
          <ul class="kb-related">
            @foreach($related as $r)
              <li><a href="{{ $r->url() }}">{{ $r->t()?->title }}</a></li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="kb-aside-meta">
        {{ __('support.views') }}: {{ number_format($article->views_count, 0, '', ' ') }}
        · {{ $article->updated_at?->format('d.m.Y') }}
      </div>
    </aside>

  </div>
</section>

@endsection
