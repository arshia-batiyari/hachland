<header class="site">
  <div class="wrap nav">
    <a href="{{ route('home') }}" class="logo"><div class="logo-mark"><img src="{{ asset('images/logo.png') }}" alt="{{ __('site.brand') }}"></div>{{ __('site.brand') }}</a>

   <nav class="navlinks">
  <div class="nav-drawer-lang">
    <a href="{{ request()->fullUrlWithQuery(['lang' => 'fa']) }}"><span class="{{ app()->getLocale() === 'fa' ? 'active' : 'inactive' }}">فا</span></a>
    <a href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}"><span class="{{ app()->getLocale() === 'en' ? 'active' : 'inactive' }}">EN</span></a>
  </div>

  <div class="nav-drawer-divider"></div>

  <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'on' : '' }}">{{ __('site.nav.home') }}</a>
  <a href="{{ route('home') }}#categories">{{ __('site.nav.categories') }}</a>
  <a href="{{ route('home') }}#booths" class="{{ request()->routeIs('booth.show') ? 'on' : '' }}">{{ __('site.nav.booths') }}</a>
  <a href="{{ route('properties.index') }}" class="{{ request()->routeIs('properties.*') ? 'on' : '' }}">{{ __('site.nav.properties') }}</a>
  <a href="{{ route('materials.index') }}" class="{{ request()->routeIs('materials.*') ? 'on' : '' }}">{{ __('site.nav.materials') }}</a>
  <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'on' : '' }}">{{ __('site.nav.about') }}</a>
  <a href="#">{{ __('site.nav.contact') }}</a>

  <div class="nav-drawer-divider"></div>

  <div class="nav-drawer-account">
    @auth
      @if (auth()->user()->isAdmin())
        <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost">{{ __('site.nav.admin_panel') }}</a>
      @elseif (auth()->user()->isVendor())
        <a href="{{ route('vendor.dashboard') }}" class="btn btn-ghost">{{ __('site.nav.vendor_panel') }}</a>
      @elseif (auth()->user()->isLandowner())
        <a href="{{ route('landowner.dashboard') }}" class="btn btn-ghost">{{ __('site.nav.landowner_panel') }}</a>
      @else
        <a href="{{ route('customer.orders.index') }}" class="btn btn-ghost">{{ __('site.nav.my_orders') }}</a>
      @endif
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-primary">{{ __('site.nav.logout') }}</button>
      </form>
    @else
      <a href="{{ route('register.landowner') }}" class="btn btn-ghost">{{ __('site.nav.register_property') }}</a>
      <a href="{{ route('register.vendor') }}" class="btn btn-ghost">{{ __('site.nav.register_booth') }}</a>
      <a href="{{ route('login') }}" class="btn btn-primary">{{ __('site.nav.login') }}</a>
    @endauth
  </div>
</nav>

    <div class="navright">
      <div class="lang-toggle desktop-only-actions">
        <a href="{{ request()->fullUrlWithQuery(['lang' => 'fa']) }}"><span class="{{ app()->getLocale() === 'fa' ? 'active' : 'inactive' }}">فا</span></a>
        <a href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}"><span class="{{ app()->getLocale() === 'en' ? 'active' : 'inactive' }}">EN</span></a>
      </div>

      @if (!auth()->check() || auth()->user()->isCustomer())
        @php $cartCount = collect(session('cart', []))->sum(); @endphp
        <div class="header-actions">
          <a href="{{ route('checkout.show') }}" class="header-cart-btn">
            <svg viewBox="0 0 24 24" style="width:21px; height:21px; stroke:var(--ink); fill:none; stroke-width:1.7;"><path d="M6 6h15l-1.5 9h-12L4 3H2M9 20a1 1 0 100-2 1 1 0 000 2zm9 0a1 1 0 100-2 1 1 0 000 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
            @if ($cartCount > 0)
              <span class="header-cart-count">{{ $cartCount }}</span>
            @endif
          </a>
        </div>
      @endif

      {{-- دکمه منوی همبرگری موبایل عمداً همین‌جا، کنار سبد خرید، تو خود
           navright قرار گرفته (نه به‌صورت یه آیتم جدا تو نوار بالا) تا
           کنار هم بچسبن سمت چپ، نه اینکه با justify-content:space-between
           از هم فاصله بگیرن و همبرگر وسط صفحه شناور بمونه. --}}
      <button type="button" class="nav-toggle js-nav-toggle" aria-label="{{ __('site.nav.menu') }}" aria-expanded="false">
        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>

      <div class="desktop-only-actions">
        @auth
          @if (auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost">{{ __('site.nav.admin_panel') }}</a>
          @elseif (auth()->user()->isVendor())
            <a href="{{ route('vendor.dashboard') }}" class="btn btn-ghost">{{ __('site.nav.vendor_panel') }}</a>
          @elseif (auth()->user()->isLandowner())
            <a href="{{ route('landowner.dashboard') }}" class="btn btn-ghost">{{ __('site.nav.landowner_panel') }}</a>
          @else
            <a href="{{ route('customer.orders.index') }}" class="btn btn-ghost">{{ __('site.nav.my_orders') }}</a>
          @endif
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-primary">{{ __('site.nav.logout') }}</button>
          </form>
        @else
          <a href="{{ route('register.landowner') }}" class="btn btn-ghost">{{ __('site.nav.register_property') }}</a>
          <a href="{{ route('register.vendor') }}" class="btn btn-ghost">{{ __('site.nav.register_booth') }}</a>
          <a href="{{ route('login') }}" class="btn btn-primary">{{ __('site.nav.login') }}</a>
        @endauth
      </div>
    </div>
  </div>
  <div class="nav-scrim js-nav-scrim"></div>
</header>