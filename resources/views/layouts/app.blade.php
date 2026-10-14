<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'fa' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

@php
  $seoTitle = trim(($__env->yieldContent('title') ?: '') . ' | ' . __('site.brand') . ' — ' . __('site.home.tagline'));
  $seoTitle = ltrim($seoTitle, '| ');
  $seoDescription = $__env->yieldContent('meta_description') ?: __('site.home.subtitle');
  $seoImage = $__env->yieldContent('meta_image') ?: asset('images/og-default.jpg');
@endphp

<title>{{ $seoTitle }}</title>
<meta name="description" content="{{ Str::limit(strip_tags($seoDescription), 160) }}">
<meta name="robots" content="{{ $__env->yieldContent('robots') ?: 'index, follow' }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
<link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">
<link rel="canonical" href="{{ url()->current() }}">

{{-- PWA — نصب روی گوشی/سیستم به‌عنوان یک اپلیکیشن مستقل --}}
<link rel="manifest" href="{{ asset('manifest.json') }}">
<meta name="theme-color" content="#100D09">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="{{ __('site.brand') }}">

@if (config('services.google.site_verification'))
<meta name="google-site-verification" content="{{ config('services.google.site_verification') }}">
@endif

{{-- Organization schema — تو همه صفحات سایت ثابته، به گوگل می‌گه «هاچ لند» دقیقاً
     مال همین سایته (نه هیچ کسب‌وکار دیگه‌ای با اسم مشابه)، و برای برندسازی
     جستجوی «هاچ لند» تو نتایج سرچ (و در آینده Knowledge Panel) کمک می‌کنه. --}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => __('site.brand'),
    'alternateName' => 'Hachland Marketplace',
    'url' => url('/'),
    'logo' => asset('images/logo.png'),
    'telephone' => '+989111775006',
    'description' => __('site.home.subtitle'),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

{{-- سایت دوزبانه‌ست؛ hreflang به گوگل کمک می‌کنه نسخه درست رو به کاربر هر زبان نشون بده --}}
<link rel="alternate" hreflang="fa" href="{{ url()->current() }}?lang=fa">
<link rel="alternate" hreflang="en" href="{{ url()->current() }}?lang=en">
<link rel="alternate" hreflang="x-default" href="{{ url()->current() }}">

{{-- Open Graph / Twitter Card — برای نمایش درست وقتی لینک تو تلگرام/واتساپ/توییتر شیر می‌شه --}}
<meta property="og:type" content="{{ $__env->yieldContent('og_type') ?: 'website' }}">
<meta property="og:site_name" content="هاچ لند">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($seoDescription), 200) }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ $seoImage }}">
<meta property="og:locale" content="{{ app()->getLocale() === 'fa' ? 'fa_IR' : 'en_US' }}">
<meta name="twitter:card" content="summary_large_image">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
{{-- فونت گوگل به‌صورت غیرمسدودکننده لود می‌شه: مرورگر باهاش رندر صفحه رو
     معطل نمی‌کنه، و به‌محض آماده شدن، خودش جایگزین می‌شه. برای مرورگرهای
     بدون جاوااسکریپت (نایاب) نسخه noscript هم به‌عنوان پشتیبان هست. --}}
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Vazirmatn:wght@400;500;600;700;800&display=swap" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Vazirmatn:wght@400;500;600;700;800&display=swap"></noscript>
<link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ @filemtime(public_path('css/app.css')) ?: time() }}">
<script src="{{ asset('js/pwa.js') }}" defer></script>

{{-- کش‌شدن صفحات با Turbo باعث باگ‌های ناسازگار بین ناوبری‌های AJAX‌محور و
     دکمه‌ی بازگشت مرورگر می‌شد (صفحه بعد از back خراب می‌موند تا رفرش دستی)،
     و چون خیلی از اسکریپت‌های صفحات فقط منتظر رویداد turbo:load بودن، اگه
     این اسکریپت به هر دلیلی (کندی شبکه، فیلترشدن CDN) دیر لود می‌شد یا لود
     نمی‌شد، خیلی از دکمه‌ها و تعامل‌های صفحه اصلاً کار نمی‌کردن. برای پایداری
     بیشتر حذف شد؛ سایت به‌صورت رندر سنتی سمت سرور کار می‌کنه.
--}}

