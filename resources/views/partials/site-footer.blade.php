<footer class="site">
  <div class="wrap">

    {{-- بنر همکاری با زمین‌دار/ملک‌دار — عمداً جدا و بالای بقیه‌ی فوتر، چون
         باید راحت و سریع به چشم بیاد، نه گم بشه لابه‌لای لینک‌های ریز --}}
    <div class="foot-cta">
      <div>
        <strong>{{ __('site.properties.cta_own_property') }}</strong>
        <span>{{ __('site.properties.cta_own_property_text') }}</span>
      </div>
      <div class="foot-cta-actions">
        <a href="{{ route('register.landowner') }}" class="btn btn-accent">{{ __('site.footer.register_property') }}</a>
        <a href="{{ route('properties.index') }}" class="btn btn-ghost-light">{{ __('site.footer.browse_properties') }}</a>
      </div>
    </div>

    <div class="foot-grid">
      <div>
        <div class="foot-logo"><img src="{{ asset('images/logo.png') }}" alt="{{ __('site.brand') }}">{{ __('site.brand') }}</div>
        <p style="font-size:13.5px; max-width:260px;">{{ __('site.footer.tagline') }}</p>
        <a href="tel:09111775006" class="foot-phone">
          <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          <span>{{ __('site.footer.phone_number') }}</span>
        </a>
        {{-- برای مخاطب‌های خارج از کشور که تماس تلفنی باهاشون سخته، یه
             راه ارتباطی با ایمیل هم زیر شماره تماس گذاشته شده --}}
        <a href="mailto:{{ __('site.footer.email_address') }}" class="foot-phone" style="margin-top:8px;">
          <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          <span>{{ __('site.footer.email_address') }}</span>
        </a>
      </div>
      <div class="foot-col">
        <h5>{{ __('site.footer.categories') }}</h5>
        @foreach (\App\Models\Category::roots()->where('is_active', true)->take(4)->get() as $cat)
          <a href="{{ route('category.show', $cat->slug) }}">{{ $cat->localized_name }}</a>
        @endforeach
      </div>
      <div class="foot-col">
        <h5>{{ __('site.footer.company') }}</h5>
        <a href="{{ route('about') }}">{{ __('site.footer.about') }}</a><a href="#">{{ __('site.footer.work_with_us') }}</a><a href="tel:09111775006">{{ __('site.footer.contact') }}</a>
      </div>
      <div class="foot-col">
        <h5>{{ __('site.footer.vendors') }}</h5>
        <a href="{{ route('register.vendor') }}">{{ __('site.footer.register_booth') }}</a><a href="#">{{ __('site.footer.seller_guide') }}</a><a href="#">{{ __('site.footer.settlement_rules') }}</a>
      </div>
      <div class="foot-col">
        <h5>{{ __('site.footer.landowners') }}</h5>
        <a href="{{ route('register.landowner') }}">{{ __('site.footer.register_property') }}</a><a href="{{ route('properties.index') }}">{{ __('site.footer.browse_properties') }}</a><a href="{{ route('materials.index') }}">{{ __('site.footer.browse_materials') }}</a>
      </div>
    </div>
    <div class="foot-bottom">
      <span>{{ __('site.footer.copyright', ['year' => jdate(now(), 'Y')]) }}</span>
      <a referrerpolicy='origin' target='_blank' href='https://trustseal.enamad.ir/?id=7767570&Code=gJui3uzBRfIysxASki5OVd4dtJpyEORH'><img referrerpolicy='origin' src='https://trustseal.enamad.ir/logo.aspx?id=7767570&Code=gJui3uzBRfIysxASki5OVd4dtJpyEORH' alt='' style='cursor:pointer' code='gJui3uzBRfIysxASki5OVd4dtJpyEORH'></a>
      <span>{{ __('site.footer.made_with') }} <a href="https://sitedooz.ir/"> سایت دوز </a></span>
    </div>
  </div>
</footer>