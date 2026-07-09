@extends('layouts.app')

@section('title', 'Profil Usaha')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-dark fw-800 mb-1"><i class="fa-solid fa-store text-primary me-2"></i>Profil Usaha</h2>
            <p class="text-muted">Kelola data legalitas, jenis bisnis, kontak, dan keterangan publik UMKM Anda</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="glass-card">
                <form action="{{ route('owner.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="name">Nama UMKM <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control form-control-glass @error('name') is-invalid @enderror" value="{{ old('name', $umkm->name) }}" required placeholder="Nama usaha Anda">
                            @error('name')
                                <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="business_type">Kategori Bisnis <span class="text-danger">*</span></label>
                            <select name="business_type" id="business_type" class="form-select form-control-glass @error('business_type') is-invalid @enderror" required>
                                <option value="kuliner" {{ old('business_type', $umkm->business_type) == 'kuliner' ? 'selected' : '' }}>Kuliner</option>
                                <option value="fashion" {{ old('business_type', $umkm->business_type) == 'fashion' ? 'selected' : '' }}>Fashion</option>
                                <option value="kerajinan" {{ old('business_type', $umkm->business_type) == 'kerajinan' ? 'selected' : '' }}>Kerajinan</option>
                                <option value="jasa" {{ old('business_type', $umkm->business_type) == 'jasa' ? 'selected' : '' }}>Jasa</option>
                                <option value="pertanian" {{ old('business_type', $umkm->business_type) == 'pertanian' ? 'selected' : '' }}>Pertanian</option>
                                <option value="perikanan" {{ old('business_type', $umkm->business_type) == 'perikanan' ? 'selected' : '' }}>Perikanan</option>
                            </select>
                            @error('business_type')
                                <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="phone">Nomor Telepon / WhatsApp Kontak <span class="text-danger">*</span></label>
                        <input type="text" name="phone" id="phone" class="form-control form-control-glass @error('phone') is-invalid @enderror" value="{{ old('phone', $umkm->phone) }}" required placeholder="Contoh: 0812XXXXXXXX">
                        @error('phone')
                            <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="address">Alamat Fisik Usaha <span class="text-danger">*</span></label>
                        <textarea name="address" id="address" rows="3" class="form-control form-control-glass @error('address') is-invalid @enderror" required placeholder="Tuliskan alamat lengkap lokasi usaha...">{{ old('address', $umkm->address) }}</textarea>
                        @error('address')
                            <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="description">Deskripsi Singkat Usaha (Publik)</label>
                        <textarea name="description" id="description" rows="3" class="form-control form-control-glass" placeholder="Tuliskan profil singkat usaha Anda yang akan tampil di halaman utama pengunjung...">{{ old('description', $umkm->description) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-glow-primary">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Perbarui Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
