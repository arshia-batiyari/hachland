@php
  $isPanel = ($context ?? 'site') === 'panel';
  $pwaTitle = __('site.pwa.site_title');
  $pwaSubtitle = __('site.pwa.site_subtitle');
  if ($isPanel && auth()->check()) {
      if (auth()->user()->isVendor()) {
          $pwaTitle = __('site.pwa.vendor_title');
          $pwaSubtitle = __('site.pwa.vendor_subtitle');
      } elseif (auth()->user()->isLandowner()) {
          $pwaTitle = __('site.pwa.landowner_title');
          $pwaSubtitle = __('site.pwa.landowner_subtitle');
      } else {
          $pwaTitle = __('site.pwa.panel_title');
          $pwaSubtitle = __('site.pwa.panel_subtitle');
      }
  }
@endphp
<div class="pwa-banner js-pwa-banner" data-dismiss-key="{{ $isPanel ? 'pwa_panel_dismissed_at' : 'pwa_site_dismissed_at' }}">
  <div class="pwa-banner-inner">
    <div class="pwa-banner-icon"><img src="{{ asset('images/logo.png') }}" alt=""></div>
    <div class="pwa-banner-text">
      <strong>{{ $pwaTitle }}</strong>
      <span class="pwa-banner-default-text">{{ $pwaSubtitle }}</span>
      <span class="pwa-banner-android-text">{{ __('site.pwa.android_instructions') }}</span>
      <span class="pwa-banner-ios-text">{{ __('site.pwa.ios_instructions') }}</span>
    </div>
    <div class="pwa-banner-actions">
      <button type="button" class="btn btn-primary btn-sm js-pwa-install">{{ __('site.pwa.install_button') }}</button>
      <button type="button" class="pwa-banner-close js-pwa-dismiss" aria-label="{{ __('site.common.cancel') }}">&times;</button>
    </div>
  </div>
</div>
