@extends('layouts.app')

@section('title', 'Mitra UMKM')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="text-dark fw-800 mb-1"><i class="fa-solid fa-shop text-primary me-2"></i>Kemitraan UMKM</h2>
            <p class="text-muted">Kelola unit usaha mitra terdaftar dan status verifikasinya</p>
        </div>
        <a href="{{ route('admin.umkms.create') }}" class="btn btn-glow-primary">
            <i class="fa-solid fa-plus me-2"></i> Tambah Mitra Usaha
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="glass-card mb-4">
        <form action="{{ route('admin.umkms.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Status Verifikasi</label>
                <select name="status" class="form-select form-control-glass">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active (Aktif)</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive (Nonaktif)</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Kategori Unit Usaha</label>
                <select name="type" class="form-select form-control-glass">
                    <option value="">Semua Kategori</option>
                    <option value="kuliner" {{ request('type') === 'kuliner' ? 'selected' : '' }}>Kuliner</option>
                    <option value="fashion" {{ request('type') === 'fashion' ? 'selected' : '' }}>Fashion</option>
                    <option value="kerajinan" {{ request('type') === 'kerajinan' ? 'selected' : '' }}>Kerajinan</option>
                    <option value="jasa" {{ request('type') === 'jasa' ? 'selected' : '' }}>Jasa</option>
                    <option value="pertanian" {{ request('type') === 'pertanian' ? 'selected' : '' }}>Pertanian</option>
                    <option value="perikanan" {{ request('type') === 'perikanan' ? 'selected' : '' }}>Perikanan</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-glow-outline w-100 py-2.5">
                    <i class="fa-solid fa-filter me-2"></i> Terapkan Pencarian
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
                        <th>ID</th>
                        <th>Nama UMKM</th>
                        <th>Kategori</th>
                        <th>Nama Pemilik</th>
                        <th>Kontak Telepon</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($umkms as $umkm)
                        <tr>
                            <td>{{ $umkm->id }}</td>
                            <td>
                                <span class="fw-700 text-dark d-block" style="font-size:15px;">{{ $umkm->name }}</span>
                                <span class="text-muted" style="font-size: 12px;">{{ Str::limit($umkm->address, 50) }}</span>
                            </td>
                            <td><span class="badge-glass-info text-capitalize">{{ $umkm->business_type }}</span></td>
                            <td>{{ $umkm->owner ? $umkm->owner->name : 'Belum Ditautkan' }}</td>
                            <td>{{ $umkm->phone }}</td>
                            <td>
                                @if($umkm->status === 'active')
                                    <span class="badge-glass-success">Active</span>
                                @elseif($umkm->status === 'pending')
                                    <span class="badge-glass-warning">Pending</span>
                                @else
                                    <span class="badge-glass-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.umkms.edit', $umkm->id) }}" class="btn btn-sm btn-outline-warning me-2" style="border-radius: 8px;">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <form action="{{ route('admin.umkms.destroy', $umkm->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;" onclick="confirmDelete(event, 'Mitra usaha beserta datanya akan dinonaktifkan atau dihapus dari sistem.')">
                                        <i class="fa-solid fa-trash-can"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada mitra usaha yang terdaftar di sistem.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-4 pagination-glass">
            {{ $umkms->links() }}
        </div>
    </div>
</div>
@endsection
