@extends('layouts.app')

@section('title', 'Perbarui Profil Mitra')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-dark fw-800 mb-1"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>Perbarui Profil Mitra</h2>
            <p class="text-muted">Perbarui data profil usaha serta penanggung jawab (Owner)</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="glass-card">
                <form action="{{ route('admin.umkms.update', $umkm->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" for="owner_id">Penanggung Jawab (Owner) <span class="text-danger">*</span></label>
                        <select name="owner_id" id="owner_id" class="form-select form-control-glass @error('owner_id') is-invalid @enderror" required>
                            @foreach($owners as $owner)
                                <option value="{{ $owner->id }}" {{ old('owner_id', $umkm->owner_id) == $owner->id ? 'selected' : '' }}>
                                    {{ $owner->name }} ({{ $owner->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('owner_id')
                            <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="name">Nama Unit Usaha <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control form-control-glass @error('name') is-invalid @enderror" value="{{ old('name', $umkm->name) }}" required placeholder="Contoh: Butik Cantik Mode">
                            @error('name')
                                <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="business_type">Kategori Usaha <span class="text-danger">*</span></label>
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

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="phone">Nomor Telepon / WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" name="phone" id="phone" class="form-control form-control-glass @error('phone') is-invalid @enderror" value="{{ old('phone', $umkm->phone) }}" required placeholder="Contoh: 0812XXXXXXXX">
                            @error('phone')
                                <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="status">Status Akun Mitra <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select form-control-glass @error('status') is-invalid @enderror" required>
                                <option value="pending" {{ old('status', $umkm->status) == 'pending' ? 'selected' : '' }}>Pending (Menunggu Persetujuan)</option>
                                <option value="active" {{ old('status', $umkm->status) == 'active' ? 'selected' : '' }}>Active (Aktif)</option>
                                <option value="inactive" {{ old('status', $umkm->status) == 'inactive' ? 'selected' : '' }}>Inactive (Dinonaktifkan)</option>
                            </select>
                            @error('status')
                                <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="address">Alamat Operasional <span class="text-danger">*</span></label>
                        <textarea name="address" id="address" rows="3" class="form-control form-control-glass @error('address') is-invalid @enderror" required placeholder="Tuliskan alamat lengkap badan usaha mitra...">{{ old('address', $umkm->address) }}</textarea>
                        @error('address')
                            <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="description">Catatan / Deskripsi Usaha</label>
                        <textarea name="description" id="description" rows="2" class="form-control form-control-glass" placeholder="Keterangan atau profil singkat toko mitra...">{{ old('description', $umkm->description) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('admin.umkms.index') }}" class="btn btn-glow-outline">Batal</a>
                        <button type="submit" class="btn btn-glow-primary">Perbarui Profil Mitra</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
