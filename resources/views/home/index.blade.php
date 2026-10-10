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

    <div class="hero-visual">
      <div class="hero-mosaic" id="heroMosaic">
        @foreach ($categories->take(5) as $cat)
          <a href="{{ route('category.show', $cat->slug) }}" class="hero-tile hero-tile-{{ $loop->index + 1 }}">
            <img src="{{ $cat->hero_image_url }}" alt="{{ $cat->localized_name }}" loading="lazy">
            <span class="hero-tile-overlay"></span>
            <div class="hero-tile-content">
              <span class="hero-tile-icon">{{ $cat->icon ?? '📦' }}</span>
              <strong>{{ $cat->localized_name }}</strong>
              <small>{{ $cat->display_booths_count }} {{ __('site.common.booth') }}</small>
            </div>
          </a>
        @endforeach
      </div>
      <div class="hero-mosaic-dots" id="heroMosaicDots"></div>
    </div>
  </div>
</section>

{{-- آمار — یک‌بار، شکیل، زیر هیرو --}}
<section class="hero-stats-band">
  <div class="wrap">
    <div class="hero-stats-grid" id="heroStatsGrid">
      <div class="hero-stat stat-booths"><div class="hero-stat-main"><span class="hero-stat-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9.5 4.5 4h15L21 9.5"/><path d="M3 9.5a2.5 2.5 0 0 0 5 0 2.5 2.5 0 0 0 5 0 2.5 2.5 0 0 0 5 0 2.5 2.5 0 0 0 5 0"/><path d="M5 9.5V20h14V9.5"/><path d="M9.5 20v-5.5h5V20"/></svg></span><b data-count="{{ $stats['booths'] }}" data-suffix="+">0</b></div><span>{{ __('site.home.stat_active_booths') }}</span></div>
      <div class="hero-stat stat-products"><div class="hero-stat-main"><span class="hero-stat-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8l9 5 9-5Z"/><path d="M3 8v8l9 5 9-5V8"/><path d="M12 13v8"/></svg></span><b data-count="{{ $stats['products'] }}" data-suffix="+">0</b></div><span>{{ __('site.home.stat_listed_products') }}</span></div>
      <div class="hero-stat stat-categories"><div class="hero-stat-main"><span class="hero-stat-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="8" height="8" rx="1.5"/><rect x="13" y="3" width="8" height="8" rx="1.5"/><rect x="3" y="13" width="8" height="8" rx="1.5"/><rect x="13" y="13" width="8" height="8" rx="1.5"/></svg></span><b data-count="{{ $stats['categories'] }}">0</b></div><span>{{ __('site.home.stat_main_categories') }}</span></div>
    </div>
  </div>
</section>

