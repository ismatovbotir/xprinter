@extends('layouts.admin')
@section('title', 'Yordam maqolalari')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Yordam maqolalari</div>
    <div class="page-subtitle">Marketplace-da qo'llaniladigan yordam va qo'llanma</div>
  </div>
  <a href="{{ route('admin.help.create') }}" class="btn btn-primary">+ Yangi maqola</a>
</div>

@if($articles->isEmpty())
  <div class="card" style="text-align:center;padding:40px">
    <div style="color:var(--muted);margin-bottom:16px">Hali maqola yo'q</div>
    <a href="{{ route('admin.help.create') }}" class="btn btn-primary">Birinchi maqolani qo'shish</a>
  </div>
@else
  <div class="card">
    <table style="width:100%;border-collapse:collapse">
      <thead>
        <tr style="border-bottom:1px solid var(--line);background:var(--bg-soft)">
          <th style="padding:12px 16px;text-align:left;font-size:13px;color:var(--muted);font-weight:600">Slug</th>
          <th style="padding:12px 16px;text-align:left;font-size:13px;color:var(--muted);font-weight:600">Bo'lim</th>
          <th style="padding:12px 16px;text-align:left;font-size:13px;color:var(--muted);font-weight:600">Joylashish</th>
          <th style="padding:12px 16px;text-align:left;font-size:13px;color:var(--muted);font-weight:600">Faol</th>
          <th style="padding:12px 16px;text-align:center;font-size:13px;color:var(--muted);font-weight:600">Amallar</th>
        </tr>
      </thead>
      <tbody>
        @foreach($articles as $article)
          <tr style="border-bottom:1px solid var(--line)">
            <td style="padding:12px 16px;font-size:13px;color:var(--ink)">
              <code style="background:var(--bg-soft);padding:4px 8px;border-radius:4px;font-family:monospace">{{ $article->slug }}</code>
            </td>
            <td style="padding:12px 16px;font-size:13px;color:var(--ink-soft)">
              {{ $article->section }}
            </td>
            <td style="padding:12px 16px;font-size:13px;color:var(--ink-soft)">
              <span style="display:inline-block;background:var(--blue-soft);color:var(--blue);padding:4px 8px;border-radius:4px">{{ $article->placement }}</span>
            </td>
            <td style="padding:12px 16px;font-size:13px;text-align:center">
              @if($article->is_active)
                <span style="color:var(--green);font-weight:600">✓ Faol</span>
              @else
                <span style="color:var(--muted)">○ Faol emas</span>
              @endif
            </td>
            <td style="padding:12px 16px;text-align:center;font-size:13px">
              <a href="{{ route('admin.help.edit', $article) }}" class="action-btn" title="Tahrirlash">
                <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
              <form method="POST" action="{{ route('admin.help.destroy', $article) }}" style="display:inline" data-confirm="Ishonchingiz komilmi?">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn danger" title="O'chirish">
                  <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6M10 11v6M14 11v6"/></svg>
                </button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div style="margin-top:20px">
    {{ $articles->links() }}
  </div>
@endif

@endsection
