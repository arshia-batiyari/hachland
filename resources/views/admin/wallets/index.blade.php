@extends('layouts.panel')
@section('title', __('wallet.admin_wallets_title'))

@section('sidebar')
  @include('partials.admin-sidebar')
@endsection

@section('content')
<div class="topbar"><div><h1>{{ __('wallet.admin_wallets_title') }}</h1><p>{{ __('wallet.admin_wallets_sub') }}</p></div></div>

<div class="kpi-row">
  <div class="kpi"><div class="kpi-icon">💰</div><b>{{ number_format($totals['gross']) }}</b><span>{{ __('wallet.platform_gross') }}</span></div>
  <div class="kpi"><div class="kpi-icon">🏛️</div><b>{{ number_format($totals['commission']) }}</b><span>{{ __('wallet.platform_commission') }}</span></div>
  <div class="kpi"><div class="kpi-icon">👛</div><b>{{ number_format($totals['owed']) }}</b><span>{{ __('wallet.platform_owed') }}</span></div>
  <div class="kpi"><div class="kpi-icon">✅</div><b>{{ number_format($totals['paid_out']) }}</b><span>{{ __('wallet.platform_paid_out') }}</span></div>
</div>

<div class="table-wrap">
  <table>
    <thead><tr>
      <th>{{ __('site.panel.admin.th_booth') }}</th>
      <th>{{ __('wallet.wallet_gross_sales') }}</th>
      <th>{{ __('wallet.wallet_commission_total') }}</th>
      <th>{{ __('wallet.wallet_paid_out') }}</th>
      <th>{{ __('wallet.th_booth_balance') }}</th>
      <th></th>
    </tr></thead>
    <tbody>
      @foreach ($booths as $booth)
        @php $r = $rows->get($booth->id); @endphp
        <tr>
          <td>
            <a href="{{ route('admin.wallets.show', $booth) }}" class="cell-booth" style="color:inherit; text-decoration:none;">
              <div class="cell-thumb">{{ mb_substr($booth->localized_name, 0, 2) }}</div>
              {{ $booth->localized_name }}
            </a>
          </td>
          <td>{{ number_format($r->gross ?? 0) }}</td>
          <td>{{ number_format($r->commission ?? 0) }}</td>
          <td>{{ number_format($r->paid_out ?? 0) }}</td>
          <td><b>{{ number_format($r->balance ?? 0) }}</b></td>
          <td><a href="{{ route('admin.wallets.show', $booth) }}" class="row-actions">{{ __('wallet.wallet_ledger_title') }}</a></td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div style="margin-top:20px;">{{ $booths->links() }}</div>
@endsection
