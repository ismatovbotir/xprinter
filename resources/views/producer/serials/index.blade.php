@extends('layouts.producer')
@section('title', 'Seriya raqamlari')
@section('breadcrumb', 'Seriya raqamlari')

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">Seriya raqamlari</div>
        <div class="page-subtitle">Jami: {{ $serials->total() }} ta</div>
    </div>
    <a href="{{ route('producer.serials.import') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        CSV import
    </a>
</div>

{{-- Filters --}}
<form method="GET" style="display:flex;gap:12px;margin-bottom:16px;flex-wrap:wrap">
    <select name="product_id" class="form-input" style="width:auto;flex:1;min-width:200px" onchange="this.form.submit()">
        <option value="">Barcha mahsulotlar</option>
        @foreach($products as $product)
        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
            {{ $product->translation?->name ?? $product->model_number }}
        </option>
        @endforeach
    </select>
    <select name="status" class="form-input" style="width:auto;min-width:160px" onchange="this.form.submit()">
        <option value="">Barcha statuslar</option>
        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Mavjud</option>
        <option value="sold" {{ request('status') === 'sold' ? 'selected' : '' }}>Sotilgan</option>
        <option value="registered" {{ request('status') === 'registered' ? 'selected' : '' }}>Ro'yxatdan o'tgan</option>
        <option value="warranty_claim" {{ request('status') === 'warranty_claim' ? 'selected' : '' }}>Kafolat</option>
    </select>
</form>

<div class="card">
    @if($serials->isEmpty())
    <div style="padding:48px;text-align:center;color:var(--muted)">Seriya raqamlari topilmadi</div>
    @else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Seriya raqam</th>
                    <th>Mahsulot</th>
                    <th>Status</th>
                    <th>Kafolat tugash</th>
                    <th>Qo'shilgan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($serials as $serial)
                <tr>
                    <td>
                        <span style="font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:600;color:var(--ink)">
                            {{ $serial->serial_number }}
                        </span>
                    </td>
                    <td style="color:var(--muted);font-size:13px">
                        {{ $serial->product->translation?->name ?? $serial->product->model_number }}
                    </td>
                    <td>
                        @php
                        $statusColors = [
                            'available'      => ['bg'=>'var(--blue-soft)','color'=>'var(--blue-deep)','label'=>'Mavjud'],
                            'sold'           => ['bg'=>'#E0FAF3','color'=>'#007A5A','label'=>'Sotilgan'],
                            'registered'     => ['bg'=>'#F0EAFF','color'=>'#4B00B8','label'=>'Ro\'yxatdan o\'tgan'],
                            'warranty_claim' => ['bg'=>'#FFEBEE','color'=>'#B0001E','label'=>'Kafolat'],
                        ];
                        $sc = $statusColors[$serial->status] ?? ['bg'=>'var(--bg-soft)','color'=>'var(--muted)','label'=>$serial->status];
                        @endphp
                        <span style="display:inline-block;padding:3px 10px;border-radius:20px;font-family:'JetBrains Mono',monospace;font-size:10px;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;background:{{ $sc['bg'] }};color:{{ $sc['color'] }}">
                            {{ $sc['label'] }}
                        </span>
                    </td>
                    <td style="font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--muted)">
                        {{ $serial->warranty_expires_at?->format('d.m.Y') ?? '—' }}
                    </td>
                    <td style="font-size:12px;color:var(--muted)">{{ $serial->created_at->format('d.m.Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:16px 20px;border-top:1px solid var(--line)">
        {{ $serials->links() }}
    </div>
    @endif
</div>

@endsection
