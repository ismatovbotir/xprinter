@extends('layouts.admin')
@section('title', isset($category) ? 'Kategoriyani tahrirlash' : 'Yangi kategoriya')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">{{ isset($category) ? 'Kategoriyani tahrirlash' : 'Yangi kategoriya' }}</div>
    <div class="page-subtitle">{{ isset($category) ? $category->translations->firstWhere('lang','uz')?->name : "Yangi kategoriya qo'shish" }}</div>
  </div>
  <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost">
    <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
    Orqaga
  </a>
</div>

@if(session('success'))
<div class="alert alert-success" style="max-width:680px;margin-bottom:16px">{{ session('success') }}</div>
@endif

{{-- Main info --}}
<div class="card" style="max-width:680px;margin-bottom:20px">
  <div style="padding:24px">
    <form method="POST" action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
      @csrf
      @if(isset($category)) @method('PUT') @endif

      @isset($category)
      <div class="form-group">
        <label class="form-label">Slug</label>
        <input type="text" class="form-input" value="{{ $category->slug }}" disabled
               style="background:var(--bg-soft);color:var(--muted);cursor:not-allowed">
        <div class="form-hint">Avtomatik yaratilgan, o'zgartirib bo'lmaydi</div>
      </div>
      @else
      <div class="form-group">
        <label class="form-label">Slug</label>
        <div class="form-hint">English nomidan avtomatik yaratiladi. Masalan: <code>receipt-printers</code></div>
      </div>
      @endisset

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-top:16px">
        <div class="form-group">
          <label class="form-label">O'zbekcha <span style="color:var(--red)">*</span></label>
          <input type="text" name="name_uz" class="form-input {{ $errors->has('name_uz') ? 'is-invalid' : '' }}"
                 value="{{ old('name_uz', ($category ?? null)?->translations->firstWhere('lang','uz')?->name) }}"
                 placeholder="Chek printerlari">
          @error('name_uz') <div class="invalid-feedback" style="display:block">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Ruscha <span style="color:var(--red)">*</span></label>
          <input type="text" name="name_ru" class="form-input {{ $errors->has('name_ru') ? 'is-invalid' : '' }}"
                 value="{{ old('name_ru', ($category ?? null)?->translations->firstWhere('lang','ru')?->name) }}"
                 placeholder="Принтеры чеков">
          @error('name_ru') <div class="invalid-feedback" style="display:block">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">English <span style="color:var(--red)">*</span></label>
          <input type="text" name="name_en" class="form-input {{ $errors->has('name_en') ? 'is-invalid' : '' }}"
                 value="{{ old('name_en', ($category ?? null)?->translations->firstWhere('lang','en')?->name) }}"
                 placeholder="Receipt printers">
          @error('name_en') <div class="invalid-feedback" style="display:block">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="form-actions" style="margin-top:24px">
        <button type="submit" class="btn btn-primary">
          <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
          {{ isset($category) ? 'Saqlash' : "Qo'shish" }}
        </button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost">Bekor qilish</a>
      </div>
    </form>
  </div>
</div>

