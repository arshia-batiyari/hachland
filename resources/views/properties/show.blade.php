@extends('layouts.app')
@section('title', $property->localized_title)
@section('meta_description', \Illuminate\Support\Str::limit($property->localized_description, 155))

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'RealEstateListing',
    'name' => $property->localized_title,
    'description' => $property->localized_description,
    'image' => $property->first_image_url,
    'url' => route('properties.show', $property),
    'address' => [
        '@type' => 'PostalAddress',
        'addressRegion' => $property->province,
        'addressLocality' => $property->city,
        'addressCountry' => 'IR',
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<div class="wrap">
  <div class="crumb">
    <a href="{{ route('home') }}">{{ __('site.nav.home') }}</a><span class="sep">/</span>
    <a href="{{ route('properties.index') }}">{{ __('site.properties.nav_title') }}</a><span class="sep">/</span>
    <span style="color:var(--ink);">{{ $property->localized_title }}</span>
  </div>

  <div class="product-detail-grid">
    {{-- ---------- IMAGE GALLERY ---------- --}}
    <div class="product-detail-gallery">
      @php $images = $property->image_urls; @endphp
      @if (!empty($images))
        <div class="gallery-main">
          <img src="{{ $images[0] }}" alt="{{ $property->localized_title }}" id="galleryMainImg">
        </div>
        @if (count($images) > 1)
          <div class="gallery-thumbs">
            @foreach ($images as $url)
              <div class="gallery-thumb" data-src="{{ $url }}">
                <img src="{{ $url }}" alt="{{ $property->localized_title }}" loading="lazy">
              </div>
            @endforeach
          </div>
        @endif
      @else
        <div class="gallery-main">
          <img src="{{ $property->first_image_url }}" alt="{{ $property->localized_title }}" id="galleryMainImg">
        </div>
      @endif
    </div>

    {{-- ---------- PROPERTY INFO ---------- --}}
    <div class="product-detail-info">
      <span style="font-size:13px; color:var(--accent); font-weight:600;">{{ $property->localized_property_type }} · {{ $property->localized_deal_type }}</span>
      <h1 style="font-size:26px; font-weight:800; margin:10px 0 16px; letter-spacing:-0.3px;">{{ $property->localized_title }}</h1>

      <div style="font-size:28px; font-weight:800; color:var(--primary-dark); margin-bottom:20px;">
        @if ($property->price)
          {{ number_format($property->price) }} <small style="font-size:14px; font-weight:500; color:var(--ink-soft);">{{ __('site.common.toman') }}</small>
        @else
          <span style="font-size:18px; color:var(--ink-soft);">{{ __('site.properties.price_on_request') }}</span>
        @endif
      </div>

      <div style="display:flex; gap:16px; flex-wrap:wrap; margin-bottom:20px; font-size:13px; color:var(--ink-soft);">
        <span>📍 {{ $property->city }}، {{ $property->province }}</span>
        @if ($property->area_sqm)
          <span>·</span><span>📐 {{ number_format($property->area_sqm) }} {{ __('site.properties.area_suffix') }}</span>
        @endif
        @if ($property->document_type)
          <span>·</span><span>📄 {{ $property->document_type }}</span>
        @endif
      </div>

      @if ($property->address)
        <p style="font-size:13.5px; color:var(--ink-soft); margin-bottom:16px;">{{ $property->address }}</p>
      @endif

      <div style="font-size:15px; color:var(--ink); line-height:2; margin-bottom:30px;">{!! nl2br(e($property->localized_description)) !!}</div>

      {{-- ---------- INQUIRY FORM ---------- --}}
      <div class="panel" style="background:var(--primary-tint); border:none;">
        <div class="panel-head"><h3>{{ __('site.properties.inquiry_box_title') }}</h3></div>
        <p style="font-size:13px; color:var(--ink-soft); margin-bottom:16px;">{{ __('site.properties.inquiry_box_text') }}</p>

        @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
          <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('properties.inquiry.store', $property) }}" method="POST">
          @csrf
          <div class="form-grid-2">
            <div class="field-group"><label>{{ __('site.properties.inquiry_name') }}</label><input type="text" name="name" class="field" required value="{{ old('name', auth()->user()->name ?? '') }}"></div>
            <div class="field-group"><label>{{ __('site.properties.inquiry_phone') }}</label><input type="text" name="phone" class="field" required value="{{ old('phone', auth()->user()->phone ?? '') }}"></div>
          </div>
          <div class="field-group">
            <label>{{ __('site.properties.inquiry_type_label') }}</label>
            <select name="inquiry_type" class="field" required>
              <option value="buy">{{ __('site.properties.inquiry_types.buy') }}</option>
              <option value="partnership">{{ __('site.properties.inquiry_types.partnership') }}</option>
              <option value="other">{{ __('site.properties.inquiry_types.other') }}</option>
            </select>
          </div>
          <div class="field-group">
            <label>{{ __('site.properties.inquiry_message') }}</label>
            <textarea name="message" class="field" rows="3" placeholder="{{ __('site.properties.inquiry_message_placeholder') }}">{{ old('message') }}</textarea>
          </div>
          <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">{{ __('site.properties.inquiry_submit') }}</button>
          <p style="font-size:11px; color:var(--ink-soft); margin-top:10px; text-align:center;">{{ __('site.properties.inquiry_privacy_note') }}</p>
        </form>
      </div>

      @if ($related->isNotEmpty())
      <div style="margin-top:40px;">
        <h4 style="font-size:16px; font-weight:700; margin-bottom:16px;">{{ __('site.properties.related_title') }}</h4>
        <div style="display:grid; grid-template-columns:repeat(1,1fr); gap:14px;">
          @foreach ($related as $r)
            <a href="{{ route('properties.show', $r) }}" style="display:flex; justify-content:space-between; align-items:center; padding:14px; border:1px solid var(--border); border-radius:var(--radius-sm); font-size:13px; background:var(--surface); transition:all 0.2s; text-decoration:none;">
              <div>
                <span style="font-weight:600;">{{ $r->localized_title }}</span><br>
                <small style="color:var(--ink-soft);">{{ $r->city }}، {{ $r->province }}</small>
              </div>
              <b style="color:var(--primary-dark); font-size:15px; white-space:nowrap;">{{ $r->price ? number_format($r->price).' '.__('site.common.toman') : __('site.properties.price_on_request') }}</b>
            </a>
          @endforeach
        </div>
      </div>
      @endif
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.gallery-thumb').forEach(function(thumb) {
        thumb.addEventListener('click', function() {
            document.querySelectorAll('.gallery-thumb').forEach(function(t){t.classList.remove('active');});
            thumb.classList.add('active');
            document.getElementById('galleryMainImg').src = thumb.dataset.src;
        });
    });
});
</script>
@endpush
