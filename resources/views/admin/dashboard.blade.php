@extends('layouts.panel')
@section('title', __('site.panel.admin.dashboard_title'))

@section('sidebar')
  @include('partials.admin-sidebar')
@endsection

@section('content')
<div class="topbar">
  <div><h1>{{ __('site.panel.admin.dashboard_heading') }}</h1><p>{{ __('site.panel.admin.dashboard_sub') }}</p></div>
  <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">{{ __('site.panel.admin.new_category') }}</a>
</div>

<div class="kpi-row">
  <div class="kpi"><div class="kpi-icon">🏬</div><b>{{ $stats['active_booths'] }}</b><span>{{ __('site.panel.admin.kpi_active_booths') }}</span></div>
  <div class="kpi"><div class="kpi-icon">📦</div><b>{{ $stats['products'] }}</b><span>{{ __('site.panel.admin.kpi_active_products') }}</span></div>
  <div class="kpi"><div class="kpi-icon">🧾</div><b>{{ $stats['monthly_orders'] }}</b><span>{{ __('site.panel.admin.kpi_monthly_orders') }}</span></div>
  <div class="kpi"><div class="kpi-icon">💳</div><b>{{ number_format($stats['monthly_commission']) }}</b><span>{{ __('site.panel.admin.kpi_monthly_commission') }}</span></div>
</div>

<div class="grid-2">
  <div class="panel">
    <div class="panel-head"><h3>{{ __('site.panel.admin.pending_booths') }}</h3><a href="{{ route('admin.booths.index', ['status'=>'pending']) }}" class="link">{{ __('site.panel.admin.view_all') }}</a></div>

    @forelse ($pendingBooths as $booth)
      <div class="approve-item">
        <div class="approve-avatar">{{ mb_substr($booth->localized_name, 0, 2) }}</div>
        <div class="approve-info">
          <b>{{ $booth->localized_name }}</b>
          <span>{{ $booth->categories->pluck('localized_name')->implode(' · ') }} · {{ __('site.panel.admin.registered_ago', ['time' => $booth->created_at->locale(app()->getLocale())->diffForHumans()]) }}</span>
        </div>
        <div class="approve-actions">
          <form action="{{ route('admin.booths.approve', $booth) }}" method="POST">
            @csrf
            <button type="submit" class="icon-btn approve">✓</button>
          </form>
          <form action="{{ route('admin.booths.reject', $booth) }}" method="POST" onsubmit="return confirm(@json(__('site.panel.confirm_reject')))">
            @csrf
            <input type="hidden" name="rejection_reason" value="{{ __('site.panel.rejection_reason_default') }}">
            <button type="submit" class="icon-btn reject">✕</button>
          </form>
        </div>
      </div>
    @empty
      <p style="font-size:13px; color:var(--ink-soft);">{{ __('site.panel.admin.no_pending_booths') }}</p>
    @endforelse
  </div>

  <div class="panel">
    <div class="panel-head"><h3>{{ __('site.panel.admin.category_tree') }}</h3><a href="{{ route('admin.categories.index') }}" class="link">{{ __('site.panel.admin.full_management') }}</a></div>
    @foreach ($categoryTree as $cat)
      <div class="tree-item"><span>{{ $cat->icon ?? '📦' }} {{ $cat->localized_name }}</span><span class="tree-count">{{ $cat->booths_count }} {{ __('site.common.booth') }}</span></div>
      @if ($cat->children->isNotEmpty())
        <div class="tree-sub">
          @foreach ($cat->children as $sub)
            <div class="tree-item"><span>— {{ $sub->localized_name }}</span><span class="tree-count">{{ $sub->booths()->count() }}</span></div>
          @endforeach
        </div>
      @endif
    @endforeach
  </div>
</div>

<div class="panel-head" style="margin-bottom:16px;"><h3>{{ __('site.panel.admin.settlement_queue') }}</h3><a href="{{ route('admin.settlements.index') }}" class="link">{{ __('site.panel.admin.view_all') }}</a></div>
@if ($pendingSettlements->isEmpty())
  <div class="empty-state"><div class="icon">💳</div>{{ __('site.panel.admin.no_pending_settlements') }}</div>
@else
  <div class="table-wrap">
    <table>
      <thead><tr>
        <th>{{ __('site.panel.admin.th_booth') }}</th>
        <th>{{ __('site.panel.admin.th_period_sales') }}</th>
        <th>{{ __('site.panel.admin.th_payable') }}</th>
        <th>{{ __('site.panel.pending') }}</th>
        <th></th>
      </tr></thead>
      <tbody>
        @foreach ($pendingSettlements as $s)
          <tr>
            <td><div class="cell-booth"><div class="cell-thumb">{{ mb_substr($s->booth->localized_name, 0, 2) }}</div>{{ $s->booth->localized_name }}</div></td>
            <td>{{ number_format($s->total_sales) }} {{ __('site.common.toman') }}</td>
            <td><b>{{ number_format($s->payable_amount) }} {{ __('site.common.toman') }}</b></td>
            <td><span class="status-pill pending">{{ __('site.panel.pending') }}</span></td>
            <td>
              <form action="{{ route('admin.settlements.pay', $s) }}" method="POST" class="js-confirm-payment-ref">
                @csrf
                <input type="hidden" name="payment_reference" value="">
                <button type="submit" class="pay-btn">{{ __('site.panel.confirm_deposit') }}</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endif

@endsection
