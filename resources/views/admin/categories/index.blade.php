@extends('layouts.panel')
@section('title', __('site.sidebar.categories'))

@section('sidebar')
  @include('partials.admin-sidebar')
@endsection

@section('content')
<div class="topbar"><div><h1>{{ __('site.panel.admin.categories_title') }}</h1><p>{{ __('site.panel.admin.categories_sub') }}</p></div><a href="{{ route('admin.categories.create') }}" class="btn btn-primary">{{ __('site.panel.admin.new_category') }}</a></div>

<div class="panel">
  @forelse ($categories as $cat)
    <div class="tree-item" style="padding:14px 0;">
      <span style="font-size:14.5px; font-weight:600;">{{ $cat->icon ?? '📦' }} {{ $cat->localized_name }}</span>
      <span style="display:flex; align-items:center; gap:14px;">
        <span class="tree-count">{{ $cat->booths_count }} {{ __('site.common.booth') }}</span>
        <a href="{{ route('admin.categories.edit', $cat) }}" class="row-actions">{{ __('site.panel.edit') }}</a>
        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm(@json(__('site.panel.confirm_delete')))">
          @csrf @method('DELETE')
          <button type="submit" class="row-actions" style="background:none; border:none; cursor:pointer; color:var(--red);">{{ __('site.panel.delete') }}</button>
        </form>
      </span>
    </div>
    @if ($cat->children->isNotEmpty())
      <div class="tree-sub">
        @foreach ($cat->children as $sub)
          <div class="tree-item">
            <span>— {{ $sub->localized_name }}</span>
            <span style="display:flex; align-items:center; gap:14px;">
              <span class="tree-count">{{ $sub->booths()->count() }} {{ __('site.common.booth') }}</span>
              <a href="{{ route('admin.categories.edit', $sub) }}" class="row-actions">{{ __('site.panel.edit') }}</a>
              <form action="{{ route('admin.categories.destroy', $sub) }}" method="POST" onsubmit="return confirm(@json(__('site.panel.confirm_delete')))">
                @csrf @method('DELETE')
                <button type="submit" class="row-actions" style="background:none; border:none; cursor:pointer; color:var(--red);">{{ __('site.panel.delete') }}</button>
              </form>
            </span>
          </div>
        @endforeach
      </div>
    @endif
  @empty
    <div class="empty-state"><div class="icon">🗂</div>{{ __('site.panel.admin.no_categories_yet') }}</div>
  @endforelse
</div>
@endsection
