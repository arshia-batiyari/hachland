@extends('layouts.auth')
@section('title', __('site.auth.vendor_register_title'))
@section('subtitle', __('site.auth.vendor_register_subtitle'))
@section('box-class', 'wide')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
  /* هماهنگ‌سازی ظاهر Select2 با استایل فیلدهای فرم هاچ لند */
  .select2-container .select2-selection--multiple{min-height:44px; border:1px solid var(--border); border-radius:8px; padding:4px 8px; font-family:inherit;}
  .select2-container--default .select2-selection--multiple .select2-selection__choice{background:var(--primary-tint); border-color:var(--border); color:var(--primary-dark); border-radius:6px;}
  .select2-dropdown{border-color:var(--border); font-family:inherit;}
  .select2-container--default .select2-results__option--highlighted[aria-selected]{background:var(--primary);}
</style>
@endpush

@section('content')
<form method="POST" action="{{ route('register.vendor.store') }}" enctype="multipart/form-data">
  @csrf

  <h4 style="font-size:13.5px; font-weight:700; color:var(--primary); margin-bottom:14px;">{{ __('site.auth.section_account') }}</h4>
  <div class="form-grid-2">
    <div class="field-group"><label>{{ __('site.auth.full_name') }}</label><input type="text" name="owner_name" class="field" required value="{{ old('owner_name') }}"></div>
    <div class="field-group"><label>{{ __('site.auth.phone') }}</label><input type="text" name="phone" class="field" required value="{{ old('phone') }}"></div>
  </div>
  <div class="field-group"><label>{{ __('site.auth.email') }}</label><input type="email" name="email" class="field" required value="{{ old('email') }}"></div>
  <div class="form-grid-2">
    <div class="field-group"><label>{{ __('site.auth.password') }}</label><input type="password" name="password" class="field" required></div>
    <div class="field-group"><label>{{ __('site.auth.password_confirm') }}</label><input type="password" name="password_confirmation" class="field" required></div>
  </div>

  <h4 style="font-size:13.5px; font-weight:700; color:var(--primary); margin:24px 0 14px;">{{ __('site.auth.section_booth') }}</h4>
  <div class="field-group"><label>{{ __('site.auth.booth_name') }}</label><input type="text" name="booth_name" class="field" required value="{{ old('booth_name') }}"></div>

  <div class="field-group">
    <label>{{ __('site.auth.categories_label') }}</label>
    <select name="categories[]" id="vendor-categories" class="field" multiple required style="height:auto;">
      @foreach ($categories as $cat)
        <option value="{{ $cat->id }}">{{ $cat->localized_name }}</option>
      @endforeach
    </select>
  </div>

  <div class="form-grid-2">
    <div class="field-group"><label>{{ __('site.auth.province') }}</label><input type="text" name="province" class="field" required value="{{ old('province') }}"></div>
    <div class="field-group"><label>{{ __('site.auth.city') }}</label><input type="text" name="city" class="field" required value="{{ old('city') }}"></div>
  </div>
  <div class="form-grid-2">
    <div class="field-group"><label>{{ __('site.auth.booth_phone') }}</label><input type="text" name="booth_phone" class="field" required value="{{ old('booth_phone') }}"></div>
    <div class="field-group"><label>{{ __('site.auth.national_id') }}</label><input type="text" name="national_id" class="field" required value="{{ old('national_id') }}"></div>
  </div>
  <div class="field-group"><label>{{ __('site.auth.booth_description') }}</label><textarea name="description" class="field" rows="3">{{ old('description') }}</textarea></div>

  <h4 style="font-size:13.5px; font-weight:700; color:var(--primary); margin:24px 0 14px;">{{ __('site.auth.section_bank') }}</h4>
  <div class="field-group"><label>{{ __('site.auth.bank_account_holder') }}</label><input type="text" name="bank_account_holder" class="field" required value="{{ old('bank_account_holder') }}"></div>
  <div class="form-grid-2">
    <div class="field-group"><label>{{ __('site.auth.bank_name') }}</label><input type="text" name="bank_name" class="field" required placeholder="{{ __('site.auth.bank_name_placeholder') }}" value="{{ old('bank_name') }}"></div>
    <div class="field-group"><label>{{ __('site.auth.bank_iban') }}</label><input type="text" name="bank_iban" class="field" required placeholder="IR820540102680020817909002" maxlength="26" value="{{ old('bank_iban') }}"></div>
  </div>
  <p style="font-size:11.5px; color:var(--ink-soft); margin:-10px 0 16px;">{{ __('site.auth.bank_note') }}</p>

  <h4 style="font-size:13.5px; font-weight:700; color:var(--primary); margin:24px 0 14px;">{{ __('site.auth.section_verification') }}</h4>
  <div class="field-group">
    <label>{{ __('site.auth.verification_document') }}</label>
    <input type="file" name="verification_document" class="field" accept=".jpg,.jpeg,.png,.pdf" required>
    <p style="font-size:11.5px; color:var(--ink-soft); margin-top:6px;">{{ __('site.auth.verification_document_note') }}</p>
  </div>
  <div class="field-group">
    <label>{{ __('site.auth.booth_logo') }}</label>
    <input type="file" name="logo" class="field" accept="image/*">
  </div>

  <div class="alert" style="background:var(--accent-tint); color:#7A5A1F; margin-bottom:20px;">
    {{ __('site.auth.vendor_pending_note') }}
  </div>

  <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">{{ __('site.auth.submit_vendor_request') }}</button>
</form>

<div class="auth-footer">
  {{ __('site.auth.has_account') }} <a href="{{ route('login') }}">{{ __('site.auth.login_button') }}</a>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  // چون این فرم می‌تونه با خطای اعتبارسنجی دوباره از سرور برگرده (و در آینده
  // با Turbo هم جایگزین بشه)، مقداردهی رو تو یه تابع می‌ذاریم و هم موقع بارگذاری
  // اولیه و هم بعد از هر ناوبری Turbo صداش می‌زنیم تا Select2 همیشه فعال بمونه.
  function initVendorCategoriesSelect2() {
    var $el = jQuery('#vendor-categories');
    if (!$el.length || $el.data('select2')) return;
    $el.select2({
      width: '100%',
      placeholder: @json(__('site.auth.categories_label')),
      dir: document.documentElement.dir || 'rtl',
    });
  }
  document.addEventListener('DOMContentLoaded', initVendorCategoriesSelect2);
</script>
@endpush
