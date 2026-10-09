<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('user')
            ->when($request->status, fn ($q) => $q->where('payment_status', $request->status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.booth', 'items.product', 'address']);

        return view('admin.orders.show', compact('order'));
    }

    /** تأیید دستی پرداخت کارت‌به‌کارت بعد از بررسی رسید/شماره پیگیری */
    public function confirmPayment(Order $order)
    {
        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'پرداخت سفارش تأیید شد.');
    }

    public function rejectPayment(Request $request, Order $order)
    {
        $data = $request->validate(['reject_reason' => 'nullable|string|max:500']);

        $order->update([
            'payment_status' => 'failed',
            'status' => 'cancelled',
        ]);

        return back()->with('success', 'پرداخت سفارش رد شد.');
    }
}
