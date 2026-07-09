<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUmkmActive = Umkm::where('status', 'active')->count();
        $totalOwner = User::where('role', 'owner')->count();
        $totalStaff = User::where('role', 'staff')->count();
        $totalTransactions = Order::count();
        
        $totalRevenue = Payment::where('payment_status', 'paid')->sum('amount');

        // Recent transactions for the global view
        $recentOrders = Order::with(['umkm', 'payment'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUmkmActive',
            'totalOwner',
            'totalStaff',
            'totalTransactions',
            'totalRevenue',
            'recentOrders'
        ));
    }
}
