<aside class="sidebar">
  <div class="side-logo"><div class="mark"><img src="{{ asset('images/logo.png') }}" alt="{{ __('site.brand') }}"></div>{{ __('site.brand') }}</div>

  <div class="side-section">{{ __('site.sidebar.main') }}</div>
  <a href="{{ route('vendor.dashboard') }}" class="side-link {{ request()->routeIs('vendor.dashboard') ? 'on' : '' }}"><span class="lft">📊 {{ __('site.sidebar.dashboard') }}</span></a>
  <a href="{{ route('vendor.products.index') }}" class="side-link {{ request()->routeIs('vendor.products.*') ? 'on' : '' }}"><span class="lft">📦 {{ __('site.sidebar.products') }}</span></a>
  <a href="{{ route('vendor.orders.index') }}" class="side-link {{ request()->routeIs('vendor.orders.*') ? 'on' : '' }}"><span class="lft">🧾 {{ __('site.sidebar.orders') }}</span></a>

  <div class="side-section">{{ __('site.sidebar.finance') }}</div>
  <a href="{{ route('vendor.settlements.index') }}" class="side-link {{ request()->routeIs('vendor.settlements.*') ? 'on' : '' }}"><span class="lft">💳 {{ __('site.sidebar.settlement') }}</span></a>

  <div class="side-bottom">
    <div class="side-user">
      <div class="side-avatar">{{ mb_substr(auth()->user()->booth->localized_name ?? auth()->user()->name, 0, 2) }}</div>
      <div><b>{{ auth()->user()->booth->localized_name ?? auth()->user()->name }}</b><span>{{ __('site.sidebar.vendor_role') }}</span></div>
    </div>
    <form method="POST" action="{{ route('logout') }}" style="margin-top:10px;">
      @csrf
      <button type="submit" class="side-link" style="width:100%; border:none; background:transparent; cursor:pointer; text-align:right;">{{ __('site.nav.logout') }}</button>
    </form>
  </div>
</aside>
