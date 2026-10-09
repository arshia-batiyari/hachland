@extends('layouts.app')
@section('title', __('site.about.meta_title'))
@section('meta_description', __('site.about.meta_description'))

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type'=>'ListItem','position'=>1,'name'=>__('site.nav.home'),'item'=>url('/')],
        ['@type'=>'ListItem','position'=>2,'name'=>__('site.nav.about'),'item'=>route('about')],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')

{{-- ---------- HERO ---------- --}}
<section class="section" style="padding-bottom:0;">
  <div class="wrap" style="max-width:760px; text-align:center;">
    <div class="hero-kicker" style="justify-content:center;">{{ __('site.about.kicker') }}</div>
    <h1 style="font-size:34px; color:var(--primary-dark); margin:14px 0 18px;">{{ __('site.about.heading') }}</h1>
    <p style="font-size:15px; line-height:2.1; color:var(--ink-soft);">{{ __('site.about.intro') }}</p>
  </div>
</section>

{{-- ---------- STATS (همون طراحی صفحه اصلی) ---------- --}}
<section class="hero-stats-band">
  <div class="wrap">
    <div class="hero-stats-grid" id="aboutStatsGrid">
      <div class="hero-stat stat-booths"><div class="hero-stat-main"><span class="hero-stat-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9.5 4.5 4h15L21 9.5"/><path d="M3 9.5a2.5 2.5 0 0 0 5 0 2.5 2.5 0 0 0 5 0 2.5 2.5 0 0 0 5 0 2.5 2.5 0 0 0 5 0"/><path d="M5 9.5V20h14V9.5"/><path d="M9.5 20v-5.5h5V20"/></svg></span><b data-count="{{ $stats['booths'] }}" data-suffix="+">0</b></div><span>{{ __('site.home.stat_active_booths') }}</span></div>
      <div class="hero-stat stat-products"><div class="hero-stat-main"><span class="hero-stat-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8l9 5 9-5Z"/><path d="M3 8v8l9 5 9-5V8"/><path d="M12 13v8"/></svg></span><b data-count="{{ $stats['products'] }}" data-suffix="+">0</b></div><span>{{ __('site.home.stat_listed_products') }}</span></div>
      <div class="hero-stat stat-categories"><div class="hero-stat-main"><span class="hero-stat-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="8" height="8" rx="1.5"/><rect x="13" y="3" width="8" height="8" rx="1.5"/><rect x="3" y="13" width="8" height="8" rx="1.5"/><rect x="13" y="13" width="8" height="8" rx="1.5"/></svg></span><b data-count="{{ $stats['categories'] }}">0</b></div><span>{{ __('site.home.stat_main_categories') }}</span></div>
    </div>
  </div>
</section>

{{-- ---------- چرا هاچ لند ---------- --}}
<section class="section">
  <div class="wrap">
    <div class="section-head" style="justify-content:center; text-align:center; flex-direction:column;">
      <h2>{{ __('site.about.values_heading') }}</h2>
    </div>
    <div class="value-grid">
      <div class="value-card">
        <span class="value-card-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 3h5v5"/><path d="M8 21H3v-5"/><path d="M21 3 13 11"/><path d="M3 21l8-8"/></svg></span>
        <b>{{ __('site.about.value_1_title') }}</b>
        <p>{{ __('site.about.value_1_text') }}</p>
      </div>
      <div class="value-card">
        <span class="value-card-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="9"/></svg></span>
        <b>{{ __('site.about.value_2_title') }}</b>
        <p>{{ __('site.about.value_2_text') }}</p>
      </div>
      <div class="value-card">
        <span class="value-card-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg></span>
        <b>{{ __('site.about.value_3_title') }}</b>
        <p>{{ __('site.about.value_3_text') }}</p>
      </div>
      <div class="value-card">
        <span class="value-card-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg></span>
        <b>{{ __('site.about.value_4_title') }}</b>
        <p>{{ __('site.about.value_4_text') }}</p>
      </div>
    </div>
  </div>
</section>

{{-- ---------- دسته‌بندی‌ها (همون کامپوننت صفحه اصلی) ---------- --}}
<section class="section" style="padding-top:0;">
  <div class="wrap">
    <div class="section-head">
      <div><h2>{{ __('site.about.categories_heading') }}</h2><p>{{ __('site.about.categories_sub') }}</p></div>
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

{{-- ---------- CTA ثبت غرفه ---------- --}}
<section class="section" style="padding-top:0;">
  <div class="wrap">
    <div class="cta-panel" style="text-align:center;">
      <h2>{{ __('site.about.cta_heading') }}</h2>
      <p>{{ __('site.about.cta_text') }}</p>
      <a href="{{ route('register.vendor') }}" class="btn btn-white" style="margin-top:16px;">{{ __('site.about.cta_button') }}</a>
    </div>
  </div>
</section>

@endsection

@push('styles')
<style>
  .value-grid{ display:grid; grid-template-columns:repeat(4,1fr); gap:20px; }
  .value-card{ background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); padding:26px 22px; text-align:center; }
  .value-card-ico{ width:44px; height:44px; border-radius:12px; background:var(--primary); color:#fff; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; position:relative; }
  .value-card-ico svg{ position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:20px; height:20px; }
  .value-card b{ display:block; font-size:15px; color:var(--primary-dark); margin-bottom:8px; }
  .value-card p{ font-size:13px; line-height:1.9; color:var(--ink-soft); }
  @media(max-width:900px){ .value-grid{grid-template-columns:repeat(2,1fr);} }
  @media(max-width:560px){ .value-grid{grid-template-columns:1fr;} }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var statsGrid = document.getElementById('aboutStatsGrid');
    if (!statsGrid) return;
    var statNumbers = statsGrid.querySelectorAll('b[data-count]');
    var statsObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            statNumbers.forEach(function (el) {
                var target = parseInt(el.dataset.count, 10) || 0;
                var suffix = el.dataset.suffix || '';
                var start = performance.now();
                var duration = 900;
                function tick(now) {
                    var progress = Math.min((now - start) / duration, 1);
                    var eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.round(target * eased).toLocaleString('en-US') + (progress >= 1 ? suffix : '');
                    if (progress < 1) requestAnimationFrame(tick);
                }
                requestAnimationFrame(tick);
            });
            statsObserver.disconnect();
        });
    }, { threshold: 0.4 });
    statsObserver.observe(statsGrid);
});
</script>
@endpush