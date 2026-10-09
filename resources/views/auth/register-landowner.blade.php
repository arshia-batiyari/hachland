@extends('layouts.auth')
@section('title', __('site.auth.landowner_register_title'))
@section('subtitle', __('site.auth.landowner_register_subtitle'))

@section('content')
<form method="POST" action="{{ route('register.landowner.store') }}">
  @csrf

  <div class="field-group"><label>{{ __('site.auth.full_name') }}</label><input type="text" name="name" class="field" required value="{{ old('name') }}"></div>
  <div class="field-group"><label>{{ __('site.auth.phone') }}</label><input type="text" name="phone" class="field" required value="{{ old('phone') }}"></div>
  <div class="field-group"><label>{{ __('site.auth.email') }} <span style="color:var(--ink-soft); font-weight:400;">({{ app()->getLocale() === 'fa' ? 'اختیاری' : 'optional' }})</span></label><input type="email" name="email" class="field" value="{{ old('email') }}"></div>
  <div class="form-grid-2">
    <div class="field-group"><label>{{ __('site.auth.password') }}</label><input type="password" name="password" class="field" required></div>
    <div class="field-group"><label>{{ __('site.auth.password_confirm') }}</label><input type="password" name="password_confirmation" class="field" required></div>
  </div>

  <div class="alert" style="background:var(--primary-tint); color:var(--primary-dark); margin-bottom:20px;">
    {{ __('site.auth.landowner_note') }}
  </div>

  <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">{{ __('site.auth.submit_landowner_request') }}</button>
</form>

<div class="auth-footer">
  {{ __('site.auth.has_account') }} <a href="{{ route('login') }}">{{ __('site.auth.login_button') }}</a>
</div>
@endsection
