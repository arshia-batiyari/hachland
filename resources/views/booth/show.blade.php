@extends('layouts.app')
@section('title', $booth->localized_name)
@section('meta_description', ($booth->description[app()->getLocale()] ?? $booth->description['fa'] ?? '').' — '.$booth->localized_name.' — '.$booth->city.', '.$booth->province)

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Store',
    'name' => $booth->localized_name,
    'description' => $booth->description[app()->getLocale()] ?? $booth->description['fa'] ?? null,
    'image' => $booth->banner_url,
    'url' => route('booth.show', $booth->slug),
    'address' => [
        '@type' => 'PostalAddress',
        'addressRegion' => $booth->province,
        'addressLocality' => $booth->city,
        'addressCountry' => 'IR',
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type'=>'ListItem','position'=>1,'name'=>__('site.nav.home'),'item'=>url('/')],
        ['@type'=>'ListItem','position'=>2,'name'=>$booth->categories->first()?->localized_name,'item'=>$booth->categories->first() ? route('category.show', $booth->categories->first()->slug) : url('/')],
        ['@type'=>'ListItem','position'=>3,'name'=>$booth->localized_name,'item'=>route('booth.show', $booth->slug)],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<div class="wrap">
  <div class="crumb">
    <a href="{{ route('home') }}">{{ __('site.nav.home') }}</a><span class="sep">/</span>
    <a href="{{ route('category.show', $booth->categories->first()?->slug) }}">{{ $booth->categories->first()?->localized_name }}</a><span class="sep">/</span>
    <span style="color:var(--ink);">{{ $booth->localized_name }}</span>
  </div>

  <div class="booth-banner">
    <img src="{{ $booth->banner_url }}" alt="{{ $booth->localized_name }}" loading="lazy">
    @if ($booth->categories->first())
      <div class="booth-banner-category">
        <span class="booth-banner-kicker">{{ $booth->categories->first()->localized_name }}</span>
      </div>
    @endif
    <div class="booth-banner-actions">
      <button type="button" class="btn-glass js-booth-share" data-title="{{ $booth->localized_name }}" data-url="{{ route('booth.show', $booth->slug) }}">
        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.6" y1="10.5" x2="15.4" y2="6.5"/><line x1="8.6" y1="13.5" x2="15.4" y2="17.5"/></svg>
        <span class="js-share-label">{{ __('site.common.share') }}</span>
      </button>
    </div>
  </div>

  <div class="booth-headbar">
    <div class="booth-id">
      <div class="booth-avatar">
        @if ($booth->logo_url)
          <img src="{{ $booth->logo_url }}" alt="{{ $booth->localized_name }}">
        @else
          {{ mb_substr($booth->localized_name, 0, 1) }}
        @endif
      </div>
      <div class="booth-id-text">
        <h1>{{ $booth->localized_name }}</h1>
        <div class="booth-meta">
          @if ($booth->online_payment_enabled)
            <span class="badge-online">{{ __('site.booth.online_payment_active') }}</span>
          @endif
          <span class="booth-meta-pill">{{ $booth->categories->pluck('localized_name')->implode(' · ') }}</span>
          <span class="booth-meta-pill booth-location">{{ $booth->city }}، {{ $booth->province }}</span>
        </div>
      </div>
    </div>
  </div>

  <div class="layout layout-booth">
    <main>
      @if ($booth->categories->count() > 1)
      <div class="subcats">
        <a href="{{ route('booth.show', $booth->slug) }}" class="chip {{ !request('category') ? 'on' : '' }}">{{ __('site.booth.all_products') }}</a>
        @foreach ($booth->categories as $cat)
          <a href="{{ route('booth.show', $booth->slug) }}?category={{ $cat->id }}" class="chip {{ request('category') == $cat->id ? 'on' : '' }}">{{ $cat->localized_name }}</a>
        @endforeach
      </div>
      @endif

      <div class="toolbar">
        <span class="result-txt">{{ $products->total() }} {{ __('site.common.product') }}</span>
        <form method="GET" class="sort">
          {{ __('site.booth.sort_by') }}
          <select name="sort" class="field" onchange="this.form.submit()" style="width:auto;">
            <option value="">{{ __('site.booth.sort_newest') }}</option>
            <option value="cheapest" {{ request('sort') == 'cheapest' ? 'selected' : '' }}>{{ __('site.booth.sort_cheapest') }}</option>
            <option value="expensive" {{ request('sort') == 'expensive' ? 'selected' : '' }}>{{ __('site.booth.sort_expensive') }}</option>
          </select>
        </form>
      </div>

      @if ($products->isEmpty())
        <div class="empty-state"><div class="icon">📦</div>{{ __('site.booth.no_products_yet') }}</div>
      @else
        <div class="prod-grid">
          @foreach ($products as $product)
            <div class="booth-card booth-product-card" data-product-slug="{{ $product->slug }}" style="position:relative;">
              <a href="{{ route('product.show', $product) }}" style="position:absolute; inset:0; z-index:1;" aria-label="مشاهده {{ $product->localized_name }}"></a>
              <div class="prod-media">
                <img src="{{ $product->image_url }}" alt="{{ $product->localized_name }}" class="media-img" loading="lazy">
                @if(($product->discount_percent ?? 0) > 0)
                  <span class="discount-badge">٪{{ $product->discount_percent }} تخفیف</span>
                @endif
                <span class="prod-badge" style="{{ $product->stock == 0 ? 'color:#9AA79B;' : '' }}">
                  {{ $product->stock == 0 ? __('site.common.unavailable') : ($product->stock <= 3 ? __('site.common.items_left', ['count' => $product->stock]) : __('site.common.available')) }}
                </span>
              </div>
              <div class="prod-body">
                <b>{{ $product->localized_name }}</b>
                <div class="prod-price-row">
                  <div class="prod-price">
                    @if($product->discount_percent > 0)
                      <del style="font-size:11px; margin-left:6px;">{{ number_format($product->price) }}</del>
                    @endif
                    {{ number_format($product->final_price) }}<small> {{ __('site.common.toman') }}</small>
                  </div>
                </div>
                @if($product->booth->online_payment_enabled && $product->stock > 0)
                  <form action="{{ route('product.cart.ajax', $product) }}" method="POST" class="js-add-to-cart-form" data-product-name="{{ $product->localized_name }}" style="position:relative; z-index:2; margin-top:10px;">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-primary" style="padding:6px 14px; font-size:12px; width:100%;">
                      {{ __('site.common.add_to_cart') }}
                    </button>
                  </form>
                @elseif($product->stock <= 0)
                  <div style="position:relative; z-index:2; margin-top:10px;">
                    <span class="status-pill cancelled" style="font-size:11px;">{{ __('site.common.unavailable') }}</span>
                  </div>
                @endif
              </div>
            </div>
          @endforeach
        </div>
        <div class="pagination">{{ $products->links() }}</div>
      @endif
    </main>

    <aside>
      <div class="filter-box">
        <h4>{{ __('site.booth.about_booth') }}</h4>
        <p style="font-size:13px; color:var(--ink-soft); margin-bottom:16px; line-height:1.8;">{{ $booth->description[app()->getLocale()] ?? $booth->description['fa'] ?? '' }}</p>
      </div>
      <div class="filter-box">
        <h4>{{ __('site.booth.booth_stats') }}</h4>
        <div class="booth-stat-grid">
          <div class="booth-stat-card">
            <span class="ico">📦</span>
            <div><b>{{ $booth->products()->count() }}</b><span>{{ __('site.common.products') }}</span></div>
          </div>
          <div class="booth-stat-card">
            <span class="ico">📅</span>
            <div><b>{{ jdate($booth->approved_at, 'Y') }}</b><span>{{ __('site.booth.member_since') }}</span></div>
          </div>
        </div>
      </div>
      @if ($booth->online_payment_enabled)
      <div class="trust-note">{{ __('site.booth.trust_note') }}</div>
      @endif
    </aside>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.booth-product-card').forEach(function(card) {
        card.style.cursor = 'pointer';
        card.addEventListener('click', function(e) {
            if (e.target.closest('form, button, a')) return;
            var slug = card.dataset.productSlug;
            if (slug) openProductModal(slug);
        });
    });

    // دکمه‌ی اشتراک‌گذاری — اول Web Share API بومی موبایل، اگر نبود کپی لینک
    var shareBtn = document.querySelector('.js-booth-share');
    if (shareBtn) {
        shareBtn.addEventListener('click', function() {
            var url = shareBtn.dataset.url;
            var title = shareBtn.dataset.title;
            if (navigator.share) {
                navigator.share({ title: title, url: url }).catch(function(){});
                return;
            }
            var label = shareBtn.querySelector('.js-share-label');
            var original = label.textContent;
            navigator.clipboard.writeText(url).then(function() {
                label.textContent = @json(__('site.common.link_copied'));
                setTimeout(function() { label.textContent = original; }, 2000);
            });
        });
    }
});
</script>
@endpush

