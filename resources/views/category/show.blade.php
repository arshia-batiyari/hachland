@extends('layouts.app')
@section('title', $category->localized_name)
@section('meta_description', ($category->description[app()->getLocale()] ?? $category->description['fa'] ?? $category->localized_name))
{{-- ترکیب فیلترها (زیردسته/فقط‌آنلاین) و صفحه‌های بعدی pagination محتوای تکراری با نسخه اصلی این دسته دارن؛
     برای جلوگیری از افت رتبه به‌خاطر محتوای تکراری، این حالت‌ها ایندکس نمی‌شن ولی لینک‌هاشون دنبال می‌شه --}}
@if (request('sub') || request('online_only') || request('page', 1) > 1)
@section('robots', 'noindex, follow')
@endif

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type'=>'ListItem','position'=>1,'name'=>__('site.nav.home'),'item'=>url('/')],
        ['@type'=>'ListItem','position'=>2,'name'=>__('site.nav.categories'),'item'=>url('/').'/#categories'],
        ['@type'=>'ListItem','position'=>3,'name'=>$category->localized_name,'item'=>route('category.show', $category->slug)],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<div class="wrap">
  <div class="crumb">
    <a href="{{ route('home') }}">{{ __('site.nav.home') }}</a><span class="sep">/</span>
    <a href="{{ route('home') }}#categories">{{ __('site.nav.categories') }}</a><span class="sep">/</span>
    <span style="color:var(--ink);">{{ $category->localized_name }}</span>
  </div>

  <div class="section-head" style="padding:20px 0 10px;">
    <div>
      <h1 style="font-size:32px; font-weight:800; color:var(--primary-dark);">{{ $category->localized_name }}</h1>
      <p>{{ $category->description[app()->getLocale()] ?? $category->description['fa'] ?? '' }}</p>
    </div>
    <span style="font-size:13px; color:var(--ink-soft); background:var(--surface); border:1px solid var(--border); padding:8px 16px; border-radius:20px; white-space:nowrap;">{{ __('site.category.booths_in_category', ['count' => $booths->total()]) }}</span>
  </div>

  @if ($category->children->isNotEmpty())
  <div class="subcats">
    <a href="{{ route('category.show', $category->slug) }}" class="chip {{ !request('sub') ? 'on' : '' }}">{{ __('site.common.all') }}</a>
    @foreach ($category->children as $sub)
      <a href="{{ route('category.show', $category->slug) }}?sub[]={{ $sub->id }}" class="chip {{ in_array($sub->id, (array) request('sub', [])) ? 'on' : '' }}">{{ $sub->localized_name }}</a>
    @endforeach
  </div>
  @endif

  <div class="layout" style="padding-bottom:70px;">
    <aside>
      <form method="GET">
        @if ($category->children->isNotEmpty())
        <div class="filter-box">
          <h4>{{ __('site.category.subcategory') }}</h4>
          @foreach ($category->children as $sub)
            <label class="filter-opt">
              <input type="checkbox" name="sub[]" value="{{ $sub->id }}" {{ in_array($sub->id, (array) request('sub', [])) ? 'checked' : '' }} onchange="this.form.submit()">
              {{ $sub->localized_name }}
              <span class="count">{{ $sub->booths()->count() }}</span>
            </label>
          @endforeach
        </div>
        @endif

        <div class="filter-box">
          <h4>{{ __('site.category.purchase_type') }}</h4>
          <label class="filter-opt">
            <input type="checkbox" name="online_only" value="1" {{ request('online_only') ? 'checked' : '' }} onchange="this.form.submit()">
            {{ __('site.category.online_only') }}
          </label>
        </div>
      </form>
    </aside>

    <main>
      <div class="toolbar">
        <span class="result-txt">{{ __('site.category.showing', ['from' => $booths->firstItem() ?? 0, 'to' => $booths->lastItem() ?? 0, 'total' => $booths->total()]) }}</span>
      </div>

      @if ($booths->isEmpty())
        <div class="empty-state"><div class="icon">🔍</div>{{ __('site.category.no_booths_yet') }}</div>
      @else
        <div class="booth-grid">
          @foreach ($booths as $booth)
            <a href="{{ route('booth.show', $booth->slug) }}" class="booth-card">
              <div class="booth-media">
                <img src="{{ $booth->banner_url }}" alt="{{ $booth->localized_name }}" class="media-img" loading="lazy">
                @if ($booth->online_payment_enabled)<span class="booth-badge">{{ __('site.common.online_payment') }}</span>@endif
              </div>
              <div class="booth-body">
                <div class="booth-top">
                  <div class="booth-logo">
                    @if ($booth->logo_url)<img src="{{ $booth->logo_url }}" alt="{{ $booth->localized_name }}">@else{{ mb_substr($booth->localized_name, 0, 1) }}@endif
                  </div>
                  <div><h3>{{ $booth->localized_name }}</h3><span class="cat">{{ $booth->city }}</span></div>
                </div>
                <p class="booth-desc">{{ \Illuminate\Support\Str::limit($booth->description[app()->getLocale()] ?? $booth->description['fa'] ?? '', 65) }}</p>
                <div class="booth-foot">
                  <span class="products">{{ $booth->products_count }} {{ __('site.common.product') }}</span>
                  @if ($booth->online_payment_enabled)<span class="pay">{{ __('site.common.buy_direct') }}</span>@endif
                </div>
              </div>
            </a>
          @endforeach
        </div>

        <div class="pagination">{{ $booths->links() }}</div>
      @endif
    </main>
  </div>
</div>
@endsection
