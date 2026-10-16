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
<div class="pwa-banner js-pwa-banner" data-dismiss-key="{{ $isPanel ? 'pwa_panel_dismissed_v2' : 'pwa_site_dismissed_v2' }}">
  <div class="pwa-banner-inner">
    <div class="pwa-banner-icon"><img src="{{ asset('images/logo.png') }}" alt=""></div>
    <div class="pwa-banner-text">
      <strong>{{ $pwaTitle }}</strong>
      <span class="pwa-banner-default-text">{{ $pwaSubtitle }}</span>
      <span class="pwa-banner-android-text"
        data-chrome="{{ __('site.pwa.android_instructions') }}"
        data-firefox="{{ __('site.pwa.firefox_instructions') }}"
        data-samsung="{{ __('site.pwa.samsung_instructions') }}"
        data-webview="{{ __('site.pwa.webview_instructions') }}">{{ __('site.pwa.android_instructions') }}</span>
      <span class="pwa-banner-ios-text">{{ __('site.pwa.ios_instructions') }}</span>
    </div>
    <div class="pwa-banner-actions">
      <button type="button" class="btn btn-primary btn-sm js-pwa-install">{{ __('site.pwa.install_button') }}</button>
      <a class="js-pwa-open-external" href="#">{{ __('site.pwa.open_in_chrome') }}</a>
      <button type="button" class="pwa-banner-close js-pwa-dismiss" aria-label="{{ __('site.common.cancel') }}">&times;</button>
    </div>
  </div>
</div>
