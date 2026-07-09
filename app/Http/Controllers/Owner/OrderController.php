<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $umkmId = auth()->user()->umkm_id;
        $query = Order::where('umkm_id', $umkmId)->with(['creator', 'payment']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('order_date', $request->date);
        }

        if ($request->filled('customer')) {
            $query->where('customer_name', 'like', '%' . $request->customer . '%');
        }

        if ($request->filled('payment_method')) {
            $query->whereHas('payment', function ($q) use ($request) {
                $q->where('payment_method', $request->payment_method);
            });
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('owner.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        Gate::authorize('view', $order);

        $order->load(['items.product', 'payment', 'creator']);

        return view('owner.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        Gate::authorize('updateStatus', $order);

        $request->validate([
            'status' => ['required', 'in:draft,pending,processed,completed,cancelled'],
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        if ($oldStatus === $newStatus) {
            return back()->with('info', 'Status pesanan tidak berubah.');
        }

        // Rule: Completed order items cannot be edited or modified
        if ($oldStatus === 'completed' && $newStatus !== 'cancelled') {
            return back()->with('error', 'Pesanan yang sudah selesai tidak dapat diubah statusnya kecuali dibatalkan.');
        }

        try {
            DB::beginTransaction();

            // 1. Stock changes
            // When transition to completed: decrement stock, write stock movements
            if ($newStatus === 'completed') {
                foreach ($order->items as $item) {
                    $product = Product::findOrFail($item->product_id);
                    
                    // Double check stock availability
                    if ($product->stock < $item->qty) {
                        throw new \Exception("Stok produk {$product->name} tidak mencukupi untuk menyelesaikan pesanan.");
                    }

                    $beforeStock = $product->stock;
                    $product->decrement('stock', $item->qty);
                    $afterStock = $product->fresh()->stock;

                    StockMovement::create([
                        'product_id' => $product->id,
                        'type' => 'out',
                        'qty' => $item->qty,
                        'before_stock' => $beforeStock,
                        'after_stock' => $afterStock,
                        'notes' => "Pesanan #{$order->id} selesai",
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            // When transition from completed to cancelled: increment stock (restore), write stock movements
            if ($oldStatus === 'completed' && $newStatus === 'cancelled') {
                foreach ($order->items as $item) {
                    $product = Product::findOrFail($item->product_id);

                    $beforeStock = $product->stock;
                    $product->increment('stock', $item->qty);
                    $afterStock = $product->fresh()->stock;

                    StockMovement::create([
                        'product_id' => $product->id,
                        'type' => 'in',
                        'qty' => $item->qty,
                        'before_stock' => $beforeStock,
                        'after_stock' => $afterStock,
                        'notes' => "Pesanan #{$order->id} dibatalkan (Pengembalian stok)",
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            // 2. Update status
            $order->update(['status' => $newStatus]);

            DB::commit();
            return back()->with('success', 'Status pesanan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    public function exportCsv(Request $request)
    {
        $umkmId = auth()->user()->umkm_id;
        $query = Order::where('umkm_id', $umkmId)->with(['creator', 'payment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('order_date', $request->date);
        }
        if ($request->filled('customer')) {
            $query->where('customer_name', 'like', '%' . $request->customer . '%');
        }

        $orders = $query->latest()->get();

        $filename = "Laporan_Pesanan_" . Carbon::now()->format('Ymd_His') . ".csv";
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID Pesanan', 'Tanggal', 'Nama Pelanggan', 'No HP', 'Total Belanja', 'Status Pesanan', 'Metode Pembayaran', 'Status Pembayaran', 'Catatan'];

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for proper Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->order_date->format('Y-m-d H:i'),
                    $order->customer_name,
                    $order->customer_phone,
                    $order->total_amount,
                    strtoupper($order->status),
                    $order->payment ? strtoupper($order->payment->payment_method) : '-',
                    $order->payment ? strtoupper($order->payment->payment_status) : '-',
                    $order->notes,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
