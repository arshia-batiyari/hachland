@switch($slug)
  @case('building-materials')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" aria-hidden="true"><path d="M3 9h8V4H3v5Zm10 0h8V4h-8v5ZM3 16h8v-5H3v5Zm10 0h8v-5h-8v5ZM3 21h8v-3H3v3Zm10 0h8v-3h-8v3Z"/></svg>
    @break
  @case('real-estate')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 11.2 12 4l8 7.2"/><path d="M6.5 10.2V20h11V10.2"/><path d="M10 20v-4.5h4V20"/></svg>
    @break
  @case('handicrafts')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 4.2c4.2 0 7.4 2.9 7.4 6.6 0 2-1.1 3-2.4 3h-1.5c-.7 0-1.2.5-1.2 1.2 0 1.5-1.1 2.5-2.6 2.5-4.1 0-7.1-3.2-7.1-6.7 0-3.7 3.3-6.6 7.4-6.6Z"/><circle cx="8.4" cy="9.2" r=".8" fill="currentColor" stroke="none"/><circle cx="11.2" cy="7.1" r=".8" fill="currentColor" stroke="none"/><circle cx="14.4" cy="7.4" r=".8" fill="currentColor" stroke="none"/><circle cx="16.2" cy="10.2" r=".8" fill="currentColor" stroke="none"/></svg>
    @break
  @case('automotive')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 15h16"/><path d="M6.2 15 7.6 9.6A2 2 0 0 1 9.5 8h5a2 2 0 0 1 1.9 1.6L17.8 15"/><path d="M5 15v2.2M19 15v2.2"/><circle cx="7.5" cy="17.4" r="1.5"/><circle cx="16.5" cy="17.4" r="1.5"/></svg>
    @break
  @case('clothing')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8.2 6.2 6.2 4.2 3.2 7.2l2.8 2.2V20h12V9.4l2.8-2.2-3-3-2 2"/><path d="M8.2 6.2c.7 1.6 2.1 2.5 3.8 2.5s3.1-.9 3.8-2.5"/></svg>
    @break
  @case('agriculture')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21V10"/><path d="M12 15c-4.2 0-6.5-2.4-6.5-5.4 3.4.1 6.5 2.2 6.5 5.4Z"/><path d="M12 13.2c4.2 0 6.5-2.2 6.5-5.2-3.4.1-6.5 2.2-6.5 5.2Z"/></svg>
    @break
  @case('food')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 11h14"/><path d="M6.4 11c.5 4.6 2.4 7.5 5.6 7.5s5.1-2.9 5.6-7.5"/><path d="M12 7.2c.2-1.8 1.3-3.2 3-4"/></svg>
    @break
  @case('electronics')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="4.5" width="16" height="11" rx="1.6"/><path d="M8 19.5h8"/><path d="M12 15.5v4"/></svg>
    @break
  @default
    @if(!empty($emoji))
      <span class="cat-emoji">{{ $emoji }}</span>
    @else
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="4" width="7" height="7" rx="1.5"/><rect x="13" y="4" width="7" height="7" rx="1.5"/><rect x="4" y="13" width="7" height="7" rx="1.5"/><rect x="13" y="13" width="7" height="7" rx="1.5"/></svg>
    @endif
@endswitch
