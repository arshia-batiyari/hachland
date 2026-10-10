@extends('layouts.app')
@section('title', __('site.materials.create_title'))

@section('content')
<div class="wrap" style="max-width:640px; padding-bottom:60px;">
  <div class="crumb">
    <a href="{{ route('home') }}">{{ __('site.nav.home') }}</a><span class="sep">/</span>
    <a href="{{ route('materials.index') }}">{{ __('site.materials.nav_title') }}</a><span class="sep">/</span>
    <span style="color:var(--ink);">{{ __('site.materials.create_title') }}</span>
  </div>

  <div class="panel" style="margin-top:16px;">
    <div class="panel-head"><h3>{{ __('site.materials.create_title') }}</h3></div>
    <p style="font-size:13px; color:var(--ink-soft); margin-bottom:20px;">{{ __('site.materials.form_note') }}</p>

    @if ($errors->any())
      <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('materials.store') }}" method="POST">
      @csrf
      <div class="form-grid-2">
        <div class="field-group"><label>{{ __('site.properties.inquiry_name') }}</label><input type="text" name="name" class="field" required value="{{ old('name', auth()->user()->name ?? '') }}"></div>
        <div class="field-group"><label>{{ __('site.properties.inquiry_phone') }}</label><input type="text" name="phone" class="field" required value="{{ old('phone', auth()->user()->phone ?? '') }}"></div>
      </div>

      <div class="field-group">
        <label>{{ __('site.materials.title_label') }}</label>
        <input type="text" name="title" class="field" required maxlength="255" placeholder="{{ __('site.materials.title_placeholder') }}" value="{{ old('title') }}">
      </div>

      <div class="form-grid-2">
        <div class="field-group">
          <label>{{ __('site.materials.material_type') }}</label>
          <select name="material_type" class="field" required>
            @foreach (\App\Models\MaterialRequest::TYPES as $type)
              <option value="{{ $type }}" {{ old('material_type', 'cement') === $type ? 'selected' : '' }}>{{ __('site.materials.types.'.$type) }}</option>
            @endforeach
          </select>
        </div>
        <div class="field-group">
          <label>{{ __('site.materials.fulfillment') }}</label>
          <select name="fulfillment" class="field" required>
            @foreach (\App\Models\MaterialRequest::FULFILLMENTS as $fulfillment)
              <option value="{{ $fulfillment }}" {{ old('fulfillment', 'purchase') === $fulfillment ? 'selected' : '' }}>{{ __('site.materials.fulfillments.'.$fulfillment) }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-grid-2">
        <div class="field-group">
          <label>{{ __('site.materials.quantity') }}</label>
          <input type="text" name="quantity_label" class="field" required maxlength="120" placeholder="{{ __('site.materials.quantity_placeholder') }}" value="{{ old('quantity_label') }}">
        </div>
        <div class="field-group">
          <label>{{ __('site.materials.needed_on') }}</label>
          <input type="date" name="needed_on" class="field" value="{{ old('needed_on') }}">
        </div>
      </div>

      <div class="form-grid-2">
        <div class="field-group"><label>{{ __('site.panel.landowner.province') }}</label><input type="text" name="province" class="field" required value="{{ old('province') }}"></div>
        <div class="field-group"><label>{{ __('site.panel.landowner.city') }}</label><input type="text" name="city" class="field" value="{{ old('city') }}"></div>
      </div>

      <div class="field-group">
        <label>{{ __('site.materials.delivery_place') }}</label>
        <input type="text" name="delivery_place" class="field" maxlength="255" placeholder="{{ __('site.materials.delivery_placeholder') }}" value="{{ old('delivery_place') }}">
      </div>

      <div class="field-group">
        <label>{{ __('site.materials.budget_label') }}</label>
        <input type="text" inputmode="numeric" id="budget_display" class="field" placeholder="{{ __('site.materials.budget_placeholder') }}">
        <input type="hidden" name="budget" id="budget_raw" value="{{ old('budget') }}">
      </div>

      <div class="field-group">
        <label>{{ __('site.materials.description_label') }}</label>
        <textarea name="description" class="field" rows="4" placeholder="{{ __('site.materials.description_placeholder') }}">{{ old('description') }}</textarea>
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">{{ __('site.materials.submit_request') }}</button>
    </form>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  var display = document.getElementById('budget_display');
  var raw = document.getElementById('budget_raw');
  if (!display || !raw) return;
  function paint(digits) {
    raw.value = digits;
    display.value = digits ? Number(digits).toLocaleString('en-US') : '';
  }
  if (raw.value) paint(String(raw.value).replace(/[^0-9]/g, ''));
  display.addEventListener('input', function () {
    paint(this.value.replace(/[^0-9]/g, ''));
  });
});
</script>
@endpush
@endsection
