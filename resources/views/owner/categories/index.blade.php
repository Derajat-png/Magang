@extends('layouts.app')

@section('title', 'Kelola Kategori Produk')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-dark fw-800 mb-1"><i class="fa-solid fa-tags text-primary me-2"></i>Kategori Produk</h2>
            <p class="text-muted">Kelola pengelompokan produk barang atau jasa pada toko Anda</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Add Category Form -->
        <div class="col-md-5">
            <div class="glass-card">
                <h5 class="text-dark fw-700 mb-4"><i class="fa-solid fa-folder-plus text-primary me-2"></i>Tambah Kategori</h5>
                
                <form action="{{ route('owner.categories.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label" for="name">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control form-control-glass @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Contoh: Makanan Berat" minlength="3">
                        @error('name')
                            <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="status">Status Kategori <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select form-control-glass">
                            <option value="active">Active (Tampil di katalog)</option>
                            <option value="inactive">Inactive (Disembunyikan)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-glow-primary w-100">
                        <i class="fa-solid fa-plus me-1"></i> Tambahkan Kategori
                    </button>
                </form>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="col-md-7">
            <div class="glass-card">
                <h5 class="text-dark fw-700 mb-4"><i class="fa-solid fa-list text-primary me-2"></i>Daftar Kategori Terdaftar</h5>
                <div class="table-responsive">
                    <table class="table table-glass text-dark mb-0">
                        <thead>
                            <tr>
                                <th>Nama Kategori</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $cat)
                                <tr>
                                    <td><span class="fw-700 text-dark">{{ $cat->name }}</span></td>
                                    <td>
                                        @if($cat->status === 'active')
                                            <span class="badge-glass-success">Active</span>
                                        @else
                                            <span class="badge-glass-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('owner.categories.destroy', $cat->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;" onclick="confirmDelete(event, 'Kategori ini akan dihapus. Produk di dalamnya tidak akan terhapus otomatis.')">
                                                <i class="fa-solid fa-trash-can"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada kategori produk terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4 pagination-glass">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
