@extends('layouts.panel')
@section('title', __('wallet.contract_title'))

@section('sidebar')
  @include('partials.vendor-sidebar')
@endsection

@section('content')
<div class="topbar">
  <div><h1>{{ __('wallet.contract_title') }}</h1><p>{{ $contract->contract_number }}</p></div>
  <div style="display:flex; gap:10px;">
    <a href="{{ route('vendor.contract.print', $contract) }}" target="_blank" class="btn btn-ghost btn-sm">{{ __('wallet.print_view') }}</a>
    @if ($contract->isSigned())
      <a href="{{ route('vendor.contract.print', ['contract' => $contract, 'pdf' => 1]) }}" target="_blank" class="btn btn-primary btn-sm">{{ __('wallet.download_pdf') }}</a>
    @endif
  </div>
</div>

@if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if ($errors->any())
  <div class="alert alert-error">{{ $errors->first() }}</div>
@endif

<div class="contract-doc">

  {{-- ---------- SUMMARY STRIP ---------- --}}
  <div class="contract-summary-grid">
    <div class="contract-summary-cell"><b>{{ $contract->share_percent_label }}٪</b><span>{{ __('wallet.contract_share') }}</span></div>
    <div class="contract-summary-cell"><b>{{ $contract->commission_percent_label }}٪</b><span>{{ __('wallet.contract_commission') }}</span></div>
    <div class="contract-summary-cell"><b>{{ __('wallet.contract_cycle_days', ['count' => $contract->settlement_cycle_days]) }}</b><span>{{ __('wallet.contract_cycle') }}</span></div>
    <div class="contract-summary-cell"><b>{{ __('wallet.contract_term_months', ['count' => $contract->term_months]) }}</b><span>{{ __('wallet.contract_term') }}</span></div>
  </div>

  @if ($contract->isAwaitingSignature())
    <div class="alert" style="background:var(--primary-tint); color:var(--primary-hover); margin-bottom:20px;">{{ __('wallet.contract_awaiting_notice') }}</div>
  @else
    <div class="alert alert-success" style="margin-bottom:20px;">{{ __('wallet.contract_signed_notice', ['date' => jdate($contract->signed_at, 'j F Y - H:i'), 'name' => $contract->signer_name]) }}</div>
  @endif

  {{-- ---------- SAMPLE CALCULATION ---------- --}}
  <div class="panel" style="margin-bottom:20px;">
    <div class="panel-head"><h3>{{ __('wallet.sample_calc_title') }}</h3></div>
    @php
      $sampleGross = 1000000;
      $sampleCommission = round($sampleGross * $contract->commission_percent / 100);
      $sampleShare = $sampleGross - $sampleCommission;
    @endphp
    <p style="font-size:13px; color:var(--ink-soft); line-height:1.9;">
      {{ __('wallet.sample_calc_body', ['commission' => number_format($sampleCommission), 'share' => number_format($sampleShare)]) }}
    </p>
  </div>

  {{-- ---------- FULL TERMS ---------- --}}
  <div class="panel">
    <div class="panel-head"><h3>{{ __('wallet.contract_title') }} — {{ $contract->contract_number }}</h3></div>
    <div class="contract-terms-scroll" id="contractTermsScroll">
      @foreach ($contract->terms as $article)
        <div class="contract-article">
          <h4><span class="no">{{ $article['no'] }}</span> {{ $article['title'] }}</h4>
          <ol>
            @foreach ($article['clauses'] as $clause)
              <li>{{ $clause }}</li>
            @endforeach
          </ol>
        </div>
      @endforeach
    </div>
  </div>

  {{-- ---------- SIGN FORM / SIGNED RESULT ---------- --}}
  @if ($contract->isAwaitingSignature())
    <div class="panel" style="margin-top:20px;">
      <div class="panel-head"><h3>{{ __('wallet.sign_title') }}</h3></div>
      <p class="js-scroll-hint" style="font-size:12px; color:var(--primary-hover); background:var(--primary-tint); padding:10px 14px; border-radius:8px; margin-bottom:18px;">{{ __('wallet.read_scroll_hint') }}</p>

      <form action="{{ route('vendor.contract.sign', $contract) }}" method="POST" id="contractSignForm">
        @csrf
        <div class="field-group">
          <label>{{ __('wallet.signer_name_label') }}</label>
          <input type="text" name="signer_name" class="field" required minlength="3" maxlength="120" placeholder="{{ __('wallet.signer_name_placeholder') }}" value="{{ old('signer_name') }}">
        </div>

        <div class="field-group">
          <label>{{ __('wallet.signature_pad_label') }}</label>
          <div class="signature-pad-wrap">
            <canvas id="signaturePad" width="600" height="220"></canvas>
            <button type="button" id="signatureClear" class="signature-clear-btn">{{ __('wallet.signature_clear') }}</button>
          </div>
          <input type="hidden" name="signature" id="signatureData">
        </div>

        <label class="accept-terms-row">
          <input type="checkbox" name="accepted" id="acceptTerms" value="1" required disabled>
          <span>{{ __('wallet.accept_terms_label') }}</span>
        </label>

        <button type="submit" class="btn btn-primary" id="signSubmitBtn" disabled>{{ __('wallet.sign_submit') }}</button>
      </form>
    </div>
  @else
    <div class="panel" style="margin-top:20px;">
      <div class="panel-head"><h3>{{ __('wallet.sign_title') }}</h3></div>
      <div class="signed-result-grid">
        @if ($contract->signature_data_uri)
          <div class="signed-image-box"><img src="{{ $contract->signature_data_uri }}" alt=""></div>
        @endif
        <div class="sign-meta">
          {{ __('wallet.signed_by') }}: <b>{{ $contract->signer_name }}</b><br>
          {{ __('wallet.signed_at_label') }}: <b>{{ jdate($contract->signed_at, 'j F Y - H:i') }}</b><br>
          {{ __('wallet.verification_code') }}: <code style="direction:ltr; display:inline-block; font-weight:800;">{{ $contract->verification_code }}</code>
        </div>
      </div>
    </div>
  @endif

  {{-- ---------- HISTORY ---------- --}}
  @if ($history->isNotEmpty())
    <div class="panel" style="margin-top:20px;">
      <div class="panel-head"><h3>{{ __('wallet.contract_history') }}</h3></div>
      <div class="table-wrap" style="border:none;">
        <table>
          <thead><tr><th>{{ __('wallet.th_contract_number') }}</th><th>{{ __('wallet.th_share') }}</th><th>{{ __('wallet.th_issued_at') }}</th><th>{{ __('wallet.th_status') }}</th></tr></thead>
          <tbody>
            @foreach ($history as $c)
              <tr>
                <td>{{ $c->contract_number }}</td>
                <td>{{ $c->share_percent_label }}٪</td>
                <td>{{ $c->issued_at ? jdate($c->issued_at, 'j F Y') : '—' }}</td>
                <td><span class="status-pill {{ $c->status === 'void' ? 'cancelled' : 'pending' }}">{{ $c->status_label }}</span></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif

