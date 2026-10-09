@extends('layouts.panel')
@section('title', __('site.sidebar.products'))

@section('sidebar')
  @include('partials.vendor-sidebar')
@endsection

@section('content')
<div class="topbar">
  <div><h1>{{ __('site.panel.vendor.products_title') }}</h1><p>{{ __('site.panel.vendor.products_count', ['count' => $products->total()]) }}</p></div>
  <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">{{ __('site.panel.vendor.add_product') }}</a>
</div>

@if ($products->isEmpty())
  <div class="empty-state"><div class="icon">📦</div>{{ __('site.panel.vendor.no_products_yet') }}</div>
@else
  <div class="vendor-product-grid">
    @foreach ($products as $product)
      <div class="vendor-product-card">
        <div class="vendor-product-media">
          <img src="{{ $product->image_url }}" alt="{{ $product->localized_name }}" loading="lazy">
          <span class="status-pill {{ $product->status == 'active' ? 'done' : 'cancelled' }}">{{ __('site.status.product.'.$product->status) }}</span>
        </div>
        <div class="vendor-product-body">
          <b>{{ $product->localized_name }}</b>
          <span class="cat">{{ $product->category->localized_name }}</span>
          <div class="vendor-product-price">{{ number_format($product->price) }} <small>{{ __('site.common.toman') }}</small></div>

          <form action="{{ route('vendor.products.update', $product) }}" method="POST" class="vendor-stock-form">
            @csrf @method('PUT')
            <label>{{ __('site.panel.vendor.th_stock') }}</label>
            <div class="vendor-stock-row">
              <input type="number" name="stock" value="{{ $product->stock }}" class="field">
              <input type="hidden" name="price" value="{{ $product->price }}">
              <input type="hidden" name="status" value="{{ $product->status }}">
              <button type="submit" class="btn btn-primary btn-sm">{{ __('site.panel.save') }}</button>
            </div>
          </form>

          <form action="{{ route('vendor.products.destroy', $product) }}" method="POST" onsubmit="return confirm(@json(__('site.panel.confirm_delete')))">
            @csrf @method('DELETE')
            <button type="submit" class="vendor-product-delete">{{ __('site.panel.delete') }}</button>
          </form>
        </div>
      </div>
    @endforeach
  </div>
  <div class="pagination">{{ $products->links() }}</div>
@endif
@endsection
