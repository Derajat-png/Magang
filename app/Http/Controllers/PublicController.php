<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PublicController extends Controller
{
    public function index()
    {
        $umkms = Umkm::where('status', 'active')->latest()->paginate(9);
        return view('public.landing', compact('umkms'));
    }

    public function catalog(Request $request, Umkm $umkm)
    {
        if ($umkm->status !== 'active') {
            abort(404, 'UMKM ini tidak aktif atau sedang dinonaktifkan.');
        }

        $categories = Category::where(function ($q) use ($umkm) {
            $q->whereNull('umkm_id')->orWhere('umkm_id', $umkm->id);
        })->where('status', 'active')->get();

        $query = Product::where('umkm_id', $umkm->id)->where('status', 'active');

        // Apply Category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Apply keyword search
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $products = $query->latest()->paginate(12);

        return view('public.catalog', compact('umkm', 'categories', 'products'));
    }

    public function placeOrder(StoreOrderRequest $request, Umkm $umkm)
    {
        if ($umkm->status !== 'active') {
            return back()->with('error', 'UMKM ini sedang tidak menerima pesanan.');
        }

        $items = $request->input('items');
        $orderItemsData = [];
        $totalAmount = 0;

        try {
            DB::beginTransaction();

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Ensure product belongs to the current UMKM
                if ($product->umkm_id !== $umkm->id) {
                    throw new \Exception("Produk {$product->name} tidak terdaftar di UMKM ini.");
                }

                // Ensure product is active
                if ($product->status !== 'active') {
                    throw new \Exception("Produk {$product->name} saat ini tidak tersedia.");
                }

                // Validate quantity is not greater than stock
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

            // Create Order as 'pending'
            $order = Order::create([
                'umkm_id' => $umkm->id,
                'created_by' => null, // Placed by Guest
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
            return redirect()->route('public.umkm.catalog', $umkm->id)->with('success', 'Pesanan Anda berhasil dikirim! Silakan hubungi kasir/owner untuk pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