</div>
@endsection

@push('styles')
<style>
.contract-summary-grid{ display:grid; grid-template-columns:repeat(4,1fr); gap:1px; background:var(--border); border:1px solid var(--border); border-radius:12px; overflow:hidden; margin-bottom:20px; }
.contract-summary-cell{ background:var(--surface); padding:16px 12px; text-align:center; }
.contract-summary-cell b{ display:block; font-size:19px; font-weight:800; color:var(--primary-hover); }
.contract-summary-cell span{ font-size:11px; color:var(--ink-soft); }
@media(max-width:640px){ .contract-summary-grid{ grid-template-columns:repeat(2,1fr); } }

.contract-terms-scroll{ max-height:420px; overflow-y:auto; padding-left:6px; border:1px solid var(--border); border-radius:10px; padding:16px; background:var(--bg); }
.contract-article{ margin-bottom:18px; }
.contract-article:last-child{ margin-bottom:0; }
.contract-article h4{ font-size:13.5px; font-weight:800; margin:0 0 8px; display:flex; align-items:center; gap:8px; }
.contract-article h4 .no{ width:22px; height:22px; border-radius:6px; background:var(--gradient-dark); color:var(--gold-soft); font-size:11px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.contract-article ol{ margin:0; padding-right:20px; }
.contract-article li{ font-size:12.5px; color:var(--ink); margin-bottom:6px; line-height:1.85; }

.signature-pad-wrap{ position:relative; border:1.5px dashed var(--border); border-radius:12px; overflow:hidden; background:#fff; touch-action:none; }
.signature-pad-wrap canvas{ display:block; width:100%; height:220px; cursor:crosshair; }
.signature-clear-btn{ position:absolute; top:8px; left:8px; font-size:11.5px; font-weight:700; padding:5px 12px; border-radius:8px; border:1px solid var(--border); background:var(--surface); cursor:pointer; }

.accept-terms-row{ display:flex; align-items:flex-start; gap:10px; font-size:12.5px; color:var(--ink-soft); margin:18px 0; cursor:pointer; line-height:1.8; }
.accept-terms-row input{ margin-top:3px; accent-color:var(--primary); width:16px; height:16px; flex-shrink:0; }

.signed-result-grid{ display:flex; align-items:center; gap:20px; flex-wrap:wrap; }
.signed-image-box{ background:#fff; border:1px solid var(--border); border-radius:10px; padding:10px 16px; }
.signed-image-box img{ max-height:70px; display:block; }
.sign-meta{ font-size:12.5px; color:var(--ink-soft); line-height:2; }
.sign-meta b{ color:var(--ink); }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  var canvas = document.getElementById('signaturePad');
  if (!canvas) return; // قرارداد قبلاً امضا شده، فرم امضا اصلاً رندر نشده

  var ctx = canvas.getContext('2d');
  var ratio = Math.max(window.devicePixelRatio || 1, 1);
  function sizeCanvas() {
    var rect = canvas.getBoundingClientRect();
    canvas.width = rect.width * ratio;
    canvas.height = 220 * ratio;
    ctx.scale(ratio, ratio);
    ctx.lineWidth = 2.2;
    ctx.lineCap = 'round';
    ctx.strokeStyle = '#17140F';
  }
  sizeCanvas();

  var drawing = false, hasInk = false, last = null;

  function pos(e) {
    var rect = canvas.getBoundingClientRect();
    return { x: e.clientX - rect.left, y: e.clientY - rect.top };
  }
  function start(e) {
    drawing = true;
    last = pos(e);
    canvas.setPointerCapture(e.pointerId);
  }
  function move(e) {
    if (!drawing) return;
    var p = pos(e);
    ctx.beginPath();
    ctx.moveTo(last.x, last.y);
    ctx.lineTo(p.x, p.y);
    ctx.stroke();
    last = p;
    hasInk = true;
    updateSignatureField();
  }
  function end() { drawing = false; }

  canvas.addEventListener('pointerdown', start);
  canvas.addEventListener('pointermove', move);
  canvas.addEventListener('pointerup', end);
  canvas.addEventListener('pointerleave', end);

  document.getElementById('signatureClear').addEventListener('click', function () {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    hasInk = false;
    document.getElementById('signatureData').value = '';
  });

  function updateSignatureField() {
    document.getElementById('signatureData').value = canvas.toDataURL('image/png');
  }

  // فعال‌شدن چک‌باکس و دکمه‌ی ثبت فقط بعد از این‌که تا انتهای متن قرارداد اسکرول کرد
  var scrollBox = document.getElementById('contractTermsScroll');
  var accept = document.getElementById('acceptTerms');
  var submitBtn = document.getElementById('signSubmitBtn');
  var hint = document.querySelector('.js-scroll-hint');

  function checkScrolled() {
    if (scrollBox.scrollTop + scrollBox.clientHeight >= scrollBox.scrollHeight - 30) {
      accept.disabled = false;
      if (hint) hint.style.display = 'none';
      scrollBox.removeEventListener('scroll', checkScrolled);
    }
  }
  scrollBox.addEventListener('scroll', checkScrolled);
  // اگه متن قرارداد اصلاً اسکرول نداشت (صفحه‌نمایش خیلی بزرگ)، مستقیم فعال بشه
  if (scrollBox.scrollHeight <= scrollBox.clientHeight + 10) checkScrolled();

  function refreshSubmitState() {
    submitBtn.disabled = !(accept.checked && hasInk);
  }
  accept.addEventListener('change', refreshSubmitState);
  canvas.addEventListener('pointerup', refreshSubmitState);

  document.getElementById('contractSignForm').addEventListener('submit', function (e) {
    if (!hasInk) {
      e.preventDefault();
      alert(@json(__('wallet.err_signature_blank')));
    }
  });
});
</script>
@endpush
