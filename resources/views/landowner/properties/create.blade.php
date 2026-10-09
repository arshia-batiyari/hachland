@extends('layouts.panel')
@section('title', __('site.panel.landowner.create_title'))

@section('sidebar')
  @include('partials.landowner-sidebar')
@endsection

@section('content')
<div class="topbar"><div><h1>{{ __('site.panel.landowner.create_title') }}</h1><p>{{ __('site.panel.landowner.form_note') }}</p></div></div>

<div class="panel" style="max-width:640px;">
  <form action="{{ route('landowner.properties.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('landowner.properties._form', ['property' => null])
    <button type="submit" class="btn btn-primary">{{ __('site.panel.landowner.submit_create') }}</button>
  </form>
</div>
@endsection
