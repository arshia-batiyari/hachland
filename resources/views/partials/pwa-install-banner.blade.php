@php
  $isPanel = ($context ?? 'site') === 'panel';
@endphp
<div class="pwa-banner js-pwa-banner" data-dismiss-key="{{ $isPanel ? 'pwa_panel_dismissed_at' : 'pwa_site_dismissed_at' }}">
  <div class="pwa-banner-inner">
    <div class="pwa-banner-icon"><img src="{{ asset('images/logo.png') }}" alt=""></div>
    <div class="pwa-banner-text">
      <strong>{{ $isPanel ? __('site.pwa.panel_title') : __('site.pwa.site_title') }}</strong>
      <span class="pwa-banner-default-text">{{ $isPanel ? __('site.pwa.panel_subtitle') : __('site.pwa.site_subtitle') }}</span>
      <span class="pwa-banner-ios-text">{{ __('site.pwa.ios_instructions') }}</span>
    </div>
    <div class="pwa-banner-actions">
      <button type="button" class="btn btn-primary btn-sm js-pwa-install">{{ __('site.pwa.install_button') }}</button>
      <button type="button" class="pwa-banner-close js-pwa-dismiss" aria-label="{{ __('site.common.cancel') }}">&times;</button>
    </div>
  </div>
</div>
