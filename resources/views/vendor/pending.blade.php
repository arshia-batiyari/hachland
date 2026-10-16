@extends('layouts.app')
@section('title', __('site.panel.vendor.pending_title'))

@section('content')
<div class="wrap" style="padding:80px 0; text-align:center;">
  <div style="font-size:40px; margin-bottom:20px;">⏳</div>
  <h1 style="font-size:22px; font-weight:800; color:var(--primary-dark); margin-bottom:12px;">{{ __('site.panel.vendor.pending_title') }}</h1>
  <p style="color:var(--ink-soft); max-width:460px; margin:0 auto;">{{ __('site.panel.vendor.pending_text', ['phone' => auth()->user()->phone]) }}</p>
</div>
@endsection
