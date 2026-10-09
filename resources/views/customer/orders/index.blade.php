@extends('layouts.app')
@section('title', __('site.panel.customer_orders.title'))

@section('content')
<div class="wrap" style="padding-bottom:80px;">
  <h1 style="font-size:22px; font-weight:800; color:var(--primary-dark); margin:24px 0 24px;">{{ __('site.panel.customer_orders.title') }}</h1>

  @if ($orders->isEmpty())
    <div class="empty-state"><div class="icon">📦</div>{{ __('site.panel.customer_orders.empty') }}</div>
  @else
    <div class="table-wrap">
      <table>
        <thead><tr>
          <th>{{ __('site.panel.customer_orders.th_order_number') }}</th>
          <th>{{ __('site.panel.customer_orders.th_date') }}</th>
          <th>{{ __('site.panel.customer_orders.th_amount') }}</th>
          <th>{{ __('site.panel.customer_orders.th_payment_status') }}</th>
          <th>{{ __('site.panel.customer_orders.th_order_status') }}</th>
          <th></th>
        </tr></thead>
        <tbody>
          @foreach ($orders as $order)
            <tr>
              <td><b>{{ $order->order_number }}</b></td>
              <td>{{ jdate($order->created_at) }}</td>
              <td>{{ number_format($order->total_amount) }} {{ __('site.common.toman') }}</td>
              <td><span class="status-pill {{ $order->payment_status }}">{{ __('site.status.payment.'.$order->payment_status) }}</span></td>
              <td><span class="status-pill {{ $order->status == 'delivered' ? 'delivered' : ($order->status == 'shipped' ? 'shipped' : 'pending') }}">{{ __('site.status.order.'.$order->status) }}</span></td>
              <td><a href="{{ route('customer.orders.show', $order) }}" class="row-actions">{{ __('site.panel.view_details') }}</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="pagination">{{ $orders->links() }}</div>
  @endif
</div>
@endsection
