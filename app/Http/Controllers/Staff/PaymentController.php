<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Http\Requests\StorePaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function store(StorePaymentRequest $request, Order $order)
    {
        Gate::authorize('update', $order);

        // Rule: Satu order hanya boleh memiliki satu payment aktif
        if ($order->payment()->whereIn('payment_status', ['paid', 'unpaid', 'failed'])->count() > 0) {
            return back()->with('error', 'Pesanan ini sudah memiliki transaksi pembayaran.');
        }

        // Rule: amount wajib angka dan minimal sama dengan total pesanan
        if ($request->amount < $order->total_amount) {
            return back()->with('error', 'Jumlah pembayaran kurang dari total tagihan (Minimal: Rp ' . number_format($order->total_amount, 0, ',', '.') . ').');
        }

        $paidAt = $request->payment_status === 'paid' ? Carbon::now() : null;

        Payment::create([
            'order_id' => $order->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'paid_at' => $paidAt,
        ]);

        return back()->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function update(StorePaymentRequest $request, Payment $payment)
    {
        Gate::authorize('update', $payment);

        $order = $payment->order;

        if ($request->amount < $order->total_amount) {
            return back()->with('error', 'Jumlah pembayaran kurang dari total tagihan (Minimal: Rp ' . number_format($order->total_amount, 0, ',', '.') . ').');
        }

        $paidAt = $payment->paid_at;
        if ($request->payment_status === 'paid' && !$paidAt) {
            $paidAt = Carbon::now();
        } elseif ($request->payment_status !== 'paid') {
            $paidAt = null;
        }

        $payment->update([
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'paid_at' => $paidAt,
        ]);

        return back()->with('success', 'Transaksi pembayaran berhasil diperbarui.');
    }
}
