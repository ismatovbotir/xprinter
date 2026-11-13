@extends('layouts.app')
@section('title', __('support.type_' . $type) . ' — ' . __('support.title'))
@section('description', __('support.type_desc_' . $type))

@section('content')

<div class="pub-page-header">
  <div class="container">
    <nav class="kb-breadcrumb" aria-label="{{ __('product.breadcrumb') }}">
      <a href="{{ route('home') }}">{{ __('product.home') }}</a> ›
      <a href="{{ route('support.index') }}">{{ __('support.title') }}</a> ›
      <span>{{ __('support.type_' . $type) }}</span>
    </nav>
    <h1>{{ __('support.type_' . $type) }}</h1>
    <p>{{ __('support.type_desc_' . $type) }}</p>
    @if($product)
      <p class="kb-filter-note">
        {{ __('support.filtered_by_model') }} <strong>{{ $product->model_number }}</strong>
        · <a href="{{ route('support.type', $type) }}">{{ __('support.show_all') }}</a>
      </p>
    @endif
  </div>
</div>

<section class="section">
  <div class="container">
    @if($articles->isEmpty())
      <div class="pub-empty"><p>{{ __('support.no_results') }}</p></div>
    @else
      <div class="kb-grid">
        @foreach($articles as $article)
          @include('support._card', ['article' => $article])
        @endforeach
      </div>
      @if($articles->hasPages())
        <div class="pub-pagination">{{ $articles->links() }}</div>
      @endif
    @endif

    <p class="kb-more"><a href="{{ route('support.index') }}">← {{ __('support.back_to_support') }}</a></p>
  </div>
</section>

@endsection