{{-- Parameters table — only on edit --}}
@isset($category)
<div style="max-width:680px">
  <div class="card-header" style="margin-bottom:12px">
    <div class="card-title">Parametrlar</div>
    <div style="font-size:12px;color:var(--muted)">Bu kategoriyaga biriktirilgan parametrlar</div>
  </div>

  {{-- Attached parameters --}}
  @if($category->parameters->isEmpty())
  <div class="card" style="padding:32px;text-align:center;color:var(--muted);margin-bottom:16px">
    Hech qanday parametr biriktirilmagan
  </div>
  @else
  <div class="card" style="margin-bottom:16px">
    <table>
      <thead>
        <tr>
          <th>Parametr</th>
          <th style="width:100px;text-align:center">Tartib</th>
          <th style="width:140px;text-align:center">Sotuvchi tanlaydi</th>
          <th style="width:90px"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($category->parameters as $param)
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:8px">
              <div style="font-weight:600;font-size:13px">
                {{ $param->translations->firstWhere('lang','uz')?->name ?? '—' }}
              </div>
              <span style="font-family:'JetBrains Mono',monospace;font-size:10px;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:var(--muted);background:var(--bg-soft);border:1px solid var(--line);padding:1px 7px;border-radius:10px">
                {{ $param->type }}
              </span>
            </div>
            <div style="font-size:11px;color:var(--muted)">
              ru: {{ $param->translations->firstWhere('lang','ru')?->name ?? '—' }}
              &nbsp;·&nbsp;
              en: {{ $param->translations->firstWhere('lang','en')?->name ?? '—' }}
            </div>
          </td>
          <td style="text-align:center">
            <span style="font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--muted)">
              {{ $param->pivot->sort_order }}
            </span>
          </td>
          <td style="text-align:center">
            <form method="POST" action="{{ route('admin.categories.parameters.toggle-variant', [$category, $param]) }}">
              @csrf @method('PATCH')
              <button type="submit" style="background:none;border:none;cursor:pointer;padding:0" title="Bu parametrni dilerlar o'zi tanlaydimi">
                @if($param->pivot->is_variant)
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;background:#E0FAF3;border-radius:20px">
                  <span style="width:5px;height:5px;border-radius:50%;background:var(--green);display:inline-block"></span>
                  <span style="font-size:11px;font-weight:600;color:#007A5A;font-family:'JetBrains Mono',monospace">HA</span>
                </span>
                @else
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;background:var(--bg-soft);border:1px solid var(--line);border-radius:20px">
                  <span style="width:5px;height:5px;border-radius:50%;background:var(--faint);display:inline-block"></span>
                  <span style="font-size:11px;font-weight:600;color:var(--muted);font-family:'JetBrains Mono',monospace">YO'Q</span>
                </span>
                @endif
              </button>
            </form>
          </td>
          <td>
            <div class="actions-cell" style="justify-content:flex-end">
              <button type="button" class="action-btn" title="Tahrirlash"
                data-update-url="{{ route('admin.parameters.update', $param) }}"
                data-destroy-url="{{ route('admin.parameters.destroy', $param) }}"
                data-parameter-id="{{ $param->id }}"
                data-type="{{ $param->type }}"
                data-name-uz="{{ $param->translations->firstWhere('lang','uz')?->name }}"
                data-name-ru="{{ $param->translations->firstWhere('lang','ru')?->name }}"
                data-name-en="{{ $param->translations->firstWhere('lang','en')?->name }}"
                onclick="openParamSlideoverFromButton(this)">
                <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
              </button>
              <form method="POST" action="{{ route('admin.categories.parameters.detach', [$category, $param]) }}"
                    data-confirm="Parametrni olib tashlaysizmi?">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn danger" title="Olib tashlash">
                  <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
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

  {{-- Attach existing / create new parameter --}}
  @php
    $attachedIds = $category->parameters->pluck('id');
    $available   = $allParameters->whereNotIn('id', $attachedIds);
  @endphp

  <div class="card" style="padding:20px;display:flex;gap:16px;flex-wrap:wrap;align-items:flex-end">
    @if($available->isNotEmpty())
    <form method="POST" action="{{ route('admin.categories.parameters.attach', $category) }}"
          style="display:flex;gap:12px;align-items:flex-end;flex:1;min-width:240px">
      @csrf
      <div class="form-group" style="flex:1;margin:0">
        <label class="form-label">Mavjud parametrni biriktirish</label>
        <select name="parameter_id" class="form-input" required>
          <option value="">— Tanlang —</option>
          @foreach($available as $param)
          <option value="{{ $param->id }}">
            {{ $param->translations->firstWhere('lang','uz')?->name ?? "Parameter #{$param->id}" }}
          </option>
          @endforeach
        </select>
      </div>
      <input type="hidden" name="sort_order" value="{{ $category->parameters->count() }}">
      <button type="submit" class="btn btn-primary" style="margin-bottom:0;flex-shrink:0">
        <svg viewBox="0 0 24 24" style="width:16px;height:16px"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Biriktirish
      </button>
    </form>
    @endif

    <button type="button" class="btn btn-ghost" style="margin-bottom:0;flex-shrink:0" onclick='openParamSlideover("create")'>
      <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Yangi parametr
    </button>
  </div>
</div>

