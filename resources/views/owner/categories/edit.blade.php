@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-white fw-800 mb-1"><i class="fa-solid fa-pen-to-square text-accent-glow me-2"></i>Edit Kategori</h2>
            <p class="text-muted">Perbarui nama dan status kategori produk Anda</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="glass-card">
                <form action="{{ route('owner.categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" for="name">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control form-control-glass @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required placeholder="Contoh: Makanan Utama" minlength="2">
                        @error('name')
                            <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="status">Status Kategori <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select form-control-glass @error('status') is-invalid @enderror" required>
                            <option value="active" {{ old('status', $category->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $category->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('owner.categories.index') }}" class="btn btn-glow-outline">Batal</a>
                        <button type="submit" class="btn btn-glow-primary">Perbarui Kategori</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
