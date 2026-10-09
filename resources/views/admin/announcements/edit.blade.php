@extends('layouts.panel')
@section('title', __('site.announcements.edit_title'))

@section('sidebar')
  @include('partials.admin-sidebar')
@endsection

@section('content')
<div class="topbar"><div><h1>{{ __('site.announcements.edit_title') }}</h1></div></div>

@if ($errors->any())
  <div class="alert alert-error">
    <ul style="margin:0; padding-inline-start:18px;">
      @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
  </div>
@endif

<div class="panel" style="max-width:620px;">
  <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST">
    @csrf @method('PUT')
    @include('admin.announcements._form')
    <button type="submit" class="btn btn-primary">{{ __('site.common.save') }}</button>
    <a href="{{ route('admin.announcements.index') }}" class="btn btn-ghost">{{ __('site.common.cancel') }}</a>
  </form>
</div>
@endsection