{{-- Parameter quick-edit slide-over --}}
<div class="slideover-overlay" id="param-overlay" onclick="closeParamSlideover()"></div>
<div class="slideover-panel" id="param-panel" role="dialog" aria-modal="true">
  <div class="slideover-header">
    <div class="slideover-title" id="param-title">Yangi parametr</div>
    <button type="button" class="slideover-close" onclick="closeParamSlideover()" aria-label="Yopish">
      <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
  <div class="slideover-body">
    <form id="param-form" method="POST" action="{{ route('admin.parameters.store') }}">
      @csrf
      <input type="hidden" name="_method" id="param-method" value="">
      <input type="hidden" name="category_id" value="{{ $category->id }}">
      <input type="hidden" name="category_ids[]" value="{{ $category->id }}">

      <div class="form-group">
        <label class="form-label">Turi</label>
        <select name="type" id="param-type" class="form-input">
          <option value="string">Matn (string)</option>
          <option value="integer">Son (integer)</option>
          <option value="boolean">Ha/Yo'q (boolean)</option>
        </select>
      </div>

      <div class="form-group" style="margin-top:16px">
        <label class="form-label">O'zbekcha <span style="color:var(--red)">*</span></label>
        <input type="text" name="name_uz" id="param-name-uz" class="form-input" required>
      </div>
      <div class="form-group" style="margin-top:16px">
        <label class="form-label">Ruscha <span style="color:var(--red)">*</span></label>
        <input type="text" name="name_ru" id="param-name-ru" class="form-input" required>
      </div>
      <div class="form-group" style="margin-top:16px">
        <label class="form-label">English <span style="color:var(--red)">*</span></label>
        <input type="text" name="name_en" id="param-name-en" class="form-input" required>
      </div>
    </form>

    {{-- Values — only shown in edit mode, one panel per parameter --}}
    <div id="param-values-wrapper" style="display:none;margin-top:24px;padding-top:20px;border-top:1px solid var(--line)">
      <div class="form-label" style="margin-bottom:10px">Qiymatlar</div>

      @foreach($category->parameters as $param)
      <div class="param-values-panel" data-parameter-id="{{ $param->id }}" style="display:none">

        @if($param->values->isEmpty())
        <div style="padding:14px;text-align:center;color:var(--muted);font-size:12px;background:var(--bg-soft);border-radius:8px;margin-bottom:10px">
          Hali qiymatlar qo'shilmagan
        </div>
        @else
        <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:10px">
          @foreach($param->values as $value)
          <div style="display:flex;gap:6px;align-items:center">
            <form method="POST" action="{{ route('admin.parameters.values.update', [$param, $value]) }}" style="display:flex;gap:6px;flex:1">
              @csrf @method('PUT')
              <input type="hidden" name="category_id" value="{{ $category->id }}">
              @if($param->type === 'integer')
              <input type="number" name="value" value="{{ $value->translations->firstWhere('lang','uz')?->name }}" class="form-input" style="font-size:12px;padding:6px 8px" placeholder="Son" required>
              @else
              <input type="text" name="name_uz" value="{{ $value->translations->firstWhere('lang','uz')?->name }}" class="form-input" style="font-size:12px;padding:6px 8px" placeholder="UZ" required>
              <input type="text" name="name_ru" value="{{ $value->translations->firstWhere('lang','ru')?->name }}" class="form-input" style="font-size:12px;padding:6px 8px" placeholder="RU" required>
              <input type="text" name="name_en" value="{{ $value->translations->firstWhere('lang','en')?->name }}" class="form-input" style="font-size:12px;padding:6px 8px" placeholder="EN" required>
              @endif
              <button type="submit" class="action-btn" title="Saqlash" style="flex-shrink:0">
                <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
              </button>
            </form>
            <form method="POST" action="{{ route('admin.parameters.values.destroy', [$param, $value]) }}"
                  data-confirm="Qiymatni o'chirasizmi?">
              @csrf @method('DELETE')
              <input type="hidden" name="category_id" value="{{ $category->id }}">
              <button type="submit" class="action-btn danger" title="O'chirish">
                <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </button>
            </form>
          </div>
          @endforeach
        </div>
        @endif

        @if($param->type === 'boolean')
        <div style="font-size:11px;color:var(--muted)">Ha/Yo'q qiymatlari avtomatik yaratiladi</div>
        @elseif($param->type === 'integer')
        <form method="POST" action="{{ route('admin.parameters.values.store', $param) }}" style="display:flex;gap:6px">
          @csrf
          <input type="hidden" name="category_id" value="{{ $category->id }}">
          <input type="number" name="value" class="form-input" style="font-size:12px;padding:6px 8px" placeholder="Son, masalan: 203" required>
          <button type="submit" class="btn btn-primary" style="padding:0 12px;flex-shrink:0" title="Qiymat qo'shish">
            <svg viewBox="0 0 24 24" style="width:14px;height:14px"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          </button>
        </form>
        @else
        <form method="POST" action="{{ route('admin.parameters.values.store', $param) }}" style="display:flex;gap:6px">
          @csrf
          <input type="hidden" name="category_id" value="{{ $category->id }}">
          <input type="text" name="name_uz" class="form-input" style="font-size:12px;padding:6px 8px" placeholder="UZ" required>
          <input type="text" name="name_ru" class="form-input" style="font-size:12px;padding:6px 8px" placeholder="RU" required>
          <input type="text" name="name_en" class="form-input" style="font-size:12px;padding:6px 8px" placeholder="EN" required>
          <button type="submit" class="btn btn-primary" style="padding:0 12px;flex-shrink:0" title="Qiymat qo'shish">
            <svg viewBox="0 0 24 24" style="width:14px;height:14px"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          </button>
        </form>
        @endif
      </div>
      @endforeach

    </div>
  </div>
  <div class="slideover-footer" style="flex-direction:column;gap:10px">
    <div style="display:flex;gap:10px;width:100%">
      <button type="submit" form="param-form" class="btn btn-primary" style="flex:1">Saqlash</button>
      <button type="button" class="btn btn-ghost" onclick="closeParamSlideover()">Bekor qilish</button>
    </div>
    <form id="param-delete-form" method="POST" action="" style="width:100%">
      @csrf @method('DELETE')
      <input type="hidden" name="category_id" value="{{ $category->id }}">
      <button type="submit" class="btn btn-ghost" id="param-delete-btn"
              style="display:none;width:100%;color:var(--red);border-color:#FFCDD2">
        Parametrni butunlay o'chirish
      </button>
    </form>
  </div>
