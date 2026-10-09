@extends('layouts.app')
@section('title', __('site.checkout.title'))

@section('content')
<div class="wrap" style="padding-bottom:80px;">
  <h1 style="font-size:24px; font-weight:800; color:var(--primary-dark); margin:24px 0 30px;">{{ __('site.checkout.heading') }}</h1>

  @if ($cartItems->isEmpty())
    <div class="empty-state"><div class="icon">🛒</div>{{ __('site.checkout.empty_cart') }}<br><a href="{{ route('home') }}" class="btn btn-primary" style="margin-top:16px;">{{ __('site.common.back_to_shop') }}</a></div>
  @else
    <form action="{{ route('checkout.pay') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="checkout-grid">

        <div>
          <div class="panel" style="margin-bottom:20px;">
            <div class="panel-head"><h3>{{ __('site.checkout.shipping_address') }}</h3></div>
            @forelse ($addresses as $address)
              <label class="filter-opt" style="align-items:flex-start; padding:12px; border:1px solid var(--border); border-radius:10px; margin-bottom:10px;">
                <input type="radio" name="address_id" value="{{ $address->id }}" {{ $address->is_default ? 'checked' : '' }} required>
                <span>
                  <b style="display:block; font-size:13.5px;">{{ $address->receiver_name }} — {{ $address->receiver_phone }}</b>
                  <span style="font-size:12.5px;">{{ $address->province }}، {{ $address->city }} — {{ $address->address_line }}</span>
                </span>
              </label>
            @empty
              <p style="font-size:13px; color:var(--ink-soft);">{{ __('site.checkout.no_address_yet') }} <a href="#" style="color:var(--primary); font-weight:600;">{{ __('site.checkout.add_new_address') }}</a></p>
            @endforelse
          </div>

          <div class="panel">
            <div class="panel-head"><h3>{{ __('site.checkout.order_items') }}</h3></div>
            @foreach ($cartItems as $row)
              <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid var(--border);">
                <div style="display:flex; align-items:center; gap:12px;">
                  <img src="{{ $row['product']->image_url }}" alt="{{ $row['product']->localized_name }}" style="width:44px; height:44px; border-radius:9px; object-fit:cover; background:var(--primary-tint);">
                  <div>
                    <b style="font-size:13.5px; display:block;">{{ $row['product']->localized_name }}</b>
                    <span style="font-size:12px; color:var(--ink-soft);">{{ $row['product']->booth->localized_name }} · {{ $row['quantity'] }} {{ __('site.checkout.qty_suffix') }}</span>
                  </div>
                </div>
                <b style="font-size:13.5px;">{{ number_format($row['line_total']) }} {{ __('site.common.toman') }}</b>
              </div>
            @endforeach
          </div>

          @if ($shippingByBooth->isNotEmpty())
          <div class="panel" style="margin-top:20px;">
            <div class="panel-head"><h3>{{ __('site.checkout.shipping_per_booth') }}</h3></div>
            @foreach ($cartItems->groupBy(fn($r) => $r['product']->booth_id) as $boothId => $rows)
              @php $ship = $shippingByBooth->get($boothId); @endphp
              @if ($ship)
                <div style="display:flex; justify-content:space-between; align-items:center; padding:11px 0; border-bottom:1px solid var(--border); font-size:13px;">
                  <div>
                    <b style="display:block;">{{ $rows->first()['product']->booth->localized_name }}</b>
                    <span style="color:var(--ink-soft); font-size:12px;">{{ $ship['carrier'] }} · {{ $ship['estimated_days'] }}</span>
                  </div>
                  <b>{{ number_format($ship['cost']) }} {{ __('site.common.toman') }}</b>
                </div>
              @endif
            @endforeach
          </div>
          @endif
        </div>

        <div class="panel">
          <div class="panel-head"><h3>{{ __('site.checkout.payment_summary') }}</h3></div>
          <div class="stat-mini"><span>{{ __('site.checkout.items_total') }}</span><b>{{ number_format($itemsAmount) }} {{ __('site.common.toman') }}</b></div>
          <div class="stat-mini"><span>{{ __('site.checkout.shipping_cost', ['count' => $shippingByBooth->count()]) }}</span><b>{{ number_format($shippingAmount) }} {{ __('site.common.toman') }}</b></div>
          <div class="stat-mini" style="font-size:16px; padding-top:16px;"><span>{{ __('site.checkout.final_amount') }}</span><b style="color:var(--primary-dark);">{{ number_format($itemsAmount + $shippingAmount) }} {{ __('site.common.toman') }}</b></div>

          {{-- ---------- کارت‌به‌کارت — چون درگاه آنلاین هنوز وصل نیست ---------- --}}
          <div class="card-transfer-box">
            <h4>{{ __('site.checkout.card_transfer_title') }}</h4>
            <p>{{ __('site.checkout.card_transfer_note', ['amount' => number_format($itemsAmount + $shippingAmount)]) }}</p>
            <div class="card-number-row">
              <span dir="ltr">{{ chunk_split(config('site_payment.card_number'), 4, ' ') }}</span>
              <button type="button" class="btn-copy" data-copy="{{ config('site_payment.card_number') }}">{{ __('site.checkout.copy') }}</button>
            </div>
            @if (config('site_payment.card_holder'))
              <span class="card-holder-name">{{ __('site.checkout.card_holder') }}: {{ config('site_payment.card_holder') }}</span>
            @endif
          </div>

          <div class="field-group" style="margin-top:16px;">
            <label>{{ __('site.checkout.payment_reference_label') }}</label>
            <input type="text" name="payment_reference" class="field" placeholder="{{ __('site.checkout.payment_reference_placeholder') }}">
          </div>
          <div class="field-group">
            <label>{{ __('site.checkout.payment_receipt_label') }}</label>
            <input type="file" name="payment_receipt" class="field" accept="image/*">
          </div>
          <p style="font-size:11.5px; color:var(--ink-soft); margin:4px 0 12px;">{{ __('site.checkout.payment_receipt_optional_note') }}</p>

          <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">{{ __('site.checkout.submit_order') }}</button>
          <p style="font-size:11.5px; color:var(--ink-soft); text-align:center; margin-top:10px;">{{ __('site.checkout.secure_payment_note') }}</p>
        </div>
      </div>
    </form>
  @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.btn-copy').forEach(function (btn) {
    btn.addEventListener('click', function () {
      navigator.clipboard.writeText(btn.dataset.copy).then(function () {
        var original = btn.textContent;
        btn.textContent = @json(__('site.checkout.copied'));
        setTimeout(function () { btn.textContent = original; }, 1800);
      });
    });
  });
});
</script>
@endpush
@endsection
