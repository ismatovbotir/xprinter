@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Dashboard</div>
    <div class="page-subtitle">Xush kelibsiz, {{ auth()->user()->name }} — {{ now()->translatedFormat('d F Y') }}</div>
  </div>
  <div style="display:flex;gap:10px">
    <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
      <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Kompaniya qo'shish
    </a>
  </div>
</div>

{{-- Stats --}}
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon blue">
      <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
    </div>
    <div class="stat-label">Kompaniyalar</div>
    <div class="stat-value">{{ $stats['companies_total'] }}</div>
    <div class="stat-delta muted">Jami ro'yxatdan o'tganlar</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon orange">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
    <div class="stat-label">Kutilayotganlar</div>
    <div class="stat-value">{{ $stats['companies_pending'] }}</div>
    <div class="stat-delta warn">● Ko'rib chiqish kerak</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon green">
      <svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-4 0v2"/></svg>
    </div>
    <div class="stat-label">Mahsulotlar</div>
    <div class="stat-value">{{ $stats['products_total'] }}</div>
    <div class="stat-delta muted">Katalogdagi jami</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon cyan">
      <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
    </div>
    <div class="stat-label">Foydalanuvchilar</div>
    <div class="stat-value">{{ $stats['users_total'] }}</div>
    <div class="stat-delta muted">Owner va user rollari</div>
  </div>
</div>

{{-- Two column --}}
<div style="display:grid;grid-template-columns:1fr 340px;gap:20px">

  {{-- Companies table --}}
  <div class="card">
    <div class="card-header">
      <div class="card-title">Kompaniyalar</div>
      <a href="{{ route('admin.companies.index') }}" class="btn btn-ghost" style="height:32px;padding:0 14px;font-size:12px">
        Barchasi
      </a>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Kompaniya</th>
            <th>Turi</th>
            <th>Status</th>
            <th>Shahar</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse($companies as $company)
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:10px">
                <div style="width:32px;height:32px;border-radius:8px;background:var(--bg-blue);border:1px solid var(--line);display:flex;align-items:center;justify-content:center;font-family:'Unbounded',sans-serif;font-size:10px;font-weight:700;color:var(--blue-deep);flex-shrink:0">
                  {{ strtoupper(substr($company->name, 0, 2)) }}
                </div>
                <div>
                  <div style="font-weight:600;color:var(--ink);font-size:13.5px">{{ $company->name }}</div>
                  <div style="font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--muted)">
                    ИНН: {{ $company->inn ?? '—' }}
                  </div>
                </div>
              </div>
            </td>
            <td>
              <div class="type-tags">
                @foreach($company->types ?? [] as $type)
                  <span class="type-tag {{ $type === 'service' ? 'service' : ($type === 'partner' ? 'partner' : '') }}">
                    {{ match($type) { 'retail' => 'Chakana', 'partner' => 'Hamkorlik', 'service' => 'Servis', default => $type } }}
                  </span>
                @endforeach
              </div>
            </td>
            <td>
              <span class="badge badge-{{ $company->status }}">
                {{ match($company->status) { 'approved' => 'Tasdiqlangan', 'pending' => 'Kutilmoqda', 'rejected' => 'Rad etilgan', default => $company->status } }}
              </span>
            </td>
            <td style="color:var(--muted);font-size:13px">—</td>
            <td>
              <div class="actions-cell">
                <a href="{{ route('admin.companies.edit', $company) }}" class="action-btn">
                  <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                </a>
                <a href="{{ route('admin.companies.show', $company) }}" class="action-btn">
                  <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </a>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" style="text-align:center;color:var(--muted);padding:32px">
              Hali kompaniyalar yo'q
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Pending approvals --}}
  <div class="card">
    <div class="card-header">
      <div class="card-title">Kutilayotganlar</div>
      @if($pending->count())
        <span class="card-count" style="background:#FFF3E0;border-color:#FFD8A8;color:#8B5000">
          {{ $pending->count() }} ta
        </span>
      @endif
    </div>

    @forelse($pending as $company)
    <div style="display:flex;align-items:center;gap:12px;padding:14px 20px;border-bottom:1px solid var(--line)">
      <div style="width:38px;height:38px;border-radius:10px;background:var(--bg-blue);border:1px solid var(--line);display:flex;align-items:center;justify-content:center;font-family:'Unbounded',sans-serif;font-size:11px;font-weight:700;color:var(--blue-deep);flex-shrink:0">
        {{ strtoupper(substr($company->name, 0, 2)) }}
      </div>
      <div style="flex:1;min-width:0">
        <div style="font-weight:600;font-size:13px;color:var(--ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
          {{ $company->name }}
        </div>
        <div style="font-family:'JetBrains Mono',monospace;font-size:10px;color:var(--muted)">
          ИНН: {{ $company->inn ?? '—' }}
        </div>
      </div>
      <div style="display:flex;gap:5px;flex-shrink:0">
        <form method="POST" action="{{ route('admin.companies.approve', $company) }}">
          @csrf @method('PATCH')
          <button type="submit" class="action-btn" title="Tasdiqlash"
                  style="background:#E0FAF3;border-color:#B2EFD8">
            <svg viewBox="0 0 24 24" style="stroke:var(--green)"><polyline points="20 6 9 17 4 12"/></svg>
          </button>
        </form>
        <form method="POST" action="{{ route('admin.companies.reject', $company) }}">
          @csrf @method('PATCH')
          <button type="submit" class="action-btn danger" title="Rad etish">
            <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </form>
      </div>
    </div>
    @empty
    <div style="padding:32px;text-align:center;color:var(--muted);font-size:13.5px">
      Kutilayotgan kompaniyalar yo'q
    </div>
    @endforelse
  </div>

</div>

@endsection
