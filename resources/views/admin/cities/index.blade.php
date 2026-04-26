@extends('layouts.admin')
@section('title', 'Shaharlar')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Shaharlar</div>
    <div class="page-subtitle">Jami {{ $cities->total() }} ta shahar</div>
  </div>
  <a href="{{ route('admin.cities.create') }}" class="btn btn-primary">
    <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Shahar qo'shish
  </a>
</div>

<form method="GET" action="{{ route('admin.cities.index') }}" class="filter-bar">
  <div class="filter-search">
    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Shahar nomi bo'yicha qidirish...">
  </div>
  <select name="region_id" class="filter-select" onchange="this.form.submit()">
    <option value="">Barcha viloyatlar</option>
    @foreach($regions as $region)
      <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>
        {{ $region->translations->firstWhere('lang','uz')?->name ?? '—' }}
        ({{ $region->country->translations->firstWhere('lang','uz')?->name ?? '—' }})
      </option>
    @endforeach
  </select>
  @if(request('search') || request('region_id'))
    <a href="{{ route('admin.cities.index') }}" class="btn btn-ghost">Tozalash</a>
  @endif
</form>

<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Nomi (UZ)</th>
          <th>Nomi (RU)</th>
          <th>Viloyat</th>
          <th>Davlat</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($cities as $city)
        <tr>
          <td style="font-weight:600;color:var(--ink)">
            {{ $city->translations->firstWhere('lang','uz')?->name ?? '—' }}
          </td>
          <td style="color:var(--ink-soft)">
            {{ $city->translations->firstWhere('lang','ru')?->name ?? '—' }}
          </td>
          <td style="font-size:13px;color:var(--muted)">
            {{ $city->region->translations->firstWhere('lang','uz')?->name ?? '—' }}
          </td>
          <td style="font-size:13px;color:var(--faint)">
            {{ $city->region->country->translations->firstWhere('lang','uz')?->name ?? '—' }}
          </td>
          <td>
            <div class="actions-cell">
              <a href="{{ route('admin.cities.edit', $city) }}" class="action-btn" title="Tahrirlash">
                <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
              </a>
              <form method="POST" action="{{ route('admin.cities.destroy', $city) }}"
                    data-confirm="{{ $city->translations->firstWhere('lang','uz')?->name ?? 'Shahar' }}ni o'chirishni tasdiqlaysizmi?">
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
          <td colspan="5" style="text-align:center;color:var(--muted);padding:48px 20px">
            @if(request('search') || request('region_id'))
              Bunday shahar topilmadi
            @else
              Hali shaharlar qo'shilmagan
            @endif
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($cities->hasPages())
  <div class="table-footer">
    {{ $cities->links() }}
  </div>
  @endif
</div>

@endsection
