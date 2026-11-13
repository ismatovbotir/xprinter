@extends('layouts.admin')
@section('title', 'Bilimlar bazasi')
@section('breadcrumb', 'Bilimlar bazasi')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Bilimlar bazasi</div>
    <div class="page-subtitle">Muammolarni hal qilish, sozlamalar, drayverlar va utilitalar haqida maqolalar — /support va chatbot uchun</div>
  </div>
  <a href="{{ route('admin.kb.create') }}" class="btn btn-primary">
    <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Yangi maqola
  </a>
</div>

<form method="GET" action="{{ route('admin.kb.index') }}" class="filter-bar">
  <div class="filter-search">
    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Sarlavha, kalit so'z...">
  </div>
  <select name="type" class="filter-select" onchange="this.form.submit()">
    <option value="">Barcha turlar</option>
    @foreach($types as $key => $label)
      <option value="{{ $key }}" @selected(request('type') === $key)>{{ $label }}</option>
    @endforeach
  </select>
  <select name="status" class="filter-select" onchange="this.form.submit()">
    <option value="">Barcha holatlar</option>
    <option value="published" @selected(request('status') === 'published')>Nashr qilingan</option>
    <option value="draft"     @selected(request('status') === 'draft')>Qoralama</option>
  </select>
  @if(request()->hasAny(['search', 'type', 'status']))
    <a href="{{ route('admin.kb.index') }}" class="btn btn-ghost">Tozalash</a>
  @endif
</form>

<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Maqola</th>
          <th style="width:150px">Turi</th>
          <th style="width:110px">Modellar</th>
          <th style="width:90px">Ko'rishlar</th>
          <th style="width:110px">Holat</th>
          <th style="width:90px"></th>
        </tr>
      </thead>
      <tbody>
        @forelse($articles as $article)
          @php $t = $article->translations->firstWhere('lang', 'uz') ?? $article->translations->first(); @endphp
          <tr>
            <td>
              <div style="font-weight:600;color:var(--ink)">{{ $t?->title ?? $article->slug }}</div>
              <div style="font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--muted)">/support/article/{{ $article->slug }}</div>
            </td>
            <td><span class="type-tag">{{ $types[$article->type] ?? $article->type }}</span></td>
            <td style="font-size:13px;color:var(--ink-soft)">
              @if($article->products_count)
                {{ $article->products->take(2)->pluck('model_number')->implode(', ') }}{{ $article->products_count > 2 ? ' +' . ($article->products_count - 2) : '' }}
              @elseif($article->category_id)
                <span style="color:var(--muted)">Kategoriya</span>
              @else
                <span style="color:var(--faint)">Umumiy</span>
              @endif
            </td>
            <td style="font-family:'JetBrains Mono',monospace;font-size:12px">{{ $article->views_count }}</td>
            <td>
              <span class="badge badge-{{ $article->is_published ? 'approved' : 'pending' }}">
                {{ $article->is_published ? 'Nashr' : 'Qoralama' }}
              </span>
            </td>
            <td>
              <div class="actions-cell">
                <a href="{{ route('support.show', $article->slug) }}" target="_blank" class="action-btn" title="Ko'rish">
                  <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </a>
                <a href="{{ route('admin.kb.edit', $article) }}" class="action-btn" title="Tahrirlash">
                  <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </a>
                <form method="POST" action="{{ route('admin.kb.destroy', $article) }}" onsubmit="return confirm('O\'chirishni tasdiqlaysizmi?')" style="display:inline">
                  @csrf @method('DELETE')
                  <button type="submit" class="action-btn" title="O'chirish" style="color:var(--red)">
                    <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted)">Hali maqolalar yo'q. <a href="{{ route('admin.kb.create') }}">Birinchisini yarating</a> yoki <code>php artisan db:seed --class=KbSeeder</code> bilan boshlang'ich to'plamni yuklang.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($articles->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--line)">{{ $articles->links() }}</div>
  @endif
</div>

@endsection
