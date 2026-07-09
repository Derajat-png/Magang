<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $umkmId = auth()->user()->umkm_id;

        if (!$umkmId) {
            return redirect()->route('login')->with('error', 'Akun Anda tidak terhubung dengan UMKM.');
        }

        $todayOrders = Order::where('umkm_id', $umkmId)
            ->whereDate('order_date', Carbon::today())
            ->count();

        $pendingOrders = Order::where('umkm_id', $umkmId)
            ->where('status', 'pending')
            ->count();

        $paidTransactions = Payment::where('payment_status', 'paid')
            ->whereHas('order', function ($query) use ($umkmId) {
                $query->where('umkm_id', $umkmId);
            })
            ->whereDate('paid_at', Carbon::today())
            ->count();

        $lowStockProducts = Product::where('umkm_id', $umkmId)
            ->where('stock', '<', 5)
            ->where('status', 'active')
            ->get();

        // Recent orders
        $recentOrders = Order::where('umkm_id', $umkmId)
            ->with(['payment'])
            ->latest()
            ->take(5)
            ->get();

        return view('staff.dashboard', compact(
            'todayOrders',
            'pendingOrders',
            'paidTransactions',
            'lowStockProducts',
            'recentOrders'
        ));
    }
}
