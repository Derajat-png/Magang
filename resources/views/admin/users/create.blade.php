@extends('layouts.app')

@section('title', 'Daftarkan Akun Pengguna Baru')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-dark fw-800 mb-1"><i class="fa-solid fa-user-plus text-primary me-2"></i>Daftarkan Akun Baru</h2>
            <p class="text-muted">Buat kredensial login pengguna sistem baru</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="glass-card">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="name">Nama Lengkap Pengguna <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control form-control-glass @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Nama lengkap">
                        @error('name')
                            <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="email">Alamat Email Login <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control form-control-glass @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="nama@email.com">
                        @error('email')
                            <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="role">Role / Tingkatan Hak Akses <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-select form-control-glass @error('role') is-invalid @enderror" required>
                                <option value="" disabled selected>Pilih role...</option>
                                <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner (Pemilik UMKM)</option>
                                <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff (Kasir Toko)</option>
                            </select>
                            @error('role')
                                <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="status">Status Keaktifan Akun <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select form-control-glass @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active (Aktif)</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive (Ditangguhkan)</option>
                            </select>
                            @error('status')
                                <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="password">Kata Sandi Akses <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="form-control form-control-glass @error('password') is-invalid @enderror" required placeholder="Minimal 8 karakter">
                        @error('password')
                            <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-glow-outline">Batal</a>
                        <button type="submit" class="btn btn-glow-primary">Daftarkan Pengguna</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
