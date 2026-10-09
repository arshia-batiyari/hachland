{{--
  پارشال مشترک بین کیف‌پول ادمین (برای یک غرفه) و کیف‌پول غرفه‌دار — چون هر دو
  دقیقاً همون کارت‌های خلاصه و همون جدول صورت‌ریز رو نشون می‌دن، فقط با یه
  ستون اضافه («ثبت‌شده توسط») تو نسخه‌ی ادمین. متغیرهای لازم:
  $summary, $transactions, $type, $running, $walletRouteName (بدون پارامتر booth)
  $walletRouteParams (آرایه‌ی پارامترهای route، خالی برای غرفه‌دار یا ['booth' => $booth] برای ادمین)
  $showCreator (اختیاری، پیش‌فرض false)
--}}
@php $showCreator = $showCreator ?? false; @endphp

<div class="kpi-row">
  <div class="kpi"><div class="kpi-icon">👛</div><b>{{ number_format($summary['balance']) }}</b><span>{{ __('wallet.wallet_balance') }}</span></div>
  <div class="kpi"><div class="kpi-icon">✅</div><b>{{ number_format($summary['available']) }}</b><span>{{ __('wallet.wallet_available') }}</span></div>
  <div class="kpi"><div class="kpi-icon">⏳</div><b>{{ number_format($summary['in_settlement']) }}</b><span>{{ __('wallet.wallet_in_settlement') }}</span></div>
  <div class="kpi"><div class="kpi-icon">📦</div><b>{{ number_format($summary['paid_out']) }}</b><span>{{ __('wallet.wallet_paid_out') }}</span></div>
</div>
<div class="kpi-row">
  <div class="kpi"><div class="kpi-icon">💰</div><b>{{ number_format($summary['gross_sales']) }}</b><span>{{ __('wallet.wallet_gross_sales') }}</span></div>
  <div class="kpi"><div class="kpi-icon">🏛️</div><b>{{ number_format($summary['commission_total']) }}</b><span>{{ __('wallet.wallet_commission_total') }}</span></div>
  <div class="kpi"><div class="kpi-icon">📈</div><b>{{ number_format($summary['net_earned']) }}</b><span>{{ __('wallet.wallet_net_earned') }}</span></div>
</div>

<div class="subcats">
  <a href="{{ route($walletRouteName, $walletRouteParams) }}" class="chip {{ !$type ? 'on' : '' }}">{{ __('wallet.filter_all') }}</a>
  <a href="{{ route($walletRouteName, $walletRouteParams + ['type' => 'sale']) }}" class="chip {{ $type=='sale' ? 'on' : '' }}">{{ __('wallet.type_sale') }}</a>
  <a href="{{ route($walletRouteName, $walletRouteParams + ['type' => 'reversal']) }}" class="chip {{ $type=='reversal' ? 'on' : '' }}">{{ __('wallet.type_reversal') }}</a>
  <a href="{{ route($walletRouteName, $walletRouteParams + ['type' => 'adjustment']) }}" class="chip {{ $type=='adjustment' ? 'on' : '' }}">{{ __('wallet.type_adjustment') }}</a>
  <a href="{{ route($walletRouteName, $walletRouteParams + ['type' => 'payout']) }}" class="chip {{ $type=='payout' ? 'on' : '' }}">{{ __('wallet.type_payout') }}</a>
</div>

@if ($transactions->isEmpty())
  <div class="empty-state"><div class="icon">👛</div><p>{{ __('wallet.wallet_empty') }}</p></div>
@else
  <div class="table-wrap">
    <table>
      <thead><tr>
        <th>{{ __('wallet.th_date') }}</th>
        <th>{{ __('wallet.th_type') }}</th>
        <th>{{ __('wallet.th_description') }}</th>
        <th>{{ __('wallet.th_gross') }}</th>
        <th>{{ __('wallet.th_commission') }}</th>
        <th>{{ __('wallet.th_amount') }}</th>
        @if (!$type)<th>{{ __('wallet.th_balance_after') }}</th>@endif
        @if ($showCreator)<th></th>@endif
      </tr></thead>
      <tbody>
        @php $runningBalance = $running; @endphp
        @foreach ($transactions as $tx)
          <tr>
            <td style="white-space:nowrap;">{{ jdate($tx->created_at, 'j F Y') }}</td>
            <td>
              <span class="status-pill {{ match($tx->type){'sale'=>'done','reversal'=>'cancelled','payout'=>'pending','adjustment'=>$tx->isCredit()?'done':'cancelled',default=>'pending'} }}">{{ $tx->type_label }}</span>
            </td>
            <td style="font-size:12.5px; color:var(--ink-soft);">
              @if ($tx->order)
                @if ($showCreator)
                  <a href="{{ route('admin.orders.show', $tx->order) }}" style="color:inherit;">{{ __('site.panel.admin.th_order') }} #{{ $tx->order->order_number }}</a>
                @else
                  <span>{{ __('site.panel.admin.th_order') }} #{{ $tx->order->order_number }}</span>
                @endif
              @endif
              @if ($tx->description) <span>{{ $tx->description }}</span> @endif
              @if ($showCreator && $tx->creator) <br><small style="color:var(--ink-soft);">{{ $tx->creator->name }}</small> @endif
            </td>
            <td>{{ $tx->gross_amount ? number_format($tx->gross_amount) : '—' }}</td>
            <td>{{ $tx->commission_amount ? number_format($tx->commission_amount) : '—' }}</td>
            <td style="font-weight:700; color:{{ $tx->isCredit() ? 'var(--primary-hover)' : 'var(--red)' }};">
              {{ $tx->isCredit() ? '+' : '' }}{{ number_format($tx->amount) }}
            </td>
            @if (!$type)
              <td>{{ number_format($runningBalance) }}</td>
              @php $runningBalance -= $tx->amount; @endphp
            @endif
            @if ($showCreator)<td></td>@endif
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div style="margin-top:20px;">{{ $transactions->links() }}</div>
@endif
