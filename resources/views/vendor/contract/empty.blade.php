@extends('layouts.panel')
@section('title', __('wallet.contract_title'))

@section('sidebar')
  @include('partials.vendor-sidebar')
@endsection

@section('content')
<div class="topbar"><div><h1>{{ __('wallet.contract_title') }}</h1></div></div>

<div class="empty-state">
  <div class="icon">📄</div>
  <p><b style="display:block; font-size:15px; color:var(--ink); margin-bottom:8px;">{{ __('wallet.contract_empty_title') }}</b>{{ __('wallet.contract_empty_body') }}</p>
</div>

@if ($history->isNotEmpty())
  <div class="panel" style="margin-top:26px;">
    <div class="panel-head"><h3>{{ __('wallet.contract_history') }}</h3></div>
    <div class="table-wrap" style="border:none;">
      <table>
        <thead><tr><th>{{ __('wallet.th_contract_number') }}</th><th>{{ __('wallet.th_share') }}</th><th>{{ __('wallet.th_issued_at') }}</th><th>{{ __('wallet.th_status') }}</th></tr></thead>
        <tbody>
          @foreach ($history as $c)
            <tr>
              <td>{{ $c->contract_number }}</td>
              <td>{{ $c->share_percent_label }}٪</td>
              <td>{{ $c->issued_at ? jdate($c->issued_at, 'j F Y') : '—' }}</td>
              <td><span class="status-pill {{ $c->status === 'void' ? 'cancelled' : 'pending' }}">{{ $c->status_label }}</span></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endif
@endsection
