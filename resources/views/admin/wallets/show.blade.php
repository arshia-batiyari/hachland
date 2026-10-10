@extends('layouts.panel')
@section('title', __('wallet.wallet_title'))

@section('sidebar')
  @include('partials.admin-sidebar')
@endsection

@section('content')
<div class="topbar">
  <div><h1>{{ $booth->localized_name }}</h1><p>{{ __('wallet.wallet_title') }}</p></div>
  <div style="display:flex; gap:10px;">
    <a href="{{ route('admin.booths.show', $booth) }}" class="btn btn-ghost btn-sm">{{ __('site.panel.view_details') }}</a>
    @if ($contract)
      <a href="{{ route('admin.contracts.show', $contract) }}" class="btn btn-ghost btn-sm">{{ __('wallet.contract_title') }} — {{ $contract->share_percent_label }}٪</a>
    @endif
  </div>
</div>

@if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if ($errors->any())<div class="alert alert-error">{{ $errors->first() }}</div>@endif

@include('partials.wallet-ledger', [
  'summary' => $summary,
  'transactions' => $transactions,
  'type' => $type,
  'running' => $running,
  'walletRouteName' => 'admin.wallets.show',
  'walletRouteParams' => ['booth' => $booth],
  'showCreator' => true,
])

<div class="panel" style="margin-top:26px; max-width:520px;">
  <div class="panel-head"><h3>{{ __('wallet.adjust_title') }}</h3></div>
  <form action="{{ route('admin.wallets.adjust', $booth) }}" method="POST">
    @csrf
    <div class="field-group">
      <label>{{ __('wallet.adjust_direction') }}</label>
      <select name="direction" class="field" required>
        <option value="credit">{{ __('wallet.adjust_credit') }}</option>
        <option value="debit">{{ __('wallet.adjust_debit') }}</option>
      </select>
    </div>
    <div class="field-group">
      <label>{{ __('wallet.adjust_amount') }}</label>
      <input type="number" name="amount" class="field" required min="1">
    </div>
    <div class="field-group">
      <label>{{ __('wallet.adjust_description') }}</label>
      <textarea name="description" class="field" rows="2" required minlength="3"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">{{ __('wallet.adjust_submit') }}</button>
  </form>
</div>
@endsection
