@extends('layouts.panel')
@section('title', __('site.panel.admin.edit_category_title'))

@section('sidebar')
  @include('partials.admin-sidebar')
@endsection

@section('content')
<div class="topbar"><div><h1>{{ __('site.panel.admin.edit_category_title') }}</h1></div></div>

<div class="panel" style="max-width:560px;">
  @if ($category->hero_image_url)
    <div style="margin-bottom:18px;">
      <img src="{{ $category->hero_image_url }}" alt="{{ $category->localized_name }}" style="width:100%; height:140px; object-fit:cover; border-radius:var(--radius-sm); border:1px solid var(--border);">
    </div>
  @endif

  <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="field-group"><label>{{ __('site.panel.admin.name_fa') }}</label><input type="text" name="name_fa" class="field" required value="{{ $category->name['fa'] ?? '' }}"></div>
    <div class="field-group"><label>{{ __('site.panel.admin.name_en') }}</label><input type="text" name="name_en" class="field" value="{{ $category->name['en'] ?? '' }}"></div>
    <div class="field-group"><label>{{ __('site.panel.admin.icon_label') }}</label><input type="text" name="icon" class="field" placeholder="🏠" value="{{ $category->icon }}"></div>
    <div class="field-group">
      <label>{{ __('site.panel.admin.banner_label') }}</label>
      <input type="file" name="image" class="field" accept="image/*">
      <span style="font-size:11.5px; color:var(--ink-soft); display:block; margin-top:4px;">{{ __('site.panel.admin.banner_hint') }}</span>
    </div>
    <label class="filter-opt" style="margin-bottom:20px;">
      <input type="hidden" name="is_active" value="0">
      <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}>
      {{ __('site.panel.admin.is_active_label') }}
    </label>
    <button type="submit" class="btn btn-primary">{{ __('site.common.save') }}</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost">{{ __('site.common.cancel') }}</a>
  </form>
</div>
@endsection
