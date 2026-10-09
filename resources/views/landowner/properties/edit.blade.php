@extends('layouts.panel')
@section('title', __('site.panel.landowner.edit_title'))

@section('sidebar')
  @include('partials.landowner-sidebar')
@endsection

@section('content')
<div class="topbar"><div><h1>{{ __('site.panel.landowner.edit_title') }}</h1><p>{{ __('site.panel.landowner.form_note') }}</p></div></div>

@if ($property->status === 'rejected' && $property->rejection_reason)
  <div class="alert alert-error" style="margin-bottom:18px;">{{ __('site.panel.landowner.rejection_reason_label') }} {{ $property->rejection_reason }}</div>
@endif

<div class="panel" style="max-width:640px;">
  <form action="{{ route('landowner.properties.update', $property) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('landowner.properties._form', ['property' => $property])
    <button type="submit" class="btn btn-primary">{{ __('site.panel.landowner.submit_update') }}</button>
  </form>
</div>
@endsection
