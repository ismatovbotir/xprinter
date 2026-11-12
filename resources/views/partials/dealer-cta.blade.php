{{-- Compact B2B call-to-action: invites resellers & service centers to register.
     Optional: $title, $text to override the default copy. --}}
<aside class="dealer-cta" aria-labelledby="dealer-cta-title">
    <div class="dealer-cta-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
    </div>
    <div class="dealer-cta-body">
        <h2 class="dealer-cta-title" id="dealer-cta-title">{{ $title ?? __('home.dealers_title') }}</h2>
        <p class="dealer-cta-text">{{ $text ?? __('home.dealers_sub') }}</p>
    </div>
    <div class="dealer-cta-actions">
        @guest
        <a href="{{ route('register') }}" class="pub-btn pub-btn-primary">{{ __('home.cta_dealer') }} →</a>
        @else
        <a href="{{ auth()->user()->redirectPath() }}" class="pub-btn pub-btn-primary">{{ __('nav.cabinet') }} →</a>
        @endguest
        <a href="{{ $siteSettings->telegram_url ?: 'https://t.me/xprinter_admin_bot' }}" target="_blank" rel="noopener" class="pub-btn pub-btn-ghost">Telegram</a>
    </div>
</aside>
