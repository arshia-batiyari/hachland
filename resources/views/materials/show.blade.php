@extends('layouts.app')
@section('title', $materialRequest->localized_title)
@section('meta_description', \Illuminate\Support\Str::limit($materialRequest->localized_description ?: $materialRequest->quantity_label, 155))

@section('content')
<div class="wrap">
  <div class="crumb">
    <a href="{{ route('home') }}">{{ __('site.nav.home') }}</a><span class="sep">/</span>
    <a href="{{ route('materials.index') }}">{{ __('site.materials.nav_title') }}</a><span class="sep">/</span>
    <span style="color:var(--ink);">{{ $materialRequest->localized_title }}</span>
  </div>

  <div class="product-detail-grid">
    <div class="product-detail-info" style="grid-column:1/3; max-width:640px;">
      <span style="font-size:13px; color:var(--accent); font-weight:600;">{{ $materialRequest->localized_type }} · {{ $materialRequest->localized_fulfillment }}</span>
      <h1 style="font-size:26px; font-weight:800; margin:10px 0 16px; letter-spacing:-0.3px;">{{ $materialRequest->localized_title }}</h1>

      <div style="display:flex; gap:16px; flex-wrap:wrap; margin-bottom:20px; font-size:13px; color:var(--ink-soft);">
        <span>📦 {{ $materialRequest->quantity_label }}</span>
        @if ($materialRequest->city || $materialRequest->province)
          <span>📍 {{ $materialRequest->city }}@if($materialRequest->city && $materialRequest->province)،@endif {{ $materialRequest->province }}</span>
        @endif
        @if ($materialRequest->needed_on)
          <span>🗓 {{ jdate($materialRequest->needed_on, 'j F Y') }}</span>
        @endif
        @if ($materialRequest->budget)
          <span>💰 {{ number_format($materialRequest->budget) }} {{ __('site.common.toman') }}</span>
        @endif
      </div>

      @if ($materialRequest->delivery_place)
        <p style="font-size:14px; margin-bottom:16px;"><b>{{ __('site.materials.delivery_place') }}:</b> {{ $materialRequest->delivery_place }}</p>
      @endif

      @if ($materialRequest->localized_description)
        <div style="font-size:15px; color:var(--ink); line-height:2; margin-bottom:30px;">{!! nl2br(e($materialRequest->localized_description)) !!}</div>
      @endif

      <div class="panel" style="background:var(--primary-tint); border:none; max-width:520px;">
        <div class="panel-head"><h3>{{ __('site.materials.offer_box_title') }}</h3></div>
        <p style="font-size:13px; color:var(--ink-soft); margin-bottom:16px;">{{ __('site.materials.offer_box_text') }}</p>

        @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
          <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        @if ($ownOffer)
          <div class="alert alert-success">{{ __('site.materials.your_offer') }}</div>
        @else
          <form action="{{ route('materials.offers.store', $materialRequest) }}" method="POST">
            @csrf
            <div class="form-grid-2">
              <div class="field-group"><label>{{ __('site.properties.inquiry_name') }}</label><input type="text" name="name" class="field" required value="{{ old('name', auth()->user()?->booth?->localized_name ?? auth()->user()?->name ?? '') }}"></div>
              <div class="field-group"><label>{{ __('site.properties.inquiry_phone') }}</label><input type="text" name="phone" class="field" required value="{{ old('phone', auth()->user()?->booth?->phone ?? auth()->user()?->phone ?? '') }}"></div>
            </div>
            <div class="form-grid-2">
              <div class="field-group">
                <label>{{ __('site.materials.offer_amount') }}</label>
                <input type="text" inputmode="numeric" id="quote_display" class="field">
                <input type="hidden" name="quoted_amount" id="quote_raw" value="{{ old('quoted_amount') }}">
              </div>
              <div class="field-group">
                <label>{{ __('site.materials.offer_lead') }}</label>
                <input type="number" name="lead_days" class="field" min="0" max="365" value="{{ old('lead_days') }}">
              </div>
            </div>
            <div class="field-group">
              <label>{{ __('site.materials.offer_message_label') }}</label>
              <textarea name="message" class="field" rows="3" placeholder="{{ __('site.materials.offer_message_placeholder') }}">{{ old('message') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">{{ __('site.materials.offer_submit') }}</button>
            <p style="font-size:11px; color:var(--ink-soft); margin-top:10px; text-align:center;">{{ __('site.properties.inquiry_privacy_note') }}</p>
          </form>
        @endif
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  var display = document.getElementById('quote_display');
  var raw = document.getElementById('quote_raw');
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
