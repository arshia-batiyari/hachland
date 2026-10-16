<a href="{{ route('booth.show', $booth->slug) }}" class="flip-card">
  <div class="flip-media">
    <img src="{{ $booth->banner_url }}" alt="{{ $booth->localized_name }}" loading="lazy">
  </div>
  <div class="flip-body">
    <h3>{{ $booth->localized_name }}</h3>
    @php $place = collect([$booth->province, $booth->city])->filter()->implode(' - '); @endphp
    @if($place !== '')
      <div class="flip-loc">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 21s7-6.1 7-11a7 7 0 1 0-14 0c0 4.9 7 11 7 11Z"/><circle cx="12" cy="10" r="2.2"/></svg>
        <span>{{ $place }}</span>
      </div>
    @endif
    <div class="flip-foot">
      <span class="flip-label">{{ $booth->categories->first()?->localized_name }}</span>
      <span class="flip-price">{{ $booth->products_count }} {{ __('site.common.product') }}</span>
    </div>
  </div>
</a>