</div>

@push('scripts')
<script>
  function openParamSlideoverFromButton(btn) {
    openParamSlideover('edit', {
      updateUrl: btn.dataset.updateUrl,
      destroyUrl: btn.dataset.destroyUrl,
      parameterId: btn.dataset.parameterId,
      type: btn.dataset.type,
      nameUz: btn.dataset.nameUz,
      nameRu: btn.dataset.nameRu,
      nameEn: btn.dataset.nameEn,
    });
  }

  function openParamSlideover(mode, data) {
    data = data || {};
    const form = document.getElementById('param-form');
    const deleteForm = document.getElementById('param-delete-form');
    const deleteBtn = document.getElementById('param-delete-btn');
    const valuesWrapper = document.getElementById('param-values-wrapper');

    form.reset();

    if (mode === 'edit') {
      form.action = data.updateUrl;
      document.getElementById('param-method').value = 'PUT';
      document.getElementById('param-title').textContent = 'Parametrni tahrirlash';
      document.getElementById('param-type').value = data.type || 'string';
      document.getElementById('param-name-uz').value = data.nameUz || '';
      document.getElementById('param-name-ru').value = data.nameRu || '';
      document.getElementById('param-name-en').value = data.nameEn || '';

      deleteForm.action = data.destroyUrl;
      deleteForm.dataset.confirm = '"' + data.nameUz + '" parametrini butunlay o\'chirishni tasdiqlaysizmi? U barcha kategoriyalardan olib tashlanadi.';
      deleteBtn.style.display = '';

      document.querySelectorAll('.param-values-panel').forEach(function (panel) {
        panel.style.display = panel.dataset.parameterId === String(data.parameterId) ? '' : 'none';
      });
      valuesWrapper.style.display = '';
    } else {
      form.action = "{{ route('admin.parameters.store') }}";
      document.getElementById('param-method').value = '';
      document.getElementById('param-title').textContent = 'Yangi parametr';
      deleteBtn.style.display = 'none';
      valuesWrapper.style.display = 'none';
    }

    document.getElementById('param-panel').classList.add('open');
    document.getElementById('param-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeParamSlideover() {
    document.getElementById('param-panel').classList.remove('open');
    document.getElementById('param-overlay').classList.remove('open');
    document.body.style.overflow = '';
  }

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeParamSlideover();
  });
</script>
@endpush
@endisset

@endsection
