@extends('layouts.app')
@section('title', $propertyRequest->localized_title)
@section('meta_description', \Illuminate\Support\Str::limit($propertyRequest->localized_description, 155))

@section('content')
<div class="wrap">
  <div class="crumb">
    <a href="{{ route('home') }}">{{ __('site.nav.home') }}</a><span class="sep">/</span>
    <a href="{{ route('property-requests.index') }}">{{ __('site.property_requests.nav_title') }}</a><span class="sep">/</span>
    <span style="color:var(--ink);">{{ $propertyRequest->localized_title }}</span>
  </div>

  <div class="product-detail-grid">
    <div class="product-detail-info" style="grid-column:1/3; max-width:640px;">
      <span style="font-size:13px; color:var(--accent); font-weight:600;">{{ $propertyRequest->localized_property_type }} · {{ $propertyRequest->localized_deal_type }}</span>
      <h1 style="font-size:26px; font-weight:800; margin:10px 0 16px; letter-spacing:-0.3px;">{{ $propertyRequest->localized_title }}</h1>

      <div style="display:flex; gap:16px; flex-wrap:wrap; margin-bottom:20px; font-size:13px; color:var(--ink-soft);">
        @if ($propertyRequest->city || $propertyRequest->province)
          <span>📍 {{ $propertyRequest->city }}@if($propertyRequest->city && $propertyRequest->province)،@endif {{ $propertyRequest->province }}</span>
        @endif
        @if ($propertyRequest->area_range_label)
          <span>·</span><span>📐 {{ $propertyRequest->area_range_label }} {{ __('site.properties.area_suffix') }}</span>
        @endif
        @if ($propertyRequest->budget)
          <span>·</span><span>💰 {{ __('site.property_requests.budget_label') }}: {{ number_format($propertyRequest->budget) }} {{ __('site.common.toman') }}</span>
        @endif
      </div>

      @if ($propertyRequest->localized_description)
        <div style="font-size:15px; color:var(--ink); line-height:2; margin-bottom:30px;">{!! nl2br(e($propertyRequest->localized_description)) !!}</div>
      @endif

      {{-- ---------- OFFER / CONTACT FORM ---------- --}}
      <div class="panel" style="background:var(--primary-tint); border:none; max-width:520px;">
        <div class="panel-head"><h3>{{ __('site.property_requests.offer_box_title') }}</h3></div>
        <p style="font-size:13px; color:var(--ink-soft); margin-bottom:16px;">{{ __('site.property_requests.offer_box_text') }}</p>

        @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
          <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('property-requests.offer.store', $propertyRequest) }}" method="POST">
          @csrf
          <div class="form-grid-2">
            <div class="field-group"><label>{{ __('site.properties.inquiry_name') }}</label><input type="text" name="name" class="field" required value="{{ old('name', auth()->user()->name ?? '') }}"></div>
            <div class="field-group"><label>{{ __('site.properties.inquiry_phone') }}</label><input type="text" name="phone" class="field" required value="{{ old('phone', auth()->user()->phone ?? '') }}"></div>
          </div>
          <div class="field-group">
            <label>{{ __('site.property_requests.offer_message_label') }}</label>
            <textarea name="message" class="field" rows="3" placeholder="{{ __('site.property_requests.offer_message_placeholder') }}">{{ old('message') }}</textarea>
          </div>
          <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">{{ __('site.property_requests.offer_submit') }}</button>
          <p style="font-size:11px; color:var(--ink-soft); margin-top:10px; text-align:center;">{{ __('site.properties.inquiry_privacy_note') }}</p>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
