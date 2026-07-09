<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $umkmId = auth()->user()->umkm_id;

        if (!$umkmId) {
            return redirect()->route('login')->with('error', 'Akun Anda tidak tertaut dengan UMKM manapun.');
        }

        $totalActiveProducts = Product::where('umkm_id', $umkmId)->where('status', 'active')->count();
        
        $lowStockProducts = Product::where('umkm_id', $umkmId)
            ->where('stock', '<', 5)
            ->where('status', 'active')
            ->get();

        $todayOrdersCount = Order::where('umkm_id', $umkmId)
            ->whereDate('order_date', Carbon::today())
            ->count();

        $pendingOrdersCount = Order::where('umkm_id', $umkmId)
            ->where('status', 'pending')
            ->count();

        // Omzet Hari Ini
        $revenueToday = Payment::where('payment_status', 'paid')
            ->whereHas('order', function ($query) use ($umkmId) {
                $query->where('umkm_id', $umkmId);
            })
            ->whereDate('paid_at', Carbon::today())
            ->sum('amount');

        // Omzet Bulan Ini
        $revenueMonth = Payment::where('payment_status', 'paid')
            ->whereHas('order', function ($query) use ($umkmId) {
                $query->where('umkm_id', $umkmId);
            })
            ->whereMonth('paid_at', Carbon::now()->month)
            ->whereYear('paid_at', Carbon::now()->year)
            ->sum('amount');

        // Omzet Harian 7 Hari Terakhir untuk Chart
        $dailyRevenues = Payment::where('payment_status', 'paid')
            ->whereHas('order', function ($query) use ($umkmId) {
                $query->where('umkm_id', $umkmId);
            })
            ->where('paid_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->select(
                DB::raw('DATE(paid_at) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // Structure charts data
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $dateString = Carbon::now()->subDays($i)->format('Y-m-d');
            $label = Carbon::now()->subDays($i)->isoFormat('D MMM');
            $chartLabels[] = $label;

            $found = $dailyRevenues->firstWhere('date', $dateString);
            $chartData[] = $found ? (float)$found->total : 0;
        }

        // Recent orders
        $recentOrders = Order::where('umkm_id', $umkmId)
            ->with(['payment'])
            ->latest()
            ->take(5)
            ->get();

        return view('owner.dashboard', compact(
            'totalActiveProducts',
            'lowStockProducts',
            'todayOrdersCount',
            'pendingOrdersCount',
            'revenueToday',
            'revenueMonth',
            'chartLabels',
            'chartData',
            'recentOrders'
        ));
    }
}