@push('styles')
<style>
/* Booth page specific fix: shorter product cards */
.layout-booth .prod-grid .booth-card .prod-body b {
    font-size:14px;
    margin-bottom:4px;
    display:-webkit-box;
    -webkit-line-clamp:2;
    -webkit-box-orient:vertical;
    overflow:hidden;
}
.layout-booth .prod-grid .booth-card .prod-body {
    padding:12px 14px;
}

/* چیپ دسته‌بندی روی بنر — گوشه‌ی بالا (سمت مقابل دکمه‌ی اشتراک‌گذاری)،
   عمداً بالا و نه پایین بنر، چون پایین بنر با کارت سفید نام غرفه (که با
   مارجین منفی روی بنر می‌شینه) هم‌پوشانی داره و اونجا محو می‌شد. */
.booth-banner-category{ position:absolute; top:20px; right:40px; z-index:3; }

/* آمار غرفه — کارت‌های کوچک بجای ردیف‌های ساده */
.booth-stat-grid{ display:grid; grid-template-columns:1fr 1fr; gap:10px; }
.booth-stat-card{ display:flex; align-items:center; gap:10px; background:var(--bg); border:1px solid var(--border); border-radius:12px; padding:11px 12px; }
.booth-stat-card .ico{ width:32px; height:32px; border-radius:9px; background:var(--gradient-gold-soft); display:flex; align-items:center; justify-content:center; font-size:15px; flex-shrink:0; }
.booth-stat-card b{ display:block; font-size:14.5px; font-weight:800; color:var(--primary-dark); line-height:1.3; }
.booth-stat-card span{ font-size:10.5px; color:var(--ink-soft); }

