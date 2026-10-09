@extends('layouts.app')
@section('title', __('site.properties.index_title'))
@section('meta_description', __('site.properties.meta_description'))

@section('content')
<div class="wrap">
  <div class="crumb">
    <a href="{{ route('home') }}">{{ __('site.nav.home') }}</a><span class="sep">/</span>
    <span style="color:var(--ink);">{{ __('site.properties.nav_title') }}</span>
  </div>

  <div class="section-head" style="padding:20px 0 10px;">
    <div>
      <h1 style="font-size:32px; font-weight:800; color:var(--primary-dark);">{{ __('site.properties.index_title') }}</h1>
      <p>{{ __('site.properties.index_subtitle') }}</p>
    </div>
    <span style="font-size:13px; color:var(--ink-soft); background:var(--surface); border:1px solid var(--border); padding:8px 16px; border-radius:20px; white-space:nowrap;">{{ __('site.properties.results_count', ['count' => $properties->total()]) }}</span>
  </div>

  <div class="subcats">
    <a href="{{ route('properties.index') }}" class="chip {{ !request('type') ? 'on' : '' }}">{{ __('site.common.all') }}</a>
    @foreach (['land','apartment','villa','commercial','other'] as $type)
      <a href="{{ route('properties.index', array_merge(request()->except('page'), ['type' => $type])) }}" class="chip {{ request('type') === $type ? 'on' : '' }}">{{ __('site.properties.types.'.$type) }}</a>
    @endforeach
  </div>

  <div class="layout" style="padding-bottom:70px;">
    <aside>
      <form method="GET">
        <div class="filter-box">
          <h4>{{ __('site.properties.filter_deal') }}</h4>
          @foreach (['sale' => __('site.properties.deal_types.sale'), 'partnership' => __('site.properties.deal_types.partnership'), 'barter' => __('site.properties.deal_types.barter'), 'both' => __('site.properties.deal_types.both')] as $deal => $label)
            <label class="filter-opt">
              <input type="radio" name="deal" value="{{ $deal }}" {{ request('deal') === $deal ? 'checked' : '' }} onchange="this.form.submit()">
              {{ $label }}
            </label>
          @endforeach
        </div>

        <div class="filter-box">
          <h4>{{ __('site.properties.filter_province') }}</h4>
          <select name="province" class="field" onchange="this.form.submit()" style="margin-bottom:6px;">
            <option value="">{{ __('site.common.all') }}</option>
            @foreach ($provinces as $province)
              <option value="{{ $province }}" {{ request('province') === $province ? 'selected' : '' }}>{{ $province }}</option>
            @endforeach
          </select>
        </div>

        <div class="filter-box">
          <h4>{{ __('site.properties.filter_price') }}</h4>
          <div class="form-grid-2">
            <input type="number" name="min_price" class="field" placeholder="{{ __('site.properties.min_price') }}" value="{{ request('min_price') }}">
            <input type="number" name="max_price" class="field" placeholder="{{ __('site.properties.max_price') }}" value="{{ request('max_price') }}">
          </div>
          <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; margin-top:12px;">{{ __('site.properties.apply_filters') }}</button>
        </div>
      </form>

      <div class="filter-box" style="background:var(--primary-tint); border-color:var(--primary);">
        <h4>{{ __('site.property_requests.sidebar_teaser_title') }}</h4>
        <p style="font-size:12px; color:var(--ink-soft); margin-bottom:10px;">{{ __('site.property_requests.sidebar_teaser_text') }}</p>
        <a href="{{ route('property-requests.index') }}" class="btn btn-primary" style="width:100%; justify-content:center;">{{ __('site.property_requests.nav_title') }}</a>
      </div>
    </aside>

    <div>
      @if ($properties->isEmpty())
        <div class="empty-state"><p>{{ __('site.properties.no_results') }}</p></div>
      @else
        <div class="booth-grid">
          @foreach ($properties as $property)
            <a href="{{ route('properties.show', $property) }}" class="booth-card">
              <div class="booth-media">
                <img src="{{ $property->first_image_url }}" alt="{{ $property->localized_title }}" class="media-img" loading="lazy">
                <span class="booth-badge">{{ $property->localized_deal_type }}</span>
              </div>
              <div class="booth-body">
                <div class="booth-top">
                  <div class="booth-logo">{{ mb_substr($property->localized_title, 0, 1) }}</div>
                  <div><h3>{{ $property->localized_title }}</h3><span class="cat">{{ $property->localized_property_type }} · {{ $property->city }}</span></div>
                </div>
                <p class="booth-desc">{{ \Illuminate\Support\Str::limit($property->localized_description, 70) }}</p>
                <div class="booth-foot">
                  <span class="products">{{ $property->area_sqm ? number_format($property->area_sqm).' '.__('site.properties.area_suffix') : '' }}</span>
                  <span class="pay">{{ $property->price ? number_format($property->price).' '.__('site.common.toman') : __('site.properties.price_on_request') }}</span>
                </div>
              </div>
            </a>
          @endforeach
        </div>
        <div class="pagination">{{ $properties->links() }}</div>
      @endif
    </div>
  </div>

  <div class="cta-panel" style="margin-bottom:60px;">
    <div>
      <h2>{{ __('site.properties.cta_own_property') }}</h2>
      <p>{{ __('site.properties.cta_own_property_text') }}</p>
    </div>
    <a href="{{ route('register.landowner') }}" class="btn btn-primary" style="background:#fff; color:var(--primary); border-color:#fff;">{{ __('site.properties.cta_register') }}</a>
  </div>
</div>
@endsection
