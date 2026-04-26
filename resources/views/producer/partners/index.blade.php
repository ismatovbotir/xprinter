@extends('layouts.producer')
@section('title', 'Hamkorlar')
@section('breadcrumb', 'Hamkorlar')

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">Hamkorlar</div>
        <div class="page-subtitle">{{ $partners->count() }} ta faol diler — faqat status boshqaruvi</div>
    </div>
</div>

<div class="card">
    @if($partners->isEmpty())
    <div style="padding:48px;text-align:center;color:var(--muted)">Faol dilerlar yo'q</div>
    @else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Diler</th>
                    <th>Faoliyat turi</th>
                    <th>Xodimlar</th>
                    <th>Hamkorlik statusi</th>
                    <th style="width:200px">Status o'zgartirish</th>
                </tr>
            </thead>
            <tbody>
                @foreach($partners as $i => $partner)
                <tr>
                    <td style="font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--faint)">{{ $i + 1 }}</td>
                    <td>
                        <div class="user-avatar" style="width:32px;height:32px;border-radius:8px;font-size:11px;display:inline-flex;vertical-align:middle;margin-right:8px">
                            {{ strtoupper(substr($partner->brand ?? 'D', 0, 1)) }}
                        </div>
                        <span style="font-weight:600;color:var(--ink);font-size:14px">{{ $partner->brand ?? 'Diler #' . ($i+1) }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:5px;flex-wrap:wrap">
                            @foreach($partner->types ?? [] as $type)
                            <span class="type-tag {{ $type }}">{{ ['retail'=>'Chakana','partner'=>'Hamkor','service'=>'Servis'][$type] ?? $type }}</span>
                            @endforeach
                        </div>
                    </td>
                    <td style="font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:600;color:var(--ink)">{{ $partner->users_count }}</td>
                    <td>
                        @if($partner->manufacturer_status === 'authorized_distributor')
                        <span class="badge" style="background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;border:none">Auth. Distributor</span>
                        @elseif($partner->manufacturer_status === 'authorized_partner')
                        <span class="badge badge-approved">Auth. Partner</span>
                        @else
                        <span class="badge" style="background:var(--bg-soft);color:var(--muted);border:none">—</span>
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('producer.partners.status', $partner) }}"
                              style="display:flex;gap:8px;align-items:center">
                            @csrf @method('PATCH')
                            <select name="manufacturer_status" class="form-input" style="font-size:12px;padding:6px 10px;height:auto"
                                    onchange="this.form.submit()">
                                <option value="none" {{ $partner->manufacturer_status === 'none' ? 'selected' : '' }}>Oddiy diler</option>
                                <option value="authorized_partner" {{ $partner->manufacturer_status === 'authorized_partner' ? 'selected' : '' }}>Auth. Partner</option>
                                <option value="authorized_distributor" {{ $partner->manufacturer_status === 'authorized_distributor' ? 'selected' : '' }}>Auth. Distributor</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection
