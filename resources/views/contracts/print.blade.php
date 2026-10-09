<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<title>{{ __('wallet.contract_title') }} — {{ $contract->contract_number }}</title>
<meta name="robots" content="noindex">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800&display=swap">
<style>
  :root{
    --ink:#17140F; --ink-soft:#6B6459; --gold:#CC9A3D; --gold-deep:#A6692A;
    --bg-paper:#FCFAF5; --border:#E7DECA; --gradient-dark:linear-gradient(150deg,#231D14 0%,#100D09 55%,#000000 100%);
  }
  *{box-sizing:border-box;}
  body{
    font-family:'Vazirmatn',sans-serif; color:var(--ink); background:#EDE7D8;
    margin:0; padding:32px 16px; line-height:1.9; font-size:13.5px;
  }
  .sheet{
    max-width:820px; margin:0 auto; background:var(--bg-paper);
    border-radius:14px; overflow:hidden; box-shadow:0 20px 60px rgba(20,16,9,.18);
  }
  .letterhead{
    background:var(--gradient-dark); color:#fff; padding:34px 40px 26px;
    display:flex; align-items:center; justify-content:space-between; gap:20px;
  }
  .letterhead .brand{ display:flex; align-items:center; gap:12px; }
  .letterhead .brand-mark{ width:44px; height:44px; border-radius:11px; background:linear-gradient(135deg,#FFE9A6,#DDA646,#A66921); display:flex; align-items:center; justify-content:center; font-weight:800; color:#2B1E08; font-size:18px; }
  .letterhead .brand-name{ font-size:19px; font-weight:800; }
  .letterhead .brand-sub{ font-size:11.5px; color:#C7BBA5; margin-top:2px; }
  .letterhead .meta{ text-align:left; font-size:11.5px; color:#D9CFBC; }
  .letterhead .meta b{ display:block; color:#fff; font-size:14px; font-weight:800; margin-bottom:2px; direction:ltr; }

  .title-band{ text-align:center; padding:26px 20px 10px; }
  .title-band h1{ font-size:21px; font-weight:800; margin:0 0 6px; }
  .title-band p{ font-size:12px; color:var(--ink-soft); margin:0; }

  .parties{ display:grid; grid-template-columns:1fr 1fr; gap:16px; padding:20px 40px; }
  .party-card{ background:#fff; border:1px solid var(--border); border-radius:12px; padding:14px 16px; }
  .party-card h4{ font-size:11.5px; color:var(--gold-deep); font-weight:800; margin:0 0 8px; text-transform:uppercase; letter-spacing:.3px; }
  .party-card div{ font-size:12.5px; margin-bottom:4px; }
  .party-card div b{ color:var(--ink-soft); font-weight:600; }

  .summary-strip{ display:grid; grid-template-columns:repeat(4,1fr); gap:1px; background:var(--border); margin:0 40px 20px; border-radius:12px; overflow:hidden; border:1px solid var(--border); }
  .summary-cell{ background:#fff; padding:14px 12px; text-align:center; }
  .summary-cell b{ display:block; font-size:17px; font-weight:800; color:var(--gold-deep); }
  .summary-cell span{ font-size:10.5px; color:var(--ink-soft); }

  .articles{ padding:0 40px; }
  .article{ margin-bottom:18px; break-inside:avoid; }
  .article h3{ font-size:13.5px; font-weight:800; margin:0 0 8px; display:flex; align-items:center; gap:8px; }
  .article h3 .no{ width:22px; height:22px; border-radius:6px; background:var(--gradient-dark); color:#fff; font-size:11px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
  .article ol{ margin:0; padding-right:20px; }
  .article li{ font-size:12.5px; color:var(--ink); margin-bottom:6px; line-height:1.85; }

  .calc-table{ width:100%; border-collapse:collapse; margin:10px 0 16px; font-size:12px; }
  .calc-table th, .calc-table td{ border:1px solid var(--border); padding:8px 10px; text-align:center; }
  .calc-table th{ background:#F3EEE1; font-weight:700; }

  .sign-section{ padding:26px 40px 10px; page-break-inside:avoid; }
  .sign-grid{ display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-top:14px; }
  .sign-box{ border:1.5px dashed var(--border); border-radius:12px; padding:16px; min-height:140px; }
  .sign-box h5{ font-size:11.5px; color:var(--ink-soft); font-weight:700; margin:0 0 10px; }
  .sign-box img{ max-width:100%; max-height:80px; display:block; margin-bottom:8px; }
  .sign-box .awaiting{ font-size:11.5px; color:var(--ink-soft); font-style:italic; padding:20px 0; text-align:center; }
  .sign-meta{ font-size:11px; color:var(--ink-soft); line-height:1.9; margin-top:6px; }
  .sign-meta b{ color:var(--ink); }

  .verify-band{ display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; margin:22px 40px 0; padding:14px 18px; background:#F3EEE1; border-radius:10px; font-size:11px; color:var(--ink-soft); }
  .verify-band code{ direction:ltr; display:inline-block; font-weight:800; color:var(--ink); letter-spacing:1px; }

  .doc-footer{ text-align:center; padding:22px 20px 30px; font-size:10.5px; color:var(--ink-soft); }

  .print-actions{ max-width:820px; margin:0 auto 16px; display:flex; justify-content:flex-end; gap:10px; }
  .print-actions button, .print-actions a{
    font-family:inherit; font-size:12.5px; font-weight:700; padding:9px 18px; border-radius:9px;
    border:1px solid var(--border); background:#fff; color:var(--ink); cursor:pointer; text-decoration:none;
  }
  .print-actions .primary{ background:var(--gradient-dark); color:#fff; border-color:transparent; }

  @media print{
    body{ background:#fff; padding:0; }
    .sheet{ box-shadow:none; border-radius:0; max-width:none; }
    .print-actions{ display:none; }
    .article{ page-break-inside:avoid; }
  }
</style>
</head>
<body>

<div class="print-actions">
  <button type="button" onclick="window.print()" class="primary">🖨️ {{ __('wallet.download_pdf') }}</button>
</div>

<div class="sheet">

  <div class="letterhead">
    <div class="brand">
      <div class="brand-mark">H</div>
      <div>
        <div class="brand-name">{{ $contract->parties['company']['name'] ?? 'هلدینگ مهرگان (هاچ لند)' }}</div>
        <div class="brand-sub">{{ $contract->parties['company']['website'] ?? 'hachland.com' }}</div>
      </div>
    </div>
    <div class="meta">
      <b>{{ $contract->contract_number }}</b>
      {{ __('wallet.contract_issued_at') }}: {{ $contract->issued_at ? jdate($contract->issued_at, 'j F Y') : '—' }}
    </div>
  </div>

  <div class="title-band">
    <h1>{{ __('wallet.contract_title') }}</h1>
    <p>{{ __('site.brand') }} — {{ __('wallet.contract_term_months', ['count' => $contract->term_months]) }} · {{ __('wallet.contract_cycle_days', ['count' => $contract->settlement_cycle_days]) }}</p>
  </div>

  <div class="parties">
    <div class="party-card">
      <h4>{{ __('wallet.contract_title') }} — طرف اول</h4>
      <div><b>نام:</b> {{ $contract->parties['company']['name'] ?? '' }}</div>
      <div><b>وب‌سایت:</b> <span style="direction:ltr; display:inline-block;">{{ $contract->parties['company']['website'] ?? '' }}</span></div>
      <div><b>تلفن:</b> <span style="direction:ltr; display:inline-block;">{{ $contract->parties['company']['phone'] ?? '' }}</span></div>
    </div>
    <div class="party-card">
      <h4>طرف دوم — غرفه‌دار</h4>
      <div><b>غرفه:</b> {{ $contract->parties['booth']['booth_name'] ?? '' }}</div>
      <div><b>نام مالک:</b> {{ $contract->parties['booth']['owner_name'] ?? '' }}</div>
      <div><b>کد ملی:</b> {{ $contract->parties['booth']['national_id'] ?? '—' }}</div>
      <div><b>شبا:</b> <span style="direction:ltr; display:inline-block;">{{ $contract->parties['booth']['bank_iban'] ?? '—' }}</span></div>
    </div>
  </div>

  <div class="summary-strip">
    <div class="summary-cell"><b>{{ $contract->share_percent_label }}٪</b><span>{{ __('wallet.contract_share') }}</span></div>
    <div class="summary-cell"><b>{{ $contract->commission_percent_label }}٪</b><span>{{ __('wallet.contract_commission') }}</span></div>
    <div class="summary-cell"><b>{{ __('wallet.contract_cycle_days', ['count' => $contract->settlement_cycle_days]) }}</b><span>{{ __('wallet.contract_cycle') }}</span></div>
    <div class="summary-cell"><b>{{ __('wallet.contract_term_months', ['count' => $contract->term_months]) }}</b><span>{{ __('wallet.contract_term') }}</span></div>
  </div>

  <div class="articles">
    @foreach ($contract->terms as $article)
      <div class="article">
        <h3><span class="no">{{ $article['no'] }}</span> {{ $article['title'] }}</h3>
        <ol>
          @foreach ($article['clauses'] as $clause)
            <li>{{ $clause }}</li>
          @endforeach
        </ol>

        @if ($article['no'] === 5)
          <table class="calc-table">
            <thead><tr><th>مبلغ کالا (تومان)</th><th>سهم غرفه‌دار ({{ $contract->share_percent_label }}٪)</th><th>کمیسیون هاچ لند ({{ $contract->commission_percent_label }}٪)</th></tr></thead>
            <tbody>
              <tr>
                <td>۱,۰۰۰,۰۰۰</td>
                <td>{{ number_format(round(1000000 * $contract->share_percent / 100)) }}</td>
                <td>{{ number_format(round(1000000 * $contract->commission_percent / 100)) }}</td>
              </tr>
            </tbody>
          </table>
        @endif
      </div>
    @endforeach
  </div>

  <div class="sign-section">
    <div class="article" style="margin-bottom:0;">
      <h3><span class="no">✓</span> امضای طرفین</h3>
    </div>
    <div class="sign-grid">
      <div class="sign-box">
        <h5>طرف اول — هاچ لند</h5>
        <div style="font-weight:800; font-size:13px;">{{ $contract->parties['company']['name'] ?? '' }}</div>
        <div class="sign-meta">صادرکننده: <b>{{ $contract->issuer->name ?? '—' }}</b><br>{{ __('wallet.contract_issued_at') }}: <b>{{ $contract->issued_at ? jdate($contract->issued_at, 'j F Y - H:i') : '—' }}</b></div>
      </div>
      <div class="sign-box">
        <h5>طرف دوم — غرفه‌دار</h5>
        @if ($contract->isSigned())
          @if ($contract->signature_data_uri)
            <img src="{{ $contract->signature_data_uri }}" alt="">
          @endif
          <div class="sign-meta">
            {{ __('wallet.signed_by') }}: <b>{{ $contract->signer_name }}</b><br>
            {{ __('wallet.signed_at_label') }}: <b>{{ jdate($contract->signed_at, 'j F Y - H:i') }}</b><br>
            {{ __('wallet.signer_ip_label') }}: <b style="direction:ltr; display:inline-block;">{{ $contract->signer_ip }}</b>
          </div>
        @else
          <div class="awaiting">{{ __('wallet.contract_status_awaiting_signature') }}</div>
        @endif
      </div>
    </div>
  </div>

  <div class="verify-band">
    <span>{{ __('wallet.verification_code') }}: <code>{{ $contract->verification_code }}</code></span>
    <span>{{ __('site.brand') }} · hachland.com</span>
  </div>

  <div class="doc-footer">
    این سند به‌صورت الکترونیکی صادر شده و بدون مُهر معتبر است. نسخه‌ی اصلی در پنل کاربری طرفین نگهداری می‌شود.
  </div>

</div>

@if ($autoPrint ?? false)
  <script>window.addEventListener('load', function(){ setTimeout(function(){ window.print(); }, 300); });</script>
@endif

</body>
</html>
