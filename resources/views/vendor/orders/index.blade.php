@extends('layouts.panel')
@section('title', __('site.sidebar.orders'))

@section('sidebar')
  @include('partials.vendor-sidebar')
@endsection

@section('content')
<div class="topbar"><div><h1>{{ __('site.panel.vendor.orders_title') }}</h1><p>{{ __('site.panel.vendor.orders_sub') }}</p></div></div>

@if ($shipments->isEmpty())
  <div class="empty-state"><div class="icon">🧾</div>{{ __('site.panel.vendor.no_orders_for_booth') }}</div>
@else
  <div class="table-wrap">
    <table>
      <thead><tr>
        <th>{{ __('site.panel.vendor.th_order') }}</th>
        <th>{{ __('site.panel.vendor.th_customer') }}</th>
        <th>{{ __('site.panel.customer_orders.th_amount') }}</th>
        <th>{{ __('site.panel.customer_orders.th_date') }}</th>
        <th>{{ __('site.panel.vendor.th_status') }}</th>
        <th></th>
      </tr></thead>
      <tbody>
        @foreach ($shipments as $shipment)
          <tr>
            <td><b>{{ $shipment->order->order_number }}</b></td>
            <td>{{ $shipment->order->user->name }}</td>
            <td>{{ number_format($shipment->items->sum('line_total')) }} {{ __('site.common.toman') }}</td>
            <td>{{ jdate($shipment->created_at) }}</td>
            <td><span class="status-pill {{ $shipment->status }}">{{ __('site.status.shipment.'.$shipment->status) }}</span></td>
            <td><a href="{{ route('vendor.orders.show', $shipment) }}" class="row-actions">{{ __('site.panel.view') }}</a></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="pagination">{{ $shipments->links() }}</div>
@endif
@endsection
