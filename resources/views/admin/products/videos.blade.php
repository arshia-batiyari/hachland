@extends('layouts.panel')
@section('title', __('site.panel.admin.product_videos_title'))

@section('sidebar')
  @include('partials.admin-sidebar')
@endsection

@section('content')
<div class="topbar"><div><h1>{{ __('site.panel.admin.product_videos_title') }}</h1><p>{{ __('site.panel.admin.product_videos_sub', ['count' => $products->total()]) }}</p></div></div>

@if ($products->isEmpty())
  <div class="empty-state">
    <div class="icon">🎬</div>
    <p>{{ __('site.panel.admin.product_videos_empty') }}</p>
  </div>
@else
  <div class="table-wrap">
    <table>
      <thead><tr>
        <th>{{ __('site.panel.admin.th_video') }}</th>
        <th>{{ __('site.panel.vendor.th_product') }}</th>
        <th>{{ __('site.panel.admin.th_booth') }}</th>
        <th></th>
      </tr></thead>
      <tbody>
        @foreach ($products as $product)
          <tr>
            <td><video src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($product->video_path) }}" controls preload="metadata" class="admin-video-preview"></video></td>
            <td>
              <a href="{{ route('product.show', $product) }}" target="_blank" class="cell-booth" style="color:inherit; text-decoration:none;">
                <div class="cell-thumb"><img src="{{ $product->image_url }}" alt=""></div>
                {{ $product->localized_name }}
              </a>
            </td>
            <td>{{ $product->booth->localized_name }}</td>
            <td style="white-space:nowrap;">
              <form action="{{ route('admin.products.video', [$product, 'approved']) }}" method="POST" style="display:inline;">@csrf<button class="row-actions" style="background:none;border:none;cursor:pointer;">{{ __('site.panel.approve') }}</button></form>
              ·
              <form action="{{ route('admin.products.video', [$product, 'rejected']) }}" method="POST" style="display:inline;">@csrf<button class="row-actions" style="background:none;border:none;cursor:pointer;color:var(--red);">{{ __('site.panel.reject') }}</button></form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div style="margin-top:20px;">{{ $products->links() }}</div>
@endif
@endsection

@push('styles')
<style>
.admin-video-preview{ width:140px; height:96px; border-radius:10px; background:#000; object-fit:cover; display:block; }
@media(max-width:640px){ .admin-video-preview{ width:110px; height:78px; } }
</style>
@endpush
