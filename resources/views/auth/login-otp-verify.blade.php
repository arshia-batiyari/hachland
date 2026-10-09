@extends('layouts.auth')
@section('title', __('site.auth.otp_login_title'))
@section('subtitle', __('site.auth.otp_verify_subtitle', ['phone' => $phone]))

@section('content')
<form method="POST" action="{{ route('login.otp.verify.store') }}">
  @csrf
  <div class="field-group">
    <label>{{ __('site.auth.login_code') }}</label>
    <input type="text" name="code" inputmode="numeric" maxlength="5" class="field" style="text-align:center; font-size:22px; letter-spacing:8px;" required autofocus>
  </div>
  <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">{{ __('site.auth.login_button') }}</button>
</form>

<form method="POST" action="{{ route('login.otp.send') }}" style="margin-top:14px;">
  @csrf
  <input type="hidden" name="phone" value="{{ $phone }}">
  <button type="submit" class="btn btn-ghost" style="width:100%; justify-content:center;">{{ __('site.auth.resend_code') }}</button>
</form>

<div class="auth-footer"><a href="{{ route('login.otp') }}">{{ __('site.auth.change_phone') }}</a></div>
@endsection
