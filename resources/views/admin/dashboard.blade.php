@extends('layouts.app')

@section('title', 'Pusat Kendali Global')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-dark fw-800 mb-1"><i class="fa-solid fa-chart-pie text-primary me-2"></i>Pusat Kendali Global</h2>
            <p class="text-muted">Ringkasan data kemitraan, jumlah operator, dan total volume transaksi seluruh unit usaha</p>
        </div>
    </div>

    <!-- Stats Widgets -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="glass-card d-flex align-items-center justify-content-between p-4">
                <div>
                    <span class="text-muted fw-600 d-block mb-1" style="font-size:13px;">Mitra UMKM Aktif</span>
                    <h2 class="text-dark fw-800 mb-0">{{ $totalUmkmActive }}</h2>
                </div>
                <div class="p-3 rounded-circle" style="background-color: var(--secondary-container);">
                    <i class="fa-solid fa-shop text-primary" style="font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="glass-card d-flex align-items-center justify-content-between p-4">
                <div>
                    <span class="text-muted fw-600 d-block mb-1" style="font-size:13px;">Akun Owner</span>
                    <h2 class="text-dark fw-800 mb-0">{{ $totalOwner }}</h2>
                </div>
                <div class="p-3 rounded-circle" style="background-color: var(--secondary-container);">
                    <i class="fa-solid fa-user-tie text-primary" style="font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="glass-card d-flex align-items-center justify-content-between p-4">
                <div>
                    <span class="text-muted fw-600 d-block mb-1" style="font-size:13px;">Akun Kasir / Staf</span>
                    <h2 class="text-dark fw-800 mb-0">{{ $totalStaff }}</h2>
                </div>
                <div class="p-3 rounded-circle" style="background-color: var(--secondary-container);">
                    <i class="fa-solid fa-users text-primary" style="font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="glass-card d-flex align-items-center justify-content-between p-4">
                <div>
                    <span class="text-muted fw-600 d-block mb-1" style="font-size:13px;">Volume Omzet Global</span>
                    <h4 class="text-primary fw-800 mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                </div>
                <div class="p-3 rounded-circle" style="background-color: #d1fae5;">
                    <i class="fa-solid fa-wallet text-success" style="font-size: 24px;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Global Transactions -->
        <div class="col-12">
            <div class="glass-card">
                <h4 class="text-dark fw-700 mb-4"><i class="fa-solid fa-clock-rotate-left me-2 text-primary"></i>Daftar Transaksi Terbaru</h4>
                <div class="table-responsive">
                    <table class="table table-glass text-dark">
                        <thead>
                            <tr>
                                <th>ID Order</th>
                                <th>Unit Usaha (UMKM)</th>
                                <th>Nama Pelanggan</th>
                                <th>Waktu Transaksi</th>
                                <th>Total Belanja</th>
                                <th>Status Order</th>
                                <th>Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td class="font-monospace fw-600">#{{ $order->id }}</td>
                                    <td><span class="fw-700 text-dark">{{ $order->umkm->name }}</span></td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ $order->order_date->format('d M Y H:i') }}</td>
                                    <td><span class="text-primary fw-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></td>
                                    <td>
                                        @if($order->status === 'completed')
                                            <span class="badge-glass-success">Completed</span>
                                        @elseif($order->status === 'cancelled')
                                            <span class="badge-glass-danger">Cancelled</span>
                                        @elseif($order->status === 'pending')
                                            <span class="badge-glass-warning">Pending</span>
                                        @else
                                            <span class="badge-glass-info">{{ ucfirst($order->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->payment)
                                            @if($order->payment->payment_status === 'paid')
                                                <span class="badge bg-success" style="font-size:11px;">PAID ({{ strtoupper($order->payment->payment_method) }})</span>
                                            @else
                                                <span class="badge bg-warning text-dark" style="font-size:11px;">UNPAID ({{ strtoupper($order->payment->payment_method) }})</span>
                                            @endif
                                        @else
                                            <span class="badge bg-danger" style="font-size:11px;">BELUM ADA PEMBAYARAN</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada aktivitas transaksi terekam saat ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
