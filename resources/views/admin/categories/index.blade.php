@extends('layouts.admin')
@section('title', 'Kategoriyalar')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Kategoriyalar</div>
    <div class="page-subtitle">Jami {{ $categories->total() }} ta kategoriya</div>
  </div>
  <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
    <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Kategoriya qo'shish
  </a>
</div>

@if(session('success'))
<div class="alert alert-success" style="margin-bottom:16px">{{ session('success') }}</div>
@endif

<form method="GET" action="{{ route('admin.categories.index') }}" class="filter-bar">
  <div class="filter-search">
    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nomi bo'yicha qidirish...">
  </div>
  @if(request('search'))
    <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost">Tozalash</a>
  @endif
</form>

<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th style="width:160px">Slug</th>
          <th>Nomi (UZ)</th>
          <th>Nomi (RU)</th>
          <th style="width:100px;text-align:center">Mahsulotlar</th>
          <th style="width:80px"></th>
        </tr>
      </thead>
      <tbody>
        @forelse($categories as $category)
        <tr>
          <td>
            <span style="font-family:'JetBrains Mono',monospace;font-size:12px;font-weight:600;color:var(--ink);background:var(--bg-soft);border:1px solid var(--line);padding:3px 10px;border-radius:6px">
              {{ $category->slug }}
            </span>
          </td>
          <td style="font-weight:600;color:var(--ink)">
            {{ $category->translations->firstWhere('lang','uz')?->name ?? '—' }}
          </td>
          <td style="color:var(--ink-soft)">
            {{ $category->translations->firstWhere('lang','ru')?->name ?? '—' }}
          </td>
          <td style="text-align:center">
            <span style="font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:600;color:var(--blue)">
              {{ $category->products_count }}
            </span>
          </td>
          <td>
            <div class="actions-cell">
              <a href="{{ route('admin.categories.edit', $category) }}" class="action-btn" title="Tahrirlash">
                <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
              </a>
              <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                    onsubmit="return confirm('{{ $category->translations->firstWhere('lang','uz')?->name ?? 'Kategoriya' }}ni o\'chirishni tasdiqlaysizmi?')">
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
            @if(request('search'))
              «{{ request('search') }}» bo'yicha natija topilmadi
            @else
              Hali kategoriyalar qo'shilmagan
            @endif
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($categories->hasPages())
  <div class="table-footer">
    {{ $categories->links() }}
  </div>
  @endif
</div>

@endsection
