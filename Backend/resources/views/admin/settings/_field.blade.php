<label class="form-label fw-semibold" style="font-size:.875rem;">{{ $setting->label }}</label>

@if($setting->type === 'textarea')
  <textarea name="{{ $setting->key }}" class="form-control" rows="3" style="border-radius:8px;">{{ $setting->value }}</textarea>

@elseif($setting->type === 'image')
  @if($setting->value)
    <div class="mb-2">
      <img src="{{ asset('uploads/settings/'.$setting->value) }}" class="rounded" style="max-height:100px;max-width:200px;object-fit:cover;" />
    </div>
  @endif
  <input type="file" name="{{ $setting->key }}" class="form-control form-control-sm" accept="image/*" style="border-radius:8px;" />
  <div class="form-text">Kosongkan jika tidak ingin mengganti gambar.</div>

@elseif($setting->type === 'color')
  <div class="input-group">
    <input type="color" name="{{ $setting->key }}" class="form-control form-control-color" value="{{ $setting->value ?? '#005BAA' }}" style="width:48px;border-radius:8px 0 0 8px;height:38px;" />
    <input type="text" class="form-control form-control-sm" value="{{ $setting->value }}" readonly style="border-radius:0 8px 8px 0;font-size:.8rem;" />
  </div>

@elseif($setting->type === 'boolean')
  <div class="form-check form-switch mt-1">
    <input class="form-check-input" type="checkbox" name="{{ $setting->key }}" value="1" {{ $setting->value ? 'checked' : '' }}>
    <label class="form-check-label" style="font-size:.875rem;">Aktif</label>
  </div>

@else
  <input type="text" name="{{ $setting->key }}" class="form-control" value="{{ $setting->value }}" style="border-radius:8px;" />
@endif
