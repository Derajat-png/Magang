@extends('layouts.app')

@section('title', 'Kontrol Akses Akun')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="text-dark fw-800 mb-1"><i class="fa-solid fa-users text-primary me-2"></i>Kontrol Akses Akun</h2>
            <p class="text-muted">Manajemen hak akses pengguna, status penangguhan, dan relasi UMKM</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-glow-primary">
            <i class="fa-solid fa-user-plus me-2"></i> Tambah Akun
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="glass-card mb-4">
        <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="keyword" class="form-control form-control-glass" value="{{ request('keyword') }}" placeholder="Cari nama atau email...">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select form-control-glass">
                    <option value="">Semua Role</option>
                    <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="owner" {{ request('role') === 'owner' ? 'selected' : '' }}>Owner (Pemilik)</option>
                    <option value="staff" {{ request('role') === 'staff' ? 'selected' : '' }}>Staff (Kasir)</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-control-glass">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active (Aktif)</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive (Ditangguhkan)</option>
                </select>
            </div>
            <div class="col-md-3 d-grid">
                <button type="submit" class="btn btn-glow-outline">
                    <i class="fa-solid fa-magnifying-glass me-2"></i> Cari Akun
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
                        <th>Nama Pengguna</th>
                        <th>Alamat Email</th>
                        <th>Role Akses</th>
                        <th>Afiliasi Unit Usaha</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td><span class="fw-700 text-dark">{{ $user->name }}</span></td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->isSuperAdmin())
                                    <span class="badge bg-danger">Super Admin</span>
                                @elseif($user->isOwner())
                                    <span class="badge bg-primary">Owner</span>
                                @else
                                    <span class="badge bg-info text-dark">Staff / Kasir</span>
                                @endif
                            </td>
                            <td>
                                {{ $user->umkm ? $user->umkm->name : '-' }}
                            </td>
                            <td>
                                @if($user->status === 'active')
                                    <span class="badge-glass-success">Active</span>
                                @else
                                    <span class="badge-glass-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning me-2" style="border-radius: 8px;">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $user->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success' }}" style="border-radius: 8px;">
                                            <i class="fa-solid {{ $user->status === 'active' ? 'fa-ban' : 'fa-check' }} me-1"></i>
                                            {{ $user->status === 'active' ? 'Tangguhkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada akun pengguna terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4 pagination-glass">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
