@extends('layouts.admin')
@section('title', 'Fayllar kutubxonasi')

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">Fayllar kutubxonasi <x-help-icon section="admin.files" /></div>
        <div class="page-subtitle">Drayverlar, manuallar, spesifikatsiyalar</div>
    </div>
    <a href="{{ route('admin.files.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Fayl qo'shish
    </a>
</div>

<div class="card">
    @if($files->isEmpty())
    <div style="padding:64px;text-align:center;color:var(--muted)">
        <svg viewBox="0 0 24 24" style="width:48px;height:48px;stroke:var(--faint);fill:none;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round;margin:0 auto 16px;display:block">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
        </svg>
        <div style="font-size:15px;font-weight:600;color:var(--ink-soft);margin-bottom:6px">Fayllar yo'q</div>
        <div style="font-size:13px;margin-bottom:20px">Drayver va manuallar yuklang</div>
        <a href="{{ route('admin.files.create') }}" class="btn btn-primary">Fayl qo'shish</a>
    </div>
    @else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nomi</th>
                    <th style="width:100px">Turi</th>
                    <th style="width:100px">Versiya</th>
                    <th style="width:120px">O'lchami</th>
                    <th style="width:100px">Mahsulotlar</th>
                    <th style="width:80px"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($files as $file)
                <tr>
                    <td>
                        <div style="font-weight:600;color:var(--ink);font-size:14px">{{ $file->name }}</div>
                        @if($file->description)
                        <div style="font-size:12px;color:var(--muted);margin-top:2px">{{ Str::limit($file->description, 80) }}</div>
                        @endif
                    </td>
                    <td>
                        <span style="font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--blue);font-weight:500;text-transform:uppercase">
                            {{ $file->file_type }}
                        </span>
                    </td>
                    <td style="color:var(--muted);font-size:13px">{{ $file->version ?? '—' }}</td>
                    <td style="color:var(--muted);font-size:13px;font-family:'JetBrains Mono',monospace">
                        {{ $file->size_formatted }}
                    </td>
                    <td style="text-align:center;color:var(--muted);font-size:13px">
                        {{ $file->products()->count() }}
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('admin.files.edit', $file) }}" class="action-btn" title="Tahrirlash">
                                <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.files.destroy', $file) }}" data-confirm="{{ $file->name }} faylini o'chirasizmi?">
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

    <div style="padding:16px;border-top:1px solid var(--line)">
        {{ $files->links() }}
    </div>
    @endif
</div>

@endsection
