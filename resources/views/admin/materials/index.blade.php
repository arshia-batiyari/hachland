@extends('layouts.panel')
@section('title', __('site.materials.admin_title'))

@section('sidebar')
  @include('partials.admin-sidebar')
@endsection

@section('content')
<div class="topbar">
  <div>
    <h1>{{ __('site.materials.admin_title') }}</h1>
    <p>{{ __('site.materials.admin_count', ['count' => $materialRequests->total()]) }}</p>
  </div>
</div>

<div class="subcats">
  <a href="{{ route('admin.materials.index') }}" class="chip {{ !request('status') ? 'on' : '' }}">{{ __('site.panel.all') }}</a>
  <a href="{{ route('admin.materials.index', ['status'=>'pending']) }}" class="chip {{ request('status')=='pending' ? 'on' : '' }}">{{ __('site.status.property.pending') }}</a>
  <a href="{{ route('admin.materials.index', ['status'=>'approved']) }}" class="chip {{ request('status')=='approved' ? 'on' : '' }}">{{ __('site.status.property.approved') }}</a>
  <a href="{{ route('admin.materials.index', ['status'=>'rejected']) }}" class="chip {{ request('status')=='rejected' ? 'on' : '' }}">{{ __('site.status.property.rejected') }}</a>
</div>

<div class="table-wrap">
  <table>
    <thead><tr>
      <th>{{ __('site.materials.nav_title') }}</th>
      <th>{{ __('site.panel.admin.th_customer') }}</th>
      <th>{{ __('site.materials.material_type') }}</th>
      <th>{{ __('site.materials.quantity') }}</th>
      <th>{{ __('site.panel.admin.th_inquiries') }}</th>
      <th>{{ __('site.panel.vendor.th_status') }}</th>
      <th></th>
    </tr></thead>
    <tbody>
    @foreach ($materialRequests as $item)
      <tr>
        <td><a href="{{ route('admin.materials.show', $item) }}" style="color:inherit; text-decoration:none; font-weight:600;">{{ $item->localized_title }}</a></td>
        <td>{{ $item->name }} — {{ $item->phone }}</td>
        <td>{{ $item->localized_type }}</td>
        <td>{{ $item->quantity_label }}</td>
        <td><a href="{{ route('admin.materials.show', $item) }}" class="row-actions">{{ $item->offers_count }}</a></td>
        <td><span class="status-pill {{ $item->status == 'approved' ? 'done' : ($item->status == 'rejected' ? 'cancelled' : 'pending') }}">{{ __('site.status.property.'.$item->status) }}</span></td>
        <td style="white-space:nowrap;">
          @if ($item->status === 'pending')
            <form action="{{ route('admin.materials.approve', $item) }}" method="POST" style="display:inline;">@csrf<button class="row-actions" style="background:none;border:none;cursor:pointer;">{{ __('site.panel.approve') }}</button></form>
            ·
            <form action="{{ route('admin.materials.reject', $item) }}" method="POST" style="display:inline;" onsubmit="return fillMaterialRejection(this)">
              @csrf<input type="hidden" name="rejection_reason" value="">
              <button class="row-actions" style="background:none;border:none;cursor:pointer;color:var(--red);">{{ __('site.panel.reject') }}</button>
            </form>
            ·
          @endif
          <a href="{{ route('admin.materials.show', $item) }}" class="row-actions">{{ __('site.panel.view_details') }}</a>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
<div class="pagination">{{ $materialRequests->links() }}</div>
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