<script>
  document.addEventListener('click', function (e) {
    if (e.target.closest('.js-nav-toggle')) {
      const opening = !document.body.classList.contains('nav-open');
      document.body.classList.toggle('nav-open', opening);
      const btn = document.querySelector('.js-nav-toggle');
      if (btn) btn.setAttribute('aria-expanded', opening ? 'true' : 'false');
      return;
    }
    if (e.target.closest('.js-nav-scrim') || (e.target.closest('.navlinks a') && document.body.classList.contains('nav-open'))) {
      document.body.classList.remove('nav-open');
    }
  });
</script>

@stack('schema')

{{-- Modal function globally available --}}
<script>
function openProductModal(slug) {
    fetch('/product/' + slug + '/modal')
        .then(r => {
            if (!r.ok) throw new Error('Network error');
            return r.json();
        })
        .then(data => {
            if (!data.success) return;
            var p = data.product;
            var images = (p.images && p.images.length > 0) ? p.images : [p.image_url];
            var mainImg = images[0];

            var gallery = document.getElementById('modalGallery');
            if (gallery) {
                gallery.innerHTML = '';
                images.forEach(function(img, i) {
                    var thumb = document.createElement('div');
                    thumb.className = 'modal-gallery-thumb' + (i === 0 ? ' active' : '');
                    thumb.innerHTML = '<img src="' + img + '" alt="" loading="lazy">';
                    thumb.addEventListener('click', function() {
                        gallery.querySelectorAll('.modal-gallery-thumb').forEach(function(t){t.classList.remove('active');});
                        thumb.classList.add('active');
                        document.getElementById('modalMainImg').innerHTML = '<img src="' + img + '" alt="' + p.name + '">';
                    });
                    gallery.appendChild(thumb);
                });
            }
            var mainImgEl = document.getElementById('modalMainImg');
            if (mainImgEl) mainImgEl.innerHTML = '<img src="' + mainImg + '" alt="' + p.name + '">';

            var hasDiscount = p.discount_percent > 0;
            var finalPrice = p.final_price || p.price;
            var priceHtml = hasDiscount ? '<del>' + p.price.toLocaleString() + '</del> <strong>' + finalPrice.toLocaleString() + '</strong>' : '<strong>' + p.price.toLocaleString() + '</strong>';

            var addToCartHtml = '';
            if (p.booth_online_payment && p.stock > 0) {
                addToCartHtml = '<div class="modal-qty"><button onclick="changeQty(-1)">-</button><input type="number" id="modalQty" value="1" min="1" max="' + p.stock + '"><button onclick="changeQty(1)">+</button></div>' +
                    '<button class="btn btn-primary" onclick="addToCart(\'' + slug + '\')">' + (p.add_to_cart_text || 'Add to cart') + '</button>';
            } else if (p.stock <= 0) {
                addToCartHtml = '<span class="status-pill cancelled">' + (p.unavailable_text || 'Unavailable') + '</span>';
            }

            var info = document.getElementById('modalInfo');
            if (info) {
                info.innerHTML =
                    '<a href="' + p.booth_url + '" style="font-size:13px; color:var(--accent); font-weight:600;">' + p.booth_name + '</a>' +
                    '<h2>' + p.name + '</h2>' +
                    '<div class="modal-price">' + priceHtml + ' ' + (p.toman_text || '') + '</div>' +
                    '<div style="font-size:13px; color:var(--ink-soft);">' + (p.booth_city || '') + ' · ' + (p.stock > 0 ? (p.stock_count_text || '') : (p.unavailable_text || '')) + '</div>' +
                    '<div class="modal-desc">' + (p.description || '').replace(/<[^>]*>/g, '') + '</div>' +
                    '<div class="modal-actions">' + addToCartHtml + '</div>';
            }

            var modal = document.getElementById('productModal');
            if (modal) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        })
        .catch(err => console.error('Modal error:', err));
}
function closeModal() {
    var modal = document.getElementById('productModal');
    if (modal) modal.classList.remove('active');
    document.body.style.overflow = '';
}
function changeQty(delta) {
    var input = document.getElementById('modalQty');
    if (!input) return;
    var val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (input.max) { var max = parseInt(input.max); if (val > max) val = max; }
    input.value = val;
}
function addToCart(slug) {
    var qtyEl = document.getElementById('modalQty');
    var qty = qtyEl ? qtyEl.value : 1;
    var nameEl = document.getElementById('modalInfo');
    var productName = nameEl ? (nameEl.querySelector('h2')?.textContent || '') : '';
    var token = document.querySelector('meta[name="csrf-token"]')?.content || '';
    fetch('/product/' + slug + '/cart-ajax', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token},
        body: JSON.stringify({quantity: qty})
    }).then(r => r.json())
    .then(data => {
        if (data.success) {
            closeModal();
            showCartAddedModal(productName, data.cart_count);
        } else {
            alert(data.message || @json(__('site.cart.add_error')));
        }
    }).catch(err => console.error('Cart error:', err));
}
</script>

