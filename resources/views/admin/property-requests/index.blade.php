@extends('layouts.panel')
@section('title', __('site.panel.admin.property_requests_title'))

@section('sidebar')
  @include('partials.admin-sidebar')
@endsection

@section('content')
<div class="topbar"><div><h1>{{ __('site.panel.admin.property_requests_title') }}</h1><p>{{ __('site.panel.admin.property_requests_count', ['count' => $propertyRequests->total()]) }}</p></div></div>

<div class="subcats">
  <a href="{{ route('admin.property-requests.index') }}" class="chip {{ !request('status') ? 'on' : '' }}">{{ __('site.panel.all') }}</a>
  <a href="{{ route('admin.property-requests.index', ['status'=>'pending']) }}" class="chip {{ request('status')=='pending' ? 'on' : '' }}">{{ __('site.status.property.pending') }}</a>
  <a href="{{ route('admin.property-requests.index', ['status'=>'approved']) }}" class="chip {{ request('status')=='approved' ? 'on' : '' }}">{{ __('site.status.property.approved') }}</a>
  <a href="{{ route('admin.property-requests.index', ['status'=>'rejected']) }}" class="chip {{ request('status')=='rejected' ? 'on' : '' }}">{{ __('site.status.property.rejected') }}</a>
</div>

<div class="table-wrap">
  <table>
    <thead><tr>
      <th>{{ __('site.property_requests.nav_title') }}</th>
      <th>{{ __('site.panel.admin.th_customer') }}</th>
      <th>{{ __('site.panel.admin.th_type') }}</th>
      <th>{{ __('site.panel.admin.th_deal') }}</th>
      <th>{{ __('site.panel.admin.th_inquiries') }}</th>
      <th>{{ __('site.panel.vendor.th_status') }}</th>
      <th></th>
    </tr></thead>
    <tbody>
      @foreach ($propertyRequests as $r)
        <tr>
          <td><a href="{{ route('admin.property-requests.show', $r) }}" style="color:inherit; text-decoration:none; font-weight:600;">{{ $r->localized_title }}</a></td>
          <td>{{ $r->name }} — {{ $r->phone }}</td>
          <td>{{ $r->localized_property_type }}</td>
          <td>{{ $r->localized_deal_type }}</td>
          <td>
            <a href="{{ route('admin.property-requests.show', $r) }}" class="row-actions">
              {{ $r->offers_count }}
              @if ($r->offers_count)
                <span class="side-count" style="position:static; display:inline-block; margin-right:4px;">{{ $r->offers_count }}</span>
              @endif
            </a>
          </td>
          <td><span class="status-pill {{ $r->status == 'approved' ? 'done' : ($r->status == 'rejected' ? 'cancelled' : 'pending') }}">{{ __('site.status.property.'.$r->status) }}</span></td>
          <td style="white-space:nowrap;">
            @if ($r->status === 'pending')
              <form action="{{ route('admin.property-requests.approve', $r) }}" method="POST" style="display:inline;">@csrf<button class="row-actions" style="background:none;border:none;cursor:pointer;">{{ __('site.panel.approve') }}</button></form>
              ·
              <form action="{{ route('admin.property-requests.reject', $r) }}" method="POST" style="display:inline;" onsubmit="return fillRejectionReason(this)">
                @csrf<input type="hidden" name="rejection_reason" value="">
                <button class="row-actions" style="background:none;border:none;cursor:pointer;color:var(--red);">{{ __('site.panel.reject') }}</button>
              </form>
              ·
            @endif
            <a href="{{ route('admin.property-requests.show', $r) }}" class="row-actions">{{ __('site.panel.view_details') }}</a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div class="pagination">{{ $propertyRequests->links() }}</div>
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
