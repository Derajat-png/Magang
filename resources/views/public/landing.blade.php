@extends('layouts.public')

@section('title', 'Direktori Mitra UMKM')

@section('content')
<!-- Hero Area -->
<section class="hero-section text-center text-dark">
    <div class="container py-5">
        <h1 class="display-4 fw-800 mb-3" style="letter-spacing: -1.5px; background: linear-gradient(135deg, var(--text-main), var(--secondary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            Manajemen Operasional UMKM Lebih Teratur
        </h1>
        <p class="lead text-muted mx-auto mb-4" style="max-width: 650px; font-size:17px; line-height: 1.6;">
            Direktori kemitraan usaha mikro, kecil, dan menengah. Jelajahi berbagai unit usaha unggulan, akses katalog produk, dan lakukan transaksi dengan praktis.
        </p>
        <div>
            @guest
                <a href="{{ route('register-umkm') }}" class="btn btn-glow-primary btn-lg px-4 py-2.5">
                    <i class="fa-solid fa-store me-2"></i> Daftarkan Usaha Anda
                </a>
            @endguest
        </div>
    </div>
</section>

<!-- Active UMKM List -->
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="text-dark fw-800"><i class="fa-solid fa-layer-group text-primary me-2"></i>Mitra UMKM Aktif</h2>
            <p class="text-muted">Jelajahi berbagai unit usaha yang bermitra secara aktif</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($umkms as $umkm)
            <div class="col-md-4">
                <div class="glass-card h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge-glass-info text-capitalize">{{ $umkm->business_type }}</span>
                            <span class="text-muted" style="font-size:12px;"><i class="fa-solid fa-circle text-success me-1" style="font-size: 8px;"></i> Aktif</span>
                        </div>
                        <h4 class="text-dark fw-800 mb-2" style="font-size:18px;">{{ $umkm->name }}</h4>
                        <p class="text-muted mb-3" style="font-size:14px; min-height: 42px; line-height:1.5;">
                            {{ Str::limit($umkm->description ?? 'Unit usaha ini belum menambahkan deskripsi profil ringkas.', 80) }}
                        </p>
                        <hr class="border-secondary opacity-10">
                        <div class="text-muted" style="font-size:13px;">
                            <p class="mb-1"><i class="fa-solid fa-map-pin me-2 text-danger"></i> {{ Str::limit($umkm->address, 50) }}</p>
                            <p class="mb-0"><i class="fa-solid fa-phone me-2 text-success"></i> {{ $umkm->phone }}</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-2">
                        <a href="{{ route('public.umkm.catalog', $umkm->id) }}" class="btn btn-glow-outline w-100">
                            <i class="fa-solid fa-boxes-stacked me-2"></i> Buka Katalog Produk
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="text-muted">
                    <i class="fa-solid fa-circle-exclamation" style="font-size: 40px;"></i>
                    <p class="mt-3">Belum ada mitra usaha yang aktif saat ini.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-5 pagination-glass">
        {{ $umkms->links() }}
    </div>
</div>
@endsection
