@extends('layouts.app')
@section('title', __('site.property_requests.create_title'))

@section('content')
<div class="wrap" style="max-width:640px; padding-bottom:60px;">
  <div class="crumb">
    <a href="{{ route('home') }}">{{ __('site.nav.home') }}</a><span class="sep">/</span>
    <a href="{{ route('property-requests.index') }}">{{ __('site.property_requests.nav_title') }}</a><span class="sep">/</span>
    <span style="color:var(--ink);">{{ __('site.property_requests.create_title') }}</span>
  </div>

  <div class="panel" style="margin-top:16px;">
    <div class="panel-head"><h3>{{ __('site.property_requests.create_title') }}</h3></div>
    <p style="font-size:13px; color:var(--ink-soft); margin-bottom:20px;">{{ __('site.property_requests.form_note') }}</p>

    @if ($errors->any())
      <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('property-requests.store') }}" method="POST">
      @csrf

      <div class="form-grid-2">
        <div class="field-group"><label>{{ __('site.properties.inquiry_name') }}</label><input type="text" name="name" class="field" required value="{{ old('name', auth()->user()->name ?? '') }}"></div>
        <div class="field-group"><label>{{ __('site.properties.inquiry_phone') }}</label><input type="text" name="phone" class="field" required value="{{ old('phone', auth()->user()->phone ?? '') }}"></div>
      </div>

      <div class="field-group">
        <label>{{ __('site.property_requests.title_label') }}</label>
        <input type="text" name="title" class="field" required maxlength="255" placeholder="{{ __('site.property_requests.title_placeholder') }}" value="{{ old('title') }}">
      </div>

      <div class="form-grid-2">
        <div class="field-group">
          <label>{{ __('site.panel.landowner.property_type') }}</label>
          <select name="property_type" class="field" required>
            @foreach (['land','apartment','villa','commercial','other'] as $type)
              <option value="{{ $type }}" {{ old('property_type', 'land') === $type ? 'selected' : '' }}>{{ __('site.properties.types.'.$type) }}</option>
            @endforeach
          </select>
        </div>
        <div class="field-group">
          <label>{{ __('site.panel.landowner.deal_type') }}</label>
          <select name="deal_type" class="field" required>
            @foreach (['sale','partnership','barter','both'] as $deal)
              <option value="{{ $deal }}" {{ old('deal_type', 'sale') === $deal ? 'selected' : '' }}>{{ __('site.properties.deal_types.'.$deal) }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-grid-2">
        <div class="field-group"><label>{{ __('site.property_requests.min_area') }}</label><input type="number" name="min_area_sqm" class="field" min="1" value="{{ old('min_area_sqm') }}"></div>
        <div class="field-group"><label>{{ __('site.property_requests.max_area') }}</label><input type="number" name="max_area_sqm" class="field" min="1" value="{{ old('max_area_sqm') }}"></div>
      </div>

      <div class="form-grid-2">
        <div class="field-group"><label>{{ __('site.panel.landowner.province') }}</label><input type="text" name="province" class="field" required value="{{ old('province') }}"></div>
        <div class="field-group"><label>{{ __('site.panel.landowner.city') }}</label><input type="text" name="city" class="field" value="{{ old('city') }}"></div>
      </div>

      <div class="field-group">
        <label>{{ __('site.property_requests.budget_label') }}</label>
        <input type="text" inputmode="numeric" id="budget_display" class="field" placeholder="{{ app()->getLocale() === 'fa' ? 'مثلاً ۵۰۰,۰۰۰,۰۰۰ (اختیاری)' : 'e.g. 500,000,000 (optional)' }}">
        <input type="hidden" name="budget" id="budget_raw" value="{{ old('budget') }}">
      </div>

      <div class="field-group">
        <label>{{ __('site.property_requests.description_label') }}</label>
        <textarea name="description" class="field" rows="4" placeholder="{{ __('site.property_requests.description_placeholder') }}">{{ old('description') }}</textarea>
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">{{ __('site.property_requests.submit_request') }}</button>
    </form>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  var display = document.getElementById('budget_display');
  var raw = document.getElementById('budget_raw');
  if (!display) return;
  display.addEventListener('input', function () {
    var digits = this.value.replace(/[^0-9]/g, '');
    raw.value = digits;
    this.value = digits ? Number(digits).toLocaleString('en-US') : '';
  });
});
</script>
@endpush
@endsection
