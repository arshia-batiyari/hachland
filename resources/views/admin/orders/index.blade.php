@extends('layouts.panel')
@section('title', __('site.panel.admin.orders_title'))

@section('sidebar')
  @include('partials.admin-sidebar')
@endsection

@section('content')
<div class="topbar"><div><h1>{{ __('site.panel.admin.orders_title') }}</h1><p>{{ __('site.panel.admin.orders_count', ['count' => $orders->total()]) }}</p></div></div>

<div class="subcats">
  <a href="{{ route('admin.orders.index') }}" class="chip {{ !request('status') ? 'on' : '' }}">{{ __('site.panel.all') }}</a>
  <a href="{{ route('admin.orders.index', ['status'=>'pending']) }}" class="chip {{ request('status')=='pending' ? 'on' : '' }}">{{ __('site.status.payment_full.pending') }}</a>
  <a href="{{ route('admin.orders.index', ['status'=>'paid']) }}" class="chip {{ request('status')=='paid' ? 'on' : '' }}">{{ __('site.status.payment_full.paid') }}</a>
  <a href="{{ route('admin.orders.index', ['status'=>'failed']) }}" class="chip {{ request('status')=='failed' ? 'on' : '' }}">{{ __('site.status.payment_full.failed') }}</a>
</div>

<div class="table-wrap">
  <table>
    <thead><tr>
      <th>{{ __('site.panel.admin.th_order') }}</th>
      <th>{{ __('site.panel.admin.th_customer') }}</th>
      <th>{{ __('site.panel.admin.th_amount') }}</th>
      <th>{{ __('site.panel.admin.th_reference') }}</th>
      <th>{{ __('site.panel.vendor.th_status') }}</th>
      <th></th>
    </tr></thead>
    <tbody>
      @foreach ($orders as $order)
        <tr>
          <td><a href="{{ route('admin.orders.show', $order) }}" style="color:inherit; text-decoration:none; font-weight:600;">{{ $order->order_number }}</a></td>
          <td>{{ $order->user->name }}</td>
          <td>{{ number_format($order->total_amount) }} {{ __('site.common.toman') }}</td>
          <td>
            @if ($order->payment_ref_id || $order->payment_receipt_path)
              <span class="status-pill new">{{ __('site.panel.admin.receipt_submitted') }}</span>
            @else
              <span style="color:var(--ink-soft); font-size:12px;">—</span>
            @endif
          </td>
          <td><span class="status-pill {{ $order->payment_status == 'paid' ? 'done' : ($order->payment_status == 'failed' ? 'cancelled' : 'pending') }}">{{ __('site.status.payment_full.'.$order->payment_status) }}</span></td>
          <td style="white-space:nowrap;">
            @if ($order->payment_status === 'pending')
              <form action="{{ route('admin.orders.confirm-payment', $order) }}" method="POST" style="display:inline;">@csrf<button class="row-actions" style="background:none;border:none;cursor:pointer;">{{ __('site.panel.admin.confirm_payment') }}</button></form>
              ·
            @endif
            <a href="{{ route('admin.orders.show', $order) }}" class="row-actions">{{ __('site.panel.view_details') }}</a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div class="pagination">{{ $orders->links() }}</div>
@endsection
