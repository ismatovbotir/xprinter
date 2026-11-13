@extends('layouts.app')
@section('title', __('support.title'))
@section('description', __('support.meta_description'))
@section('og_title', __('support.title') . ' — Xprinter.uz')
@section('og_description', __('support.meta_description'))

@push('schema')
<script type="application/ld+json">{{ json_encode(['@context'=>'https://schema.org','@type'=>'BreadcrumbList','itemListElement'=>[
    ['@type'=>'ListItem','position'=>1,'name'=>'Bosh sahifa','item'=>url('/')],
    ['@type'=>'ListItem','position'=>2,'name'=>__('support.title'),'item'=>route('support.index')],
]], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) }}</script>
@endpush

@section('content')

<div class="pub-page-header">
  <div class="container">
    <h1>{{ __('support.title') }}</h1>
    <p>{{ __('support.sub') }}</p>

    <form method="GET" action="{{ route('support.index') }}" class="kb-search" role="search">
      <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="search" name="q" value="{{ $q }}" placeholder="{{ __('support.search_placeholder') }}" aria-label="{{ __('support.search_placeholder') }}">
      <button type="submit" class="pub-btn pub-btn-primary pub-btn-sm">{{ __('support.search') }}</button>
    </form>
  </div>
</div>

<section class="section">
  <div class="container">

    @if($results !== null)
      <h2 class="kb-h2">{{ __('support.search_results', ['q' => $q, 'n' => $results->count()]) }}</h2>
      @if($results->isEmpty())
        <div class="pub-empty">
          <p>{{ __('support.no_results') }}</p>
          <a href="{{ route('support.index') }}" class="pub-btn pub-btn-ghost pub-btn-sm">{{ __('support.all_articles') }}</a>
          <a href="{{ route('partners.index', ['type' => 'service']) }}" class="pub-btn pub-btn-primary pub-btn-sm">{{ __('support.find_service') }}</a>
        </div>
      @else
        <div class="kb-grid">
          @foreach($results as $article)
            @include('support._card', ['article' => $article])
          @endforeach
        </div>
      @endif
    @else

      {{-- Type tiles --}}
      <div class="kb-types">
        @foreach($types as $type)
          <a href="{{ route('support.type', $type) }}" class="kb-type-card kb-type-card-{{ $type }}">
            <div class="kb-type-icon" aria-hidden="true">
              @switch($type)
                @case('troubleshooting')
                  <svg viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                  @break
                @case('settings')
                  <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                  @break
                @case('driver')
                  <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                  @break
                @case('tool')
                  <svg viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                  @break
                @case('glossary')
                  <svg viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                  @break
                @default
                  <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
              @endswitch
            </div>
            <div class="kb-type-title">{{ __('support.type_' . $type) }}</div>
            <div class="kb-type-desc">{{ __('support.type_desc_' . $type) }}</div>
            <div class="kb-type-count">{{ $counts[$type] ?? 0 }} {{ __('support.articles') }}</div>
          </a>
        @endforeach
      </div>

      {{-- Popular --}}
      @if($popular->isNotEmpty())
        <h2 class="kb-h2">{{ __('support.popular') }}</h2>
        <div class="kb-grid">
          @foreach($popular as $article)
            @include('support._card', ['article' => $article])
          @endforeach
        </div>
      @endif

      {{-- Glossary --}}
      @if($glossary->isNotEmpty())
        <h2 class="kb-h2">{{ __('support.glossary') }}</h2>
        <ul class="kb-glossary">
          @foreach($glossary as $article)
            @php $t = $article->t(); @endphp
            <li>
              <a href="{{ $article->url() }}"><strong>{{ $t?->title }}</strong></a>
              @if($t?->excerpt)<span>{{ Str::limit($t->excerpt, 120) }}</span>@endif
            </li>
          @endforeach
        </ul>
        <p class="kb-more"><a href="{{ route('support.type', 'glossary') }}">{{ __('support.all_terms') }} →</a></p>
      @endif

    @endif

    {{-- Didn't help --}}
    <div class="kb-cta">
      <div>
        <h3>{{ __('support.not_helped_title') }}</h3>
        <p>{{ __('support.not_helped_text') }}</p>
      </div>
      <div class="kb-cta-actions">
        <a href="{{ route('partners.index', ['type' => 'service']) }}" class="pub-btn pub-btn-primary">{{ __('support.find_service') }}</a>
        <a href="{{ route('contact') }}" class="pub-btn pub-btn-ghost">{{ __('nav.contact') }}</a>
      </div>
    </div>

  </div>
</section>

@endsection