{{-- ---------- CATEGORIES ---------- --}}
<section class="section" id="categories">
  <div class="wrap">
    <div class="section-head">
      <div><h2>{{ __('site.home.categories_heading') }}</h2><p>{{ __('site.home.categories_sub') }}</p></div>
      <a href="{{ route('category.show', $categories->first()?->slug) }}" class="more-link">{{ __('site.home.all_booths') }}</a>
    </div>
    <div class="cat-rail">
      @foreach ($categories as $cat)
        <a href="{{ route('category.show', $cat->slug) }}" class="cat-item">
          <div class="cat-icon">{{ $cat->icon ?? '📦' }}</div>
          <b>{{ $cat->localized_name }}</b>
          <span>{{ $cat->display_booths_count }} {{ __('site.common.booth') }}</span>
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
    <div class="service-grid">
      <a href="{{ route('properties.index') }}" class="service-tile">
        <span class="service-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10.5 12 4l8 6.5"/><path d="M6 10v9h12v-9"/><path d="M10 19v-5h4v5"/></svg></span>
        <b>{{ __('site.home.service_properties') }}</b>
        <span>{{ __('site.home.service_properties_sub') }}</span>
      </a>
      <a href="{{ route('property-requests.index') }}" class="service-tile">
        <span class="service-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="6"/><path d="m20 20-3.5-3.5"/><path d="M11 8.5v5M8.5 11h5"/></svg></span>
        <b>{{ __('site.home.service_requests') }}</b>
        <span>{{ __('site.home.service_requests_sub') }}</span>
      </a>
      <a href="{{ route('register.landowner') }}" class="service-tile">
        <span class="service-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/><rect x="3" y="3" width="18" height="18" rx="4"/></svg></span>
        <b>{{ __('site.home.service_list') }}</b>
        <span>{{ __('site.home.service_list_sub') }}</span>
      </a>
      <a href="{{ route('materials.index') }}" class="service-tile">
        <span class="service-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V9l7-5 7 5v12"/><path d="M9 21v-6h6v6"/></svg></span>
        <b>{{ __('site.home.service_materials') }}</b>
        <span>{{ __('site.home.service_materials_sub') }}</span>
      </a>
      <a href="{{ route('category.show', 'building-materials') }}" class="service-tile">
        <span class="service-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8l9 5 9-5Z"/><path d="M3 8v8l9 5 9-5V8"/><path d="M12 13v8"/></svg></span>
        <b>{{ __('site.home.service_buy') }}</b>
        <span>{{ __('site.home.service_buy_sub') }}</span>
      </a>
      <a href="{{ route('materials.create') }}" class="service-tile">
        <span class="service-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M8 4h8l1 3H7l1-3Z"/><path d="M6 7h12v13H6z"/><path d="M12 11v6M9 14h6"/></svg></span>
        <b>{{ __('site.home.service_post') }}</b>
        <span>{{ __('site.home.service_post_sub') }}</span>
      </a>
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

    // ---------- اسلاید خودکار موزاییک دسته‌بندی‌ها تو موبایل ----------
    var mosaic = document.getElementById('heroMosaic');
    var dotsWrap = document.getElementById('heroMosaicDots');
    if (!mosaic) return;

    var cards = Array.prototype.slice.call(mosaic.children);
    if (cards.length < 2) return;

    var currentIndex = 0;
    var userInteracting = false;
    var resumeTimer = null;

    // نقطه‌های زیر کاروسل — نشون می‌دن کاربر الان رو کدوم اسلایده
    cards.forEach(function () {
        var dot = document.createElement('span');
        dotsWrap.appendChild(dot);
    });
    var dots = Array.prototype.slice.call(dotsWrap.children);

    function setActiveDot(index) {
        dots.forEach(function (d, i) { d.classList.toggle('active', i === index); });
        cards.forEach(function (c, i) { c.classList.toggle('is-active', i === index); });
    }
    setActiveDot(0);

    // با IntersectionObserver می‌فهمیم کدوم کارت الان واقعاً جلوی چشمه —
    // چه با اسکرول خودکار جابه‌جا بشه چه با سوایپ دستی کاربر، دقیق کار می‌کنه
    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting && entry.intersectionRatio > 0.6) {
                currentIndex = cards.indexOf(entry.target);
                setActiveDot(currentIndex);
            }
        });
    }, { root: mosaic, threshold: [0.6] });
    cards.forEach(function (c) { observer.observe(c); });

    function pauseAutoplay() {
        userInteracting = true;
        clearTimeout(resumeTimer);
        resumeTimer = setTimeout(function () { userInteracting = false; }, 4000);
    }
    mosaic.addEventListener('touchstart', pauseAutoplay, { passive: true });
    mosaic.addEventListener('pointerdown', pauseAutoplay);

    // مهم: اسلاید خودکار فقط وقتی خود کاروسل واقعاً تو دید کاربره باید کار کنه.
    // بدون این چک، وقتی کاربر پایین‌تر از هیرو اسکرول کرده، فراخوانی
    // scrollIntoView سعی می‌کنه کارت رو به هر قیمتی نشون بده و کل صفحه رو
    // به‌زور می‌کشه بالا — دقیقاً همون باگی که کاربر گزارش داد.
    var heroVisibleInPage = false;
    var pageVisibilityObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            heroVisibleInPage = entry.isIntersecting;
        });
    }, { threshold: 0.4 });
    pageVisibilityObserver.observe(mosaic);

    setInterval(function () {
        if (userInteracting) return;
        if (!heroVisibleInPage) return;
        if (window.innerWidth > 680) return; // فقط تو موبایل که به‌صورت کاروسل نمایش داده می‌شه
        var nextIndex = (currentIndex + 1) % cards.length;
        cards[nextIndex].scrollIntoView({ behavior: 'smooth', inline: 'start', block: 'nearest' });
    }, 3000);

    // ---------- انیمیشن شمارش اعداد نوار آمار، وقتی وارد دید کاربر می‌شه ----------
    var statsGrid = document.getElementById('heroStatsGrid');
    if (statsGrid) {
        var statNumbers = statsGrid.querySelectorAll('b[data-count]');
        var statsAnimated = false;

        var statsObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting && !statsAnimated) {
                    statsAnimated = true;
                    statNumbers.forEach(function (el) {
                        var target = parseInt(el.dataset.count, 10) || 0;
                        var suffix = el.dataset.suffix || '';
                        var start = performance.now();
                        var duration = 900;

                        function tick(now) {
                            var progress = Math.min((now - start) / duration, 1);
                            var eased = 1 - Math.pow(1 - progress, 3); // ease-out
                            el.textContent = Math.round(target * eased).toLocaleString('en-US') + (progress >= 1 ? suffix : '');
                            if (progress < 1) requestAnimationFrame(tick);
                        }
                        requestAnimationFrame(tick);
                    });
                    statsObserver.disconnect();
                }
            });
        }, { threshold: 0.4 });
        statsObserver.observe(statsGrid);
    }
});
</script>
@endpush