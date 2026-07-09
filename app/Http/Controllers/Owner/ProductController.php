<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockMovement;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $umkmId = auth()->user()->umkm_id;

        $categories = Category::where(function ($q) use ($umkmId) {
            $q->whereNull('umkm_id')->orWhere('umkm_id', $umkmId);
        })->where('status', 'active')->get();

        $query = Product::where('umkm_id', $umkmId)->with('category');

        // Apply filters
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        return view('owner.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $umkmId = auth()->user()->umkm_id;
        $categories = Category::where(function ($q) use ($umkmId) {
            $q->whereNull('umkm_id')->orWhere('umkm_id', $umkmId);
        })->where('status', 'active')->get();

        return view('owner.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $umkmId = auth()->user()->umkm_id;

        try {
            DB::beginTransaction();

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
            }

            $product = Product::create([
                'umkm_id' => $umkmId,
                'category_id' => $request->category_id,
                'name' => $request->name,
                'sku' => $request->sku,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'unit' => $request->unit,
                'image' => $imagePath,
                'status' => $request->status,
            ]);

            // Log stock movement for initial stock
            if ($product->stock > 0) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'in',
                    'qty' => $product->stock,
                    'before_stock' => 0,
                    'after_stock' => $product->stock,
                    'notes' => 'Stok awal produk baru',
                    'created_by' => auth()->id(),
                ]);
            }

            DB::commit();
            return redirect()->route('owner.products.index')->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan produk: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Product $product)
    {
        Gate::authorize('update', $product);

        $umkmId = auth()->user()->umkm_id;
        $categories = Category::where(function ($q) use ($umkmId) {
            $q->whereNull('umkm_id')->orWhere('umkm_id', $umkmId);
        })->where('status', 'active')->get();

        return view('owner.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        Gate::authorize('update', $product);

        try {
            DB::beginTransaction();

            $oldStock = $product->stock;
            $newStock = $request->stock;

            $imagePath = $product->image;
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $imagePath = $request->file('image')->store('products', 'public');
            }

            $product->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'sku' => $request->sku,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'unit' => $request->unit,
                'image' => $imagePath,
                'status' => $request->status,
            ]);

            // Log stock adjustment if changed
            if ($oldStock != $newStock) {
                $diff = $newStock - $oldStock;
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => $diff > 0 ? 'in' : 'out',
                    'qty' => abs($diff),
                    'before_stock' => $oldStock,
                    'after_stock' => $newStock,
                    'notes' => 'Penyesuaian stok manual',
                    'created_by' => auth()->id(),
                ]);
            }

            DB::commit();
            return redirect()->route('owner.products.index')->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui produk: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Product $product)
    {
        Gate::authorize('delete', $product);

        // Soft delete product
        $product->delete();

        return redirect()->route('owner.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
