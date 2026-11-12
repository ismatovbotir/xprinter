{{-- One partner card for the public directory. Expects $partner. --}}
@php($cities = $partner->cityLabels())
<a href="{{ route('partners.show', $partner->slug) }}" class="partner-card">
  <div class="partner-card-head">
    @if($partner->logo)
      <img src="{{ Storage::url($partner->logo) }}" alt="" class="partner-logo" width="48" height="48" loading="lazy">
    @else
      <div class="partner-logo partner-logo-text" aria-hidden="true">{{ mb_strtoupper(mb_substr($partner->displayName(), 0, 2)) }}</div>
    @endif
    <div class="partner-card-title">
      <h2 class="partner-name">{{ $partner->displayName() }}</h2>
      @if($partner->isAuthorized())
        <span class="partner-badge partner-badge-auth">✓ {{ __('partners.status_' . $partner->manufacturer_status) }}</span>
      @else
        <span class="partner-badge">{{ __('partners.verified') }}</span>
      @endif
    </div>
  </div>

  <ul class="partner-types" aria-label="{{ __('partners.types_label') }}">
    @foreach($partner->types ?? [] as $t)
      <li class="partner-type partner-type-{{ $t }}">{{ __('partners.type_' . $t) }}</li>
    @endforeach
  </ul>

  <div class="partner-meta">
    @if($cities)
      <div><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22s7-6.2 7-12a7 7 0 0 0-14 0c0 5.8 7 12 7 12z"/><circle cx="12" cy="10" r="2.5"/></svg>{{ implode(' · ', $cities) }}</div>
    @endif
    <div><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="7" width="16" height="12" rx="1"/><path d="M7 7V4h10v3"/></svg>{{ __('partners.models_count', ['count' => $partner->products_count]) }}</div>
  </div>

  <span class="partner-more">{{ __('home.details') }} →</span>
</a>
