@extends('layouts.auth')
@section('title', __('site.auth.otp_login_title'))
@section('subtitle', __('site.auth.otp_login_subtitle'))

@section('content')
<form method="POST" action="{{ route('login.otp.send') }}">
  @csrf
  <div class="field-group">
    <label>{{ __('site.auth.phone') }}</label>
    <input type="tel" name="phone" inputmode="numeric" class="field" placeholder="09121234567" required autofocus value="{{ old('phone') }}">
  </div>
  <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">{{ __('site.auth.send_login_code') }}</button>
</form>

<div class="auth-footer">
  <a href="{{ route('login') }}">{{ __('site.auth.login_with_password') }}</a>
</div>
@endsection
