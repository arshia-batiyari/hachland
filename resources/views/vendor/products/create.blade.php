@extends('layouts.panel')
@section('title', __('site.panel.vendor.create_product_title'))

@section('sidebar')
  @include('partials.vendor-sidebar')
@endsection

@section('content')
<div class="topbar"><div><h1>{{ __('site.panel.vendor.create_product_title') }}</h1><p>{{ __('site.panel.vendor.create_product_sub') }}</p></div></div>

<div class="panel" style="max-width:640px;">
  <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="field-group">
      <label>{{ __('site.panel.vendor.name_fa') }}</label>
      <input type="text" name="name_fa" class="field" required value="{{ old('name_fa') }}">
    </div>
    <div class="field-group">
      <label>{{ __('site.panel.vendor.name_en') }}</label>
      <input type="text" name="name_en" class="field" value="{{ old('name_en') }}">
    </div>
    <div class="field-group">
      <label>{{ __('site.panel.vendor.category') }}</label>
      <select name="category_id" class="field" required>
        <option value="">{{ __('site.panel.vendor.choose') }}</option>
        @foreach ($categories as $cat)
          <option value="{{ $cat->id }}">{{ $cat->localized_name }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-grid-2">
      <div class="field-group">
        <label>{{ __('site.panel.vendor.price_toman') }}</label>
        <input type="text" inputmode="numeric" id="price_display" class="field" required
               placeholder="{{ app()->getLocale() === 'fa' ? 'مثلاً ۱,۸۵۰,۰۰۰' : 'e.g. 1,850,000' }}" value="{{ old('price') ? number_format(old('price')) : '' }}">
        <input type="hidden" name="price" id="price_raw" value="{{ old('price') }}">
      </div>
      <div class="field-group"><label>{{ __('site.panel.vendor.stock') }}</label><input type="number" name="stock" class="field" required min="0" value="{{ old('stock') }}"></div>
    </div>
    <div class="field-group">
      <label>{{ __('site.panel.vendor.weight_grams') }}</label>
      <input type="number" name="weight_grams" class="field" required min="50" value="500">
      <p style="font-size:11.5px; color:var(--ink-soft); margin-top:6px;">{{ __('site.panel.vendor.weight_note') }}</p>
    </div>
    <div class="field-group">
      <label>{{ __('site.panel.vendor.description') }}</label>
      <textarea name="description_fa" class="field" rows="4" placeholder="{{ __('site.panel.vendor.description_placeholder') }}">{{ old('description_fa') }}</textarea>
      <div class="field-hint">
        <b>{{ __('site.panel.vendor.description_hint_title') }}</b>
        <p>{{ __('site.panel.vendor.description_hint_body') }}</p>
        <span class="field-hint-example">{{ __('site.panel.vendor.description_example_1') }}</span>
        <span class="field-hint-example">{{ __('site.panel.vendor.description_example_2') }}</span>
      </div>
    </div>
    <div class="field-group"><label>درصد تخفیف</label><input type="number" name="discount_percent" min="0" max="90" class="field" value="0"></div>
    <div class="field-group">
      <label>{{ __('site.panel.vendor.product_images') }}</label>
      <input type="file" name="images[]" class="field" multiple accept="image/*">
    </div>
    <div class="field-group"><label>ویدیوی محصول (حداکثر 50MB - پس از تایید ادمین نمایش داده می‌شود)</label><input type="file" name="video" class="field" accept="video/mp4,video/webm">
    </div>
    <button type="submit" class="btn btn-primary">{{ __('site.panel.vendor.submit_product') }}</button>
  </form>
</div>

<script>
  // نمایش زنده قیمت با جداکننده هزارگان (۱,۸۵۰,۰۰۰) و ارسال عدد خام به سرور
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
@endsection
