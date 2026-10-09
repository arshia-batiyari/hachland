@extends('layouts.panel')
@section('title', __('wallet.wallet_title'))

@section('sidebar')
  @include('partials.vendor-sidebar')
@endsection

@section('content')
<div class="topbar">
  <div><h1>{{ __('wallet.wallet_title') }}</h1><p>{{ __('site.sidebar.wallet') }}</p></div>
  @if ($contract)
    <a href="{{ route('vendor.contract.show', $contract) }}" class="btn btn-ghost btn-sm">{{ __('wallet.contract_title') }} — {{ __('wallet.contract_share') }} {{ $contract->share_percent_label }}٪</a>
  @endif
</div>

@include('partials.wallet-ledger', [
  'summary' => $summary,
  'transactions' => $transactions,
  'type' => $type,
  'running' => $running,
  'walletRouteName' => 'vendor.wallet.index',
  'walletRouteParams' => [],
  'showCreator' => false,
])
@endsection
