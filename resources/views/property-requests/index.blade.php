@extends('layouts.app')
@section('title', __('site.property_requests.index_title'))
@section('meta_description', __('site.property_requests.meta_description'))

@section('content')
<div class="wrap">
  <div class="crumb">
    <a href="{{ route('home') }}">{{ __('site.nav.home') }}</a><span class="sep">/</span>
    <a href="{{ route('properties.index') }}">{{ __('site.properties.nav_title') }}</a><span class="sep">/</span>
    <span style="color:var(--ink);">{{ __('site.property_requests.nav_title') }}</span>
  </div>

  <div class="section-head" style="padding:20px 0 10px;">
    <div>
      <h1 style="font-size:32px; font-weight:800; color:var(--primary-dark);">{{ __('site.property_requests.index_title') }}</h1>
      <p>{{ __('site.property_requests.index_subtitle') }}</p>
    </div>
    <a href="{{ route('property-requests.create') }}" class="btn btn-primary">{{ __('site.property_requests.post_request') }}</a>
  </div>

  <div class="subcats">
    <a href="{{ route('property-requests.index') }}" class="chip {{ !request('type') ? 'on' : '' }}">{{ __('site.common.all') }}</a>
    @foreach (['land','apartment','villa','commercial','other'] as $type)
      <a href="{{ route('property-requests.index', array_merge(request()->except('page'), ['type' => $type])) }}" class="chip {{ request('type') === $type ? 'on' : '' }}">{{ __('site.properties.types.'.$type) }}</a>
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
          <h2 style="font-size:16px;">{{ __('site.property_requests.have_property_cta') }}</h2>
          <p style="font-size:12.5px;">{{ __('site.property_requests.have_property_cta_text') }}</p>
        </div>
        <a href="{{ route('properties.index') }}" class="btn btn-primary" style="background:#fff; color:var(--primary); border-color:#fff;">{{ __('site.properties.nav_title') }}</a>
      </div>
    </aside>

    <div>
      @if ($requests->isEmpty())
        <div class="empty-state"><p>{{ __('site.property_requests.no_results') }}</p></div>
      @else
        <div class="booth-grid">
          @foreach ($requests as $r)
            <a href="{{ route('property-requests.show', $r) }}" class="booth-card">
              <div class="booth-body" style="padding-top:20px;">
                <div class="booth-top">
                  <div class="booth-logo">🔍</div>
                  <div><h3>{{ $r->localized_title }}</h3><span class="cat">{{ $r->localized_property_type }} @if($r->city) · {{ $r->city }}@endif</span></div>
                </div>
                <p class="booth-desc">{{ \Illuminate\Support\Str::limit($r->localized_description, 90) }}</p>
                <div class="booth-foot">
                  <span class="products">{{ $r->area_range_label ? $r->area_range_label.' '.__('site.properties.area_suffix') : '' }}</span>
                  <span class="pay">{{ $r->localized_deal_type }}</span>
                </div>
              </div>
            </a>
          @endforeach
        </div>
        <div class="pagination">{{ $requests->links() }}</div>
      @endif
    </div>
  </div>
</div>
@endsection
