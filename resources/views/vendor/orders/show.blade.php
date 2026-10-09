@extends('layouts.panel')
@section('title', __('site.panel.vendor.order_details_title', ['number' => $shipment->order->order_number]))

@section('sidebar')
  @include('partials.vendor-sidebar')
@endsection

@section('content')
<div class="topbar"><div><h1>{{ __('site.panel.customer_orders.order_title', ['number' => $shipment->order->order_number]) }}</h1><p>{{ __('site.panel.vendor.customer_label', ['name' => $shipment->order->user->name]) }}</p></div></div>

<div class="grid-2">
  <div class="panel">
    <div class="panel-head"><h3>{{ __('site.panel.vendor.items_in_order') }}</h3></div>
    @foreach ($shipment->items as $item)
      <div style="display:flex; justify-content:space-between; padding:11px 0; border-bottom:1px solid var(--border); font-size:13.5px;">
        <span>{{ $item->product_name[app()->getLocale()] ?? $item->product_name['fa'] ?? '' }} × {{ $item->quantity }}</span>
        <b>{{ number_format($item->line_total) }} {{ __('site.common.toman') }}</b>
      </div>
    @endforeach
    <div style="display:flex; justify-content:space-between; padding:11px 0; font-size:12.5px; color:var(--ink-soft);">
      <span>{{ __('site.panel.vendor.shipping_cost_meydan_note') }}</span>
      <span>{{ number_format($shipment->shipping_cost) }} {{ __('site.common.toman') }}</span>
    </div>

    <div class="panel-head" style="margin-top:20px;"><h3>{{ __('site.panel.vendor.customer_address') }}</h3></div>
    <p style="font-size:13px; color:var(--ink-soft);">
      {{ $shipment->order->address->receiver_name }} — {{ $shipment->order->address->receiver_phone }}<br>
      {{ $shipment->order->address->province }}، {{ $shipment->order->address->city }} — {{ $shipment->order->address->address_line }}
    </p>
  </div>

  <div class="panel">
    <div class="panel-head"><h3>{{ __('site.panel.vendor.shipment_status') }}</h3></div>
    @if ($shipment->status === 'preparing')
      <form action="{{ route('vendor.orders.ship', $shipment) }}" method="POST">
        @csrf
        <div class="field-group"><label>{{ __('site.panel.vendor.carrier') }}</label><input type="text" name="carrier" class="field" placeholder="{{ __('site.panel.vendor.carrier_placeholder') }}" required></div>
        <div class="field-group"><label>{{ __('site.panel.vendor.tracking_code_label') }}</label><input type="text" name="tracking_code" class="field" required></div>
        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">{{ __('site.panel.vendor.submit_shipment') }}</button>
      </form>
    @else
      <div class="stat-mini"><span>{{ __('site.panel.vendor.carrier') }}</span><b>{{ $shipment->carrier }}</b></div>
      <div class="stat-mini"><span>{{ __('site.panel.vendor.tracking_code_label') }}</span><b>{{ $shipment->tracking_code }}</b></div>
      <div class="stat-mini"><span>{{ __('site.panel.vendor.shipped_at') }}</span><b>{{ jdate($shipment->shipped_at) }}</b></div>
    @endif
  </div>
</div>
@endsection
