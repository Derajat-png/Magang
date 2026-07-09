@extends('layouts.app')

@section('title', 'Inventori Produk')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="text-dark fw-800 mb-1"><i class="fa-solid fa-boxes-stacked text-primary me-2"></i>Inventori Produk</h2>
            <p class="text-muted">Kelola persediaan barang, SKU, harga jual, dan status pajang produk</p>
        </div>
        <a href="{{ route('owner.products.create') }}" class="btn btn-glow-primary">
            <i class="fa-solid fa-plus me-2"></i> Tambah Produk Baru
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="glass-card mb-4">
        <form action="{{ route('owner.products.index') }}" method="GET" class="row g-3">
            <div class="col-md-5">
                <input type="text" name="keyword" class="form-control form-control-glass" value="{{ request('keyword') }}" placeholder="Cari nama atau SKU produk...">
            </div>
            <div class="col-md-4">
                <select name="category_id" class="form-select form-control-glass">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-grid">
                <button type="submit" class="btn btn-glow-outline">
                    <i class="fa-solid fa-magnifying-glass me-2"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="glass-card">
        <div class="table-responsive">
            <table class="table table-glass text-dark mb-0">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Kode SKU</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga Jual</th>
                        <th>Stok Saat Ini</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td style="width: 80px;">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="rounded border" style="height: 50px; width: 50px; object-fit: cover;" alt="{{ $product->name }}">
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light text-muted rounded border" style="height: 50px; width: 50px; border: 1px dashed var(--surface-dim) !important;">
                                        <i class="fa-regular fa-image" style="font-size: 18px;"></i>
                                    </div>
                                @endif
                            </td>
                            <td><span class="font-monospace fw-600 text-dark">{{ $product->sku }}</span></td>
                            <td><span class="fw-700 text-dark" style="font-size: 15px;">{{ $product->name }}</span></td>
                            <td>{{ $product->category ? $product->category->name : '-' }}</td>
                            <td><span class="text-primary fw-700">Rp {{ number_format($product->price, 0, ',', '.') }}</span></td>
                            <td>
                                @if($product->stock < 10)
                                    <span class="text-danger fw-700"><i class="fa-solid fa-circle-exclamation me-1"></i> {{ $product->stock }} {{ $product->unit }}</span>
                                @else
                                    <span class="text-dark fw-600">{{ $product->stock }} {{ $product->unit }}</span>
                                @endif
                            </td>
                            <td>
                                @if($product->status === 'active')
                                    <span class="badge-glass-success">Active</span>
                                @else
                                    <span class="badge-glass-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('owner.products.edit', $product->id) }}" class="btn btn-sm btn-outline-warning me-2" style="border-radius: 8px;">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <form action="{{ route('owner.products.destroy', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;" onclick="confirmDelete(event, 'Produk ini beserta log mutasi stoknya akan dihapus.')">
                                        <i class="fa-solid fa-trash-can"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada produk yang didaftarkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4 pagination-glass">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
