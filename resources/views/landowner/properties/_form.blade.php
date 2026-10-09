@php
  $p = $property ?? null;
  $old = fn ($key, $default = null) => old($key, $p ? ($p->{$key} ?? $default) : $default);
@endphp

<div class="field-group">
  <label>{{ __('site.panel.landowner.title_label') }}</label>
  <input type="text" name="title_fa" class="field" required maxlength="255"
         placeholder="{{ __('site.panel.landowner.title_placeholder') }}"
         value="{{ old('title_fa', $p?->localized_title) }}">
</div>

<div class="form-grid-2">
  <div class="field-group">
    <label>{{ __('site.panel.landowner.property_type') }}</label>
    <select name="property_type" class="field" required>
      @foreach (['land','apartment','villa','commercial','other'] as $type)
        <option value="{{ $type }}" {{ old('property_type', $p->property_type ?? 'land') === $type ? 'selected' : '' }}>{{ __('site.properties.types.'.$type) }}</option>
      @endforeach
    </select>
  </div>
  <div class="field-group">
    <label>{{ __('site.panel.landowner.deal_type') }}</label>
    <select name="deal_type" class="field" required>
      @foreach (['sale','partnership','barter','both'] as $deal)
        <option value="{{ $deal }}" {{ old('deal_type', $p->deal_type ?? 'sale') === $deal ? 'selected' : '' }}>{{ __('site.properties.deal_types.'.$deal) }}</option>
      @endforeach
    </select>
  </div>
</div>

<div class="form-grid-2">
  <div class="field-group">
    <label>{{ __('site.panel.landowner.area') }}</label>
    <input type="number" name="area_sqm" class="field" min="1" value="{{ old('area_sqm', $p?->area_sqm) }}">
  </div>
  <div class="field-group">
    <label>{{ __('site.panel.landowner.price') }}</label>
    <input type="text" inputmode="numeric" id="price_display" class="field"
           placeholder="{{ app()->getLocale() === 'fa' ? 'مثلاً ۸۵۰,۰۰۰,۰۰۰' : 'e.g. 850,000,000' }}"
           value="{{ old('price', $p?->price) ? number_format(old('price', $p?->price)) : '' }}">
    <input type="hidden" name="price" id="price_raw" value="{{ old('price', $p?->price) }}">
    <p style="font-size:11.5px; color:var(--ink-soft); margin-top:6px;">{{ __('site.panel.landowner.price_note') }}</p>
  </div>
</div>

<div class="form-grid-2">
  <div class="field-group">
    <label>{{ __('site.panel.landowner.province') }}</label>
    <input type="text" name="province" class="field" required value="{{ old('province', $p?->province) }}">
  </div>
  <div class="field-group">
    <label>{{ __('site.panel.landowner.city') }}</label>
    <input type="text" name="city" class="field" required value="{{ old('city', $p?->city) }}">
  </div>
</div>

<div class="field-group">
  <label>{{ __('site.panel.landowner.address') }}</label>
  <input type="text" name="address" class="field" maxlength="500" value="{{ old('address', $p?->address) }}">
</div>

<div class="field-group">
  <label>{{ __('site.panel.landowner.document_type') }}</label>
  <input type="text" name="document_type" class="field" maxlength="100"
         placeholder="{{ __('site.panel.landowner.document_type_placeholder') }}"
         value="{{ old('document_type', $p?->document_type) }}">
</div>

<div class="field-group">
  <label>{{ __('site.panel.landowner.description') }}</label>
  <textarea name="description_fa" class="field" rows="5">{{ old('description_fa', $p?->localized_description) }}</textarea>
</div>

<div class="field-group">
  <label>{{ __('site.panel.landowner.images') }}</label>
  <input type="file" name="images[]" class="field" multiple accept="image/*">
  @if ($p && ! empty($p->images))
    <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:10px;">
      @foreach ($p->image_urls as $url)
        <div style="width:64px; height:64px; border-radius:10px; overflow:hidden; border:1px solid var(--border);">
          <img src="{{ $url }}" style="width:100%; height:100%; object-fit:cover;">
        </div>
      @endforeach
    </div>
  @endif
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const priceDisplay = document.getElementById('price_display');
    const priceRaw = document.getElementById('price_raw');
    if (!priceDisplay || priceDisplay.dataset.bound) return;
    priceDisplay.dataset.bound = '1';
    priceDisplay.addEventListener('input', function () {
      const digits = this.value.replace(/[^0-9]/g, '');
      priceRaw.value = digits;
      this.value = digits ? Number(digits).toLocaleString('en-US') : '';
    });
  });
</script>
