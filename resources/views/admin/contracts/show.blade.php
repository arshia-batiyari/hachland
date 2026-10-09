@extends('layouts.panel')
@section('title', __('wallet.contract_title'))

@section('sidebar')
  @include('partials.admin-sidebar')
@endsection

@section('content')
<div class="topbar">
  <div>
    <a href="{{ route('admin.contracts.index') }}" class="row-actions" style="display:inline-block; margin-bottom:8px;">→ {{ __('site.panel.back_to_list') }}</a>
    <h1>{{ $contract->contract_number }}</h1>
    <p>{{ $contract->booth->localized_name }}</p>
  </div>
  <div style="display:flex; align-items:center; gap:10px;">
    <span class="status-pill {{ $contract->status === 'signed' ? 'done' : ($contract->status === 'void' ? 'cancelled' : 'pending') }}">{{ $contract->status_label }}</span>
    <a href="{{ route('admin.contracts.print', $contract) }}" target="_blank" class="btn btn-ghost btn-sm">{{ __('wallet.print_view') }}</a>
  </div>
</div>

@if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<div class="grid-2">
  <div class="panel">
    <div class="panel-head"><h3>{{ __('wallet.contract_title') }}</h3></div>
    <div class="stat-mini"><span>{{ __('site.panel.admin.th_owner') }}</span><b>{{ $contract->booth->owner->name ?? '—' }}</b></div>
    <div class="stat-mini"><span>{{ __('wallet.th_share') }}</span><b>{{ $contract->share_percent_label }}٪</b></div>
    <div class="stat-mini"><span>{{ __('wallet.contract_commission') }}</span><b>{{ $contract->commission_percent_label }}٪</b></div>
    <div class="stat-mini"><span>{{ __('wallet.contract_cycle') }}</span><b>{{ __('wallet.contract_cycle_days', ['count' => $contract->settlement_cycle_days]) }}</b></div>
    <div class="stat-mini"><span>{{ __('wallet.contract_term') }}</span><b>{{ __('wallet.contract_term_months', ['count' => $contract->term_months]) }}</b></div>
    <div class="stat-mini"><span>{{ __('wallet.contract_issued_at') }}</span><b>{{ $contract->issued_at ? jdate($contract->issued_at, 'j F Y') : '—' }}</b></div>
    <div class="stat-mini"><span>{{ __('site.panel.admin.th_registered_at') }}</span><b>{{ $contract->issuer->name ?? '—' }}</b></div>
  </div>

  <div class="panel">
    <div class="panel-head"><h3>{{ __('wallet.sign_title') }}</h3></div>
    @if ($contract->isSigned())
      @if ($contract->signature_data_uri)
        <div style="background:var(--bg); border:1px solid var(--border); border-radius:10px; padding:12px 16px; margin-bottom:14px;"><img src="{{ $contract->signature_data_uri }}" style="max-height:70px;"></div>
      @endif
      <div class="stat-mini"><span>{{ __('wallet.signed_by') }}</span><b>{{ $contract->signer_name }}</b></div>
      <div class="stat-mini"><span>{{ __('wallet.signed_at_label') }}</span><b>{{ jdate($contract->signed_at, 'j F Y - H:i') }}</b></div>
      <div class="stat-mini"><span>{{ __('wallet.signer_ip_label') }}</span><b style="direction:ltr; display:inline-block;">{{ $contract->signer_ip }}</b></div>
      <div class="stat-mini"><span>{{ __('wallet.verification_code') }}</span><b style="direction:ltr; display:inline-block;">{{ $contract->verification_code }}</b></div>
    @else
      <p style="font-size:13px; color:var(--ink-soft);">{{ __('wallet.contract_status_'.$contract->status) }}</p>
    @endif

    @if (in_array($contract->status, ['awaiting_signature', 'signed']))
      <form action="{{ route('admin.contracts.void', $contract) }}" method="POST" style="margin-top:18px; border-top:1px solid var(--border); padding-top:16px;" onsubmit="return fillVoidReason(this)">
        @csrf
        <input type="hidden" name="void_reason" class="js-void-reason">
        <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--red); border-color:var(--red);">{{ __('wallet.void_contract') }}</button>
      </form>
    @endif
  </div>
</div>

<div class="panel" style="margin-top:20px;">
  <div class="panel-head"><h3>{{ __('wallet.contract_title') }} — {{ __('wallet.contract_term') }}</h3></div>
  <div style="max-height:420px; overflow-y:auto; padding-left:6px;">
    @foreach ($contract->terms as $article)
      <div style="margin-bottom:18px;">
        <h4 style="font-size:13.5px; font-weight:800; margin:0 0 8px;">{{ $article['no'] }}. {{ $article['title'] }}</h4>
        <ol style="margin:0; padding-right:20px;">
          @foreach ($article['clauses'] as $clause)
            <li style="font-size:12.5px; color:var(--ink); margin-bottom:6px; line-height:1.85;">{{ $clause }}</li>
          @endforeach
        </ol>
      </div>
    @endforeach
  </div>
</div>
@endsection

@push('scripts')
<script>
function fillVoidReason(form) {
  var reason = prompt({{ Js::from(__('wallet.void_reason_label') . ':') }});
  if (!reason) return false;
  if (!confirm({{ Js::from(__('wallet.void_confirm')) }})) return false;
  form.querySelector('.js-void-reason').value = reason;
  return true;
}
</script>
@endpush
