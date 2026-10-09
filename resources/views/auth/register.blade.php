@extends('layouts.auth')
@section('title', __('site.auth.register_title'))
@section('subtitle', __('site.auth.register_subtitle'))

@section('content')
<form method="POST" action="{{ route('register') }}">
  @csrf
  <div class="field-group">
    <label>{{ __('site.auth.full_name') }}</label>
    <input type="text" name="name" class="field" required value="{{ old('name') }}">
  </div>
  <div class="field-group">
    <label>{{ __('site.auth.phone') }}</label>
    <input type="text" name="phone" class="field" required value="{{ old('phone') }}">
  </div>
  <div class="field-group">
    <label>{{ __('site.auth.email') }}</label>
    <input type="email" name="email" class="field" required value="{{ old('email') }}">
  </div>
  <div class="form-grid-2">
    <div class="field-group"><label>{{ __('site.auth.password') }}</label><input type="password" name="password" class="field" required></div>
    <div class="field-group"><label>{{ __('site.auth.password_confirm') }}</label><input type="password" name="password_confirmation" class="field" required></div>
  </div>
  <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">{{ __('site.auth.register_button') }}</button>
</form>

<div class="auth-footer">
  {{ __('site.auth.has_account') }} <a href="{{ route('login') }}">{{ __('site.auth.login_button') }}</a><br><br>
  {{ __('site.auth.want_to_sell') }} <a href="{{ route('register.vendor') }}">{{ __('site.auth.register_vendor_link') }}</a>
</div>
@endsection
