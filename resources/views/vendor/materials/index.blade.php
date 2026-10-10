@extends('layouts.panel')
@section('title', __('site.materials.vendor_title'))

@section('sidebar')
  @include('partials.vendor-sidebar')
@endsection

@section('content')
<div class="topbar">
  <div>
    <h1>{{ __('site.materials.vendor_title') }}</h1>
    <p>{{ __('site.materials.vendor_sub') }}</p>
  </div>
  <a href="{{ route('materials.index') }}" class="btn btn-ghost btn-sm">{{ __('site.materials.nav_title') }}</a>
</div>

@if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if ($errors->any())<div class="alert alert-error">{{ $errors->first() }}</div>@endif

<div class="subcats">
  <a href="{{ route('vendor.materials.index') }}" class="chip {{ !request('type') ? 'on' : '' }}">{{ __('site.common.all') }}</a>
  @foreach (\App\Models\MaterialRequest::TYPES as $type)
    <a href="{{ route('vendor.materials.index', ['type' => $type]) }}" class="chip {{ request('type') === $type ? 'on' : '' }}">{{ __('site.materials.types.'.$type) }}</a>
  @endforeach
</div>

@if ($requests->isEmpty())
  <div class="empty-state"><div class="icon">🏗️</div><p>{{ __('site.materials.vendor_empty') }}</p></div>
@else
  <div class="grid-2">
    @foreach ($requests as $item)
      @php $offer = $item->offers->first(); @endphp
      <div class="panel">
        <div class="panel-head">
          <h3 style="font-size:15px;">{{ $item->localized_title }}</h3>
          <a href="{{ route('materials.show', $item) }}" class="row-actions">{{ __('site.panel.view_details') }}</a>
        </div>
        <div class="stat-mini"><span>{{ __('site.materials.material_type') }}</span><b>{{ $item->localized_type }}</b></div>
        <div class="stat-mini"><span>{{ __('site.materials.quantity') }}</span><b>{{ $item->quantity_label }}</b></div>
        <div class="stat-mini"><span>{{ __('site.materials.fulfillment') }}</span><b>{{ $item->localized_fulfillment }}</b></div>
        <div class="stat-mini"><span>{{ __('site.panel.landowner.city') }}</span><b>{{ $item->city }}@if($item->city && $item->province)،@endif {{ $item->province }}</b></div>
        @if ($item->delivery_place)
          <div class="stat-mini"><span>{{ __('site.materials.delivery_place') }}</span><b>{{ $item->delivery_place }}</b></div>
        @endif

        @if ($offer)
          <div class="alert alert-success" style="margin-top:14px;">
            {{ __('site.materials.your_offer') }}
            @if ($offer->quoted_amount)
              — {{ number_format($offer->quoted_amount) }} {{ __('site.common.toman') }}
            @endif
          </div>
        @else
          <form action="{{ route('materials.offers.store', $item) }}" method="POST" style="margin-top:14px; border-top:1px solid var(--border); padding-top:14px;">
            @csrf
            <div class="form-grid-2">
              <div class="field-group">
                <label>{{ __('site.properties.inquiry_name') }}</label>
                <input type="text" name="name" class="field" required value="{{ auth()->user()->booth->localized_name ?? auth()->user()->name }}">
              </div>
              <div class="field-group">
                <label>{{ __('site.properties.inquiry_phone') }}</label>
                <input type="text" name="phone" class="field" required value="{{ auth()->user()->booth->phone ?? auth()->user()->phone }}">
              </div>
            </div>
            <div class="form-grid-2">
              <div class="field-group">
                <label>{{ __('site.materials.offer_amount') }}</label>
                <input type="number" name="quoted_amount" class="field" min="0" inputmode="numeric">
              </div>
              <div class="field-group">
                <label>{{ __('site.materials.offer_lead') }}</label>
                <input type="number" name="lead_days" class="field" min="0" max="365">
              </div>
            </div>
            <div class="field-group">
              <label>{{ __('site.materials.offer_message_label') }}</label>
              <textarea name="message" class="field" rows="2" placeholder="{{ __('site.materials.offer_message_placeholder') }}"></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">{{ __('site.materials.offer_submit') }}</button>
          </form>
        @endif
      </div>
    @endforeach
  </div>
  <div class="pagination">{{ $requests->links() }}</div>
@endif
@endsection
