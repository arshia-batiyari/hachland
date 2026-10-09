@extends('layouts.panel')
@section('title', __('site.panel.admin.property_request_details_title'))

@section('sidebar')
  @include('partials.admin-sidebar')
@endsection

@section('content')
<div class="topbar">
  <div>
    <h1>{{ $propertyRequest->localized_title }}</h1>
    <p>{{ $propertyRequest->localized_property_type }} · {{ $propertyRequest->localized_deal_type }} @if($propertyRequest->city) · {{ $propertyRequest->city }}، {{ $propertyRequest->province }} @endif</p>
  </div>
  <div style="display:flex; align-items:center; gap:10px;">
    <span class="status-pill {{ $propertyRequest->status == 'approved' ? 'done' : ($propertyRequest->status == 'rejected' ? 'cancelled' : 'pending') }}">{{ __('site.status.property.'.$propertyRequest->status) }}</span>
    @if ($propertyRequest->status === 'pending')
      <form action="{{ route('admin.property-requests.approve', $propertyRequest) }}" method="POST">@csrf<button type="submit" class="btn btn-primary">{{ __('site.panel.approve') }}</button></form>
      <form action="{{ route('admin.property-requests.reject', $propertyRequest) }}" method="POST" onsubmit="return fillRejectionReason(this)">
        @csrf<input type="hidden" name="rejection_reason" value="">
        <button type="submit" class="btn btn-ghost" style="color:var(--red); border-color:var(--red);">{{ __('site.panel.reject') }}</button>
      </form>
    @endif
  </div>
</div>

@if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<div class="grid-2">
  <div class="panel">
    <div class="panel-head"><h3>{{ __('site.panel.admin.property_request_details_title') }}</h3></div>

    <div class="stat-mini"><span>{{ __('site.panel.admin.th_customer') }}</span><b>{{ $propertyRequest->name }}</b></div>
    <div class="stat-mini"><span>{{ __('site.auth.phone') }}</span><b>{{ $propertyRequest->phone }}</b></div>
    @if ($propertyRequest->area_range_label)
      <div class="stat-mini"><span>{{ __('site.properties.area_suffix') }}</span><b>{{ $propertyRequest->area_range_label }}</b></div>
    @endif
    @if ($propertyRequest->budget)
      <div class="stat-mini"><span>{{ __('site.property_requests.budget_label') }}</span><b>{{ number_format($propertyRequest->budget) }} {{ __('site.common.toman') }}</b></div>
    @endif

    @if ($propertyRequest->localized_description)
      <p style="font-size:13.5px; color:var(--ink-soft); line-height:1.9; margin-top:16px;">{{ $propertyRequest->localized_description }}</p>
    @endif

    @if ($propertyRequest->status === 'rejected' && $propertyRequest->rejection_reason)
      <div class="alert alert-error" style="margin-top:16px;">{{ __('site.panel.landowner.rejection_reason_label') }} {{ $propertyRequest->rejection_reason }}</div>
    @endif
  </div>

  {{-- ---------- OFFERS (ADMIN-ONLY) ---------- --}}
  <div class="panel">
    <div class="panel-head"><h3>{{ __('site.panel.admin.request_offers_title') }}</h3></div>
    <p style="font-size:12px; color:var(--ink-soft); margin-bottom:16px;">{{ __('site.panel.admin.request_offers_note') }}</p>

    @if ($offers->isEmpty())
      <p style="font-size:13px; color:var(--ink-soft);">{{ __('site.panel.admin.no_offers_yet') }}</p>
    @else
      @foreach ($offers as $offer)
        <div style="border:1px solid var(--border); border-radius:var(--radius-sm); padding:14px; margin-bottom:12px;">
          <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:10px; margin-bottom:8px;">
            <div>
              <b style="font-size:13.5px;">{{ $offer->name }}</b>
              <span style="font-size:12px; color:var(--ink-soft); display:block;">{{ $offer->phone }}</span>
            </div>
            <span class="status-pill {{ $offer->status === 'contacted' ? 'done' : ($offer->status === 'archived' ? 'cancelled' : 'new') }}">{{ __('site.status.inquiry.'.$offer->status) }}</span>
          </div>
          <div style="font-size:12px; color:var(--ink-soft); margin-bottom:6px;">{{ jdate($offer->created_at, 'j F Y - H:i') }}</div>
          @if ($offer->message)
            <p style="font-size:13px; color:var(--ink); background:var(--primary-tint); border-radius:8px; padding:10px; margin-bottom:10px;">{{ $offer->message }}</p>
          @endif

          @if ($offer->status !== 'contacted')
            <form action="{{ route('admin.property-requests.offers.status', $offer) }}" method="POST" style="display:inline;">
              @csrf<input type="hidden" name="status" value="contacted">
              <button type="submit" class="row-actions" style="background:none; border:none; cursor:pointer;">{{ __('site.panel.admin.mark_contacted') }}</button>
            </form>
          @endif
          @if ($offer->status !== 'archived')
            <span style="margin:0 4px; color:var(--border);">·</span>
            <form action="{{ route('admin.property-requests.offers.status', $offer) }}" method="POST" style="display:inline;">
              @csrf<input type="hidden" name="status" value="archived">
              <button type="submit" class="row-actions" style="background:none; border:none; cursor:pointer; color:var(--ink-soft);">{{ __('site.panel.admin.mark_archived') }}</button>
            </form>
          @endif
        </div>
      @endforeach
      <div class="pagination">{{ $offers->links() }}</div>
    @endif
  </div>
</div>
@endsection

@push('scripts')
<script>
  function fillRejectionReason(form) {
    const reason = prompt(@json(__('site.panel.admin.reject_property_reason_prompt')));
    if (!reason) return false;
    form.querySelector('[name=rejection_reason]').value = reason;
    return true;
  }
</script>
@endpush
