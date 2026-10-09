@extends('layouts.auth')
@section('title', __('site.auth.login_title'))
@section('subtitle', __('site.auth.login_subtitle'))

@section('content')
<form method="POST" action="{{ route('login') }}">
  @csrf
  <div class="field-group">
    <label>{{ __('site.auth.email') }}</label>
    <input type="email" name="email" class="field" required autofocus value="{{ old('email') }}">
  </div>
  <div class="field-group">
    <label>{{ __('site.auth.password') }}</label>
    <input type="password" name="password" class="field" required>
  </div>
  <label class="filter-opt" style="margin-bottom:20px;">
    <input type="checkbox" name="remember" checked> {{ __('site.auth.remember_me') }}
  </label>
  <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">{{ __('site.auth.login_button') }}</button>
</form>

<div class="auth-footer" style="margin-top:10px;">
  <a href="{{ route('login.otp') }}">{{ __('site.auth.login_with_otp') }}</a>
</div>

<div class="auth-footer">
  {{ __('site.auth.no_account') }}
  <a href="{{ route('register') }}">{{ __('site.auth.register_customer') }}</a> ·
  <a href="{{ route('register.vendor') }}">{{ __('site.auth.register_booth') }}</a>
</div>
@endsection
