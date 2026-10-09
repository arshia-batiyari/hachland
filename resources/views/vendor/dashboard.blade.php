@extends('layouts.panel')
@section('title', __('site.panel.vendor.dashboard_title'))

@section('sidebar')
  @include('partials.vendor-sidebar')
@endsection

@section('content')
<div class="topbar">
  <div>
    <h1>{{ __('site.panel.vendor.greeting', ['name' => $booth->localized_name]) }}</h1>
    <p>{{ __('site.panel.vendor.greeting_sub') }}</p>
  </div>
  <div style="display:flex; gap:12px;">
    <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">{{ __('site.panel.vendor.add_product') }}</a>
  </div>
</div>

<div class="kpi-row">
  <div class="kpi"><div class="kpi-icon">🧾</div><b>{{ $monthlyOrders }}</b><span>{{ __('site.panel.vendor.kpi_monthly_orders') }}</span></div>
  <div class="kpi"><div class="kpi-icon">💰</div><b>{{ number_format($monthlySales) }}</b><span>{{ __('site.panel.vendor.kpi_monthly_sales') }}</span></div>
  <div class="kpi"><div class="kpi-icon">📦</div><b>{{ $activeProducts }}</b><span>{{ __('site.panel.vendor.kpi_active_products') }}</span></div>
  <div class="kpi"><div class="kpi-icon">💳</div><b>{{ number_format($unsettledAmount) }}</b><span>{{ __('site.panel.vendor.kpi_unsettled') }}</span></div>
</div>

<div class="grid-2">
  <div class="panel">
    <div class="panel-head"><h3>{{ __('site.panel.vendor.recent_orders') }}</h3><a href="{{ route('vendor.orders.index') }}" class="link">{{ __('site.panel.vendor.view_all') }}</a></div>
    @forelse ($recentOrderItems as $item)
      <div style="display:flex; justify-content:space-between; padding:11px 0; border-bottom:1px solid var(--border); font-size:13px;">
        <span>{{ $item->product_name[app()->getLocale()] ?? $item->product_name['fa'] ?? '' }}</span>
        <span style="color:var(--ink-soft);">{{ $item->order->user->name }}</span>
        <b>{{ number_format($item->line_total) }} {{ __('site.common.toman') }}</b>
      </div>
    @empty
      <p style="font-size:13px; color:var(--ink-soft);">{{ __('site.panel.vendor.no_orders_yet') }}</p>
    @endforelse
  </div>

  <div class="panel">
    <div class="panel-head"><h3>{{ __('site.panel.vendor.next_settlement') }}</h3><a href="{{ route('vendor.settlements.index') }}" class="link">{{ __('site.panel.vendor.history') }}</a></div>
    @if ($nextSettlement)
      <div style="font-size:26px; font-weight:800; color:var(--primary-dark); margin-bottom:4px;">{{ number_format($nextSettlement->payable_amount) }} {{ __('site.common.toman') }}</div>
      <div class="stat-mini"><span>{{ __('site.panel.vendor.total_period_sales') }}</span><b>{{ number_format($nextSettlement->total_sales) }}</b></div>
      <div class="stat-mini"><span>{{ __('site.panel.vendor.meydan_commission') }}</span><b style="color:var(--red);">{{ number_format($nextSettlement->commission_amount) }}-</b></div>
      <div class="stat-mini"><span>{{ __('site.panel.vendor.th_status') }}</span><span class="status-pill pending">{{ __('site.panel.vendor.awaiting_deposit') }}</span></div>
    @else
      <p style="font-size:13px; color:var(--ink-soft);">{{ __('site.panel.vendor.no_pending_balance') }}</p>
    @endif
  </div>
</div>
@endsection
