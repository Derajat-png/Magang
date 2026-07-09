@extends('layouts.public')

@section('title', 'Login Portal Bisnis')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center py-5">
        <div class="col-md-5">
            <div class="glass-card">
                <div class="text-center mb-4">
                    <i class="fa-solid fa-shield-halved text-primary" style="font-size: 40px;"></i>
                    <h3 class="text-dark mt-3 fw-800">Portal Bisnis</h3>
                    <p class="text-muted">Masuk untuk mengelola inventori dan kas toko Anda</p>
                </div>

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3 text-start">
                        <label class="form-label" for="email">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0" style="border-color:var(--surface-dim); color:var(--text-muted);">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <input type="email" name="email" id="email" class="form-control form-control-glass border-start-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="nama@email.com">
                        </div>
                        @error('email')
                            <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 text-start">
                        <label class="form-label" for="password">Kata Sandi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0" style="border-color:var(--surface-dim); color:var(--text-muted);">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input type="password" name="password" id="password" class="form-control form-control-glass border-start-0" required placeholder="••••••••">
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-glow-primary py-2.5">
                            <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk Ke Akun
                        </button>
                    </div>

                    <div class="text-center text-muted" style="font-size: 14px;">
                        Belum terdaftar? <a href="{{ route('register-umkm') }}" class="text-primary text-decoration-none fw-600">Daftarkan usaha baru</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
