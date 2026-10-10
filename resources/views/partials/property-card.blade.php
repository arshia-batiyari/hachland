<a href="{{ route('properties.show', $property) }}" class="listing-card">
  <div class="listing-media">
    <img src="{{ $property->first_image_url }}" alt="{{ $property->localized_title }}" loading="lazy">
    <span class="listing-badge">{{ $property->localized_deal_type }}</span>
  </div>
  <div class="listing-body">
    <h3>{{ $property->localized_title }}</h3>
    <div class="listing-meta">
      <span>{{ $property->localized_property_type }}</span>
      @if($property->area_sqm)
        <span>{{ number_format($property->area_sqm) }} {{ __('site.properties.area_suffix') }}</span>
      @endif
      @if($property->city)
        <span class="listing-pin">{{ $property->city }}@if($property->province) ، {{ $property->province }}@endif</span>
      @endif
    </div>
    <div class="listing-price">{{ $property->price ? number_format($property->price).' '.__('site.common.toman') : __('site.properties.price_on_request') }}</div>
  </div>
</a>
