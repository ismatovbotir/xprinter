@extends('layouts.admin')
@section('title', $company->brand ?? $company->name)

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">{{ $company->brand ?? $company->name }}</div>
    <div class="page-subtitle">
      <span class="badge badge-{{ $company->status }}">
        {{ match($company->status) { 'pending' => 'Kutilmoqda', 'approved' => 'Tasdiqlangan', 'rejected' => 'Rad etildi', default => $company->status } }}
      </span>
    </div>
  </div>
  <div style="display:flex;gap:8px;flex-wrap:wrap">
    @if($company->status === 'pending')
    <form method="POST" action="{{ route('admin.companies.reject', $company) }}">
      @csrf @method('PATCH')
      <button class="btn btn-danger" onclick="return confirm('Rad etishni tasdiqlaysizmi?')">
        <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        Rad etish
      </button>
    </form>
    <form method="POST" action="{{ route('admin.companies.approve', $company) }}">
      @csrf @method('PATCH')
      <button class="btn btn-success">
        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        Tasdiqlash
      </button>
    </form>
    @endif
    <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-ghost">
      <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
      Tahrirlash
    </a>
    <a href="{{ route('admin.companies.index') }}" class="btn btn-ghost">
      <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
      Orqaga
    </a>
  </div>
</div>

<div class="two-col">

  {{-- Left: details --}}
  <div style="display:flex;flex-direction:column;gap:16px">

    {{-- Basic info --}}
    <div class="card">
      <div class="card-header"><div class="card-title">Asosiy ma'lumotlar</div></div>
      <div style="padding:20px;display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div>
          <div style="font-family:'JetBrains Mono',monospace;font-size:9px;text-transform:uppercase;letter-spacing:0.1em;color:var(--faint);margin-bottom:4px">Kompaniya nomi</div>
          <div style="font-weight:600;color:var(--ink)">{{ $company->name ?: '—' }}</div>
        </div>
        <div>
          <div style="font-family:'JetBrains Mono',monospace;font-size:9px;text-transform:uppercase;letter-spacing:0.1em;color:var(--faint);margin-bottom:4px">Brend</div>
          <div style="font-weight:600;color:var(--ink)">{{ $company->brand ?: '—' }}</div>
        </div>
        <div>
          <div style="font-family:'JetBrains Mono',monospace;font-size:9px;text-transform:uppercase;letter-spacing:0.1em;color:var(--faint);margin-bottom:4px">INN</div>
          <div style="font-family:'JetBrains Mono',monospace;font-size:14px;font-weight:700;color:var(--ink)">{{ $company->inn ?: '—' }}</div>
        </div>
        <div>
          <div style="font-family:'JetBrains Mono',monospace;font-size:9px;text-transform:uppercase;letter-spacing:0.1em;color:var(--faint);margin-bottom:4px">Telefon</div>
          <div style="color:var(--ink-soft)">{{ $company->phone ?: '—' }}</div>
        </div>
        <div>
          <div style="font-family:'JetBrains Mono',monospace;font-size:9px;text-transform:uppercase;letter-spacing:0.1em;color:var(--faint);margin-bottom:4px">Huquqiy shakl</div>
          <div style="color:var(--ink-soft)">{{ $company->legal_form ?: '—' }}</div>
        </div>
        <div>
          <div style="font-family:'JetBrains Mono',monospace;font-size:9px;text-transform:uppercase;letter-spacing:0.1em;color:var(--faint);margin-bottom:4px">Faoliyat turi</div>
          <div class="type-tags">
            @foreach($company->types ?? [] as $type)
            <span class="type-tag {{ $type }}">
              {{ match($type) { 'retail' => 'Chakana', 'partner' => 'Hamkor', 'service' => 'Servis', default => $type } }}
            </span>
            @endforeach
          </div>
        </div>
        <div>
          <div style="font-family:'JetBrains Mono',monospace;font-size:9px;text-transform:uppercase;letter-spacing:0.1em;color:var(--faint);margin-bottom:4px">NDS holati</div>
          <span class="badge {{ $company->vat_status === 'payer' ? 'badge-approved' : 'badge-pending' }}">
            {{ $company->vat_status === 'payer' ? 'NDS to\'lovchi' : 'NDS to\'lovchi emas' }}
          </span>
        </div>
        <div>
          <div style="font-family:'JetBrains Mono',monospace;font-size:9px;text-transform:uppercase;letter-spacing:0.1em;color:var(--faint);margin-bottom:4px">Ishlab chiqaruvchi holati</div>
          <span style="font-size:12px;font-weight:600;color:var(--ink-soft)">
            {{ match($company->manufacturer_status) {
              'authorized_partner'      => 'Vakolatli hamkor',
              'authorized_distributor'  => 'Vakolatli distribyutor',
              default                   => 'Oddiy diler',
            } }}
          </span>
        </div>
        <div>
          <div style="font-family:'JetBrains Mono',monospace;font-size:9px;text-transform:uppercase;letter-spacing:0.1em;color:var(--faint);margin-bottom:4px">Ro'yxatdan o'tgan</div>
          <div style="color:var(--muted);font-size:13px">{{ $company->created_at->format('d.m.Y H:i') }}</div>
        </div>
      </div>
    </div>

    {{-- Users --}}
    @if($company->users->isNotEmpty())
    <div class="card">
      <div class="card-header">
        <div class="card-title">Foydalanuvchilar</div>
        <span class="card-count">{{ $company->users->count() }}</span>
      </div>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Ism</th>
              <th>Email</th>
              <th style="width:90px">Rol</th>
              <th style="width:70px">Til</th>
            </tr>
          </thead>
          <tbody>
            @foreach($company->users as $u)
            <tr>
              <td style="font-weight:600;color:var(--ink)">{{ $u->name }}</td>
              <td style="font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--muted)">{{ $u->email }}</td>
              <td>
                <span style="font-family:'JetBrains Mono',monospace;font-size:10px;text-transform:uppercase;letter-spacing:0.05em;font-weight:600;color:var(--blue-deep);background:var(--blue-soft);padding:3px 8px;border-radius:6px">
                  {{ $u->role }}
                </span>
              </td>
              <td style="font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--muted);text-transform:uppercase">{{ $u->lang }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif

    {{-- Addresses --}}
    @if($company->addresses->isNotEmpty())
    <div class="card">
      <div class="card-header">
        <div class="card-title">Manzillar</div>
        <span class="card-count">{{ $company->addresses->count() }}</span>
      </div>
      <div style="padding:16px 22px;display:flex;flex-direction:column;gap:10px">
        @foreach($company->addresses as $addr)
        <div style="padding:12px;background:var(--bg-soft);border-radius:10px;font-size:13.5px;color:var(--ink-soft)">
          {{ $addr->name ? "«{$addr->name}» — " : '' }}{{ $addr->description ?? '' }}
        </div>
        @endforeach
      </div>
    </div>
    @endif

  </div>

</div>

@endsection
