<a href="{{ route('product.show', $product) }}" class="flip-card">
  <div class="flip-media">
    <img src="{{ $product->image_url }}" alt="{{ $product->localized_name }}" loading="lazy">
  </div>
  <div class="flip-body">
    <h3>{{ $product->localized_name }}</h3>
    <div class="flip-loc">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 21s7-6.1 7-11a7 7 0 1 0-14 0c0 4.9 7 11 7 11Z"/><circle cx="12" cy="10" r="2.2"/></svg>
      <span>{{ $product->booth->localized_name }}</span>
    </div>
    <div class="flip-foot">
      <span class="flip-label">{{ __('site.common.amount') }}</span>
      <span class="flip-price">
        @if(($product->discount_percent ?? 0) > 0)
          <del>{{ number_format($product->price) }}</del>
        @endif
        {{ number_format($product->final_price) }} {{ __('site.common.toman') }}
      </span>
    </div>
  </div>
</a>
