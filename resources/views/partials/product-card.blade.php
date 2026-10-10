<div class="product-card product-card-advanced" data-product-id="{{ $product->id }}" data-product-slug="{{ $product->slug }}">
  <a href="{{ route('product.show', $product) }}" class="product-card-media">
    <img src="{{ $product->image_url }}" alt="{{ $product->localized_name }}" loading="lazy">
    @if(($product->discount_percent ?? 0) > 0)
      <span class="discount-badge">{{ __('site.common.discount_off', ['percent' => $product->discount_percent]) }}</span>
    @endif
  </a>
  <div class="product-card-content">
    <span class="product-booth-name">{{ $product->booth->localized_name }}</span>
    <a href="{{ route('product.show', $product) }}" class="product-card-title">{{ $product->localized_name }}</a>
    @if($product->category)
      <small>{{ $product->category->localized_name }}</small>
    @endif
    <div class="product-price-stack">
      @if(($product->discount_percent ?? 0) > 0)
        <del>{{ number_format($product->price) }}</del>
      @endif
      <strong>{{ number_format($product->final_price) }} <small>{{ __('site.common.toman') }}</small></strong>
    </div>
    @if($product->stock > 0 && $product->booth->online_payment_enabled)
      <form action="{{ route('product.cart.ajax', $product) }}" method="POST" class="js-add-to-cart-form" data-product-name="{{ $product->localized_name }}">
        @csrf
        <input type="hidden" name="quantity" value="1">
        <button type="submit" class="btn btn-primary btn-sm product-card-buy">{{ __('site.common.add_to_cart') }}</button>
      </form>
    @endif
  </div>
</div>
