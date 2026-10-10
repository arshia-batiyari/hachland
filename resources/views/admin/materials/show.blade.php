@extends('layouts.panel')
@section('title', __('site.materials.admin_details'))

@section('sidebar')
  @include('partials.admin-sidebar')
@endsection

@section('content')
<div class="topbar">
  <div>
    <a href="{{ route('admin.materials.index') }}" class="row-actions" style="display:inline-block; margin-bottom:8px;">→ {{ __('site.materials.back_to_list') }}</a>
    <h1>{{ $materialRequest->localized_title }}</h1>
    <p>{{ $materialRequest->localized_type }} · {{ $materialRequest->quantity_label }} @if($materialRequest->city) · {{ $materialRequest->city }}، {{ $materialRequest->province }} @endif</p>
  </div>
  <div style="display:flex; align-items:center; gap:10px;">
    <span class="status-pill {{ $materialRequest->status == 'approved' ? 'done' : ($materialRequest->status == 'rejected' ? 'cancelled' : 'pending') }}">{{ __('site.status.property.'.$materialRequest->status) }}</span>
    @if ($materialRequest->status === 'pending')
      <form action="{{ route('admin.materials.approve', $materialRequest) }}" method="POST">@csrf<button type="submit" class="btn btn-primary">{{ __('site.panel.approve') }}</button></form>
      <form action="{{ route('admin.materials.reject', $materialRequest) }}" method="POST" onsubmit="return fillMaterialRejection(this)">
        @csrf<input type="hidden" name="rejection_reason" value="">
        <button type="submit" class="btn btn-ghost" style="color:var(--red); border-color:var(--red);">{{ __('site.panel.reject') }}</button>
      </form>
    @endif
  </div>
</div>

<div class="grid-2">
  <div class="panel">
    <div class="panel-head"><h3>{{ __('site.materials.admin_details') }}</h3></div>
    <div class="stat-mini"><span>{{ __('site.panel.admin.th_customer') }}</span><b>{{ $materialRequest->name }}</b></div>
    <div class="stat-mini"><span>{{ __('site.auth.phone') }}</span><b>{{ $materialRequest->phone }}</b></div>
    <div class="stat-mini"><span>{{ __('site.materials.fulfillment') }}</span><b>{{ $materialRequest->localized_fulfillment }}</b></div>
    @if ($materialRequest->needed_on)
      <div class="stat-mini"><span>{{ __('site.materials.needed_on') }}</span><b>{{ jdate($materialRequest->needed_on, 'j F Y') }}</b></div>
    @endif
    @if ($materialRequest->delivery_place)
      <div class="stat-mini"><span>{{ __('site.materials.delivery_place') }}</span><b>{{ $materialRequest->delivery_place }}</b></div>
    @endif
    @if ($materialRequest->budget)
      <div class="stat-mini"><span>{{ __('site.materials.budget_label') }}</span><b>{{ number_format($materialRequest->budget) }} {{ __('site.common.toman') }}</b></div>
    @endif
    @if ($materialRequest->localized_description)
      <p style="font-size:13.5px; color:var(--ink-soft); line-height:1.9; margin-top:16px;">{{ $materialRequest->localized_description }}</p>
    @endif
    @if ($materialRequest->status === 'rejected' && $materialRequest->rejection_reason)
      <div class="alert alert-error" style="margin-top:16px;">{{ __('site.panel.admin.rejection_reason_label') }}: {{ $materialRequest->rejection_reason }}</div>
    @endif
  </div>

  <div class="panel">
    <div class="panel-head"><h3>{{ __('site.materials.admin_offers') }}</h3></div>
    <p style="font-size:12px; color:var(--ink-soft); margin-bottom:16px;">{{ __('site.materials.admin_offers_note') }}</p>

    @if ($offers->isEmpty())
      <p style="font-size:13px; color:var(--ink-soft);">{{ __('site.panel.admin.no_offers_yet') }}</p>
    @else
      @foreach ($offers as $offer)
        <div style="border:1px solid var(--border); border-radius:var(--radius-sm); padding:14px; margin-bottom:12px;">
          <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:10px; margin-bottom:8px;">
            <div>
              <b style="font-size:13.5px;">{{ $offer->name }}</b>
              <span style="font-size:12px; color:var(--ink-soft); display:block;">{{ $offer->phone }}</span>
              @if ($offer->booth)
                <a href="{{ route('admin.booths.show', $offer->booth) }}" class="row-actions">{{ $offer->booth->localized_name }}</a>
              @endif
            </div>
            <span class="status-pill {{ $offer->status === 'contacted' ? 'done' : ($offer->status === 'archived' ? 'cancelled' : 'new') }}">{{ __('site.status.inquiry.'.$offer->status) }}</span>
          </div>
          <div style="font-size:12px; color:var(--ink-soft); margin-bottom:6px;">{{ jdate($offer->created_at, 'j F Y - H:i') }}</div>
          @if ($offer->quoted_amount)
            <div class="stat-mini"><span>{{ __('site.materials.offer_amount') }}</span><b>{{ number_format($offer->quoted_amount) }} {{ __('site.common.toman') }}</b></div>
          @endif
          @if ($offer->lead_days !== null)
            <div class="stat-mini"><span>{{ __('site.materials.offer_lead') }}</span><b>{{ $offer->lead_days }}</b></div>
          @endif
          @if ($offer->message)
            <p style="font-size:13px; color:var(--ink); background:var(--primary-tint); border-radius:8px; padding:10px; margin:10px 0;">{{ $offer->message }}</p>
          @endif
          @if ($offer->status !== 'contacted')
            <form action="{{ route('admin.materials.offers.status', $offer) }}" method="POST" style="display:inline;">
              @csrf<input type="hidden" name="status" value="contacted">
              <button type="submit" class="row-actions" style="background:none; border:none; cursor:pointer;">{{ __('site.panel.admin.mark_contacted') }}</button>
            </form>
          @endif
          @if ($offer->status !== 'archived')
            <span style="margin:0 4px; color:var(--border);">·</span>
            <form action="{{ route('admin.materials.offers.status', $offer) }}" method="POST" style="display:inline;">
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
  function fillMaterialRejection(form) {
    const reason = prompt(@json(__('site.materials.reject_prompt')));
    if (!reason) return false;
    form.querySelector('[name=rejection_reason]').value = reason;
    return true;
  }
</script>
@endpush
