@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-dark fw-800 mb-1"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>Edit Produk</h2>
            <p class="text-muted">Perbarui data detail produk dan level persediaan barang</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="glass-card">
                <form action="{{ route('owner.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Product Basic Info -->
                        <div class="col-md-7">
                            <h5 class="text-dark mb-4"><i class="fa-solid fa-circle-info text-primary me-2"></i>Informasi Dasar</h5>
                            
                            <div class="mb-3">
                                <label class="form-label" for="name">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control form-control-glass @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required placeholder="Nama produk" minlength="3">
                                @error('name')
                                    <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="sku">Kode SKU <span class="text-danger">*</span></label>
                                    <input type="text" name="sku" id="sku" class="form-control form-control-glass @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku) }}" required placeholder="Contoh: PROD-001">
                                    @error('sku')
                                        <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="category_id">Kategori Produk <span class="text-danger">*</span></label>
                                    <select name="category_id" id="category_id" class="form-select form-control-glass @error('category_id') is-invalid @enderror" required>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="price">Harga Produk (Rupiah) <span class="text-danger">*</span></label>
                                    <input type="number" name="price" id="price" class="form-control form-control-glass @error('price') is-invalid @enderror" value="{{ old('price', intval($product->price)) }}" required placeholder="Minimal Rp 100" min="100">
                                    @error('price')
                                        <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label" for="stock">Stok Produk <span class="text-danger">*</span></label>
                                    <input type="number" name="stock" id="stock" class="form-control form-control-glass @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}" required min="0">
                                    @error('stock')
                                        <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label" for="unit">Satuan Unit <span class="text-danger">*</span></label>
                                    <input type="text" name="unit" id="unit" class="form-control form-control-glass @error('unit') is-invalid @enderror" value="{{ old('unit', $product->unit) }}" required placeholder="pcs, kg, pack">
                                    @error('unit')
                                        <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="description">Deskripsi Produk</label>
                                <textarea name="description" id="description" rows="3" class="form-control form-control-glass" placeholder="Keterangan lengkap detail produk barang...">{{ old('description', $product->description) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="status">Status Produk <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select form-control-glass @error('status') is-invalid @enderror" required>
                                    <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Product Image & Preview -->
                        <div class="col-md-5 ps-md-4 mt-4 mt-md-0 border-start border-light" style="border-color: var(--surface-container) !important;">
                            <h5 class="text-dark mb-4"><i class="fa-solid fa-image text-secondary me-2"></i>Gambar Produk</h5>

                            <div class="mb-4 text-center">
                                <div class="rounded border d-flex flex-column align-items-center justify-content-center bg-light p-4" style="height: 250px; border: 2px dashed var(--surface-dim) !important;">
                                    @if($product->image)
                                        <img id="image-preview" src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded" style="max-height: 220px; object-fit: contain;" alt="{{ $product->name }}">
                                        <div id="image-preview-placeholder" class="d-none">
                                            <i class="fa-regular fa-image text-muted d-block mb-2" style="font-size: 60px;"></i>
                                            <span class="text-muted" style="font-size:13px;">Format: JPG, PNG, WEBP</span>
                                        </div>
                                    @else
                                        <img id="image-preview" class="img-fluid rounded d-none" style="max-height: 220px; object-fit: contain;" alt="Preview">
                                        <div id="image-preview-placeholder">
                                            <i class="fa-regular fa-image text-muted d-block mb-2" style="font-size: 60px;"></i>
                                            <span class="text-muted" style="font-size:13px;">Format: JPG, PNG, WEBP (Maks 2MB)</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="image">Ubah Gambar</label>
                                <input type="file" name="image" id="image" class="form-control form-control-glass @error('image') is-invalid @enderror" onchange="previewImage(event)">
                                @error('image')
                                    <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 border-light" style="border-color: var(--surface-container) !important;">

                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('owner.products.index') }}" class="btn btn-glow-outline">Batal</a>
                        <button type="submit" class="btn btn-glow-primary">
                            <i class="fa-solid fa-save me-1"></i> Perbarui Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('image-preview');
                output.src = reader.result;
                output.classList.remove('d-none');
                document.getElementById('image-preview-placeholder').classList.add('d-none');
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
