@extends('layouts.marketplace')
@section('title', 'Assortiment')
@section('breadcrumb', 'Assortiment')

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">Assortiment</div>
        <div class="page-subtitle">{{ $company->brand ?? $company->name }} — mahsulotlar va narxlar</div>
    </div>
    <a href="{{ route('marketplace.assortiment.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Mahsulot qo'shish
    </a>
</div>

<div class="card">
    @if($items->isEmpty())
    <div style="padding:64px;text-align:center;color:var(--muted)">
        <svg viewBox="0 0 24 24" style="width:48px;height:48px;stroke:var(--faint);fill:none;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round;margin:0 auto 16px;display:block">
            <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>
            <line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
        </svg>
        <div style="font-size:15px;font-weight:600;color:var(--ink-soft);margin-bottom:6px">Assortiment bo'sh</div>
        <div style="font-size:13px;margin-bottom:20px">Katalogdan mahsulotlar qo'shing va narxlarini belgilang</div>
        <a href="{{ route('marketplace.assortiment.create') }}" class="btn btn-primary">Mahsulot qo'shish</a>
    </div>
    @else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Mahsulot</th>
                    <th>Kategoriya</th>
                    <th style="text-align:right">Chakana narx</th>
                    <th style="text-align:right">Ulgurji narx</th>
                    <th>Holat</th>
                    <th style="width:80px"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                @php
                    $retail    = $item->prices->firstWhere('type', 'retail');
                    $wholesale = $item->prices->firstWhere('type', 'wholesale');
                    $name      = $item->product->translation?->name ?? $item->product->model_number;
                    $catName   = $item->product->category->translations->firstWhere('lang', app()->getLocale())?->name
                                 ?? $item->product->category->translations->first()?->name;
                @endphp
                <tr>
                    <td>
                        <div style="font-weight:600;color:var(--ink);font-size:14px">{{ $name }}</div>
                        <div style="font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--muted);margin-top:2px">{{ $item->product->model_number }}</div>
                    </td>
                    <td style="color:var(--muted);font-size:13px">{{ $catName }}</td>
                    <td style="text-align:right">
                        @if($retail)
                        <div style="font-weight:600;color:var(--ink);font-size:14px">{{ number_format($retail->value, 0, '.', ' ') }}</div>
                        <div style="font-family:'JetBrains Mono',monospace;font-size:10px;color:var(--faint);text-transform:uppercase">{{ $retail->currency }}</div>
                        @else
                        <span style="color:var(--faint)">—</span>
                        @endif
                    </td>
                    <td style="text-align:right">
                        @if($wholesale)
                        <div style="font-weight:600;color:var(--ink);font-size:14px">{{ number_format($wholesale->value, 0, '.', ' ') }}</div>
                        <div style="font-family:'JetBrains Mono',monospace;font-size:10px;color:var(--faint);text-transform:uppercase">{{ $wholesale->currency }}</div>
                        @else
                        <span style="color:var(--faint)">—</span>
                        @endif
                    </td>
                    <td>
                        @if($item->is_available)
                        <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:linear-gradient(135deg,#E8F5E9,#C8E6C9);border-radius:20px">
                            <span style="width:5px;height:5px;border-radius:50%;background:#388E3C;display:inline-block"></span>
                            <span style="font-size:11px;font-weight:600;color:#1B5E20">Mavjud</span>
                        </span>
                        @else
                        <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:var(--bg-soft);border-radius:20px">
                            <span style="width:5px;height:5px;border-radius:50%;background:var(--faint);display:inline-block"></span>
                            <span style="font-size:11px;font-weight:600;color:var(--muted)">Yo'q</span>
                        </span>
                        @endif
                        @if($item->quantity !== null)
                        <div style="font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--faint);margin-top:3px">{{ $item->quantity }} dona</div>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('marketplace.assortiment.edit', $item) }}" class="action-btn" title="Tahrirlash">
                                <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('marketplace.assortiment.destroy', $item) }}" data-confirm="{{ $name }} ni assortimentdan olib tashlaysizmi?">
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
