@extends('layouts.panel')
@section('title', __('site.panel.admin.order_details_title'))

@section('sidebar')
  @include('partials.admin-sidebar')
@endsection

@section('content')
<div class="topbar">
  <div>
    <h1>{{ $order->order_number }}</h1>
    <p>{{ __('site.panel.customer_orders.placed_at', ['date' => jdate($order->created_at, 'Y/m/d - H:i')]) }}</p>
  </div>
  <div style="display:flex; align-items:center; gap:10px;">
    <span class="status-pill {{ $order->payment_status == 'paid' ? 'done' : ($order->payment_status == 'failed' ? 'cancelled' : 'pending') }}">{{ __('site.status.payment_full.'.$order->payment_status) }}</span>
    @if ($order->payment_status === 'pending')
      <form action="{{ route('admin.orders.confirm-payment', $order) }}" method="POST">@csrf<button type="submit" class="btn btn-primary">{{ __('site.panel.admin.confirm_payment') }}</button></form>
      <form action="{{ route('admin.orders.reject-payment', $order) }}" method="POST" onsubmit="return confirm(@json(__('site.panel.confirm_delete')))">@csrf<button type="submit" class="btn btn-ghost" style="color:var(--red); border-color:var(--red);">{{ __('site.panel.reject') }}</button></form>
    @endif
  </div>
</div>

@if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<div class="grid-2">
  <div class="panel">
    <div class="panel-head"><h3>{{ __('site.panel.customer_orders.order_items') }}</h3></div>
    @foreach ($order->items as $item)
      <div style="display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid var(--border); font-size:13.5px;">
        <span>{{ $item->product_name[app()->getLocale()] ?? $item->product_name['fa'] ?? '' }} × {{ $item->quantity }} <span style="color:var(--ink-soft); font-size:12px;">({{ $item->booth->localized_name }})</span></span>
        <b>{{ number_format($item->line_total) }} {{ __('site.common.toman') }}</b>
      </div>
    @endforeach
    <div class="stat-mini" style="margin-top:14px;"><span>{{ __('site.panel.admin.th_customer') }}</span><b>{{ $order->user->name }} — {{ $order->user->phone }}</b></div>
    @if ($order->address)
      <div class="stat-mini"><span>{{ __('site.checkout.shipping_address') }}</span><b>{{ $order->address->province }}، {{ $order->address->city }} — {{ $order->address->address_line }}</b></div>
    @endif
    <div class="stat-mini" style="font-size:15px; padding-top:14px;"><span>{{ __('site.checkout.final_amount') }}</span><b style="color:var(--primary-dark);">{{ number_format($order->total_amount) }} {{ __('site.common.toman') }}</b></div>
  </div>

  <div class="panel">
    <div class="panel-head"><h3>{{ __('site.panel.admin.payment_proof_title') }}</h3></div>
    @if (! $order->payment_ref_id && ! $order->payment_receipt_url)
      <p style="font-size:13px; color:var(--ink-soft);">{{ __('site.panel.admin.no_payment_proof_yet') }}</p>
    @else
      @if ($order->payment_ref_id)
        <div class="stat-mini"><span>{{ __('site.panel.customer_orders.transaction_ref') }}</span><b>{{ $order->payment_ref_id }}</b></div>
      @endif
      @if ($order->payment_submitted_at)
        <div class="stat-mini"><span>{{ __('site.panel.admin.submitted_at') }}</span><b>{{ jdate($order->payment_submitted_at, 'Y/m/d - H:i') }}</b></div>
      @endif
      @if ($order->payment_receipt_url)
        <a href="{{ $order->payment_receipt_url }}" target="_blank" style="display:block; margin-top:14px;">
          <img src="{{ $order->payment_receipt_url }}" alt="{{ __('site.checkout.payment_receipt_label') }}" style="width:100%; border-radius:var(--radius-sm); border:1px solid var(--border);">
        </a>
      @endif
    @endif

    <div class="card-transfer-box" style="margin-top:18px;">
      <h4>{{ __('site.panel.admin.expected_card') }}</h4>
      <div class="card-number-row"><span dir="ltr">{{ chunk_split(config('site_payment.card_number'), 4, ' ') }}</span></div>
    </div>
  </div>
</div>
@endsection
