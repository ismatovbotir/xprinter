@extends('layouts.admin')
@section('title', 'Xabarlar')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Telegram xabarlari</div>
    <div class="page-subtitle">Jami {{ $clients->count() }} ta suhbat</div>
  </div>
</div>

<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Mijoz</th>
          <th style="width:140px">Telefon</th>
          <th>Oxirgi xabar</th>
          <th style="width:160px">Sana</th>
          <th style="width:90px"></th>
        </tr>
      </thead>
      <tbody>
        @forelse($clients as $client)
        @php($last = $client->clientMessages->first())
        <tr>
          <td>
            <div class="company-cell">
              <div class="company-logo">{{ strtoupper(substr($client->name, 0, 2)) }}</div>
              <div>
                <div class="company-name">
                  {{ trim($client->name . ' ' . ($client->last_name ?? '')) }}
                  @if($client->unread_count > 0)
                    <span class="badge badge-pending" style="margin-left:6px">{{ $client->unread_count }}</span>
                  @endif
                </div>
              </div>
            </div>
          </td>
          <td style="font-size:13px;color:var(--ink-soft)">{{ $client->phone ?? '—' }}</td>
          <td style="font-size:13px;color:var(--ink-soft)">
            {{ $last ? Str::limit($last->body, 60) : '—' }}
          </td>
          <td style="font-size:12px;color:var(--muted)">{{ $last?->created_at?->diffForHumans() }}</td>
          <td>
            <div class="actions-cell">
              <a href="{{ route('admin.messages.show', $client) }}" class="action-btn" title="Ochish">
                <svg viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
              </a>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" style="text-align:center;color:var(--muted);padding:48px 20px">
            Hali xabarlar yo'q
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
