@extends('layouts.admin')
@section('title', 'Kompaniyani tahrirlash')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Kompaniyani tahrirlash</div>
    <div class="page-subtitle">{{ $company->brand ?? $company->name }}</div>
  </div>
  <div style="display:flex;gap:8px">
    <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-ghost">
      <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
      Orqaga
    </a>
  </div>
</div>

<div class="card" style="max-width:760px">
  <div style="padding:24px">
    <form method="POST" action="{{ route('admin.companies.update', $company) }}">
      @csrf @method('PUT')

      <div style="margin-bottom:24px">
        <div style="font-family:'JetBrains Mono',monospace;font-size:9px;text-transform:uppercase;letter-spacing:0.12em;color:var(--faint);margin-bottom:14px">Asosiy ma'lumotlar</div>
        <div class="form-grid" style="margin-bottom:16px">
          <div class="form-group">
            <label class="form-label">Kompaniya nomi <span style="color:#D32F2F">*</span></label>
            <input type="text" name="name" class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                   value="{{ old('name', $company->name) }}" placeholder="Masalan: Maktab savdo MChJ">
            @error('name') <div class="form-error">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Brend</label>
            <input type="text" name="brand" class="form-input"
                   value="{{ old('brand', $company->brand) }}" placeholder="Qisqa nom yoki brend">
          </div>
        </div>
        <div class="form-grid" style="margin-bottom:16px">
          <div class="form-group">
            <label class="form-label">INN</label>
            <input type="text" name="inn" class="form-input"
                   value="{{ old('inn', $company->inn) }}" placeholder="123456789"
                   style="font-family:'JetBrains Mono',monospace">
          </div>
          <div class="form-group">
            <label class="form-label">Telefon</label>
            <input type="text" name="phone" class="form-input"
                   value="{{ old('phone', $company->phone) }}" placeholder="+998901234567">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Huquqiy shakl</label>
          <input type="text" name="legal_form" class="form-input"
                 value="{{ old('legal_form', $company->legal_form) }}" placeholder="MChJ, YaTT, AJ...">
        </div>
      </div>

      <div style="margin-bottom:24px;padding-top:20px;border-top:1px solid var(--line)">
        <div style="font-family:'JetBrains Mono',monospace;font-size:9px;text-transform:uppercase;letter-spacing:0.12em;color:var(--faint);margin-bottom:14px">Admin sozlamalari</div>
        <div class="form-grid" style="margin-bottom:16px">
          <div class="form-group">
            <label class="form-label">Status <span style="color:#D32F2F">*</span></label>
            <select name="status" class="form-input">
              <option value="pending"  {{ old('status', $company->status) === 'pending'  ? 'selected' : '' }}>Kutilmoqda</option>
              <option value="approved" {{ old('status', $company->status) === 'approved' ? 'selected' : '' }}>Tasdiqlangan</option>
              <option value="rejected" {{ old('status', $company->status) === 'rejected' ? 'selected' : '' }}>Rad etildi</option>
            </select>
            @error('status') <div class="form-error">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">NDS holati <span style="color:#D32F2F">*</span></label>
            <select name="vat_status" class="form-input">
              <option value="non_payer" {{ old('vat_status', $company->vat_status) === 'non_payer' ? 'selected' : '' }}>NDS to'lovchi emas</option>
              <option value="payer"     {{ old('vat_status', $company->vat_status) === 'payer'     ? 'selected' : '' }}>NDS to'lovchi</option>
            </select>
            @error('vat_status') <div class="form-error">{{ $message }}</div> @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Ishlab chiqaruvchi holati <span style="color:#D32F2F">*</span></label>
          <select name="manufacturer_status" class="form-input">
            <option value="none"                  {{ old('manufacturer_status', $company->manufacturer_status) === 'none'                  ? 'selected' : '' }}>Oddiy diler</option>
            <option value="authorized_partner"    {{ old('manufacturer_status', $company->manufacturer_status) === 'authorized_partner'    ? 'selected' : '' }}>Vakolatli hamkor</option>
            <option value="authorized_distributor"{{ old('manufacturer_status', $company->manufacturer_status) === 'authorized_distributor' ? 'selected' : '' }}>Vakolatli distribyutor</option>
          </select>
          @error('manufacturer_status') <div class="form-error">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">
          <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
          Saqlash
        </button>
        <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-ghost">Bekor qilish</a>
      </div>
    </form>
  </div>
</div>

@endsection