@stack('styles')
</head>
<body>

@include('partials.announcement-bar')
@include('partials.site-header')

@if (session('success') || session('error'))
    <div class="wrap" style="padding-top:20px;">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
    </div>
@endif

@yield('content')

@include('partials.site-footer')

{{-- Global product modal (used on homepage and booth page) --}}
<div class="modal-overlay" id="productModal">
  <div class="modal-box">
    <button class="modal-close" id="modalCloseBtn" aria-label="بستن">&times;</button>
    <div class="wrap" style="padding:24px; padding-top:18px;">
      <div class="modal-gallery" id="modalGallery"></div>
      <div class="modal-main-img" id="modalMainImg"></div>
      <div class="modal-info" id="modalInfo"></div>
    </div>
  </div>
</div>

{{-- ---------- مودال «به سبد اضافه شد» ----------
     چون افزودن به سبد قبلاً یا هیچ فیدبکی نداشت یا فقط alert() خام مرورگر
     بود، این مودال مسیر ادامه‌ی خرید رو جلوی چشم کاربر می‌ذاره: یا ادامه‌ی
     خرید (بستن مودال) یا رفتن مستقیم به تسویه‌حساب. --}}
<div class="modal-overlay" id="cartAddedModal">
  <div class="modal-box" style="max-width:420px;">
    <button class="modal-close js-cart-modal-close" aria-label="بستن">&times;</button>
    <div style="padding:36px 28px 28px; text-align:center;">
      <div style="width:56px; height:56px; border-radius:50%; background:var(--accent-tint); color:var(--accent); display:flex; align-items:center; justify-content:center; font-size:28px; margin:0 auto 16px;">✓</div>
      <h3 style="font-size:16px; font-weight:800; color:var(--ink); margin-bottom:6px;">{{ __('site.cart.added_title') }}</h3>
      <p id="cartAddedProductName" style="font-size:13.5px; color:var(--ink-soft); margin-bottom:4px;"></p>
      <p id="cartAddedCount" style="font-size:12.5px; color:var(--ink-soft); margin-bottom:22px;"></p>
      <div style="display:flex; gap:10px;">
        <button type="button" class="btn btn-ghost js-cart-modal-close" style="flex:1; justify-content:center;">{{ __('site.cart.continue_shopping') }}</button>
        <a href="{{ route('checkout.show') }}" class="btn btn-primary" style="flex:1; justify-content:center;">{{ __('site.cart.go_to_checkout') }}</a>
      </div>
    </div>
  </div>
</div>

<script>
function showCartAddedModal(productName, cartCount) {
    var nameEl = document.getElementById('cartAddedProductName');
    var countEl = document.getElementById('cartAddedCount');
    if (nameEl) nameEl.textContent = productName || '';
    if (countEl && cartCount) countEl.textContent = @json(__('site.cart.items_in_cart_prefix')) + ' ' + cartCount + ' ' + @json(__('site.cart.items_in_cart_suffix'));
    var modal = document.getElementById('cartAddedModal');
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}
function closeCartAddedModal() {
    var modal = document.getElementById('cartAddedModal');
    if (modal) modal.classList.remove('active');
    document.body.style.overflow = '';
}
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.js-cart-modal-close').forEach(function (btn) {
        btn.addEventListener('click', closeCartAddedModal);
    });
    var modal = document.getElementById('cartAddedModal');
    if (modal) modal.addEventListener('click', function (e) {
        if (e.target === this) closeCartAddedModal();
    });

    // فرم‌های ساده‌ی «افزودن به سبد خرید» تو کارت‌های محصول (نه مودال quick-view)
    document.querySelectorAll('.js-add-to-cart-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var token = document.querySelector('meta[name="csrf-token"]')?.content || '';
            fetch(form.action, {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token, 'Accept': 'application/json'},
                body: JSON.stringify({quantity: (new FormData(form)).get('quantity') || 1})
            }).then(r => r.json())
              .then(data => {
                  if (data.success) {
                      showCartAddedModal(form.dataset.productName, data.cart_count);
                  } else {
                      alert(data.message || @json(__('site.cart.add_error')));
                  }
              })
              .catch(() => alert(@json(__('site.cart.add_error'))));
        });
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('modalCloseBtn');
    if (btn) btn.addEventListener('click', closeModal);
    var modal = document.getElementById('productModal');
    if (modal) modal.addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') { closeModal(); closeCartAddedModal(); }
    });
});
</script>

