<a href="{{ route('properties.show', $property) }}" class="flip-card">
  <div class="flip-media">
    <img src="{{ $property->first_image_url }}" alt="{{ $property->localized_title }}" loading="lazy">
  </div>
  <div class="flip-body">
    <h3>{{ $property->localized_title }}</h3>
    @if($property->city || $property->province)
      <div class="flip-loc">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 21s7-6.1 7-11a7 7 0 1 0-14 0c0 4.9 7 11 7 11Z"/><circle cx="12" cy="10" r="2.2"/></svg>
        <span>{{ collect([$property->province, $property->city])->filter()->implode(' - ') }}</span>
      </div>
    @endif
    <div class="flip-foot">
      @if($property->price)
        <span class="flip-label">{{ __('site.common.amount') }}</span>
        <span class="flip-price">{{ number_format($property->price) }} {{ __('site.common.toman') }}</span>
      @else
        <span class="flip-label">{{ __('site.common.negotiable') }}</span>
      @endif
    </div>
  </div>
</a>
