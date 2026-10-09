@extends('layouts.panel')
@section('title', __('site.announcements.title_page'))

@section('sidebar')
  @include('partials.admin-sidebar')
@endsection

@section('content')
<div class="topbar">
  <div>
    <h1>{{ __('site.announcements.title_page') }}</h1>
    <p>{{ __('site.announcements.subtitle', ['count' => $announcements->total()]) }}</p>
  </div>
  <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">{{ __('site.announcements.new') }}</a>
</div>

@if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<div class="table-wrap">
  <table>
    <thead><tr>
      <th>{{ __('site.announcements.th_title') }}</th>
      <th>{{ __('site.announcements.th_type') }}</th>
      <th>{{ __('site.announcements.th_priority') }}</th>
      <th>{{ __('site.announcements.th_period') }}</th>
      <th>{{ __('site.announcements.th_status') }}</th>
      <th></th>
    </tr></thead>
    <tbody>
      @forelse ($announcements as $a)
        <tr>
          <td><b>{{ $a->localized_title }}</b></td>
          <td>
            <span class="status-pill {{ $a->type == 'offer' ? 'new' : ($a->type == 'urgent' ? 'cancelled' : 'processing') }}">{{ __('site.announcements.type_'.$a->type) }}</span>
          </td>
          <td>{{ $a->priority }}</td>
          <td style="font-size:12.5px; color:var(--ink-soft); white-space:nowrap;">
            @if ($a->starts_at || $a->ends_at)
              {{ $a->starts_at ? jdate($a->starts_at, 'Y/m/d') : '…' }} – {{ $a->ends_at ? jdate($a->ends_at, 'Y/m/d') : '…' }}
            @else
              {{ __('site.announcements.period_always') }}
            @endif
          </td>
          <td>
            <span class="status-pill {{ $a->is_currently_visible ? 'done' : 'cancelled' }}">
              {{ $a->is_active ? __('site.status.product.active') : __('site.status.product.inactive') }}
            </span>
          </td>
          <td style="white-space:nowrap;">
            <a href="{{ route('admin.announcements.edit', $a) }}" class="row-actions">{{ __('site.panel.edit') }}</a>
            ·
            <form action="{{ route('admin.announcements.toggle', $a) }}" method="POST" style="display:inline;">
              @csrf
              <button type="submit" class="row-actions" style="background:none; border:none; cursor:pointer;">{{ $a->is_active ? __('site.announcements.deactivate') : __('site.announcements.activate') }}</button>
            </form>
            ·
            <form action="{{ route('admin.announcements.destroy', $a) }}" method="POST" style="display:inline;" onsubmit="return confirm(@json(__('site.panel.confirm_delete')))">
              @csrf @method('DELETE')
              <button type="submit" class="row-actions" style="background:none; border:none; cursor:pointer; color:var(--red);">{{ __('site.panel.delete') }}</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" style="text-align:center; color:var(--ink-soft); padding:28px 0;">{{ __('site.announcements.no_announcements') }}</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div class="pagination">{{ $announcements->links() }}</div>
@endsection