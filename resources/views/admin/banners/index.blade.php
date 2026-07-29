@extends('layouts.admin')
@section('title', 'Bannerlar')

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">Bannerlar <x-help-icon section="admin.banners" /></div>
        <div class="page-subtitle">Bosh sahifa slayderlari</div>
    </div>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Banner qo'shish
    </a>
</div>

<div class="card">
    @if($banners->isEmpty())
    <div style="padding:64px;text-align:center;color:var(--muted)">
        <svg viewBox="0 0 24 24" style="width:48px;height:48px;stroke:var(--faint);fill:none;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round;margin:0 auto 16px;display:block">
            <rect x="3" y="3" width="18" height="14" rx="2"/><path d="M3 9h18"/>
        </svg>
        <div style="font-size:15px;font-weight:600;color:var(--ink-soft);margin-bottom:6px">Bannerlar yo'q</div>
        <div style="font-size:13px;margin-bottom:20px">Bosh sahifaga slider qo'shing</div>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">Banner qo'shish</a>
    </div>
    @else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th>Banner</th>
                    <th>Havola</th>
                    <th style="width:80px">Tartib</th>
                    <th style="width:100px">Holat</th>
                    <th style="width:80px"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($banners as $banner)
                <tr>
                    <td style="color:var(--faint);font-family:'JetBrains Mono',monospace;font-size:11px">{{ $banner->id }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:14px">
                            @if($banner->image_url)
                            <div style="width:80px;height:44px;border-radius:8px;overflow:hidden;border:1px solid var(--line);flex-shrink:0">
                                <img src="{{ $banner->image_url }}" alt="" style="width:100%;height:100%;object-fit:cover">
                            </div>
                            @else
                            <div style="width:80px;height:44px;border-radius:8px;background:var(--bg-soft);border:1px solid var(--line);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <svg viewBox="0 0 24 24" style="width:18px;height:18px;stroke:var(--faint);fill:none;stroke-width:1.5"><rect x="3" y="3" width="18" height="14" rx="2"/><path d="M3 9h18"/></svg>
                            </div>
                            @endif
                            <div>
                                <div style="font-weight:600;color:var(--ink);font-size:14px">{{ $banner->title }}</div>
                                @if($banner->subtitle)
                                <div style="font-size:12px;color:var(--muted);margin-top:2px">{{ Str::limit($banner->subtitle, 60) }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($banner->button_link)
                        <div style="font-size:12px;color:var(--muted);font-family:'JetBrains Mono',monospace;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                            {{ $banner->button_link }}
                        </div>
                        @else
                        <span style="color:var(--faint)">—</span>
                        @endif
                    </td>
                    <td>
                        <span style="font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--muted)">{{ $banner->sort_order }}</span>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.banners.toggle', $banner) }}" style="display:inline">
                            @csrf @method('PATCH')
                            <button type="submit" style="background:none;border:none;cursor:pointer;padding:0">
                                @if($banner->is_active)
                                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;background:#E0FAF3;border-radius:20px">
                                    <span style="width:5px;height:5px;border-radius:50%;background:var(--green);display:inline-block"></span>
                                    <span style="font-size:11px;font-weight:600;color:#007A5A;font-family:'JetBrains Mono',monospace">AKTIV</span>
                                </span>
                                @else
                                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;background:var(--bg-soft);border-radius:20px">
                                    <span style="width:5px;height:5px;border-radius:50%;background:var(--faint);display:inline-block"></span>
                                    <span style="font-size:11px;font-weight:600;color:var(--muted);font-family:'JetBrains Mono',monospace">YOPIQ</span>
                                </span>
                                @endif
                            </button>
                        </form>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('admin.banners.edit', $banner) }}" class="action-btn" title="Tahrirlash">
                                <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" data-confirm="{{ $banner->title }} bannerini o'chirasizmi?">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn danger" title="O'chirish">
                                    <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection
