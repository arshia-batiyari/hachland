@extends('layouts.panel')
@section('title', __('site.panel.admin.new_category_title'))

@section('sidebar')
  @include('partials.admin-sidebar')
@endsection

@section('content')
<div class="topbar"><div><h1>{{ __('site.panel.admin.new_category_title') }}</h1></div></div>

<div class="panel" style="max-width:560px;">
  <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="field-group"><label>{{ __('site.panel.admin.name_fa') }}</label><input type="text" name="name_fa" class="field" required></div>
    <div class="field-group"><label>{{ __('site.panel.admin.name_en') }}</label><input type="text" name="name_en" class="field"></div>
    <div class="field-group">
      <label>{{ __('site.panel.admin.parent_category') }}</label>
      <select name="parent_id" class="field">
        <option value="">{{ __('site.panel.admin.root_category') }}</option>
        @foreach ($parents as $p)
          <option value="{{ $p->id }}">{{ $p->localized_name }}</option>
        @endforeach
      </select>
    </div>
    <div class="field-group"><label>{{ __('site.panel.admin.icon_label') }}</label><input type="text" name="icon" class="field" placeholder="🏠"></div>
    <div class="field-group">
      <label>{{ __('site.panel.admin.banner_label') }}</label>
      <input type="file" name="image" class="field" accept="image/*">
      <span style="font-size:11.5px; color:var(--ink-soft); display:block; margin-top:4px;">{{ __('site.panel.admin.banner_hint') }}</span>
    </div>
    <button type="submit" class="btn btn-primary">{{ __('site.panel.admin.submit_category') }}</button>
  </form>
</div>
@endsection
