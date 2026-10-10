@extends('layouts.app')
@section('title', __('site.materials.index_title'))
@section('meta_description', __('site.materials.meta_description'))

@section('content')
<div class="wrap">
  <div class="crumb">
    <a href="{{ route('home') }}">{{ __('site.nav.home') }}</a><span class="sep">/</span>
    <a href="{{ route('properties.index') }}">{{ __('site.nav.properties') }}</a><span class="sep">/</span>
    <span style="color:var(--ink);">{{ __('site.materials.nav_title') }}</span>
  </div>

  <div class="section-head" style="padding:20px 0 10px;">
    <div>
      <h1 style="font-size:32px; font-weight:800; color:var(--primary-dark);">{{ __('site.materials.index_title') }}</h1>
      <p>{{ __('site.materials.index_subtitle') }}</p>
    </div>
    <a href="{{ route('materials.create') }}" class="btn btn-primary">{{ __('site.materials.post_request') }}</a>
  </div>

  <div class="subcats">
    <a href="{{ route('materials.index', request()->except('type', 'page')) }}" class="chip {{ !request('type') ? 'on' : '' }}">{{ __('site.common.all') }}</a>
    @foreach (\App\Models\MaterialRequest::TYPES as $type)
      <a href="{{ route('materials.index', array_merge(request()->except('page'), ['type' => $type])) }}" class="chip {{ request('type') === $type ? 'on' : '' }}">{{ __('site.materials.types.'.$type) }}</a>
    @endforeach
  </div>

  <div class="layout" style="padding-bottom:40px;">
    <aside>
      <form method="GET">
        @if (request('type'))<input type="hidden" name="type" value="{{ request('type') }}">@endif
        <div class="filter-box">
          <h4>{{ __('site.materials.fulfillment') }}</h4>
          <label class="filter-opt">
            <input type="radio" name="fulfillment" value="" {{ !request('fulfillment') ? 'checked' : '' }} onchange="this.form.submit()">
            {{ __('site.common.all') }}
          </label>
          @foreach (\App\Models\MaterialRequest::FULFILLMENTS as $fulfillment)
            <label class="filter-opt">
              <input type="radio" name="fulfillment" value="{{ $fulfillment }}" {{ request('fulfillment') === $fulfillment ? 'checked' : '' }} onchange="this.form.submit()">
              {{ __('site.materials.fulfillments.'.$fulfillment) }}
            </label>
          @endforeach
        </div>
        <div class="filter-box">
          <h4>{{ __('site.properties.filter_province') }}</h4>
          <select name="province" class="field" onchange="this.form.submit()">
            <option value="">{{ __('site.common.all') }}</option>
            @foreach ($provinces as $province)
              <option value="{{ $province }}" {{ request('province') === $province ? 'selected' : '' }}>{{ $province }}</option>
            @endforeach
          </select>
        </div>
      </form>

      <div class="cta-panel" style="padding:22px; flex-direction:column; align-items:flex-start; gap:10px;">
        <div>
          <h2 style="font-size:16px;">{{ __('site.materials.supplier_cta_title') }}</h2>
          <p style="font-size:12.5px;">{{ __('site.materials.supplier_cta_text') }}</p>
        </div>
        @auth
          @if (auth()->user()->isVendor())
            <a href="{{ route('vendor.materials.index') }}" class="btn btn-primary" style="background:#fff; color:var(--primary); border-color:#fff;">{{ __('site.materials.vendor_title') }}</a>
          @else
            <a href="{{ route('register.vendor') }}" class="btn btn-primary" style="background:#fff; color:var(--primary); border-color:#fff;">{{ __('site.materials.open_booth') }}</a>
          @endif
        @else
          <a href="{{ route('register.vendor') }}" class="btn btn-primary" style="background:#fff; color:var(--primary); border-color:#fff;">{{ __('site.materials.open_booth') }}</a>
        @endauth
      </div>
    </aside>

    <div>
      @if ($requests->isEmpty())
        <div class="empty-state"><p>{{ __('site.materials.no_results') }}</p></div>
      @else
        <div class="booth-grid">
          @foreach ($requests as $item)
            <a href="{{ route('materials.show', $item) }}" class="booth-card">
              <div class="booth-body" style="padding-top:20px;">
                <div class="booth-top">
                  <div class="booth-logo">🏗️</div>
                  <div>
                    <h3>{{ $item->localized_title }}</h3>
                    <span class="cat">{{ $item->localized_type }} @if($item->city) · {{ $item->city }}@endif</span>
                  </div>
                </div>
                <p class="booth-desc">{{ \Illuminate\Support\Str::limit($item->localized_description ?: $item->quantity_label, 90) }}</p>
                <div class="booth-foot">
                  <span class="products">{{ $item->quantity_label }}</span>
                  <span class="pay">{{ $item->localized_fulfillment }}</span>
                </div>
              </div>
            </a>
          @endforeach
        </div>
        <div class="pagination">{{ $requests->links() }}</div>
      @endif
    </div>
  </div>

  <div class="section-head">
    <div>
      <h2>{{ __('site.materials.suppliers_title') }}</h2>
      <p>{{ __('site.materials.suppliers_sub') }}</p>
    </div>
    <a href="{{ route('category.show', 'building-materials') }}" class="more-link">{{ __('site.common.view_all') }}</a>
  </div>

  @if ($suppliers->isEmpty())
    <div class="empty-state" style="margin-bottom:50px;"><p>{{ __('site.materials.no_suppliers') }}</p></div>
  @else
    <div class="booth-grid" style="padding-bottom:70px;">
      @foreach ($suppliers as $booth)
        <a href="{{ route('booth.show', $booth->slug) }}" class="booth-card">
          <div class="booth-media">
            <img src="{{ $booth->banner_url }}" alt="{{ $booth->localized_name }}" class="media-img" loading="lazy">
          </div>
          <div class="booth-body">
            <div class="booth-top">
              <div class="booth-logo">
                @if ($booth->logo_url)<img src="{{ $booth->logo_url }}" alt="{{ $booth->localized_name }}">@else{{ mb_substr($booth->localized_name, 0, 1) }}@endif
              </div>
              <div>
                <h3>{{ $booth->localized_name }}</h3>
                <span class="cat">{{ $booth->city }}@if($booth->city && $booth->province)،@endif {{ $booth->province }}</span>
              </div>
            </div>
            <div class="booth-foot">
              <span class="products">{{ $booth->products_count }} {{ __('site.common.product') }}</span>
              <span class="pay">{{ __('site.materials.view_booth') }}</span>
            </div>
          </div>
        </a>
      @endforeach
    </div>
  @endif
</div>
@endsection
