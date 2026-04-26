@extends('layouts.admin')
@section('title', 'Foydalanuvchilar')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Foydalanuvchilar</div>
    <div class="page-subtitle">Jami {{ $users->total() }} ta foydalanuvchi</div>
  </div>
  <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
    <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Foydalanuvchi qo'shish
  </a>
</div>

<form method="GET" action="{{ route('admin.users.index') }}" class="filter-bar">
  <div class="filter-search">
    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ism yoki email...">
  </div>
  <select name="role" class="filter-select" onchange="this.form.submit()">
    <option value="">Barcha rollar</option>
    @foreach(['admin','producer','owner','user','client'] as $r)
    <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
    @endforeach
  </select>
  @if(request('search') || request('role'))
    <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Tozalash</a>
  @endif
</form>

<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Foydalanuvchi</th>
          <th style="width:90px">Rol</th>
          <th>Kompaniya</th>
          <th style="width:60px;text-align:center">Til</th>
          <th style="width:130px">Ro'yxat sanasi</th>
          <th style="width:80px"></th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $user)
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              <div style="width:32px;height:32px;border-radius:10px;background:linear-gradient(135deg,var(--blue),var(--cyan));display:flex;align-items:center;justify-content:center;font-family:'Unbounded',sans-serif;font-size:11px;font-weight:700;color:#fff;flex-shrink:0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
              </div>
              <div>
                <div style="font-weight:600;color:var(--ink)">{{ $user->name }}</div>
                <div style="font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--muted)">{{ $user->email }}</div>
              </div>
            </div>
          </td>
          <td>
            @php
              $roleColors = ['admin' => 'var(--blue)', 'producer' => 'var(--cyan)', 'owner' => 'var(--green)', 'user' => 'var(--muted)', 'client' => 'var(--faint)'];
            @endphp
            <span style="font-family:'JetBrains Mono',monospace;font-size:10px;text-transform:uppercase;letter-spacing:0.05em;font-weight:700;color:{{ $roleColors[$user->role] ?? 'var(--muted)' }}">
              {{ $user->role }}
            </span>
          </td>
          <td>
            @if($user->company)
            <span style="font-size:13px;color:var(--ink-soft);font-weight:500">
              {{ $user->company->brand ?? $user->company->name }}
            </span>
            @else
            <span style="color:var(--faint)">—</span>
            @endif
          </td>
          <td style="text-align:center">
            <span style="font-family:'JetBrains Mono',monospace;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase">
              {{ $user->lang }}
            </span>
          </td>
          <td style="font-size:12px;color:var(--muted)">{{ $user->created_at->format('d.m.Y') }}</td>
          <td>
            <div class="actions-cell">
              <a href="{{ route('admin.users.edit', $user) }}" class="action-btn" title="Tahrirlash">
                <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
              </a>
              @if($user->id !== auth()->id())
              <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                    data-confirm="{{ $user->name }}ni o'chirishni tasdiqlaysizmi?">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn danger" title="O'chirish">
                  <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                </button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="text-align:center;color:var(--muted);padding:48px 20px">
            @if(request('search') || request('role'))
              Filtr bo'yicha foydalanuvchi topilmadi
            @else
              Hali foydalanuvchilar yo'q
            @endif
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($users->hasPages())
  <div class="table-footer">
    {{ $users->links() }}
  </div>
  @endif
</div>

@endsection
