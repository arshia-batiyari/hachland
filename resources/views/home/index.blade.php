@extends('layouts.app')
@section('title', __('site.nav.home'))
@section('meta_description', __('site.home.subtitle'))

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => __('site.brand'),
    'url' => url('/'),
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => url('/search').'?q={search_term_string}',
        'query-input' => 'required name=search_term_string',
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')

{{-- ---------- HERO — CLEAN & BALANCED ---------- --}}
<section class="hero hero-home">
  <div class="wrap hero-shell">
    <div class="hero-copy">
      <div class="hero-kicker">{{ __('site.home.tagline') }}</div>
      <h1>{{ __('site.home.title') }}</h1>
      <p>{{ __('site.home.subtitle') }}</p>

      <form action="{{ route('search') }}" method="GET" class="hero-search">
        <input type="text" name="q" placeholder="{{ __('site.common.search_placeholder') }}">
        <button type="submit">{{ __('site.common.search') }}</button>
      </form>

      <div class="hero-actions">
        <a href="#categories" class="btn btn-primary">{{ __('site.nav.categories') }}</a>
        <a href="{{ route('register.vendor') }}" class="btn btn-ghost">{{ __('site.nav.register_booth') }}</a>
      </div>
    </div>
  </div>
</section>

<section class="section cat-board" id="categories" aria-label="{{ __('site.home.categories_heading') }}">
  <div class="wrap">
    <div class="cat-grid">
      @foreach ($categories as $cat)
        @php
          $tones = ['clay', 'teal', 'violet', 'blue', 'rose', 'green', 'amber', 'indigo'];
          $tone = match ($cat->slug) {
            'building-materials' => 'clay',
            'real-estate' => 'teal',
            'handicrafts' => 'violet',
            'automotive' => 'blue',
            'clothing' => 'rose',
            'agriculture' => 'green',
            'food' => 'amber',
            'electronics' => 'indigo',
            default => $tones[$loop->index % 8],
          };
          $sep = app()->getLocale() === 'en' ? ', ' : '، ';
          $names = $cat->children->take(3)->map(fn ($child) => $child->localized_name)->filter();
          $fallbackKey = 'site.home.category_fallback.'.$cat->slug;
          $fallback = __($fallbackKey);
          if ($fallback === $fallbackKey) {
            $fallback = __('site.home.category_fallback.default');
          }
          $blurb = $names->isNotEmpty() ? $names->implode($sep) : $fallback;
        @endphp
        <a class="cat-card tone-{{ $tone }}" href="{{ route('category.show', $cat->slug) }}" data-slug="{{ $cat->slug }}">
          <span class="cat-card-ico" aria-hidden="true">
            @include('partials.category-glyph', ['slug' => $cat->slug, 'emoji' => $cat->icon])
          </span>
          <b>{{ $cat->localized_name }}</b>
          <small>{{ $blurb }}</small>
        </a>
      @endforeach
    </div>
  </div>
</section>

{{-- ---------- املاک و ساخت‌وساز: کاشی خدمات، مثل ردیف سرویس صفحهٔ اصلی مرجع ---------- --}}
<section class="section home-block" id="real-estate">
  <div class="wrap">
    <div class="section-head">
      <div>
        <h2>{{ __('site.home.services_heading') }}</h2>
        <p>{{ __('site.home.services_sub') }}</p>
      </div>
    </div>
    <div class="estate-split">
      <div class="estate-col">
        <h3>{{ __('site.home.estate_col') }}</h3>
        <a href="{{ route('properties.index') }}" class="estate-cover">
          <img src="{{ asset('images/home/estate-property.jpg') }}" alt="">
          <span><b>{{ __('site.home.service_properties') }}</b><small>{{ __('site.home.service_properties_sub') }}</small></span>
        </a>
        <div class="estate-menu">
          <a href="{{ route('property-requests.index') }}" class="estate-line">
            <span class="estate-mark" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="6"/><path d="m20 20-3.5-3.5"/></svg></span>
            <span class="estate-copy"><b>{{ __('site.home.service_requests') }}</b><small>{{ __('site.home.service_requests_sub') }}</small></span>
            <svg class="estate-go" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 6-6 6 6 6"/></svg>
          </a>
          <a href="{{ route('register.landowner') }}" class="estate-line">
            <span class="estate-mark" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 5v14M5 12h14"/><rect x="4" y="4" width="16" height="16" rx="3"/></svg></span>
            <span class="estate-copy"><b>{{ __('site.home.service_list') }}</b><small>{{ __('site.home.service_list_sub') }}</small></span>
            <svg class="estate-go" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 6-6 6 6 6"/></svg>
          </a>
        </div>
      </div>
      <div class="estate-col">
        <h3>{{ __('site.home.build_col') }}</h3>
        <a href="{{ route('materials.index') }}" class="estate-cover">
          <img src="{{ asset('images/home/estate-build.jpg') }}" alt="">
          <span><b>{{ __('site.home.service_materials') }}</b><small>{{ __('site.home.service_materials_sub') }}</small></span>
        </a>
        <div class="estate-menu">
          <a href="{{ route('category.show', 'building-materials') }}" class="estate-line">
            <span class="estate-mark" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 8 12 3 3 8l9 5 9-5Z"/><path d="M3 8v8l9 5 9-5V8"/></svg></span>
            <span class="estate-copy"><b>{{ __('site.home.service_buy') }}</b><small>{{ __('site.home.service_buy_sub') }}</small></span>
            <svg class="estate-go" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 6-6 6 6 6"/></svg>
          </a>
          <a href="{{ route('materials.create') }}" class="estate-line">
            <span class="estate-mark" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 4h8l1 3H7l1-3Z"/><path d="M6 7h12v13H6z"/><path d="M12 11v6M9 14h6"/></svg></span>
            <span class="estate-copy"><b>{{ __('site.home.service_post') }}</b><small>{{ __('site.home.service_post_sub') }}</small></span>
            <svg class="estate-go" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 6-6 6 6 6"/></svg>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

