@extends('layouts.panel')
@section('title', __('wallet.admin_contracts_title'))

@section('sidebar')
  @include('partials.admin-sidebar')
@endsection

@section('content')
<div class="topbar"><div><h1>{{ __('wallet.admin_contracts_title') }}</h1><p>{{ __('wallet.admin_contracts_sub', ['count' => $contracts->total()]) }}</p></div></div>

<div class="subcats">
  <a href="{{ route('admin.contracts.index') }}" class="chip {{ !$status ? 'on' : '' }}">{{ __('site.panel.all') }}</a>
  <a href="{{ route('admin.contracts.index', ['status'=>'awaiting_signature']) }}" class="chip {{ $status=='awaiting_signature' ? 'on' : '' }}">{{ __('wallet.contract_status_awaiting_signature') }} @if($counts->get('awaiting_signature'))<span class="side-count" style="position:static; margin-right:4px;">{{ $counts->get('awaiting_signature') }}</span>@endif</a>
  <a href="{{ route('admin.contracts.index', ['status'=>'signed']) }}" class="chip {{ $status=='signed' ? 'on' : '' }}">{{ __('wallet.contract_status_signed') }}</a>
  <a href="{{ route('admin.contracts.index', ['status'=>'superseded']) }}" class="chip {{ $status=='superseded' ? 'on' : '' }}">{{ __('wallet.contract_status_superseded') }}</a>
  <a href="{{ route('admin.contracts.index', ['status'=>'void']) }}" class="chip {{ $status=='void' ? 'on' : '' }}">{{ __('wallet.contract_status_void') }}</a>
</div>

@if ($contracts->isEmpty())
  <div class="empty-state"><div class="icon">📄</div><p>{{ __('wallet.no_contract_yet') }}</p></div>
@else
  <div class="table-wrap">
    <table>
      <thead><tr>
        <th>{{ __('wallet.th_contract_number') }}</th>
        <th>{{ __('site.panel.admin.th_booth') }}</th>
        <th>{{ __('wallet.th_share') }}</th>
        <th>{{ __('wallet.th_issued_at') }}</th>
        <th>{{ __('wallet.th_status') }}</th>
        <th></th>
      </tr></thead>
      <tbody>
        @foreach ($contracts as $contract)
          <tr>
            <td>{{ $contract->contract_number }}</td>
            <td>{{ $contract->booth->localized_name }}</td>
            <td>{{ $contract->share_percent_label }}٪</td>
            <td>{{ $contract->issued_at ? jdate($contract->issued_at, 'j F Y') : '—' }}</td>
            <td><span class="status-pill {{ $contract->status === 'signed' ? 'done' : ($contract->status === 'void' ? 'cancelled' : 'pending') }}">{{ $contract->status_label }}</span></td>
            <td><a href="{{ route('admin.contracts.show', $contract) }}" class="row-actions">{{ __('site.panel.view_document') }}</a></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div style="margin-top:20px;">{{ $contracts->links() }}</div>
@endif
@endsection
