<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockMovement;
use App\Http\Requests\StoreOrderRequest;
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

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('order_date', $request->date);
        }

        if ($request->filled('customer')) {
            $query->where('customer_name', 'like', '%' . $request->customer . '%');
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('staff.orders.index', compact('orders'));
    }

    public function create()
    {
        $umkmId = auth()->user()->umkm_id;
        // Only active products with stock > 0 can be ordered
        $products = Product::where('umkm_id', $umkmId)
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->get();

        return view('staff.orders.create', compact('products'));
    }

    public function store(StoreOrderRequest $request)
    {
        $umkmId = auth()->user()->umkm_id;
        $items = $request->input('items');
        $orderItemsData = [];
        $totalAmount = 0;

        try {
            DB::beginTransaction();

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->umkm_id !== $umkmId) {
                    throw new \Exception("Produk {$product->name} tidak terdaftar di UMKM Anda.");
                }

                if ($product->status !== 'active') {
                    throw new \Exception("Produk {$product->name} tidak aktif.");
                }

                if ($item['qty'] > $product->stock) {
                    throw new \Exception("Stok produk {$product->name} tidak mencukupi (Tersedia: {$product->stock}, Diminta: {$item['qty']}).");
                }

                $subtotal = $product->price * $item['qty'];
                $totalAmount += $subtotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ];
            }

            // Create Order starting as pending
            $order = Order::create([
                'umkm_id' => $umkmId,
                'created_by' => auth()->id(),
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'order_date' => Carbon::now(),
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            // Save order items
            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);
            }

            DB::commit();
            return redirect()->route('staff.orders.index')->with('success', 'Pesanan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Order $order)
    {
        Gate::authorize('view', $order);

        $order->load(['items.product', 'payment', 'creator']);

        return view('staff.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        Gate::authorize('updateStatus', $order);

        $request->validate([
            'status' => ['required', 'in:processed,completed'],
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        if ($oldStatus === $newStatus) {
            return back()->with('info', 'Status pesanan tidak berubah.');
        }

        try {
            DB::beginTransaction();

            // When transition to completed: decrement stock, write stock movements
            if ($newStatus === 'completed') {
                foreach ($order->items as $item) {
                    $product = Product::findOrFail($item->product_id);
                    
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
                        'notes' => "Pesanan #{$order->id} selesai oleh Kasir",
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            $order->update(['status' => $newStatus]);

            DB::commit();
            return back()->with('success', 'Status pesanan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }
}
