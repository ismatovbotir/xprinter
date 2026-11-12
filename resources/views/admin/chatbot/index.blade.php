@extends('layouts.admin')
@section('title', 'Chatbot')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Chatbot</div>
    <div class="page-subtitle">Bot javob bera olmagan savollar va botga o'rgatilgan bilimlar</div>
  </div>
  <a href="{{ route('admin.chatbot.knowledge.create') }}" class="btn btn-primary">+ Bilim qo'shish</a>
</div>

<div style="display:flex;gap:8px;margin-bottom:16px">
  <a href="{{ route('admin.chatbot.index') }}" class="btn {{ $tab === 'questions' ? 'btn-primary' : 'btn-ghost' }}">
    Javobsiz savollar @if($openCount)<span class="nav-badge" style="margin-left:6px">{{ $openCount }}</span>@endif
  </a>
  <a href="{{ route('admin.chatbot.index', ['tab' => 'knowledge']) }}" class="btn {{ $tab === 'knowledge' ? 'btn-primary' : 'btn-ghost' }}">
    Bilimlar ({{ $knowledge->total() }})
  </a>
</div>

@if($tab === 'questions')
<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Savol</th>
          <th style="width:180px">Kimdan</th>
          <th style="width:90px">Kanal</th>
          <th style="width:110px">Holat</th>
          <th style="width:140px">Sana</th>
          <th style="width:90px"></th>
        </tr>
      </thead>
      <tbody>
        @forelse($questions as $q)
        <tr>
          <td style="font-size:13.5px">{{ Str::limit($q->question, 90) }}</td>
          <td style="font-size:13px;color:var(--ink-soft)">{{ $q->user ? trim($q->user->name . ' ' . $q->user->last_name) : 'Mehmon' }}</td>
          <td style="font-size:12px;text-transform:uppercase;color:var(--muted)">{{ $q->channel }} · {{ $q->lang }}</td>
          <td>
            @if($q->status === 'open')
              <span class="badge badge-pending">Ochiq</span>
            @else
              <span style="color:var(--green);font-weight:600;font-size:13px">✓ Javob berildi</span>
            @endif
          </td>
          <td style="font-size:12px;color:var(--muted)">{{ $q->created_at->diffForHumans() }}</td>
          <td>
            <div class="actions-cell">
              <a href="{{ route('admin.chatbot.questions.show', $q) }}" class="action-btn" title="Javob berish">
                <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
              <form method="POST" action="{{ route('admin.chatbot.questions.destroy', $q) }}" style="display:inline" data-confirm="O'chirilsinmi?">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn danger" title="O'chirish">
                  <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6M10 11v6M14 11v6"/></svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:48px 20px">Javobsiz savollar yo'q</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<div style="margin-top:16px">{{ $questions->appends(['tab' => 'questions'])->links() }}</div>

@else
<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Savol</th>
          <th>Javob</th>
          <th style="width:70px">Til</th>
          <th style="width:90px">Holat</th>
          <th style="width:90px"></th>
        </tr>
      </thead>
      <tbody>
        @forelse($knowledge as $k)
        <tr>
          <td style="font-size:13.5px">{{ Str::limit($k->question, 70) }}</td>
          <td style="font-size:13px;color:var(--ink-soft)">{{ Str::limit($k->answer, 90) }}</td>
          <td style="font-size:12px;text-transform:uppercase;color:var(--muted)">{{ $k->lang ?? 'uz/ru' }}</td>
          <td style="font-size:13px">
            @if($k->is_active)<span style="color:var(--green);font-weight:600">✓ Faol</span>@else<span style="color:var(--muted)">○ O'chiq</span>@endif
          </td>
          <td>
            <div class="actions-cell">
              <a href="{{ route('admin.chatbot.knowledge.edit', $k) }}" class="action-btn" title="Tahrirlash">
                <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
              <form method="POST" action="{{ route('admin.chatbot.knowledge.destroy', $k) }}" style="display:inline" data-confirm="O'chirilsinmi?">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn danger" title="O'chirish">
                  <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6M10 11v6M14 11v6"/></svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:48px 20px">Hali bilim qo'shilmagan. Bot faqat katalog va sayt ma'lumotlaridan javob beradi.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<div style="margin-top:16px">{{ $knowledge->appends(['tab' => 'knowledge'])->links() }}</div>
@endif

@endsection
