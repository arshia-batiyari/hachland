@extends('layouts.app')
@section('title', $q !== '' ? __('site.search.title_for', ['q' => $q]) : __('site.search.title'))
@section('meta_description', __('site.search.title'))
{{-- صفحه نتایج جستجو معمولاً محتوای کم‌ارزش و متغیر داره؛ بهتره ایندکس نشه ولی لینک‌هاش دنبال بشه --}}
@section('robots', 'noindex, follow')

@section('content')
<div class="wrap" style="padding-bottom:80px;">
  <form action="{{ route('search') }}" method="GET" style="margin:24px 0 30px; max-width:520px; display:flex; gap:10px; background:var(--surface); border:1px solid var(--border); border-radius:12px; padding:6px;">
    <input type="text" name="q" value="{{ $q }}" placeholder="{{ __('site.common.search_placeholder') }}" autofocus style="flex:1; border:none; outline:none; background:transparent; font-family:inherit; font-size:14px; padding:10px 12px; color:var(--ink);">
    <button type="submit" style="background:var(--accent); color:#fff; border:none; border-radius:8px; padding:10px 20px; font-family:inherit; font-weight:600; font-size:14px; cursor:pointer;">{{ __('site.common.search') }}</button>
  </form>

  @if ($q === '')
    <div class="empty-state"><div class="icon">🔍</div>{{ __('site.search.prompt') }}</div>
  @else
    @if ($booths->isNotEmpty())
      <div class="section-head" style="padding-top:0;"><div><h2 style="font-size:18px;">{{ __('site.nav.booths') }}</h2></div></div>
      <div class="booth-grid" style="margin-bottom:44px;">
        @foreach ($booths as $booth)
          <a href="{{ route('booth.show', $booth->slug) }}" class="booth-card">
            <div class="booth-media">
              <img src="{{ $booth->banner_url }}" alt="{{ $booth->localized_name }}" class="media-img" loading="lazy">
              @if ($booth->online_payment_enabled)<span class="booth-badge">{{ __('site.common.online_payment') }}</span>@endif
            </div>
            <div class="booth-body">
              <div class="booth-top">
                <div class="booth-logo">
                  @if ($booth->logo_url)<img src="{{ $booth->logo_url }}" alt="{{ $booth->localized_name }}">@else{{ mb_substr($booth->localized_name, 0, 1) }}@endif
                </div>
                <div><h3>{{ $booth->localized_name }}</h3><span class="cat">{{ $booth->city }}</span></div>
              </div>
              <div class="booth-foot"><span class="products">{{ $booth->products_count }} {{ __('site.common.product') }}</span></div>
            </div>
          </a>
        @endforeach
      </div>
    @endif

    <div class="section-head" style="padding-top:0;"><div><h2 style="font-size:18px;">{{ __('site.common.products') }} ({{ $products->total() ?? 0 }})</h2></div></div>

    @if ($products->isEmpty())
      <div class="empty-state"><div class="icon">📦</div>{{ __('site.search.no_results_for', ['q' => $q]) }}</div>
    @else
      <div class="product-grid enhanced-grid">
        @foreach ($products as $product)
          <div class="product-card product-card-advanced" data-product-id="{{ $product->id }}" data-product-slug="{{ $product->slug }}">
            <div class="product-card-media">
              <img src="{{ $product->image_url }}" alt="{{ $product->localized_name }}" loading="lazy">
              @if(($product->discount_percent ?? 0) > 0)
                <span class="discount-badge">٪{{ $product->discount_percent }} تخفیف</span>
              @endif
              <span class="product-chip">{{ $product->stock > 0 ? __('site.common.available') : __('site.common.unavailable') }}</span>
            </div>
            <div class="product-card-content">
              <span class="product-booth-name">{{ $product->booth->localized_name }}</span>
              <b>{{ $product->localized_name }}</b>
              <small>{{ $product->category?->localized_name }}</small>
              <div class="product-price-stack">
                @if(($product->discount_percent ?? 0) > 0)
                  <del>{{ number_format($product->price) }} {{ __('site.common.toman') }}</del>
                @endif
                <strong>{{ number_format($product->final_price) }} {{ __('site.common.toman') }}</strong>
              </div>
              <span class="product-card-cta">بررسی جزئیات</span>
            </div>
          </div>
        @endforeach
      </div>
      <div class="pagination">{{ $products->links() }}</div>
    @endif
  @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.product-card-advanced').forEach(function(card) {
        card.style.cursor = 'pointer';
        card.addEventListener('click', function(e) {
            if (e.target.closest('a')) return;
            var slug = card.dataset.productSlug;
            if (slug) openProductModal(slug);
        });
    });
});
</script>
@endpush
