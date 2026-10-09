@extends('layouts.panel')
@section('title', __('site.sidebar.settlement'))

@section('sidebar')
  @include('partials.vendor-sidebar')
@endsection

@section('content')
<div class="topbar"><div><h1>{{ __('site.panel.vendor.settlements_title') }}</h1><p>{{ __('site.panel.vendor.settlements_sub') }}</p></div></div>

<div class="panel" style="margin-bottom:24px; max-width:340px;">
  <div class="panel-head"><h3>{{ __('site.panel.vendor.unsettled_balance') }}</h3></div>
  <div style="font-size:28px; font-weight:800; color:var(--primary-dark);">{{ number_format($unsettledAmount) }} {{ __('site.common.toman') }}</div>
  <p style="font-size:12px; color:var(--ink-soft); margin-top:8px;">{{ __('site.panel.vendor.unsettled_note') }}</p>
</div>

@if ($settlements->isEmpty())
  <div class="empty-state"><div class="icon">💳</div>{{ __('site.panel.vendor.no_settlements_yet') }}</div>
@else
  <div class="table-wrap">
    <table>
      <thead><tr>
        <th>{{ __('site.panel.vendor.th_period') }}</th>
        <th>{{ __('site.panel.vendor.th_total_sales') }}</th>
        <th>{{ __('site.panel.vendor.th_commission') }}</th>
        <th>{{ __('site.panel.vendor.th_payable') }}</th>
        <th>{{ __('site.panel.vendor.th_status') }}</th>
      </tr></thead>
      <tbody>
        @foreach ($settlements as $s)
          <tr>
            <td>{{ __('site.panel.vendor.period_to', ['start' => jdate($s->period_start), 'end' => jdate($s->period_end)]) }}</td>
            <td>{{ number_format($s->total_sales) }} {{ __('site.common.toman') }}</td>
            <td>{{ number_format($s->commission_amount) }} {{ __('site.common.toman') }}</td>
            <td><b>{{ number_format($s->payable_amount) }} {{ __('site.common.toman') }}</b></td>
            <td><span class="status-pill {{ $s->status }}">{{ $s->status == 'paid' ? __('site.status.settlement.paid') : __('site.status.settlement.pending') }}</span></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="pagination">{{ $settlements->links() }}</div>
@endif
@endsection
