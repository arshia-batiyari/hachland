@extends('layouts.panel')
@section('title', __('site.sidebar.booths'))

@section('sidebar')
  @include('partials.admin-sidebar')
@endsection

@section('content')
<div class="topbar"><div><h1>{{ __('site.panel.admin.booths_title') }}</h1><p>{{ __('site.panel.admin.booths_count', ['count' => $booths->total()]) }}</p></div></div>

<div class="subcats">
  <a href="{{ route('admin.booths.index') }}" class="chip {{ !request('status') ? 'on' : '' }}">{{ __('site.panel.all') }}</a>
  <a href="{{ route('admin.booths.index', ['status'=>'pending']) }}" class="chip {{ request('status')=='pending' ? 'on' : '' }}">{{ __('site.status.booth.pending') }}</a>
  <a href="{{ route('admin.booths.index', ['status'=>'approved']) }}" class="chip {{ request('status')=='approved' ? 'on' : '' }}">{{ __('site.status.booth.approved') }}</a>
  <a href="{{ route('admin.booths.index', ['status'=>'rejected']) }}" class="chip {{ request('status')=='rejected' ? 'on' : '' }}">{{ __('site.status.booth.rejected') }}</a>
</div>

<div class="table-wrap">
  <table>
    <thead><tr>
      <th>{{ __('site.panel.admin.th_booth') }}</th>
      <th>{{ __('site.panel.admin.th_owner') }}</th>
      <th>{{ __('site.panel.admin.th_category') }}</th>
      <th>{{ __('site.panel.admin.th_commission') }}</th>
      <th>{{ __('site.panel.vendor.th_status') }}</th>
      <th></th>
    </tr></thead>
    <tbody>
      @foreach ($booths as $booth)
        <tr>
          <td><div class="cell-booth"><div class="cell-thumb">{{ mb_substr($booth->localized_name, 0, 2) }}</div>{{ $booth->localized_name }}</div></td>
          <td>{{ $booth->owner->name }}</td>
          <td>{{ $booth->categories->pluck('localized_name')->implode('، ') }}</td>
          <td>
            <form action="{{ route('admin.booths.commission', $booth) }}" method="POST" style="display:flex; align-items:center; gap:4px;">
              @csrf
              <input type="number" name="commission_percent" value="{{ $booth->commission_percent }}" step="0.5" class="field" style="width:60px; padding:5px 8px;">
              <button type="submit" class="row-actions" style="background:none;border:none;cursor:pointer;">٪</button>
            </form>
          </td>
          <td>
            <span class="status-pill {{ $booth->status == 'approved' ? 'done' : ($booth->status == 'rejected' ? 'cancelled' : 'pending') }}">{{ __('site.status.booth.'.$booth->status) }}</span>
          </td>
          <td style="white-space:nowrap;">
            <div style="display:flex; align-items:center; gap:10px;">
              <a href="{{ route('admin.booths.show', $booth) }}" class="row-actions">{{ __('site.panel.view') }}</a>

              {{-- سلکت سریع تغییر وضعیت — هر لحظه می‌شه غرفه رو به هر وضعیتی
                   (در انتظار/تأییدشده/ردشده/تعلیق) برد، بدون محدودیت به
                   وضعیت فعلیش --}}
              <form action="{{ route('admin.booths.status', $booth) }}" method="POST" class="js-status-form" data-current="{{ $booth->status }}" data-prompt="{{ __('site.panel.prompt_rejection_reason') }}" data-default-reason="{{ __('site.panel.rejection_reason_default') }}" data-confirm-label="{{ __('site.panel.change_status') }}">
                @csrf
                <input type="hidden" name="rejection_reason" class="js-rejection-reason">
                <select name="status" class="field js-status-select" style="padding:5px 8px; font-size:12.5px;" onchange="hachlandBoothStatusChange(this)">
                  <option value="pending" {{ $booth->status=='pending'?'selected':'' }}>{{ __('site.status.booth.pending') }}</option>
                  <option value="approved" {{ $booth->status=='approved'?'selected':'' }}>{{ __('site.status.booth.approved') }}</option>
                  <option value="rejected" {{ $booth->status=='rejected'?'selected':'' }}>{{ __('site.status.booth.rejected') }}</option>
                  <option value="suspended" {{ $booth->status=='suspended'?'selected':'' }}>{{ __('site.status.booth.suspended') }}</option>
                </select>
              </form>
            </div>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div class="pagination">{{ $booths->links() }}</div>

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
@endsection