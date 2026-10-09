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

{{-- ---------- PROPERTY / LAND PROMO — عمداً همین‌جا، بالای صفحه، نه فقط تو
     هدر یا فوتر، چون قرار بود هر کسی زمین/ملک داره یا دنبالشه، بدون گشتن
     زود ببینتش --}}
<section class="section" style="padding-top:0;">
  <div class="wrap">
    <div class="property-promo">
      <div class="property-promo-icon">🏡</div>
      <div class="property-promo-text">
        <h2>{{ __('site.home.property_promo_heading') }}</h2>
        <p>{{ __('site.home.property_promo_sub') }}</p>
      </div>
      <div class="property-promo-actions">
        <a href="{{ route('properties.index') }}" class="btn btn-accent">{{ __('site.home.property_promo_browse') }}</a>
        <a href="{{ route('register.landowner') }}" class="btn btn-ghost-light">{{ __('site.home.property_promo_register') }}</a>
      </div>
    </div>
    <p style="text-align:center; font-size:13px; color:var(--ink-soft); margin-top:14px;">
      {{ __('site.home.property_request_teaser') }}
      <a href="{{ route('property-requests.create') }}" style="color:var(--primary); font-weight:700;">{{ __('site.home.property_request_teaser_link') }}</a>
    </p>
  </div>
</section>

{{-- ---------- FEATURED BOOTHS ---------- --}}
<section class="section" id="booths" style="padding-top:0;">
  <div class="wrap">
    <div class="section-head">
      <div><h2>{{ __('site.home.featured_booths_heading') }}</h2><p>{{ __('site.home.featured_booths_sub') }}</p></div>
      <a href="{{ route('category.show', $categories->first()?->slug) }}" class="more-link">{{ __('site.home.all_booths') }}</a>
    </div>
    <div class="booth-grid">
      @foreach ($featuredBooths as $booth)
        <a href="{{ route('booth.show', $booth->slug) }}" class="booth-card">
          <div class="booth-media">
            <img src="{{ $booth->banner_url }}" alt="{{ $booth->localized_name }}" class="media-img" loading="lazy">
            @if ($booth->online_payment_enabled)
              <span class="booth-badge">{{ __('site.common.online_payment') }}</span>
            @endif
          </div>
          <div class="booth-body">
            <div class="booth-top">
              <div class="booth-logo">
                @if ($booth->logo_url)<img src="{{ $booth->logo_url }}" alt="{{ $booth->localized_name }}">@else{{ mb_substr($booth->localized_name, 0, 1) }}@endif
              </div>
              <div><h3>{{ $booth->localized_name }}</h3><span class="cat">{{ $booth->categories->first()?->localized_name }}</span></div>
            </div>
            <p class="booth-desc">{{ \Illuminate\Support\Str::limit($booth->description[app()->getLocale()] ?? $booth->description['fa'] ?? '', 70) }}</p>
            <div class="booth-foot">
              <span class="products">{{ $booth->products_count }} {{ __('site.common.product') }}</span>
              @if ($booth->online_payment_enabled)
                <span class="pay">{{ __('site.common.buy_direct') }}</span>
              @endif
            </div>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>

{{-- ---------- BUILDING MATERIALS ---------- --}}
@if($buildingBooths->isNotEmpty())
<section class="section" style="padding-top:0;">
  <div class="wrap">
    <div class="section-head">
      <div><h2>{{ __('site.home.building_materials_heading') }}</h2><p>{{ __('site.home.building_materials_sub') }}</p></div>
      <a href="{{ route('category.show', 'building-materials') }}" class="more-link">{{ __('site.common.view_all') }}</a>
    </div>
    <div class="booth-grid">
      @foreach ($buildingBooths as $booth)
        <a href="{{ route('booth.show', $booth->slug) }}" class="booth-card">
          <div class="booth-media">
            <img src="{{ $booth->banner_url }}" alt="{{ $booth->localized_name }}" class="media-img" loading="lazy">
            @if ($booth->online_payment_enabled)
              <span class="booth-badge">{{ __('site.common.online_payment') }}</span>
            @endif
          </div>
          <div class="booth-body">
            <div class="booth-top">
              <div class="booth-logo">
                @if ($booth->logo_url)<img src="{{ $booth->logo_url }}" alt="{{ $booth->localized_name }}">@else{{ mb_substr($booth->localized_name, 0, 1) }}@endif
              </div>
              <div><h3>{{ $booth->localized_name }}</h3><span class="cat">{{ $booth->categories->first()?->localized_name }}</span></div>
            </div>
            <p class="booth-desc">{{ \Illuminate\Support\Str::limit($booth->description[app()->getLocale()] ?? $booth->description['fa'] ?? '', 70) }}</p>
            <div class="booth-foot">
              <span class="products">{{ $booth->products_count }} {{ __('site.common.product') }}</span>
              @if ($booth->online_payment_enabled)
                <span class="pay">{{ __('site.common.buy_direct') }}</span>
              @endif
            </div>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ---------- PRODUCTS SHOWCASE ---------- --}}
