@php $t = $article->t(); @endphp
<a href="{{ $article->url() }}" class="kb-card">
  <div class="kb-card-top">
    <span class="kb-type kb-type-{{ $article->type }}">{{ __('support.type_' . $article->type) }}</span>
    <span class="kb-difficulty">{{ __('support.difficulty_' . $article->difficulty) }}</span>
  </div>
  <h3 class="kb-card-title">{{ $t?->title }}</h3>
  @if($t?->excerpt)
    <p class="kb-card-excerpt">{{ Str::limit($t->excerpt, 140) }}</p>
  @endif
  @if($article->relationLoaded('products') && $article->products->isNotEmpty())
    <div class="kb-card-models">
      @foreach($article->products->take(4) as $p)
        <span>{{ $p->model_number }}</span>
      @endforeach
      @if($article->products->count() > 4)<span>+{{ $article->products->count() - 4 }}</span>@endif
    </div>
  @endif
</a>
