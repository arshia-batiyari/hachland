<a href="{{ route('materials.show', $item) }}" class="listing-card listing-card-plain">
  <div class="listing-media listing-media-plain">
    <span class="listing-plain-icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V8l7-5 7 5v13"/><path d="M9 21v-6h6v6"/></svg>
    </span>
    <span class="listing-badge">{{ $item->localized_fulfillment }}</span>
  </div>
  <div class="listing-body">
    <h3>{{ $item->localized_title }}</h3>
    <div class="listing-meta">
      <span>{{ $item->localized_type }}</span>
      @if($item->quantity_label)
        <span>{{ $item->quantity_label }}</span>
      @endif
      @if($item->city)
        <span class="listing-pin">{{ $item->city }}@if($item->province) ، {{ $item->province }}@endif</span>
      @endif
    </div>
    <div class="listing-price">{{ $item->budget ? number_format($item->budget).' '.__('site.common.toman') : $item->localized_type }}</div>
  </div>
</a>
