<a href="{{ route('materials.show', $item) }}" class="flip-card">
  <div class="flip-media flip-media-empty" aria-hidden="true"></div>
  <div class="flip-body">
    <h3>{{ $item->localized_title }}</h3>
    @if($item->city || $item->province)
      <div class="flip-loc">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 21s7-6.1 7-11a7 7 0 1 0-14 0c0 4.9 7 11 7 11Z"/><circle cx="12" cy="10" r="2.2"/></svg>
        <span>{{ collect([$item->province, $item->city])->filter()->implode(' - ') }}</span>
      </div>
    @endif
    <div class="flip-foot">
      <span class="flip-label">{{ $item->quantity_label }}</span>
      @if($item->budget)
        <span class="flip-price">{{ number_format($item->budget) }} {{ __('site.common.toman') }}</span>
      @else
        <span class="flip-price">{{ $item->localized_type }}</span>
      @endif
    </div>
  </div>
</a>
