@extends('layouts.admin')
@section('title', isset($product) ? 'Mahsulotni tahrirlash' : 'Yangi mahsulot')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">{{ isset($product) ? 'Mahsulotni tahrirlash' : 'Yangi mahsulot' }}</div>
    <div class="page-subtitle">{{ isset($product) ? $product->model_number : "Yangi mahsulot qo'shish" }}</div>
  </div>
  <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">
    <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
    Orqaga
  </a>
</div>

<livewire:admin.product-form :product="$product ?? null" />

@if(isset($product))
<div style="margin-top:28px">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Qo'shilgan fayllar</div>
        </div>
        @php $productFiles = $product->files; @endphp

        @if($productFiles->isEmpty())
        <div style="padding:20px;text-align:center;color:var(--muted);font-size:13px">
            Hali hechqanday fayl qo'shilmagan
        </div>
        @else
        <div style="padding:16px;border-bottom:1px solid var(--line)">
            @foreach($productFiles as $file)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px;border:1px solid var(--line);border-radius:8px;margin-bottom:8px;font-size:13px">
                <div>
                    <div style="font-weight:600;color:var(--ink)">{{ $file->name }}</div>
                    <div style="font-size:11px;color:var(--muted);margin-top:2px;font-family:'JetBrains Mono',monospace">
                        {{ $file->pivot->type }} · {{ strtoupper($file->pivot->language) }} · {{ $file->size_formatted }}
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.products.files.detach', [$product, $file]) }}" style="display:inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="action-btn danger" style="width:28px;height:28px" title="O'chirish">
                        <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
        @endif

        <div style="padding:20px">
            <form method="POST" action="{{ route('admin.products.files.attach', $product) }}">
                @csrf
                <div style="display:grid;grid-template-columns:1fr auto auto auto;gap:8px;align-items:end">
                    <div class="form-group" style="margin:0">
                        <label class="form-label" style="margin-bottom:4px">Fayl</label>
                        <select name="file_id" class="form-input" style="margin:0" required>
                            <option value="">— Tanlang —</option>
                            @php $existingIds = $product->files->pluck('id')->toArray(); @endphp
                            @foreach(\App\Models\File::all() as $f)
                            @if(!in_array($f->id, $existingIds))
                            <option value="{{ $f->id }}">{{ $f->name }} ({{ $f->size_formatted }})</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin:0">
                        <label class="form-label" style="margin-bottom:4px">Turi</label>
                        <select name="type" class="form-input" style="margin:0" required>
                            <option value="driver">Driver</option>
                            <option value="manual">Manual</option>
                            <option value="spec">Spec</option>
                            <option value="firmware">Firmware</option>
                            <option value="utility">Utility</option>
                            <option value="other">Boshqa</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin:0">
                        <label class="form-label" style="margin-bottom:4px">Til</label>
                        <select name="language" class="form-input" style="margin:0" required>
                            <option value="uz">O'zbek</option>
                            <option value="ru">Ruscha</option>
                            <option value="en">Ingliz</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="height:38px;width:120px;justify-content:center;font-size:13px">Qo'shish</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection
