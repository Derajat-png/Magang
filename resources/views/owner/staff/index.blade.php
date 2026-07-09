@extends('layouts.app')

@section('title', 'Anggota Kasir')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="text-dark fw-800 mb-1"><i class="fa-solid fa-user-group text-primary me-2"></i>Anggota Kasir</h2>
            <p class="text-muted">Kelola akun staf penjualan atau kasir yang bertugas di toko Anda</p>
        </div>
        <a href="{{ route('owner.staff.create') }}" class="btn btn-glow-primary">
            <i class="fa-solid fa-user-plus me-2"></i> Daftarkan Kasir
        </a>
    </div>

    <!-- Table -->
    <div class="glass-card">
        <div class="table-responsive">
            <table class="table table-glass text-dark mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kasir</th>
                        <th>Alamat Email</th>
                        <th>Peran</th>
                        <th>Status Akun</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staffs as $staff)
                        <tr>
                            <td>{{ $staff->id }}</td>
                            <td><span class="fw-700 text-dark">{{ $staff->name }}</span></td>
                            <td>{{ $staff->email }}</td>
                            <td><span class="badge bg-secondary text-white" style="font-size: 11px;">Staf Kasir</span></td>
                            <td>
                                @if($staff->status === 'active')
                                    <span class="badge-glass-success">Active</span>
                                @else
                                    <span class="badge-glass-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('owner.staff.edit', $staff->id) }}" class="btn btn-sm btn-outline-warning me-2" style="border-radius: 8px;">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <form action="{{ route('owner.staff.toggle-status', $staff->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $staff->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success' }}" style="border-radius: 8px;">
                                        <i class="fa-solid {{ $staff->status === 'active' ? 'fa-ban' : 'fa-check' }} me-1"></i>
                                        {{ $staff->status === 'active' ? 'Tangguhkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada staf kasir terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4 pagination-glass">
            {{ $staffs->links() }}
        </div>
    </div>
</div>
@endsection
