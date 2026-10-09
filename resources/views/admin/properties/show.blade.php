@extends('layouts.panel')
@section('title', __('site.panel.admin.property_details_title'))

@section('sidebar')
  @include('partials.admin-sidebar')
@endsection

@section('content')
<div class="topbar">
  <div>
    <h1>{{ $property->localized_title }}</h1>
    <p>{{ $property->localized_property_type }} · {{ $property->localized_deal_type }} @if($property->city) · {{ $property->city }}، {{ $property->province }} @endif</p>
  </div>
  <div style="display:flex; align-items:center; gap:10px;">
    <span class="status-pill {{ $property->status == 'approved' ? 'done' : ($property->status == 'rejected' ? 'cancelled' : 'pending') }}">{{ __('site.status.property.'.$property->status) }}</span>
    @if ($property->status === 'pending')
      <form action="{{ route('admin.properties.approve', $property) }}" method="POST">@csrf<button type="submit" class="btn btn-primary">{{ __('site.panel.approve') }}</button></form>
      <form action="{{ route('admin.properties.reject', $property) }}" method="POST" onsubmit="return fillRejectionReason(this)">
        @csrf<input type="hidden" name="rejection_reason" value="">
        <button type="submit" class="btn btn-ghost" style="color:var(--red); border-color:var(--red);">{{ __('site.panel.reject') }}</button>
      </form>
    @endif
  </div>
</div>

@if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<div class="grid-2">
  {{-- ---------- PROPERTY DETAILS ---------- --}}
  <div class="panel">
    <div class="panel-head"><h3>{{ __('site.panel.admin.property_details_title') }}</h3></div>

    @if (!empty($property->images))
      <div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:18px;">
        @foreach ($property->image_urls as $url)
          <div style="width:84px; height:84px; border-radius:10px; overflow:hidden; border:1px solid var(--border);">
            <img src="{{ $url }}" style="width:100%; height:100%; object-fit:cover;">
          </div>
        @endforeach
      </div>
    @endif

    <div class="stat-mini"><span>{{ __('site.sidebar.my_properties') }}</span><b>{{ __('site.panel.admin.th_owner') }}: {{ $property->owner->name }}</b></div>
    <div class="stat-mini"><span>{{ __('site.auth.phone') }}</span><b>{{ $property->owner->phone }}</b></div>
    @if ($property->area_sqm)
      <div class="stat-mini"><span>{{ __('site.properties.area_suffix') }}</span><b>{{ number_format($property->area_sqm) }}</b></div>
    @endif
    <div class="stat-mini"><span>{{ __('site.panel.landowner.price') }}</span><b>{{ $property->price ? number_format($property->price).' '.__('site.common.toman') : __('site.properties.price_on_request') }}</b></div>
    @if ($property->document_type)
      <div class="stat-mini"><span>{{ __('site.properties.document_type_label') }}</span><b>{{ $property->document_type }}</b></div>
    @endif
    @if ($property->address)
      <div class="stat-mini"><span>{{ __('site.panel.landowner.address') }}</span><b>{{ $property->address }}</b></div>
    @endif

    <p style="font-size:13.5px; color:var(--ink-soft); line-height:1.9; margin-top:16px;">{{ $property->localized_description }}</p>

    @if ($property->status === 'rejected' && $property->rejection_reason)
      <div class="alert alert-error" style="margin-top:16px;">{{ __('site.panel.landowner.rejection_reason_label') }} {{ $property->rejection_reason }}</div>
    @endif
  </div>

  {{-- ---------- INQUIRIES (ADMIN-ONLY) ---------- --}}
  <div class="panel">
    <div class="panel-head"><h3>{{ __('site.panel.admin.inquiries_title') }}</h3></div>
    <p style="font-size:12px; color:var(--ink-soft); margin-bottom:16px;">{{ __('site.panel.admin.inquiries_note') }}</p>

    @if ($inquiries->isEmpty())
      <p style="font-size:13px; color:var(--ink-soft);">{{ __('site.panel.admin.no_inquiries_yet') }}</p>
    @else
      @foreach ($inquiries as $inquiry)
        <div style="border:1px solid var(--border); border-radius:var(--radius-sm); padding:14px; margin-bottom:12px;">
          <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:10px; margin-bottom:8px;">
            <div>
              <b style="font-size:13.5px;">{{ $inquiry->name }}</b>
              <span style="font-size:12px; color:var(--ink-soft); display:block;">{{ $inquiry->phone }}</span>
            </div>
            <span class="status-pill {{ $inquiry->status === 'contacted' ? 'done' : ($inquiry->status === 'archived' ? 'cancelled' : 'new') }}">{{ __('site.status.inquiry.'.$inquiry->status) }}</span>
          </div>
          <div style="font-size:12px; color:var(--ink-soft); margin-bottom:6px;">{{ $inquiry->localized_inquiry_type }} · {{ jdate($inquiry->created_at, 'j F Y - H:i') }}</div>
          @if ($inquiry->message)
            <p style="font-size:13px; color:var(--ink); background:var(--primary-tint); border-radius:8px; padding:10px; margin-bottom:10px;">{{ $inquiry->message }}</p>
          @endif

          @if ($inquiry->status !== 'contacted')
            <form action="{{ route('admin.properties.inquiries.status', $inquiry) }}" method="POST" style="display:inline;">
              @csrf
              <input type="hidden" name="status" value="contacted">
              <button type="submit" class="row-actions" style="background:none; border:none; cursor:pointer;">{{ __('site.panel.admin.mark_contacted') }}</button>
            </form>
          @endif
          @if ($inquiry->status !== 'archived')
            <span style="margin:0 4px; color:var(--border);">·</span>
            <form action="{{ route('admin.properties.inquiries.status', $inquiry) }}" method="POST" style="display:inline;">
              @csrf
              <input type="hidden" name="status" value="archived">
              <button type="submit" class="row-actions" style="background:none; border:none; cursor:pointer; color:var(--ink-soft);">{{ __('site.panel.admin.mark_archived') }}</button>
            </form>
          @endif
        </div>
      @endforeach
      <div class="pagination">{{ $inquiries->links() }}</div>
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