@if($latestProperties->isNotEmpty())
<section class="section home-block">
  <div class="wrap">
    <div class="section-head">
      <div><h2>{{ __('site.home.latest_properties_heading') }}</h2><p>{{ __('site.home.latest_properties_sub') }}</p></div>
      <a href="{{ route('properties.index') }}" class="more-link">{{ __('site.common.view_all') }}</a>
    </div>
    @include('partials.swiper-open')
      @foreach($latestProperties as $property)
        <div class="h-swiper-item">@include('partials.property-card')</div>
      @endforeach
    @include('partials.swiper-close')
  </div>
</section>
@endif

@if($buildingProducts->isNotEmpty())
<section class="section home-block product-showcase">
  <div class="wrap">
    <div class="section-head">
      <div><h2>{{ __('site.home.building_products_heading') }}</h2><p>{{ __('site.home.building_products_sub') }}</p></div>
      <a href="{{ route('category.show', 'building-materials') }}" class="more-link">{{ __('site.common.view_all') }}</a>
    </div>
    @include('partials.swiper-open')
      @foreach($buildingProducts as $product)
        <div class="h-swiper-item">@include('partials.product-card')</div>
      @endforeach
    @include('partials.swiper-close')
  </div>
</section>
@endif

@if($materialRequests->isNotEmpty())
<section class="section home-block">
  <div class="wrap">
    <div class="section-head">
      <div><h2>{{ __('site.home.material_requests_heading') }}</h2><p>{{ __('site.home.material_requests_sub') }}</p></div>
      <a href="{{ route('materials.index') }}" class="more-link">{{ __('site.common.view_all') }}</a>
    </div>
    @include('partials.swiper-open')
      @foreach($materialRequests as $item)
        <div class="h-swiper-item">@include('partials.material-card')</div>
      @endforeach
    @include('partials.swiper-close')
  </div>
</section>
@endif

{{-- ---------- FEATURED BOOTHS ---------- --}}
<section class="section home-block" id="booths">
  <div class="wrap">
    <div class="section-head">
      <div><h2>{{ __('site.home.featured_booths_heading') }}</h2><p>{{ __('site.home.featured_booths_sub') }}</p></div>
      <a href="{{ route('category.show', $categories->first()?->slug) }}" class="more-link">{{ __('site.home.all_booths') }}</a>
    </div>
    @include('partials.swiper-open')
      @foreach ($featuredBooths as $booth)
        <div class="h-swiper-item">@include('partials.booth-flip-card')</div>
      @endforeach
    @include('partials.swiper-close')
  </div>
</section>

{{-- ---------- BUILDING MATERIALS BOOTHS ---------- --}}
@if($buildingBooths->isNotEmpty())
<section class="section home-block">
  <div class="wrap">
    <div class="section-head">
      <div><h2>{{ __('site.home.building_materials_heading') }}</h2><p>{{ __('site.home.building_materials_sub') }}</p></div>
      <a href="{{ route('category.show', 'building-materials') }}" class="more-link">{{ __('site.common.view_all') }}</a>
    </div>
    @include('partials.swiper-open')
      @foreach ($buildingBooths as $booth)
        <div class="h-swiper-item">@include('partials.booth-flip-card')</div>
      @endforeach
    @include('partials.swiper-close')
  </div>
</section>
@endif

{{-- ---------- PRODUCTS SHOWCASE ---------- --}}
@if($discountedProducts->isNotEmpty())
<section class="section home-block product-showcase special-showcase">
  <div class="wrap">
    <div class="section-head">
      <div><h2>{{ __('site.home.special_offers_heading') }}</h2><p>{{ __('site.home.special_offers_sub') }}</p></div>
    </div>
    @include('partials.swiper-open')
      @foreach($discountedProducts as $product)
        <div class="h-swiper-item">@include('partials.product-card')</div>
      @endforeach
    @include('partials.swiper-close')
  </div>
</section>
@endif

@if($randomProducts->isNotEmpty())
<section class="section home-block product-showcase curated-showcase">
  <div class="wrap">
    <div class="section-head">
      <div><h2>{{ __('site.home.curated_products_heading') }}</h2><p>{{ __('site.home.curated_products_sub') }}</p></div>
    </div>
    @include('partials.swiper-open')
      @foreach($randomProducts as $product)
        <div class="h-swiper-item">@include('partials.product-card')</div>
      @endforeach
    @include('partials.swiper-close')
  </div>
</section>
@endif

{{-- ---------- CTA ---------- --}}
<section class="section" style="padding-top:0;">
  <div class="wrap">
    <div class="cta-panel">
      <div>
        <h2>{{ __('site.home.cta_title') }}</h2>
        <p>{{ __('site.home.cta_text', ['count' => $stats['booths']]) }}</p>
      </div>
      <a href="{{ route('register.vendor') }}" class="btn btn-primary" style="background:#fff; color:var(--primary); border-color:#fff;">{{ __('site.home.cta_button') }}</a>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.product-card-advanced').forEach(function(card) {
        card.style.cursor = 'pointer';
        card.addEventListener('click', function(e) {
            if (e.target.closest('form, button, a')) return;
            var slug = card.dataset.productSlug;
            if (slug) openProductModal(slug);
        });
    });

});
</script>
@endpush