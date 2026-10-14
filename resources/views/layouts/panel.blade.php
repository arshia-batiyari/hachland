<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'fa' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>@yield('title', __('site.nav.vendor_panel')) — {{ __('site.brand') }}</title>
<link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
<link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon-panel.png') }}">

{{-- PWA — نصب پنل مدیریت/فروشندگی/ملک روی گوشی یا سیستم به‌عنوان یک اپلیکیشن
     مستقل، جدا از سایت اصلی (آیکون و نام خودش رو داره). هر نقش مانیفست خودش
     رو داره چون start_url هر پنل فرق می‌کنه (/admin، /vendor، /landowner) —
     یه مانیفست مشترک با یه start_url ثابت برای بقیه‌ی نقش‌ها ۴۰۴ می‌داد. --}}
@php
  $panelManifest = 'panel-manifest.json';
  if (auth()->check()) {
    if (auth()->user()->isAdmin()) $panelManifest = 'panel-manifest-admin.json';
    elseif (auth()->user()->isVendor()) $panelManifest = 'panel-manifest-vendor.json';
    elseif (auth()->user()->isLandowner()) $panelManifest = 'panel-manifest-landowner.json';
  }
@endphp
<link rel="manifest" href="{{ asset($panelManifest) }}">
<meta name="theme-color" content="#100D09">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="{{ __('site.brand') }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Vazirmatn:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ @filemtime(public_path('css/app.css')) ?: time() }}">
<script src="{{ asset('js/pwa.js') }}?v={{ @filemtime(public_path('js/pwa.js')) ?: time() }}" defer></script>
<script>
  // یه شنونده سراسری رو خود document که همیشه زنده می‌مونه (چون با Turbo فقط
  // محتوای body عوض می‌شه، نه خود document) — با delegation، هر فرمی که کلاس
  // js-confirm-payment-ref داشته باشه رو تو کل پنل مدیریت می‌کنه، چه الان
  // رو صفحه باشه چه بعداً با ناوبری Turbo اضافه بشه.
  document.addEventListener('submit', function (e) {
    const form = e.target.closest('.js-confirm-payment-ref');
    if (!form) return;
    const ref = prompt(@json(__('site.panel.payment_ref_prompt')));
    if (!ref) { e.preventDefault(); return; }
    form.querySelector('input[name=payment_reference]').value = ref;
  });

  // منوی موبایلی سایدبار پنل — همون منطق delegation روی document که با هر
  // ناوبری Turbo (فقط تعویض <body>) نیازی به rebind نداره.
  document.addEventListener('click', function (e) {
    if (e.target.closest('.js-panel-nav-toggle')) {
      document.body.classList.toggle('panel-nav-open');
      return;
    }
    if (e.target.closest('.js-panel-scrim') || (e.target.closest('.sidebar a, .sidebar button') && document.body.classList.contains('panel-nav-open'))) {
      document.body.classList.remove('panel-nav-open');
    }
  });
</script>
@stack('styles')
</head>
<body>
<div class="app">
  @yield('sidebar')
  <div class="panel-scrim js-panel-scrim"></div>

  <main class="main">
    <button type="button" class="panel-nav-toggle js-panel-nav-toggle">
      <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
      {{ __('site.sidebar.menu') }}
    </button>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif
    @if ($errors->any())
      <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    @yield('content')
  </main>
</div>

@include('partials.pwa-install-banner', ['context' => 'panel'])

@stack('scripts')
</body>
</html>
