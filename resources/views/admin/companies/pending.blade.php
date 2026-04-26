@extends('layouts.admin')
@section('title', 'Kutilayotgan kompaniyalar')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Kutilayotganlar</div>
    <div class="page-subtitle">{{ $companies->count() }} ta kompaniya tasdiqni kutmoqda</div>
  </div>
  <a href="{{ route('admin.companies.index') }}" class="btn btn-ghost">
    Barchasi
  </a>
</div>

@if($companies->isEmpty())
<div class="card" style="padding:64px;text-align:center">
  <svg viewBox="0 0 24 24" style="width:48px;height:48px;stroke:var(--faint);fill:none;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round;margin:0 auto 16px">
    <polyline points="20 6 9 17 4 12"/>
  </svg>
  <div style="font-size:16px;font-weight:600;color:var(--ink);margin-bottom:6px">Hammasi tekshirildi</div>
  <div style="color:var(--muted);font-size:13.5px">Hozircha tasdiqni kutayotgan kompaniyalar yo'q</div>
</div>
@else

<div style="display:flex;flex-direction:column;gap:16px">
  @foreach($companies as $company)
  <div class="card">
    <div style="padding:20px 24px">
      <div style="display:flex;align-items:flex-start;gap:16px;flex-wrap:wrap">

        {{-- Logo + name --}}
        <div class="company-logo" style="width:48px;height:48px;font-size:14px;border-radius:12px;flex-shrink:0">
          {{ strtoupper(substr($company->brand ?? $company->name, 0, 2)) }}
        </div>

        <div style="flex:1;min-width:200px">
          <div style="font-family:'Unbounded',sans-serif;font-size:15px;font-weight:700;color:var(--ink);margin-bottom:4px">
            {{ $company->brand ?? $company->name }}
          </div>
          @if($company->brand && $company->name !== $company->brand)
          <div style="font-size:12px;color:var(--muted);margin-bottom:8px">{{ $company->name }}</div>
          @endif

          <div style="display:flex;gap:20px;flex-wrap:wrap;margin-top:10px">
            @if($company->inn)
            <div>
              <div style="font-family:'JetBrains Mono',monospace;font-size:9px;text-transform:uppercase;letter-spacing:0.1em;color:var(--faint);margin-bottom:3px">INN</div>
              <div style="font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:600;color:var(--ink)">{{ $company->inn }}</div>
            </div>
            @endif
            @if($company->phone)
            <div>
              <div style="font-family:'JetBrains Mono',monospace;font-size:9px;text-transform:uppercase;letter-spacing:0.1em;color:var(--faint);margin-bottom:3px">Telefon</div>
              <div style="font-size:13px;color:var(--ink-soft)">{{ $company->phone }}</div>
            </div>
            @endif
            @if($company->types)
            <div>
              <div style="font-family:'JetBrains Mono',monospace;font-size:9px;text-transform:uppercase;letter-spacing:0.1em;color:var(--faint);margin-bottom:3px">Turi</div>
              <div class="type-tags">
                @foreach($company->types as $type)
                <span class="type-tag {{ $type }}">
                  {{ match($type) { 'retail' => 'Chakana', 'partner' => 'Hamkor', 'service' => 'Servis', default => $type } }}
                </span>
                @endforeach
              </div>
            </div>
            @endif
            <div>
              <div style="font-family:'JetBrains Mono',monospace;font-size:9px;text-transform:uppercase;letter-spacing:0.1em;color:var(--faint);margin-bottom:3px">Yuborildi</div>
              <div style="font-size:13px;color:var(--muted)">{{ $company->created_at->diffForHumans() }}</div>
            </div>
          </div>

          @if($company->users->isNotEmpty())
          <div style="margin-top:10px;font-size:12.5px;color:var(--muted)">
            <span style="color:var(--ink-soft);font-weight:600">Owner:</span>
            {{ $company->users->first()?->name }} &lt;{{ $company->users->first()?->email }}&gt;
          </div>
          @endif
        </div>

        {{-- Actions --}}
        <div style="display:flex;gap:8px;flex-shrink:0">
          <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-ghost" style="height:36px;padding:0 14px;font-size:12.5px">
            <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
            Tahrirlash
          </a>
          <form method="POST" action="{{ route('admin.companies.reject', $company) }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-danger" style="height:36px;padding:0 14px;font-size:12.5px"
                    onclick="return confirm('Rad etishni tasdiqlaysizmi?')">
              <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              Rad etish
            </button>
          </form>
          <form method="POST" action="{{ route('admin.companies.approve', $company) }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-success" style="height:36px;padding:0 14px;font-size:12.5px">
              <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
              Tasdiqlash
            </button>
          </form>
        </div>

      </div>
    </div>
  </div>
  @endforeach
</div>
@endif

@endsection
