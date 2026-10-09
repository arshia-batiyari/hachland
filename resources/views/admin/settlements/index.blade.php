@extends('layouts.panel')
@section('title', __('site.sidebar.settlements'))

@section('sidebar')
  @include('partials.admin-sidebar')
@endsection

@section('content')
<div class="topbar"><div><h1>{{ __('site.panel.admin.settlements_title') }}</h1><p>{{ __('site.panel.admin.records_count', ['count' => $settlements->total()]) }}</p></div></div>

<div class="panel" style="margin-bottom:24px;">
  <div class="panel-head"><h3>{{ __('site.panel.admin.new_batch_title') }}</h3></div>
  <form action="{{ route('admin.settlements.generate') }}" method="POST" style="display:flex; gap:14px; align-items:flex-end; flex-wrap:wrap;">
    @csrf
    <div class="field-group" style="margin-bottom:0;"><label>{{ __('site.panel.admin.from_date') }}</label><input type="date" name="period_start" class="field" required></div>
    <div class="field-group" style="margin-bottom:0;"><label>{{ __('site.panel.admin.to_date') }}</label><input type="date" name="period_end" class="field" required></div>
    <button type="submit" class="btn btn-primary">{{ __('site.panel.admin.generate_for_all') }}</button>
  </form>
</div>

<div class="subcats">
  <a href="{{ route('admin.settlements.index') }}" class="chip {{ !request('status') ? 'on' : '' }}">{{ __('site.panel.all') }}</a>
  <a href="{{ route('admin.settlements.index', ['status'=>'pending']) }}" class="chip {{ request('status')=='pending' ? 'on' : '' }}">{{ __('site.status.settlement.pending') }}</a>
  <a href="{{ route('admin.settlements.index', ['status'=>'paid']) }}" class="chip {{ request('status')=='paid' ? 'on' : '' }}">{{ __('site.status.settlement.paid') }}</a>
</div>

<div class="table-wrap">
  <table>
    <thead><tr>
      <th>{{ __('site.panel.admin.th_booth') }}</th>
      <th>{{ __('site.panel.admin.th_iban') }}</th>
      <th>{{ __('site.panel.admin.th_period') }}</th>
      <th>{{ __('site.panel.admin.th_total_sales') }}</th>
      <th>{{ __('site.panel.admin.th_payable') }}</th>
      <th>{{ __('site.panel.vendor.th_status') }}</th>
      <th></th>
    </tr></thead>
    <tbody>
      @foreach ($settlements as $s)
        <tr>
          <td><div class="cell-booth"><div class="cell-thumb">{{ mb_substr($s->booth->localized_name, 0, 2) }}</div>{{ $s->booth->localized_name }}</div></td>
          <td>
            @if ($s->booth->bank_iban)
              <b style="font-family:monospace; font-size:12px;">{{ $s->booth->bank_iban }}</b><br>
              <span style="font-size:11.5px; color:var(--ink-soft);">{{ $s->booth->bank_account_holder }} — {{ $s->booth->bank_name }}</span>
            @else
              <span class="status-pill cancelled">{{ __('site.panel.admin.no_iban') }}</span>
            @endif
          </td>
          <td>{{ jdate($s->period_start) }} — {{ jdate($s->period_end) }}</td>
          <td>{{ number_format($s->total_sales) }} {{ __('site.common.toman') }}</td>
          <td><b>{{ number_format($s->payable_amount) }} {{ __('site.common.toman') }}</b></td>
          <td><span class="status-pill {{ $s->status }}">{{ $s->status == 'paid' ? __('site.status.settlement.paid') : __('site.status.settlement.pending') }}</span></td>
          <td>
            @if ($s->status === 'pending')
              <form action="{{ route('admin.settlements.pay', $s) }}" method="POST" class="js-confirm-payment-ref">
                @csrf
                <input type="hidden" name="payment_reference" value="">
                <button type="submit" class="pay-btn">{{ __('site.panel.confirm_deposit') }}</button>
              </form>
            @else
              <span class="row-actions">{{ $s->payment_reference }}</span>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div class="pagination">{{ $settlements->links() }}</div>

@endsection