/* موبایل: گرید محصولات غرفه دو‌ستونه بمونه (به‌جای تک‌ستونه)، خواناتره */
@media(max-width:560px){
  .layout-booth .prod-grid{ grid-template-columns:repeat(2,1fr); gap:10px; }
  .layout-booth .prod-grid .booth-card .prod-body{ padding:10px 11px; }
  .layout-booth .prod-grid .booth-card .prod-body b{ font-size:12.5px; }
  .layout-booth .prod-price{ font-size:13px; }
  .layout-booth .prod-grid .booth-card .prod-body form.js-add-to-cart-form .btn{ font-size:11px; padding:6px 10px; }
  .booth-banner-actions{ top:14px; left:14px; }
  .booth-banner-actions .btn-glass{ padding:7px 12px; font-size:11.5px; gap:5px; }
  .booth-banner-category{ top:14px; right:14px; }
  .booth-banner-kicker{ font-size:11px; padding:6px 12px; }
}

/* تو بازه‌ی تبلت (۵۶۰ تا ۹۰۰ پیکسل)، ستون کناری خودش دوستونه می‌شه (طبق
   قانون global .layout-booth > aside) — اگه کارت‌های آمار هم همزمان
   دوستونه بمونن، دوستونه‌ی توی دوستونه بیش از حد جمع‌وجور می‌شه؛ پس همین
   بازه، آمار موقتاً تک‌ستونه می‌مونه. زیر ۵۶۰ (ستون کناری خودش تک‌ستونه
   می‌شه) دوباره جا برای دوستونه باز می‌شه. */
@media(max-width:900px) and (min-width:561px){
  .booth-stat-grid{ grid-template-columns:1fr; }
}
</style>
@endpush
