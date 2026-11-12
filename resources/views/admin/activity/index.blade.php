@extends('layouts.admin')
@section('title', 'Audit log')
@section('breadcrumb', 'Audit log')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Audit log</div>
    <div class="page-subtitle">Kim, qachon va nimani o'zgartirgani — kompaniyalar, narxlar, seriya raqamlari, foydalanuvchilar</div>
  </div>
</div>

<form method="GET" action="{{ route('admin.activity.index') }}" class="filter-bar">
  <div class="filter-search">
    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" name="search" value="{{ request('search') }}" placeholder="ID yoki qiymat bo'yicha...">
  </div>
  <select name="log" class="filter-select" onchange="this.form.submit()">
    <option value="">Barcha bo'limlar</option>
    @foreach($logs as $log)
      <option value="{{ $log }}" @selected(request('log') === $log)>{{ ucfirst($log) }}</option>
    @endforeach
  </select>
  <select name="event" class="filter-select" onchange="this.form.submit()">
    <option value="">Barcha hodisalar</option>
    @foreach($events as $event)
      <option value="{{ $event }}" @selected(request('event') === $event)>{{ ucfirst($event) }}</option>
    @endforeach
  </select>
  @if(request()->hasAny(['search', 'log', 'event']))
    <a href="{{ route('admin.activity.index') }}" class="btn btn-ghost">Tozalash</a>
  @endif
</form>

<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th style="width:150px">Vaqt</th>
          <th style="width:180px">Kim</th>
          <th style="width:110px">Bo'lim</th>
          <th style="width:90px">Hodisa</th>
          <th style="width:200px">Obyekt</th>
          <th>O'zgarishlar</th>
        </tr>
      </thead>
      <tbody>
        @forelse($activities as $activity)
          @php
            $new = $activity->properties['attributes'] ?? [];
            $old = $activity->properties['old'] ?? [];
            $subjectLabel = $activity->subject
                ? (method_exists($activity->subject, 'displayName') ? $activity->subject->displayName()
                   : ($activity->subject->serial_number ?? $activity->subject->name ?? $activity->subject->email ?? '#'.$activity->subject_id))
                : '#'.$activity->subject_id;
          @endphp
          <tr>
            <td style="font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--ink-soft)">
              {{ $activity->created_at->format('d.m.Y H:i') }}
            </td>
            <td style="font-size:13px">
              @if($activity->causer)
                <div style="font-weight:600;color:var(--ink)">{{ $activity->causer->name }}</div>
                <div style="font-size:11px;color:var(--muted)">{{ strtoupper($activity->causer->role ?? '') }}</div>
              @else
                <span style="color:var(--faint)">system</span>
              @endif
            </td>
            <td><span class="type-tag">{{ $activity->log_name }}</span></td>
            <td>
              <span class="badge badge-{{ match($activity->event) { 'created' => 'approved', 'deleted' => 'rejected', default => 'pending' } }}">
                {{ $activity->event }}
              </span>
            </td>
            <td style="font-size:13px">
              <div style="font-weight:600;color:var(--ink)">{{ $subjectLabel }}</div>
              <div style="font-family:'JetBrains Mono',monospace;font-size:10px;color:var(--muted)">{{ class_basename($activity->subject_type) }}</div>
            </td>
            <td style="font-size:12px">
              @forelse($new as $key => $value)
                <div style="display:flex;gap:6px;align-items:baseline;margin-bottom:2px">
                  <span style="font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--muted)">{{ $key }}</span>
                  @if(array_key_exists($key, $old))
                    <span style="color:var(--red);text-decoration:line-through">{{ is_array($old[$key]) ? json_encode($old[$key]) : ($old[$key] ?? '—') }}</span>
                    <span style="color:var(--faint)">→</span>
                  @endif
                  <span style="color:var(--ink);font-weight:600">{{ is_array($value) ? json_encode($value) : ($value ?? '—') }}</span>
                </div>
              @empty
                <span style="color:var(--faint)">—</span>
              @endforelse
            </td>
          </tr>
        @empty
          <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted)">Hali yozuvlar yo'q</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($activities->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--line)">{{ $activities->links() }}</div>
  @endif
</div>

@endsection
