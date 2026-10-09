<aside class="sidebar">
  <div class="side-logo"><div class="mark"><img src="{{ asset('images/logo.png') }}" alt="{{ __('site.brand') }}"></div>{{ __('site.brand') }}</div>

  <div class="side-section">{{ __('site.sidebar.main') }}</div>
  <a href="{{ route('landowner.dashboard') }}" class="side-link {{ request()->routeIs('landowner.dashboard') ? 'on' : '' }}"><span class="lft">🏡 {{ __('site.sidebar.my_properties') }}</span></a>
  <a href="{{ route('landowner.properties.create') }}" class="side-link {{ request()->routeIs('landowner.properties.create') ? 'on' : '' }}"><span class="lft">➕ {{ __('site.panel.landowner.add_property') }}</span></a>
  <a href="{{ route('properties.index') }}" class="side-link" target="_blank" rel="noopener"><span class="lft">🌐 {{ __('site.properties.nav_title') }}</span></a>

  <div class="side-bottom">
    <div class="side-user">
      <div class="side-avatar">{{ mb_substr(auth()->user()->name, 0, 2) }}</div>
      <div><b>{{ auth()->user()->name }}</b><span>{{ __('site.sidebar.landowner_role') }}</span></div>
    </div>
    <form method="POST" action="{{ route('logout') }}" style="margin-top:10px;">
      @csrf
      <button type="submit" class="side-link" style="width:100%; border:none; background:transparent; cursor:pointer; text-align:right;">{{ __('site.nav.logout') }}</button>
    </form>
  </div>
</aside>
