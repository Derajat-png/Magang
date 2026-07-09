<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $umkmId = auth()->user()->umkm_id;

        $query = Payment::whereHas('order', function ($q) use ($umkmId) {
            $q->where('umkm_id', $umkmId);
        })->with('order');

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments = $query->latest()->paginate(10)->withQueryString();

        return view('owner.payments.index', compact('payments'));
    }
}
