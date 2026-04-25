@extends('layouts.admin')
@section('title', 'Tarjimalar')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Tarjimalar</div>
    <div class="page-subtitle">Interfeys matnlarini UZ / RU tillariga tarjima qilish</div>
  </div>
</div>

{{-- Add new key --}}
<div class="card" style="margin-bottom:20px">
  <div class="card-header">
    <div class="card-title">Yangi kalit qo'shish</div>
  </div>
  <div style="padding:20px">
    <form method="POST" action="{{ route('admin.translations.add-key') }}">
      @csrf
      <div style="display:grid;grid-template-columns:160px 220px 1fr 1fr auto;gap:12px;align-items:end">
        <div class="form-group">
          <label class="form-label">Guruh</label>
          <input type="text" name="group" class="form-input" placeholder="menu" value="{{ old('group') }}" required>
        </div>
        <div class="form-group">
          <label class="form-label">Kalit</label>
          <input type="text" name="key" class="form-input" placeholder="dashboard" value="{{ old('key') }}" required>
        </div>
        <div class="form-group">
          <label class="form-label">O'zbekcha <span style="color:#D32F2F">*</span></label>
          <input type="text" name="value_uz" class="form-input" placeholder="Bosh sahifa" value="{{ old('value_uz') }}" required>
        </div>
        <div class="form-group">
          <label class="form-label">Ruscha <span style="color:#D32F2F">*</span></label>
          <input type="text" name="value_ru" class="form-input" placeholder="Дашборд" value="{{ old('value_ru') }}" required>
        </div>
        <button type="submit" class="btn btn-primary" style="height:42px">
          <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Qo'shish
        </button>
      </div>
      @foreach(['group','key','value_uz','value_ru'] as $f)
        @error($f) <span class="form-error" style="margin-top:8px;display:block">{{ $message }}</span> @enderror
      @endforeach
    </form>
  </div>
</div>

{{-- Groups --}}
@forelse($groups as $group => $keys)
<div class="card" style="margin-bottom:20px">
  <div class="card-header">
    <div class="card-title" style="display:flex;align-items:center;gap:10px">
      <span style="font-family:'JetBrains Mono',monospace;font-size:11px;background:var(--blue-soft);color:var(--blue-deep);padding:3px 10px;border-radius:6px;font-weight:600">
        {{ $group }}
      </span>
      <span style="font-family:'Manrope',sans-serif;font-size:13px;color:var(--muted);font-weight:400">
        {{ count($keys) }} kalit
      </span>
    </div>
    <button type="submit" form="form-{{ $group }}" class="btn btn-ghost" style="height:32px;padding:0 14px;font-size:12px">
      <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
      Saqlash
    </button>
  </div>

  <form method="POST" action="{{ route('admin.translations.store') }}" id="form-{{ $group }}">
    @csrf
    <input type="hidden" name="group" value="{{ $group }}">

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th style="width:200px">Kalit</th>
            <th>O'zbekcha</th>
            <th>Ruscha</th>
            <th style="width:48px"></th>
          </tr>
        </thead>
        <tbody>
          @foreach($keys as $key => $langs)
          <tr>
            <td>
              <span style="font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--ink);font-weight:500">
                {{ $key }}
              </span>
            </td>
            <td style="padding:8px 22px">
              <input type="text"
                     name="t[{{ $key }}][uz]"
                     class="form-input"
                     value="{{ $langs['uz'] ?? '' }}"
                     placeholder="O'zbekcha tarjima">
            </td>
            <td style="padding:8px 22px">
              <input type="text"
                     name="t[{{ $key }}][ru]"
                     class="form-input"
                     value="{{ $langs['ru'] ?? '' }}"
                     placeholder="Ruscha tarjima">
            </td>
            <td>
              <form method="POST" action="{{ route('admin.translations.destroy-key') }}"
                    onsubmit="return confirm('{{ $key }} kalitini o\'chirishni tasdiqlaysizmi?')">
                @csrf @method('DELETE')
                <input type="hidden" name="group" value="{{ $group }}">
                <input type="hidden" name="key" value="{{ $key }}">
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
  </form>
</div>
@empty
<div class="card" style="padding:48px;text-align:center;color:var(--muted)">
  Hali tarjimalar yo'q. Yuqoridagi forma orqali birinchi kalitni qo'shing.
</div>
@endforelse

@endsection
