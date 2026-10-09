@extends('layouts.panel')
@section('title', __('site.panel.admin.booth_details_title'))

@section('sidebar')
  @include('partials.admin-sidebar')
@endsection

@section('content')
<div class="topbar">
  <div>
    <a href="{{ route('admin.booths.index') }}" class="row-actions" style="display:inline-block; margin-bottom:8px;">→ {{ __('site.panel.back_to_list') }}</a>
    <h1>{{ $booth->localized_name }}</h1>
    <p>{{ $booth->categories->pluck('localized_name')->implode('، ') }}</p>
  </div>
  <div style="display:flex; align-items:center; gap:10px;">
    <span class="status-pill {{ $booth->status == 'approved' ? 'done' : ($booth->status == 'rejected' ? 'cancelled' : 'pending') }}">{{ __('site.status.booth.'.$booth->status) }}</span>

    {{-- همون سلکت سریع تغییر وضعیت، این‌جا هم در دسترسه تا بعد از بررسی
         کامل مدارک، ادمین بلافاصله همین‌جا تصمیمشو ثبت کنه --}}
    <form action="{{ route('admin.booths.status', $booth) }}" method="POST" class="js-status-form" data-current="{{ $booth->status }}" data-prompt="{{ __('site.panel.prompt_rejection_reason') }}" data-default-reason="{{ __('site.panel.rejection_reason_default') }}" data-confirm-label="{{ __('site.panel.change_status') }}">
      @csrf
      <input type="hidden" name="rejection_reason" class="js-rejection-reason">
      <select name="status" class="field js-status-select" style="padding:8px 12px;" onchange="hachlandBoothStatusChange(this)">
        <option value="pending" {{ $booth->status=='pending'?'selected':'' }}>{{ __('site.status.booth.pending') }}</option>
        <option value="approved" {{ $booth->status=='approved'?'selected':'' }}>{{ __('site.status.booth.approved') }}</option>
        <option value="rejected" {{ $booth->status=='rejected'?'selected':'' }}>{{ __('site.status.booth.rejected') }}</option>
        <option value="suspended" {{ $booth->status=='suspended'?'selected':'' }}>{{ __('site.status.booth.suspended') }}</option>
      </select>
    </form>
  </div>
</div>

@if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

@if ($booth->status === 'rejected' && $booth->rejection_reason)
  <div class="alert alert-error" style="margin-bottom:20px;">{{ __('site.panel.rejection_reason_label') }}: {{ $booth->rejection_reason }}</div>
@endif

<div class="grid-2">
  {{-- ---------- BOOTH & OWNER INFO ---------- --}}
  <div class="panel">
    <div class="panel-head"><h3>{{ __('site.panel.admin.booth_details_title') }}</h3></div>

    <div style="display:flex; gap:14px; align-items:center; margin-bottom:18px;">
      <div style="width:60px; height:60px; border-radius:12px; overflow:hidden; border:1px solid var(--border); flex-shrink:0; background:var(--primary-tint); display:flex; align-items:center; justify-content:center;">
        @if ($booth->logo_url)
          <img src="{{ $booth->logo_url }}" style="width:100%; height:100%; object-fit:cover;">
        @else
          <span style="font-weight:700; color:var(--primary);">{{ mb_substr($booth->localized_name, 0, 2) }}</span>
        @endif
      </div>
      <div>
        <b style="font-size:15px; display:block;">{{ $booth->localized_name }}</b>
        <span style="font-size:12.5px; color:var(--ink-soft);">{{ __('site.panel.admin.th_registered_at') }}: {{ jdate($booth->created_at, 'j F Y') }}</span>
      </div>
    </div>

    <div class="stat-mini"><span>{{ __('site.panel.admin.th_owner') }}</span><b>{{ $booth->owner->name }}</b></div>
    <div class="stat-mini"><span>{{ __('site.panel.admin.th_phone') }}</span><b>{{ $booth->phone ?: $booth->owner->phone }}</b></div>
    <div class="stat-mini"><span>{{ __('site.panel.admin.th_location') }}</span><b>{{ $booth->city }}{{ $booth->city && $booth->province ? '، ' : '' }}{{ $booth->province }}</b></div>
    <div class="stat-mini"><span>{{ __('site.panel.admin.th_category') }}</span><b>{{ $booth->categories->pluck('localized_name')->implode('، ') }}</b></div>
    <div class="stat-mini"><span>{{ __('site.panel.admin.th_commission') }}</span><b>{{ $booth->commission_percent }}٪</b></div>
    <div class="stat-mini"><span>{{ __('site.panel.admin.th_products_count') }}</span><b>{{ $booth->products->count() }}</b></div>

    @if ($booth->description)
      <p style="font-size:13.5px; color:var(--ink-soft); line-height:1.9; margin-top:16px;">
        <b style="display:block; color:var(--ink); margin-bottom:6px; font-size:13px;">{{ __('site.panel.admin.th_description') }}</b>
        {{ $booth->description[app()->getLocale()] ?? $booth->description['fa'] ?? '' }}
      </p>
    @endif
  </div>

  {{-- ---------- BANK & IDENTITY (برای بررسی قبل از تأیید) ---------- --}}
  <div class="panel">
    <div class="panel-head"><h3>{{ __('site.panel.admin.identity_title') }}</h3></div>

    <div class="stat-mini"><span>{{ __('site.auth.national_id') }}</span><b>{{ $booth->national_id ?: '—' }}</b></div>

    <div style="margin-top:14px; margin-bottom:22px;">
      @if ($booth->verification_document)
        <a href="{{ route('admin.booths.document', $booth) }}" target="_blank" class="btn btn-ghost" style="display:inline-flex; align-items:center; gap:6px;">
          <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          {{ __('site.panel.view_document') }}
        </a>
      @else
        <span style="font-size:13px; color:var(--ink-soft);">{{ __('site.panel.no_document') }}</span>
      @endif
    </div>

    <div class="panel-head" style="border-top:1px solid var(--border); padding-top:16px;"><h3 style="font-size:14px;">{{ __('site.panel.admin.bank_info_title') }}</h3></div>
    <div class="stat-mini"><span>{{ __('site.auth.bank_account_holder') }}</span><b>{{ $booth->bank_account_holder ?: '—' }}</b></div>
    <div class="stat-mini"><span>{{ __('site.auth.bank_name') }}</span><b>{{ $booth->bank_name ?: '—' }}</b></div>
    <div class="stat-mini"><span>{{ __('site.auth.bank_iban') }}</span><b style="direction:ltr; display:inline-block;">{{ $booth->bank_iban ?: '—' }}</b></div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function hachlandBoothStatusChange(select) {
  const form = select.closest('form');
  const current = form.dataset.current;
  if (select.value === current) return;

  if (select.value === 'rejected') {
    const reason = prompt(form.dataset.prompt, form.dataset.defaultReason);
    if (!reason) { select.value = current; return; }
    form.querySelector('.js-rejection-reason').value = reason;
    form.submit();
    return;
  }

  const label = select.options[select.selectedIndex].text;
  if (!confirm(form.dataset.confirmLabel + ': ' + label + '؟')) {
    select.value = current;
    return;
  }
  form.submit();
}
</script>
@endpush