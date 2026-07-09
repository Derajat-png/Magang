@extends('layouts.app')

@section('title', 'Perbarui Data Kasir')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-dark fw-800 mb-1"><i class="fa-solid fa-user-pen text-primary me-2"></i>Perbarui Data Kasir</h2>
            <p class="text-muted">Perbarui data profil kredensial login staf kasir Anda</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="glass-card">
                <form action="{{ route('owner.staff.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" for="name">Nama Lengkap Staf <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control form-control-glass @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required placeholder="Nama lengkap staf">
                        @error('name')
                            <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="email">Alamat Email Login <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control form-control-glass @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required placeholder="staff@email.com">
                        @error('email')
                            <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="password">Kata Sandi Baru (Kosongkan jika tidak ingin diubah)</label>
                        <input type="password" name="password" id="password" class="form-control form-control-glass @error('password') is-invalid @enderror" placeholder="••••••••">
                        @error('password')
                            <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('owner.staff.index') }}" class="btn btn-glow-outline">Batal</a>
                        <button type="submit" class="btn btn-glow-primary">Perbarui Kredensial Staf</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
