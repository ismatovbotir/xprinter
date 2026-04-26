@extends('layouts.marketplace')
@section('title', 'Jamoa')
@section('breadcrumb', 'Jamoa')

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">Jamoa</div>
        <div class="page-subtitle">{{ $company->brand ?? $company->name }} — operatorlar ro'yxati</div>
    </div>
    <a href="{{ route('marketplace.team.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Operator qo'shish
    </a>
</div>

<div class="card">
    @if($operators->isEmpty())
    <div style="padding:48px;text-align:center;color:var(--muted)">
        <svg viewBox="0 0 24 24" style="width:40px;height:40px;stroke:var(--faint);fill:none;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round;margin:0 auto 12px;display:block">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
            <line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/>
        </svg>
        Hali operatorlar yo'q. Birinchisini qo'shing.
    </div>
    @else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Xodim</th>
                    <th>Email</th>
                    <th>Til</th>
                    <th>Qo'shilgan</th>
                    <th style="width:60px"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($operators as $operator)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div class="user-avatar" style="width:32px;height:32px;font-size:11px;border-radius:8px">
                                {{ strtoupper(substr($operator->name, 0, 1)) }}
                            </div>
                            <span style="font-weight:500;color:var(--ink)">{{ $operator->name }}</span>
                        </div>
                    </td>
                    <td style="color:var(--muted);font-size:13px">{{ $operator->email }}</td>
                    <td>
                        <span style="font-family:'JetBrains Mono',monospace;font-size:11px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;background:var(--bg-soft);padding:3px 8px;border-radius:6px;color:var(--ink-soft)">
                            {{ strtoupper($operator->lang) }}
                        </span>
                    </td>
                    <td style="color:var(--muted);font-size:13px">{{ $operator->created_at->format('d.m.Y') }}</td>
                    <td>
                        <form method="POST" action="{{ route('marketplace.team.destroy', $operator) }}"
                              data-confirm="{{ $operator->name }} ni o'chirasizmi? Uning barcha ma'lumotlari sizga o'tkaziladi.">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-btn danger" title="O'chirish">
                                <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- Owner card at the bottom --}}
<div class="card" style="margin-top:16px;padding:16px">
    <div style="display:flex;align-items:center;gap:12px">
        <div class="user-avatar" style="width:40px;height:40px;font-size:14px;border-radius:10px;background:linear-gradient(135deg,var(--blue),var(--cyan))">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div>
            <div style="font-weight:600;color:var(--ink);font-size:14px">{{ auth()->user()->name }}</div>
            <div style="font-size:12px;color:var(--muted)">{{ auth()->user()->email }}</div>
        </div>
        <div style="margin-left:auto">
            <span style="font-family:'JetBrains Mono',monospace;font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;background:var(--blue-soft);color:var(--blue-deep);padding:4px 10px;border-radius:20px">
                OWNER
            </span>
        </div>
    </div>
</div>

@endsection