@include('partials.pwa-install-banner', ['context' => 'site'])

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-h-swiper]').forEach(function (root) {
        var track = root.querySelector('.h-swiper-track');
        var prev = root.querySelector('.h-swiper-prev');
        var next = root.querySelector('.h-swiper-next');
        if (!track) return;

        var rtl = getComputedStyle(track).direction === 'rtl';
        var max0 = track.scrollWidth - track.clientWidth;
        var mode = 'ltr';
        if (rtl) {
            mode = (max0 > 2 && track.scrollLeft > 1) ? 'reverse' : 'negative';
        }

        function cardStep() {
            var item = track.querySelector('.h-swiper-item');
            if (!item) return track.clientWidth;
            var styles = getComputedStyle(track);
            var gap = parseFloat(styles.columnGap || styles.gap) || 18;
            return Math.round(item.getBoundingClientRect().width + gap);
        }

        function fromStart() {
            var max = Math.max(0, track.scrollWidth - track.clientWidth);
            var left = track.scrollLeft;
            if (mode === 'ltr') return left;
            if (mode === 'reverse') return max - left;
            return Math.abs(left);
        }

        function atEnd() {
            var max = Math.max(0, track.scrollWidth - track.clientWidth);
            return fromStart() >= max - 2;
        }

        function goStart() {
            if (mode === 'reverse') track.scrollTo({ left: track.scrollWidth, behavior: 'smooth' });
            else track.scrollTo({ left: 0, behavior: 'smooth' });
        }

        function update() {
            var max = Math.max(0, track.scrollWidth - track.clientWidth);
            var idle = max < 4;
            if (prev) prev.disabled = idle || fromStart() <= 2;
            if (next) next.disabled = idle;
        }

        function scrollDir(dir) {
            var sign = mode === 'ltr' ? 1 : -1;
            if (dir > 0 && atEnd()) { goStart(); return; }
            track.scrollBy({ left: sign * dir * cardStep(), behavior: 'smooth' });
        }

        var paused = false;
        var visible = false;
        var dragging = false;
        var moved = false;
        var startX = 0;
        var startScroll = 0;

        if (prev) prev.addEventListener('click', function () { paused = true; scrollDir(-1); window.setTimeout(function () { paused = false; }, 4000); });
        if (next) next.addEventListener('click', function () { paused = true; scrollDir(1); window.setTimeout(function () { paused = false; }, 4000); });

        track.addEventListener('dragstart', function (e) { e.preventDefault(); });

        track.addEventListener('pointerdown', function (e) {
            if (e.pointerType !== 'mouse' || e.button !== 0) return;
            if (e.target.closest('button')) return;
            dragging = true;
            moved = false;
            startX = e.clientX;
            startScroll = track.scrollLeft;
            paused = true;
        });

        track.addEventListener('pointermove', function (e) {
            if (!dragging || e.pointerType !== 'mouse') return;
            var dx = e.clientX - startX;
            if (Math.abs(dx) < 4) return;
            if (!moved) {
                moved = true;
                track.classList.add('is-dragging');
                if (track.setPointerCapture) track.setPointerCapture(e.pointerId);
            }
            track.scrollLeft = startScroll - dx;
        });

        function endDrag() {
            if (!dragging) return;
            dragging = false;
            track.classList.remove('is-dragging');
            window.setTimeout(function () { paused = false; }, 4000);
        }
        track.addEventListener('pointerup', endDrag);
        track.addEventListener('pointercancel', endDrag);
        track.addEventListener('click', function (e) {
            if (!moved) return;
            e.preventDefault();
            e.stopPropagation();
            moved = false;
        }, true);

        if (window.matchMedia('(hover: hover)').matches) {
            root.addEventListener('mouseenter', function () { paused = true; });
            root.addEventListener('mouseleave', function () { if (!dragging) paused = false; });
        }

        var seen = new IntersectionObserver(function (entries) {
            visible = entries.some(function (entry) { return entry.isIntersecting; });
        }, { threshold: 0.45 });
        seen.observe(track);

        window.setInterval(function () {
            if (paused || !visible || dragging) return;
            if (document.hidden) return;
            if (track.scrollWidth - track.clientWidth < 8) return;
            scrollDir(1);
        }, 4000);

        track.addEventListener('scroll', update, { passive: true });
        window.addEventListener('resize', update);
        update();
    });
});
</script>

@stack('scripts')
</body>
</html>