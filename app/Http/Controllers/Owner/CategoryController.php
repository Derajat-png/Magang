<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    public function index()
    {
        $umkmId = auth()->user()->umkm_id;
        
        // Show categories of current UMKM and global ones
        $categories = Category::where(function ($query) use ($umkmId) {
            $query->whereNull('umkm_id')->orWhere('umkm_id', $umkmId);
        })->latest()->paginate(10);

        return view('owner.categories.index', compact('categories'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $umkmId = auth()->user()->umkm_id;

        Category::create([
            'name' => $request->name,
            'status' => $request->status,
            'umkm_id' => $umkmId,
        ]);

        return redirect()->route('owner.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        Gate::authorize('update', $category);

        return view('owner.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        Gate::authorize('update', $category);

        $category->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('owner.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        Gate::authorize('delete', $category);

        // Check if category is used by products
        if ($category->products()->count() > 0) {
            return redirect()->route('owner.categories.index')->with('error', 'Kategori tidak boleh dihapus karena sudah digunakan oleh produk.');
        }

        $category->delete();

        return redirect()->route('owner.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