@if($discountedProducts->isNotEmpty())
<section class="section product-showcase special-showcase">
  <div class="wrap">
    <div class="section-head">
      <div><h2>{{ __('site.home.special_offers_heading') }}</h2><p>{{ __('site.home.special_offers_sub') }}</p></div>
    </div>
    <div class="product-grid enhanced-grid">
      @foreach($discountedProducts as $product)
        <div class="product-card product-card-advanced" data-product-id="{{ $product->id }}" data-product-slug="{{ $product->slug }}">
          <div class="product-card-media">
            <img src="{{ $product->image_url }}" alt="{{ $product->localized_name }}" loading="lazy">
            @if(($product->discount_percent ?? 0) > 0)
              <span class="discount-badge">{{ __('site.common.discount_off', ['percent' => $product->discount_percent]) }}</span>
            @endif
          </div>
          <div class="product-card-content">
            <a href="{{ route('product.show', $product) }}" style="font-size:10.5px; color:var(--accent); font-weight:600; text-transform:uppercase; letter-spacing:.3px; text-decoration:none;">{{ $product->booth->localized_name }}</a>
            <b>{{ $product->localized_name }}</b>
            <small>{{ $product->category?->localized_name }}</small>
            <div class="product-price-stack">
              @if(($product->discount_percent ?? 0) > 0)
                <del>{{ number_format($product->price) }} {{ __('site.common.toman') }}</del>
              @endif
              <strong>{{ number_format($product->final_price) }} {{ __('site.common.toman') }}</strong>
            </div>
            @if($product->stock > 0 && $product->booth->online_payment_enabled)
              <form action="{{ route('product.cart.ajax', $product) }}" method="POST" class="js-add-to-cart-form" data-product-name="{{ $product->localized_name }}" style="position:relative; z-index:2; margin-top:6px;">
                @csrf
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn btn-primary btn-sm" style="width:100%; font-size:11px; padding:6px 12px;">{{ __('site.common.add_to_cart') }}</button>
              </form>
            @endif
            <a href="{{ route('product.show', $product) }}" style="display:inline-block; margin-top:6px; font-size:11px; font-weight:600; color:var(--primary); text-decoration:none;">{{ __('site.common.view_details') }}</a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

@if($randomProducts->isNotEmpty())
<section class="section product-showcase curated-showcase" style="padding-top:0">
  <div class="wrap">
    <div class="section-head">
      <div><h2>{{ __('site.home.curated_products_heading') }}</h2><p>{{ __('site.home.curated_products_sub') }}</p></div>
    </div>
    <div class="product-grid enhanced-grid">
      @foreach($randomProducts as $product)
        <div class="product-card product-card-advanced" data-product-id="{{ $product->id }}" data-product-slug="{{ $product->slug }}">
          <div class="product-card-media">
            <img src="{{ $product->image_url }}" alt="{{ $product->localized_name }}" loading="lazy">
            <span class="product-chip">{{ $product->stock > 0 ? __('site.common.available') : __('site.common.unavailable') }}</span>
          </div>
          <div class="product-card-content">
            <a href="{{ route('product.show', $product) }}" style="font-size:10.5px; color:var(--accent); font-weight:600; text-transform:uppercase; letter-spacing:.3px; text-decoration:none;">{{ $product->booth->localized_name }}</a>
            <b>{{ $product->localized_name }}</b>
            <small>{{ $product->category?->localized_name }}</small>
            <div class="product-price-stack">
              <strong>{{ number_format($product->final_price) }} {{ __('site.common.toman') }}</strong>
            </div>
            @if($product->stock > 0 && $product->booth->online_payment_enabled)
              <form action="{{ route('product.cart.ajax', $product) }}" method="POST" class="js-add-to-cart-form" data-product-name="{{ $product->localized_name }}" style="position:relative; z-index:2; margin-top:6px;">
                @csrf
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn btn-primary btn-sm" style="width:100%; font-size:11px; padding:6px 12px;">{{ __('site.common.add_to_cart') }}</button>
              </form>
            @endif
            <a href="{{ route('product.show', $product) }}" style="display:inline-block; margin-top:6px; font-size:11px; font-weight:600; color:var(--primary); text-decoration:none;">{{ __('site.common.view_details') }}</a>
          </div>
        </div>
      @endforeach
    </div>
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