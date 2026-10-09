<?php

namespace App\Services;

use App\Models\Booth;
use App\Models\Settlement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * محاسبه و ساخت دسته‌های تسویه‌حساب برای غرفه‌داران.
 * معمولاً به‌صورت دوره‌ای (مثلاً هر ۱۵ روز) توسط ادمین یا یک Scheduled Command اجرا می‌شود.
 */
class SettlementService
{
    /**
     * برای یک غرفه، تمام آیتم‌های سفارشِ پرداخت‌شده و هنوز تسویه‌نشده را
     * در یک رکورد Settlement جمع می‌کند.
     */
    public function generateForBooth(Booth $booth, Carbon $periodStart, Carbon $periodEnd): ?Settlement
    {
        return DB::transaction(function () use ($booth, $periodStart, $periodEnd) {

            $items = $booth->orderItems()
                ->whereNull('settlement_id')
                ->whereHas('order', function ($q) use ($periodEnd) {
                    $q->where('payment_status', 'paid')
                      ->whereDate('paid_at', '<=', $periodEnd);
                })
                ->lockForUpdate()
                ->get();

            if ($items->isEmpty()) {
                return null;
            }

            $settlement = Settlement::create([
                'booth_id' => $booth->id,
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
                'total_sales' => $items->sum('line_total'),
                'commission_amount' => $items->sum('commission_amount'),
                'payable_amount' => $items->sum('booth_payout_amount'),
                'status' => 'pending',
            ]);

            $items->each->update(['settlement_id' => $settlement->id]);

            return $settlement;
        });
    }

    /** اجرای این متد برای همه غرفه‌های دارای مانده تسویه‌نشده */
    public function generateForAllBooths(Carbon $periodStart, Carbon $periodEnd): int
    {
        $count = 0;

        Booth::approved()->each(function (Booth $booth) use ($periodStart, $periodEnd, &$count) {
            if ($this->generateForBooth($booth, $periodStart, $periodEnd)) {
                $count++;
            }
        });

        return $count;
    }
}
