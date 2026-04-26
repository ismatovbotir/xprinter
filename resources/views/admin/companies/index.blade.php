@extends('layouts.admin')
@section('title', 'Kompaniyalar')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Kompaniyalar</div>
    <div class="page-subtitle">Jami {{ $companies->total() }} ta kompaniya</div>
  </div>
</div>

<form method="GET" action="{{ route('admin.companies.index') }}" class="filter-bar">
  <div class="filter-search">
    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nomi, brend yoki INN...">
  </div>
  <select name="status" class="filter-select" onchange="this.form.submit()">
    <option value="">Barcha statuslar</option>
    <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Kutilmoqda</option>
    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Tasdiqlangan</option>
    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rad etilgan</option>
  </select>
  @if(request('search') || request('status'))
    <a href="{{ route('admin.companies.index') }}" class="btn btn-ghost">Tozalash</a>
  @endif
</form>

<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Kompaniya</th>
          <th style="width:120px">INN</th>
          <th style="width:140px">Telefon</th>
          <th style="width:120px">Turi</th>
          <th style="width:110px">Status</th>
          <th style="width:80px"></th>
        </tr>
      </thead>
      <tbody>
        @forelse($companies as $company)
        <tr>
          <td>
            <div class="company-cell">
              <div class="company-logo">{{ strtoupper(substr($company->brand ?? $company->name, 0, 2)) }}</div>
              <div>
                <div class="company-name">{{ $company->brand ?? $company->name }}</div>
                <div class="company-inn" style="color:var(--muted);font-size:11px">{{ $company->name }}</div>
              </div>
            </div>
          </td>
          <td>
            <span style="font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--ink)">
              {{ $company->inn ?? '—' }}
            </span>
          </td>
          <td style="font-size:13px;color:var(--ink-soft)">{{ $company->phone ?? '—' }}</td>
          <td>
            @if($company->types)
            <div class="type-tags">
              @foreach($company->types as $type)
              <span class="type-tag {{ $type }}">
                {{ match($type) { 'retail' => 'Chakana', 'partner' => 'Hamkor', 'service' => 'Servis', default => $type } }}
              </span>
              @endforeach
            </div>
            @else
            <span style="color:var(--faint)">—</span>
            @endif
          </td>
          <td>
            <span class="badge badge-{{ $company->status }}">
              {{ match($company->status) { 'pending' => 'Kutilmoqda', 'approved' => 'Tasdiqlangan', 'rejected' => 'Rad etildi', default => $company->status } }}
            </span>
          </td>
          <td>
            <div class="actions-cell">
              <a href="{{ route('admin.companies.show', $company) }}" class="action-btn" title="Ko'rish">
                <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </a>
              <a href="{{ route('admin.companies.edit', $company) }}" class="action-btn" title="Tahrirlash">
                <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
              </a>
              <form method="POST" action="{{ route('admin.companies.destroy', $company) }}"
                    data-confirm="{{ $company->name }}ni o'chirishni tasdiqlaysizmi?">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn danger" title="O'chirish">
                  <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="text-align:center;color:var(--muted);padding:48px 20px">
            @if(request('search') || request('status'))
              Filtr bo'yicha kompaniya topilmadi
            @else
              Hali kompaniyalar yo'q
            @endif
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($companies->hasPages())
  <div class="table-footer">
    {{ $companies->links() }}
  </div>
  @endif
</div>

@endsection
