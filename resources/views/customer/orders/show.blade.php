@extends('layouts.app')
@section('title', __('site.panel.customer_orders.order_title', ['number' => $order->order_number]))

@section('content')
<div class="wrap" style="padding-bottom:80px;">
  <div class="crumb"><a href="{{ route('customer.orders.index') }}">{{ __('site.panel.customer_orders.title') }}</a><span class="sep">/</span><span style="color:var(--ink);">{{ $order->order_number }}</span></div>

  <div class="topbar" style="margin-top:20px;">
    <div>
      <h1>{{ __('site.panel.customer_orders.order_title', ['number' => $order->order_number]) }}</h1>
      <p>{{ __('site.panel.customer_orders.placed_at', ['date' => jdate($order->created_at, 'Y/m/d - H:i')]) }}</p>
    </div>
    <span class="status-pill {{ $order->payment_status }}" style="font-size:13px; padding:8px 16px;">{{ __('site.status.payment_full.'.$order->payment_status) }}</span>
  </div>

  {{-- هر غرفه یک مرسوله جدا دارد --}}
  @foreach ($order->shipments as $shipment)
    <div class="panel" style="margin-bottom:18px;">
      <div class="panel-head">
        <h3>{{ $shipment->booth->localized_name }}</h3>
        <span class="status-pill {{ $shipment->status }}">{{ __('site.status.shipment.'.$shipment->status) }}</span>
      </div>

      @foreach ($shipment->items as $item)
        <div style="display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid var(--border); font-size:13.5px;">
          <span>{{ $item->product_name[app()->getLocale()] ?? $item->product_name['fa'] ?? '' }} × {{ $item->quantity }}</span>
          <b>{{ number_format($item->line_total) }} {{ __('site.common.toman') }}</b>
        </div>
      @endforeach

      <div style="display:flex; justify-content:space-between; padding:10px 0; font-size:12.5px; color:var(--ink-soft);">
        <span>{{ __('site.panel.customer_orders.shipping_cost_of_shipment') }}</span>
        <span>{{ number_format($shipment->shipping_cost) }} {{ __('site.common.toman') }}</span>
      </div>

      @if ($shipment->tracking_code)
        <p style="font-size:13px; margin-top:12px; color:var(--ink-soft);">{{ __('site.panel.customer_orders.tracking_code') }}: <b style="color:var(--ink);">{{ $shipment->tracking_code }}</b> ({{ $shipment->carrier }})</p>
      @endif
    </div>
  @endforeach

  <div class="panel">
    <div class="panel-head"><h3>{{ __('site.panel.customer_orders.payment_summary') }}</h3></div>
    <div class="stat-mini"><span>{{ __('site.panel.customer_orders.items_total') }}</span><b>{{ number_format($order->items_amount) }} {{ __('site.common.toman') }}</b></div>
    <div class="stat-mini"><span>{{ __('site.panel.customer_orders.shipping_cost') }}</span><b>{{ number_format($order->shipping_amount) }} {{ __('site.common.toman') }}</b></div>
    <div class="stat-mini"><span>{{ __('site.panel.customer_orders.final_amount') }}</span><b>{{ number_format($order->total_amount) }} {{ __('site.common.toman') }}</b></div>
    @if ($order->payment_ref_id)
      <div class="stat-mini"><span>{{ __('site.panel.customer_orders.transaction_ref') }}</span><b>{{ $order->payment_ref_id }}</b></div>
    @endif
    @if ($order->payment_receipt_url)
      <div class="stat-mini"><span>{{ __('site.checkout.payment_receipt_label') }}</span><a href="{{ $order->payment_receipt_url }}" target="_blank" style="color:var(--primary); font-weight:600;">{{ __('site.checkout.view_receipt') }}</a></div>
    @endif
  </div>

  @if ($order->payment_gateway === 'card_transfer' && ! $order->isPaid())
    <div class="panel" style="margin-top:18px;">
      <div class="panel-head"><h3>{{ __('site.checkout.card_transfer_title') }}</h3></div>
      <p style="font-size:13px; color:var(--ink-soft); margin-bottom:14px;">{{ __('site.checkout.card_transfer_note', ['amount' => number_format($order->total_amount)]) }}</p>
      <div class="card-transfer-box" style="margin-bottom:16px;">
        <div class="card-number-row">
          <span dir="ltr">{{ chunk_split(config('site_payment.card_number'), 4, ' ') }}</span>
          <button type="button" class="btn-copy" data-copy="{{ config('site_payment.card_number') }}">{{ __('site.checkout.copy') }}</button>
        </div>
        @if (config('site_payment.card_holder'))
          <span class="card-holder-name">{{ __('site.checkout.card_holder') }}: {{ config('site_payment.card_holder') }}</span>
        @endif
      </div>

      @if ($order->payment_submitted_at)
        <p style="font-size:12.5px; color:var(--accent); margin-bottom:12px;">✓ {{ __('site.checkout.payment_already_submitted') }}</p>
      @endif

      <form action="{{ route('customer.orders.payment.submit', $order) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="field-group">
          <label>{{ __('site.checkout.payment_reference_label') }}</label>
          <input type="text" name="payment_reference" class="field" value="{{ $order->payment_ref_id }}" placeholder="{{ __('site.checkout.payment_reference_placeholder') }}">
        </div>
        <div class="field-group">
          <label>{{ __('site.checkout.payment_receipt_label') }}</label>
          <input type="file" name="payment_receipt" class="field" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">{{ __('site.checkout.submit_payment_info') }}</button>
      </form>
    </div>
  @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.btn-copy').forEach(function (btn) {
    btn.addEventListener('click', function () {
      navigator.clipboard.writeText(btn.dataset.copy).then(function () {
        var original = btn.textContent;
        btn.textContent = @json(__('site.checkout.copied'));
        setTimeout(function () { btn.textContent = original; }, 1800);
      });
    });
  });
});
</script>
@endpush
@endsection
