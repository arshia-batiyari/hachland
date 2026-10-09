<aside class="sidebar">
  <div class="side-logo"><div class="mark"><img src="{{ asset('images/logo.png') }}" alt="{{ __('site.brand') }}"></div>{{ __('site.brand') }}</div>
  <div class="side-tag">{{ __('site.sidebar.admin_panel_tag') }}</div>

  <div class="side-section">{{ __('site.sidebar.main') }}</div>
  <a href="{{ route('admin.dashboard') }}" class="side-link {{ request()->routeIs('admin.dashboard') ? 'on' : '' }}"><span class="lft">📊 {{ __('site.sidebar.dashboard') }}</span></a>
  <a href="{{ route('admin.booths.index') }}" class="side-link {{ request()->routeIs('admin.booths.*') ? 'on' : '' }}">
    <span class="lft">🏬 {{ __('site.sidebar.booths') }}</span>
    @php $pendingCount = \App\Models\Booth::pending()->count(); @endphp
    @if ($pendingCount)<span class="side-count">{{ $pendingCount }}</span>@endif
  </a>
  <a href="{{ route('admin.categories.index') }}" class="side-link {{ request()->routeIs('admin.categories.*') ? 'on' : '' }}"><span class="lft">🗂 {{ __('site.sidebar.categories') }}</span></a>
  <a href="{{ route('admin.announcements.index') }}" class="side-link {{ request()->routeIs('admin.announcements.*') ? 'on' : '' }}"><span class="lft">📣 {{ __('site.sidebar.announcements') }}</span></a>
  <a href="{{ route('admin.properties.index') }}" class="side-link {{ request()->routeIs('admin.properties.*') ? 'on' : '' }}">
    <span class="lft">🏡 {{ __('site.sidebar.properties') }}</span>
    @php $pendingProperties = \App\Models\Property::pending()->count(); @endphp
    @if ($pendingProperties)<span class="side-count">{{ $pendingProperties }}</span>@endif
  </a>
  <a href="{{ route('admin.products.videos') }}" class="side-link {{ request()->routeIs('admin.products.*') ? 'on' : '' }}">
    <span class="lft">🎬 {{ __('site.sidebar.product_videos') }}</span>
    @php $pendingVideos = \App\Models\Product::where('video_status', 'pending')->count(); @endphp
    @if ($pendingVideos)<span class="side-count">{{ $pendingVideos }}</span>@endif
  </a>
  <a href="{{ route('admin.property-requests.index') }}" class="side-link {{ request()->routeIs('admin.property-requests.*') ? 'on' : '' }}">
    <span class="lft">🔍 {{ __('site.sidebar.property_requests') }}</span>
    @php $pendingPropertyRequests = \App\Models\PropertyRequest::pending()->count(); @endphp
    @if ($pendingPropertyRequests)<span class="side-count">{{ $pendingPropertyRequests }}</span>@endif
  </a>

  <div class="side-section">{{ __('site.sidebar.finance') }}</div>
  <a href="{{ route('admin.orders.index') }}" class="side-link {{ request()->routeIs('admin.orders.*') ? 'on' : '' }}">
    <span class="lft">🧾 {{ __('site.sidebar.orders') }}</span>
    @php $pendingOrders = \App\Models\Order::where('payment_status','pending')->count(); @endphp
    @if ($pendingOrders)<span class="side-count">{{ $pendingOrders }}</span>@endif
  </a>
  <a href="{{ route('admin.settlements.index') }}" class="side-link {{ request()->routeIs('admin.settlements.*') ? 'on' : '' }}">
    <span class="lft">💳 {{ __('site.sidebar.settlements') }}</span>
    @php $pendingSettle = \App\Models\Settlement::where('status','pending')->count(); @endphp
    @if ($pendingSettle)<span class="side-count">{{ $pendingSettle }}</span>@endif
  </a>
  <a href="{{ route('admin.wallets.index') }}" class="side-link {{ request()->routeIs('admin.wallets.*') ? 'on' : '' }}"><span class="lft">👛 {{ __('site.sidebar.wallets') }}</span></a>
  <a href="{{ route('admin.contracts.index') }}" class="side-link {{ request()->routeIs('admin.contracts.*') ? 'on' : '' }}">
    <span class="lft">📄 {{ __('site.sidebar.contracts') }}</span>
    @php $awaitingContracts = \App\Models\BoothContract::where('status', 'awaiting_signature')->count(); @endphp
    @if ($awaitingContracts)<span class="side-count">{{ $awaitingContracts }}</span>@endif
  </a>

  <div class="side-bottom">
    <div class="side-user">
      <div class="side-avatar">{{ __('site.sidebar.admin_avatar') }}</div>
      <div><b>{{ auth()->user()->name }}</b><span>{{ __('site.sidebar.full_access') }}</span></div>
    </div>
    <form method="POST" action="{{ route('logout') }}" style="margin-top:10px;">
      @csrf
      <button type="submit" class="side-link" style="width:100%; border:none; background:transparent; cursor:pointer; text-align:right;">{{ __('site.nav.logout') }}</button>
    </form>
  </div>
</aside>