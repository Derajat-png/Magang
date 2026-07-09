@extends('layouts.public')

@section('title', 'Daftarkan Kemitraan UMKM')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="glass-card">
                <div class="text-center mb-5">
                    <i class="fa-solid fa-file-signature text-primary" style="font-size: 40px;"></i>
                    <h3 class="text-dark mt-3 fw-800">Registrasi Mitra Baru</h3>
                    <p class="text-muted">Isi data pemilik dan kelengkapan profil usaha untuk pengajuan kemitraan baru</p>
                </div>

                <form action="{{ route('register-umkm') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Owner Info -->
                        <div class="col-md-6 border-end border-secondary pe-md-4">
                            <h5 class="text-dark mb-4"><i class="fa-solid fa-circle-user me-2 text-primary"></i>Data Pemilik</h5>
                            
                            <div class="mb-3">
                                <label class="form-label" for="name">Nama Lengkap</label>
                                <input type="text" name="name" id="name" class="form-control form-control-glass @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Nama lengkap Anda">
                                @error('name')
                                    <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="email">Alamat Email Aktif</label>
                                <input type="email" name="email" id="email" class="form-control form-control-glass @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="nama@email.com">
                                @error('email')
                                    <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="password">Kata Sandi Akses</label>
                                <input type="password" name="password" id="password" class="form-control form-control-glass @error('password') is-invalid @enderror" required placeholder="Minimal 8 karakter">
                                @error('password')
                                    <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="password_confirmation">Ulangi Kata Sandi</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-glass" required placeholder="Konfirmasi kata sandi">
                            </div>
                        </div>

                        <!-- UMKM Info -->
                        <div class="col-md-6 ps-md-4 mt-4 mt-md-0">
                            <h5 class="text-dark mb-4"><i class="fa-solid fa-shop me-2 text-secondary"></i>Profil Badan Usaha</h5>

                            <div class="mb-3">
                                <label class="form-label" for="umkm_name">Nama Toko / UMKM</label>
                                <input type="text" name="umkm_name" id="umkm_name" class="form-control form-control-glass @error('umkm_name') is-invalid @enderror" value="{{ old('umkm_name') }}" required placeholder="Contoh: Butik Cantik Fashion">
                                @error('umkm_name')
                                    <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="business_type">Jenis Usaha</label>
                                <select name="business_type" id="business_type" class="form-select form-control-glass @error('business_type') is-invalid @enderror" required>
                                    <option value="" disabled selected>Pilih kategori usaha...</option>
                                    <option value="kuliner" {{ old('business_type') == 'kuliner' ? 'selected' : '' }}>Kuliner (Makanan & Minuman)</option>
                                    <option value="fashion" {{ old('business_type') == 'fashion' ? 'selected' : '' }}>Fashion (Pakaian & Aksesoris)</option>
                                    <option value="kerajinan" {{ old('business_type') == 'kerajinan' ? 'selected' : '' }}>Kerajinan Tangan (Handicraft)</option>
                                    <option value="jasa" {{ old('business_type') == 'jasa' ? 'selected' : '' }}>Jasa</option>
                                    <option value="pertanian" {{ old('business_type') == 'pertanian' ? 'selected' : '' }}>Pertanian</option>
                                    <option value="perikanan" {{ old('business_type') == 'perikanan' ? 'selected' : '' }}>Perikanan</option>
                                </select>
                                @error('business_type')
                                    <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="phone">Nomor Telepon / WhatsApp</label>
                                <input type="text" name="phone" id="phone" class="form-control form-control-glass @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required placeholder="Contoh: 0812XXXXXXXX">
                                @error('phone')
                                    <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="address">Alamat Operasional</label>
                                <textarea name="address" id="address" rows="2" class="form-control form-control-glass @error('address') is-invalid @enderror" required placeholder="Alamat fisik usaha Anda">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 border-secondary opacity-25">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <span class="text-muted" style="font-size:14px;">Sudah terdaftar? <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-600">Login di sini</a></span>
                        <button type="submit" class="btn btn-glow-primary">
                            <i class="fa-solid fa-paper-plane me-2"></i> Kirim Pengajuan Kemitraan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
