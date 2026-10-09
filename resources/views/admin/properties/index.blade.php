@extends('layouts.panel')
@section('title', __('site.panel.admin.properties_title'))

@section('sidebar')
  @include('partials.admin-sidebar')
@endsection

@section('content')
<div class="topbar"><div><h1>{{ __('site.panel.admin.properties_title') }}</h1><p>{{ __('site.panel.admin.properties_count', ['count' => $properties->total()]) }}</p></div></div>

<div class="subcats">
  <a href="{{ route('admin.properties.index') }}" class="chip {{ !request('status') ? 'on' : '' }}">{{ __('site.panel.all') }}</a>
  <a href="{{ route('admin.properties.index', ['status'=>'pending']) }}" class="chip {{ request('status')=='pending' ? 'on' : '' }}">{{ __('site.status.property.pending') }}</a>
  <a href="{{ route('admin.properties.index', ['status'=>'approved']) }}" class="chip {{ request('status')=='approved' ? 'on' : '' }}">{{ __('site.status.property.approved') }}</a>
  <a href="{{ route('admin.properties.index', ['status'=>'rejected']) }}" class="chip {{ request('status')=='rejected' ? 'on' : '' }}">{{ __('site.status.property.rejected') }}</a>
</div>

<div class="table-wrap">
  <table>
    <thead><tr>
      <th>{{ __('site.panel.admin.th_property') }}</th>
      <th>{{ __('site.panel.admin.th_owner') }}</th>
      <th>{{ __('site.panel.admin.th_type') }}</th>
      <th>{{ __('site.panel.admin.th_deal') }}</th>
      <th>{{ __('site.panel.admin.th_inquiries') }}</th>
      <th>{{ __('site.panel.vendor.th_status') }}</th>
      <th></th>
    </tr></thead>
    <tbody>
      @foreach ($properties as $property)
        <tr>
          <td>
            <a href="{{ route('admin.properties.show', $property) }}" class="cell-booth" style="color:inherit; text-decoration:none;">
              <div class="cell-thumb"><img src="{{ $property->first_image_url }}" alt=""></div>
              {{ $property->localized_title }}
            </a>
          </td>
          <td>{{ $property->owner->name }}</td>
          <td>{{ $property->localized_property_type }}</td>
          <td>{{ $property->localized_deal_type }}</td>
          <td>
            <a href="{{ route('admin.properties.show', $property) }}" class="row-actions">
              {{ $property->inquiries_count }}
              @if ($property->inquiries_count)
                <span class="side-count" style="position:static; display:inline-block; margin-right:4px;">{{ $property->inquiries_count }}</span>
              @endif
            </a>
          </td>
          <td>
            <span class="status-pill {{ $property->status == 'approved' ? 'done' : ($property->status == 'rejected' ? 'cancelled' : 'pending') }}">{{ __('site.status.property.'.$property->status) }}</span>
          </td>
          <td style="white-space:nowrap;">
            @if ($property->status === 'pending')
              <form action="{{ route('admin.properties.approve', $property) }}" method="POST" style="display:inline;">@csrf<button class="row-actions" style="background:none;border:none;cursor:pointer;">{{ __('site.panel.approve') }}</button></form>
              ·
              <form action="{{ route('admin.properties.reject', $property) }}" method="POST" style="display:inline;" onsubmit="return fillRejectionReason(this)">
                @csrf<input type="hidden" name="rejection_reason" value="">
                <button class="row-actions" style="background:none;border:none;cursor:pointer;color:var(--red);">{{ __('site.panel.reject') }}</button>
              </form>
            @endif
            ·
            <a href="{{ route('admin.properties.show', $property) }}" class="row-actions">{{ __('site.panel.view_details') }}</a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div class="pagination">{{ $properties->links() }}</div>
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
