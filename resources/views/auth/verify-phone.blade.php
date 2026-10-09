@extends('layouts.auth')
@section('title', __('site.auth.verify_phone_title'))
@section('subtitle', __('site.auth.otp_verify_subtitle', ['phone' => $phone]))

@section('content')
<form method="POST" action="{{ route('verify-phone.verify') }}">
  @csrf
  <div class="field-group">
    <label>{{ __('site.auth.verify_code') }}</label>
    <input type="text" name="code" inputmode="numeric" maxlength="5" class="field" style="text-align:center; font-size:22px; letter-spacing:8px;" required autofocus>
  </div>
  <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">{{ __('site.auth.verify_code_button') }}</button>
</form>

<form method="POST" action="{{ route('verify-phone.send') }}" style="margin-top:14px;">
  @csrf
  <button type="submit" class="btn btn-ghost" style="width:100%; justify-content:center;">{{ __('site.auth.resend_code') }}</button>
</form>

<div class="auth-footer">{{ __('site.auth.verify_code_help') }}</div>
@endsection
