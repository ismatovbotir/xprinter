<div>
  <select name="region_id"
          wire:model="regionId"
          id="region_id"
          class="form-select {{ count($regions) === 0 ? 'disabled' : '' }}"
          {{ count($regions) === 0 ? 'disabled' : '' }}
          required>
    <option value="">
      {{ count($regions) === 0 ? 'Avval davlatni tanlang' : 'Viloyatni tanlang' }}
    </option>
    @foreach($regions as $region)
      <option value="{{ $region['id'] }}" @selected($regionId == $region['id'])>
        {{ $region['name'] }}
      </option>
    @endforeach
  </select>
</div>
