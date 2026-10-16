@extends('layouts.panel')
@section('title', __('site.panel.landowner.dashboard_title'))

@section('sidebar')
  @include('partials.landowner-sidebar')
@endsection

@section('content')
<div class="topbar">
  <div>
    <h1>{{ __('site.panel.landowner.greeting', ['name' => auth()->user()->name]) }}</h1>
    <p>{{ __('site.panel.landowner.greeting_sub') }}</p>
  </div>
  <div>
    <a href="{{ route('landowner.properties.create') }}" class="btn btn-primary">{{ __('site.panel.landowner.add_property') }}</a>
  </div>
</div>

<div class="alert" style="background:var(--primary-tint); color:var(--primary-dark); margin-bottom:22px;">
  {{ __('site.panel.landowner.privacy_note') }}
</div>
@if ($stats['pending'] > 0)
  <div class="alert" style="background:#F3F6FF; color:#243056; margin-bottom:22px;">
    {{ __('site.panel.landowner.approval_sms') }}
  </div>
@endif

<div class="kpi-row">
  <div class="kpi"><div class="kpi-icon">🏡</div><b>{{ $stats['total'] }}</b><span>{{ __('site.panel.landowner.kpi_total') }}</span></div>
  <div class="kpi"><div class="kpi-icon">⏳</div><b>{{ $stats['pending'] }}</b><span>{{ __('site.panel.landowner.kpi_pending') }}</span></div>
  <div class="kpi"><div class="kpi-icon">✅</div><b>{{ $stats['approved'] }}</b><span>{{ __('site.panel.landowner.kpi_approved') }}</span></div>
  <div class="kpi"><div class="kpi-icon">⛔</div><b>{{ $stats['rejected'] }}</b><span>{{ __('site.panel.landowner.kpi_rejected') }}</span></div>
</div>

@if ($properties->isEmpty())
  <div class="empty-state">
    <p>{{ __('site.panel.landowner.no_properties_yet') }}</p>
    <a href="{{ route('landowner.properties.create') }}" class="btn btn-primary" style="margin-top:14px;">{{ __('site.panel.landowner.no_properties_cta') }}</a>
  </div>
@else
  <div class="table-wrap">
    <table>
      <thead><tr>
        <th>{{ __('site.panel.landowner.th_property') }}</th>
        <th>{{ __('site.panel.landowner.th_type') }}</th>
        <th>{{ __('site.panel.landowner.th_city') }}</th>
        <th>{{ __('site.panel.landowner.th_status') }}</th>
        <th></th>
      </tr></thead>
      <tbody>
        @foreach ($properties as $property)
          <tr>
            <td>
              <div class="cell-booth">
                <div class="cell-thumb"><img src="{{ $property->first_image_url }}" alt=""></div>
                {{ $property->localized_title }}
              </div>
              @if ($property->status === 'rejected' && $property->rejection_reason)
                <div style="font-size:11.5px; color:var(--red); margin-top:4px;">{{ __('site.panel.landowner.rejection_reason_label') }} {{ $property->rejection_reason }}</div>
              @endif
            </td>
            <td>{{ $property->localized_property_type }}</td>
            <td>{{ $property->city }}</td>
            <td>
              <span class="status-pill {{ $property->status === 'approved' ? 'done' : ($property->status === 'rejected' ? 'cancelled' : 'pending') }}">{{ __('site.status.property.'.$property->status) }}</span>
            </td>
            <td style="white-space:nowrap;">
              <a href="{{ route('landowner.properties.edit', $property) }}" class="row-actions">{{ __('site.common.edit') }}</a>
              ·
              <form action="{{ route('landowner.properties.destroy', $property) }}" method="POST" style="display:inline;" onsubmit="return confirm(@json(__('site.panel.landowner.confirm_delete_property')))">
                @csrf @method('DELETE')
                <button type="submit" class="row-actions" style="background:none; border:none; cursor:pointer; color:var(--red);">{{ __('site.common.delete') }}</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endif
@endsection
