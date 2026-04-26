<div>
{{-- ═══ Tab navigation ═══ --}}
<div style="display:flex;gap:4px;background:var(--bg-soft);border:1px solid var(--line);border-radius:12px;padding:4px;margin-bottom:20px;width:fit-content">
    @foreach(['basic' => 'Asosiy', 'content' => 'Kontent', 'parameters' => 'Parametrlar', 'photo' => 'Foto'] as $tab => $label)
    <button type="button" wire:click="setTab('{{ $tab }}')"
            style="padding:7px 18px;border-radius:9px;border:none;cursor:pointer;font-family:'Manrope',sans-serif;font-size:13px;font-weight:600;transition:all 0.15s;
                   {{ $activeTab === $tab ? 'background:var(--surface);color:var(--blue);box-shadow:0 1px 4px rgba(0,0,0,0.08)' : 'background:transparent;color:var(--muted)' }}">
        {{ $label }}
        @if($tab === 'parameters' && $this->parameters->count())
        <span style="font-family:'JetBrains Mono',monospace;font-size:10px;background:var(--blue-soft);color:var(--blue);padding:1px 6px;border-radius:10px;margin-left:4px">{{ $this->parameters->count() }}</span>
        @endif
    </button>
    @endforeach
</div>

<form wire:submit="save" style="max-width:820px">

    {{-- ═══ TAB: Basic ═══ --}}
    <div style="{{ $activeTab === 'basic' ? '' : 'display:none' }}">
        <div class="card" style="margin-bottom:16px">
            <div class="card-header"><div class="card-title">Asosiy ma'lumotlar</div></div>
            <div style="padding:20px;display:flex;flex-direction:column;gap:16px">

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div class="form-group">
                        <label class="form-label">Model raqami <span style="color:var(--red)">*</span></label>
                        <input type="text" wire:model.live="modelNumber"
                               class="form-input {{ $errors->has('modelNumber') ? 'is-invalid' : '' }}"
                               placeholder="XP-Q890K"
                               style="font-family:'JetBrains Mono',monospace;font-weight:600;letter-spacing:0.03em">
                        @error('modelNumber')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kategoriya <span style="color:var(--red)">*</span></label>
                        <select wire:model.live="categoryId" class="form-input {{ $errors->has('categoryId') ? 'is-invalid' : '' }}">
                            <option value="">— Tanlang —</option>
                            @foreach($this->categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->translations->firstWhere('lang','uz')?->name ?? $cat->slug }}</option>
                            @endforeach
                        </select>
                        @error('categoryId')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" style="display:flex;align-items:center;justify-content:space-between">
                        <span>Slug <span style="color:var(--red)">*</span></span>
                        @if($slugLocked)
                        <button type="button" wire:click="unlockSlug"
                                style="font-size:11px;color:var(--blue);background:none;border:none;cursor:pointer;font-family:'Manrope',sans-serif">
                            ✏ O'zgartirish
                        </button>
                        @endif
                    </label>
                    <div style="position:relative">
                        <input type="text" wire:model.live="slug"
                               class="form-input {{ $errors->has('slug') || $this->slugExists ? 'is-invalid' : '' }}"
                               placeholder="xp-q890k"
                               {{ $slugLocked ? 'readonly' : '' }}
                               style="{{ $slugLocked ? 'background:var(--bg-soft);color:var(--muted)' : '' }}">
                        @if($slug && !$this->slugExists && !$errors->has('slug'))
                        <span style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:var(--green);font-size:12px">✓ Mavjud emas</span>
                        @endif
                    </div>
                    @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @if($this->slugExists && !$errors->has('slug'))
                    <div class="invalid-feedback" style="display:block">Bu slug allaqachon ishlatilmoqda</div>
                    @endif
                    <div style="font-size:11px;color:var(--faint);margin-top:4px">Model raqamidan avtomatik yaratiladi. URL da ishlatiladi.</div>
                </div>

            </div>
        </div>
    </div>

    {{-- ═══ TAB: Content ═══ --}}
    <div style="{{ $activeTab === 'content' ? '' : 'display:none' }}">
        <div class="card" style="margin-bottom:16px">
            <div class="card-header"><div class="card-title">Nomlar</div></div>
            <div style="padding:20px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px">
                <div class="form-group" style="margin:0">
                    <label class="form-label">O'zbekcha <span style="color:var(--red)">*</span></label>
                    <input type="text" wire:model.live="nameUz" class="form-input {{ $errors->has('nameUz') ? 'is-invalid' : '' }}"
                           placeholder="Chek printeri XP-Q890K">
                    @error('nameUz')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="margin:0">
                    <label class="form-label">Ruscha <span style="color:var(--red)">*</span></label>
                    <input type="text" wire:model="nameRu" class="form-input {{ $errors->has('nameRu') ? 'is-invalid' : '' }}"
                           placeholder="Принтер чеков XP-Q890K">
                    @error('nameRu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="margin:0">
                    <label class="form-label">English <span style="color:var(--red)">*</span></label>
                    <input type="text" wire:model="nameEn" class="form-input {{ $errors->has('nameEn') ? 'is-invalid' : '' }}"
                           placeholder="Receipt Printer XP-Q890K">
                    @error('nameEn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
        <div class="card" style="margin-bottom:16px">
            <div class="card-header"><div class="card-title">Tavsiflar</div></div>
            <div style="padding:20px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px">
                <div class="form-group" style="margin:0">
                    <label class="form-label">O'zbekcha</label>
                    <textarea wire:model="descriptionUz" class="form-input" rows="5"
                              placeholder="Mahsulot tavsifi..."></textarea>
                </div>
                <div class="form-group" style="margin:0">
                    <label class="form-label">Ruscha</label>
                    <textarea wire:model="descriptionRu" class="form-input" rows="5"
                              placeholder="Описание товара..."></textarea>
                </div>
                <div class="form-group" style="margin:0">
                    <label class="form-label">English</label>
                    <textarea wire:model="descriptionEn" class="form-input" rows="5"
                              placeholder="Product description..."></textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ TAB: Parameters ═══ --}}
    <div style="{{ $activeTab === 'parameters' ? '' : 'display:none' }}">
        @if(!$categoryId)
        <div class="card" style="padding:48px;text-align:center;color:var(--muted)">
            <div style="font-size:32px;margin-bottom:12px">⬆</div>
            Avval <strong>Asosiy</strong> tabda kategoriyani tanlang
        </div>
        @elseif($this->parameters->isEmpty())
        <div class="card" style="padding:48px;text-align:center;color:var(--muted)">
            Bu kategoriya uchun parametrlar yo'q
        </div>
        @else
        @foreach($this->parameters as $param)
        @php $paramName = $param->translations->firstWhere('lang','uz')?->name ?? $param->translations->first()?->name; @endphp
        <div class="card" style="margin-bottom:12px">
            <div class="card-header">
                <div class="card-title" style="font-size:13px">{{ $paramName }}</div>
            </div>
            <div style="padding:16px;display:flex;gap:10px;flex-wrap:wrap">
                @foreach($param->values as $val)
                @php $valName = $val->translations->firstWhere('lang','uz')?->name ?? $val->translations->first()?->name; @endphp
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:6px 14px;border:1.5px solid var(--line);border-radius:20px;transition:all 0.15s;{{ in_array((string)$val->id, $selectedValues) ? 'border-color:var(--blue);background:var(--bg-blue);color:var(--blue-deep)' : '' }}">
                    <input type="checkbox" wire:model.live="selectedValues" value="{{ $val->id }}" style="display:none">
                    <span style="font-size:13px;font-weight:500">{{ $valName }}</span>
                </label>
                @endforeach
            </div>
        </div>
        @endforeach
        @endif
    </div>

    {{-- ═══ TAB: Photo ═══ --}}
    <div style="{{ $activeTab === 'photo' ? '' : 'display:none' }}">
        <div class="card" style="padding:24px">
            @if($existingPhoto)
            <div style="margin-bottom:16px">
                <div style="font-size:11px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:var(--muted);margin-bottom:8px">Joriy rasm</div>
                <img src="{{ Storage::url($existingPhoto) }}" style="width:200px;height:200px;object-fit:contain;border:1px solid var(--line);border-radius:12px;padding:8px">
            </div>
            @endif

            @if($photo)
            <div style="margin-bottom:16px">
                <div style="font-size:11px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:var(--muted);margin-bottom:8px">Yangi rasm (preview)</div>
                <img src="{{ $photo->temporaryUrl() }}" style="width:200px;height:200px;object-fit:contain;border:1.5px solid var(--blue);border-radius:12px;padding:8px">
            </div>
            @endif

            <div class="form-group">
                <label class="form-label">Rasm yuklash (JPG, PNG, WebP — max 2MB)</label>
                <input type="file" wire:model="photo" accept="image/*"
                       class="form-input {{ $errors->has('photo') ? 'is-invalid' : '' }}">
                @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div wire:loading wire:target="photo" style="margin-top:8px;font-size:13px;color:var(--blue)">
                Yuklanmoqda...
            </div>
        </div>
    </div>

    {{-- ═══ Actions ═══ --}}
    <div style="display:flex;align-items:center;gap:12px;margin-top:20px">
        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
            <svg viewBox="0 0 24 24" wire:loading.remove><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            <svg viewBox="0 0 24 24" wire:loading style="animation:spin 1s linear infinite"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
            <span wire:loading.remove>{{ $productId ? 'Saqlash' : "Qo'shish" }}</span>
            <span wire:loading>Saqlanmoqda...</span>
        </button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">Bekor qilish</a>
        @if($productId)
        <div style="margin-left:auto;font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--faint)">
            ID: {{ $productId }}
        </div>
        @endif
    </div>

</form>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>
</div>
