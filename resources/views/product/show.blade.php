@extends('layouts.app')
@section('title', $product->localized_name.' | '.$product->booth->localized_name)
@section('meta_description', Str::limit(($product->description[app()->getLocale()] ?? $product->description['fa'] ?? $product->localized_name), 155))

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => $product->localized_name,
    'description' => $product->description[app()->getLocale()] ?? $product->description['fa'] ?? $product->localized_name,
    'image' => $product->image_url,
    'sku' => $product->sku,
    'brand' => ['@type' => 'Brand', 'name' => $product->booth->localized_name],
    'offers' => [
        '@type' => 'Offer',
        'url' => route('product.show', $product),
        'priceCurrency' => 'IRR',
        'price' => $product->price * 10,
        'availability' => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
        'itemCondition' => 'https://schema.org/NewCondition',
        'seller' => ['@type' => 'Organization', 'name' => $product->booth->localized_name],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type'=>'ListItem','position'=>1,'name'=>__('site.nav.home'),'item'=>url('/')],
        ['@type'=>'ListItem','position'=>2,'name'=>$product->booth->localized_name,'item'=>route('booth.show', $product->booth->slug)],
        ['@type'=>'ListItem','position'=>3,'name'=>$product->localized_name,'item'=>route('product.show', $product)],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<div class="wrap">
  <div class="crumb">
    <a href="{{ route('home') }}">{{ __('site.nav.home') }}</a><span class="sep">/</span>
    <a href="{{ route('booth.show', $product->booth->slug) }}">{{ $product->booth->localized_name }}</a><span class="sep">/</span>
    <span style="color:var(--ink);">{{ $product->localized_name }}</span>
  </div>

  <div class="product-detail-grid">
    {{-- ---------- IMAGE GALLERY ---------- --}}
    <div class="product-detail-gallery">
      @if($product->images->isNotEmpty())
        <div class="gallery-main" id="galleryMain">
          <img src="{{ $product->images->first()->image_url }}" alt="{{ $product->localized_name }}" id="galleryMainImg">
        </div>
        <div class="gallery-thumbs">
          @foreach($product->images as $img)
            <div class="gallery-thumb {{ $loop->first ? 'active' : '' }}" data-type="image" data-src="{{ $img->image_url }}">
              <img src="{{ $img->image_url }}" alt="{{ $product->localized_name }}" loading="lazy">
            </div>
          @endforeach
          @if($product->video_url)
            <div class="gallery-thumb gallery-thumb-video" data-type="video" data-src="{{ $product->video_url }}">
              <img src="{{ $product->images->first()->image_url }}" alt="" loading="lazy">
              <span class="gallery-thumb-play">▶</span>
            </div>
          @endif
        </div>
      @else
        <div class="gallery-main" id="galleryMain">
          <img src="{{ $product->image_url }}" alt="{{ $product->localized_name }}" id="galleryMainImg">
        </div>
        @if($product->video_url)
          <div class="gallery-thumbs">
            <div class="gallery-thumb active" data-type="image" data-src="{{ $product->image_url }}">
              <img src="{{ $product->image_url }}" alt="" loading="lazy">
            </div>
            <div class="gallery-thumb gallery-thumb-video" data-type="video" data-src="{{ $product->video_url }}">
              <img src="{{ $product->image_url }}" alt="" loading="lazy">
              <span class="gallery-thumb-play">▶</span>
            </div>
          </div>
        @endif
      @endif
    </div>

    {{-- ---------- PRODUCT INFO ---------- --}}
    <div class="product-detail-info">
      <a href="{{ route('booth.show', $product->booth->slug) }}" style="font-size:13px; color:var(--accent); font-weight:600;">{{ $product->booth->localized_name }}</a>
      <h1 style="font-size:26px; font-weight:800; margin:10px 0 16px; letter-spacing:-0.3px;">{{ $product->localized_name }}</h1>

      <div style="font-size:28px; font-weight:800; color:var(--primary-dark); margin-bottom:20px;">
        @if($product->discount_percent > 0)
          <del style="font-size:18px; color:var(--ink-soft); margin-left:10px;">{{ number_format($product->price) }} {{ __('site.common.toman') }}</del>
        @endif
        {{ number_format($product->final_price) }} <small style="font-size:14px; font-weight:500; color:var(--ink-soft);">{{ __('site.common.toman') }}</small>
      </div>

      <div style="display:flex; gap:16px; margin-bottom:20px; font-size:13px; color:var(--ink-soft);">
        <span>{{ $product->category?->localized_name ?? '' }}</span>
        <span>·</span>
        <span>{{ $product->stock > 0 ? $product->stock.' عدد موجود' : 'ناموجود' }}</span>
      </div>

      <div style="font-size:15px; color:var(--ink); line-height:2; margin-bottom:30px;">{!! nl2br(e($product->description[app()->getLocale()] ?? $product->description['fa'] ?? '')) !!}</div>

      {{-- ---------- ADD TO CART ---------- --}}
      @if ($product->stock > 0)
        @if ($product->booth->online_payment_enabled)
          <form action="{{ route('product.cart.ajax', $product) }}" method="POST" class="js-add-to-cart-form" data-product-name="{{ $product->localized_name }}" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            @csrf
            <div style="display:flex; align-items:center; border:1px solid var(--border); border-radius:10px; overflow:hidden;">
              <button type="button" onclick="this.parentElement.querySelector('input').value = Math.max(1, parseInt(this.parentElement.querySelector('input').value)-1)" style="width:40px; height:40px; border:none; background:var(--bg); cursor:pointer; font-size:16px; font-weight:700; color:var(--ink);">-</button>
              <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" style="width:50px; height:40px; border:none; border-left:1px solid var(--border); border-right:1px solid var(--border); text-align:center; font-family:inherit; font-size:16px; font-weight:600; background:var(--surface); color:var(--ink); outline:none;">
              <button type="button" onclick="this.parentElement.querySelector('input').value = Math.min({{ $product->stock }}, parseInt(this.parentElement.querySelector('input').value)+1)" style="width:40px; height:40px; border:none; background:var(--bg); cursor:pointer; font-size:16px; font-weight:700; color:var(--ink);">+</button>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('site.common.add_to_cart') }}</button>
          </form>
          <p style="font-size:12px; color:var(--ink-soft); margin-top:10px;">{{ __('site.common.items_left', ['count' => $product->stock]) }}</p>
        @else
          <div class="alert" style="background:var(--bg); border:1px solid var(--border); color:var(--ink-soft); font-size:13px; padding:14px 16px; border-radius:var(--radius-sm);">
            {{ __('site.common.online_purchase_unavailable') }}
          </div>
        @endif
      @else
        <span class="status-pill cancelled" style="font-size:14px; padding:10px 20px;">{{ __('site.common.unavailable') }}</span>
      @endif

      @if ($related->isNotEmpty())
      <div style="margin-top:48px;">
        <h4 style="font-size:16px; font-weight:700; margin-bottom:16px;">{{ __('site.common.similar_products') }}</h4>
        <div class="similar-grid">
          @foreach ($related as $r)
            <a href="{{ route('product.show', $r) }}" class="similar-card">
              <div>
                <span>{{ $r->localized_name }}</span>
                <small>{{ $r->booth->localized_name }}</small>
              </div>
              <b>{{ number_format($r->price) }} {{ __('site.common.toman') }}</b>
            </a>
          @endforeach
        </div>
      </div>
      @endif
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // گالری: کلیک روی بندانگشتی — چون یکی از بندانگشتی‌ها می‌تونه ویدیو باشه
    // (نه عکس)، به‌جای فقط عوض‌کردن src یه <img>، کل محتوای قاب اصلی رو
    // بسته به نوع (عکس/ویدیو) می‌سازیم.
    var galleryMain = document.getElementById('galleryMain');
    document.querySelectorAll('.gallery-thumb').forEach(function(thumb) {
        thumb.addEventListener('click', function() {
            document.querySelectorAll('.gallery-thumb').forEach(function(t){t.classList.remove('active');});
            thumb.classList.add('active');
            if (!galleryMain) return;
            if (thumb.dataset.type === 'video') {
                galleryMain.innerHTML = '<video id="galleryMainImg" src="' + thumb.dataset.src + '" controls playsinline preload="metadata"></video>';
            } else {
                galleryMain.innerHTML = '<img id="galleryMainImg" src="' + thumb.dataset.src + '" alt="">';
            }
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.gallery-main{width:100%; aspect-ratio:3/4; border-radius:var(--radius); overflow:hidden; background:var(--primary-tint); margin-bottom:12px;}
.gallery-main img,.gallery-main video{width:100%; height:100%; object-fit:cover; background:#000;}
.gallery-thumbs{display:flex; gap:10px; overflow-x:auto;}
.gallery-thumb{width:72px; height:72px; border-radius:10px; overflow:hidden; cursor:pointer; border:2px solid transparent; transition:all 0.2s; flex-shrink:0; position:relative;}
.gallery-thumb:hover,.gallery-thumb.active{border-color:var(--primary);}
.gallery-thumb img{width:100%; height:100%; object-fit:cover;}
.gallery-thumb-video img{filter:brightness(.55);}
.gallery-thumb-play{position:absolute; inset:0; display:flex; align-items:center; justify-content:center; color:#fff; font-size:16px; text-shadow:0 2px 6px rgba(0,0,0,.5);}
</style>
@endpush