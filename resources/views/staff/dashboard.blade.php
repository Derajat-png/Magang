@extends('layouts.app')

@section('title', 'Terminal Kasir')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-dark fw-800 mb-1"><i class="fa-solid fa-house-laptop text-primary me-2"></i>Terminal Kasir</h2>
            <p class="text-muted">Akses cepat pembuatan transaksi order baru dan monitoring penjualan kasir hari ini</p>
        </div>
    </div>

    <!-- Stats Widgets -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="glass-card d-flex align-items-center justify-content-between p-4">
                <div>
                    <span class="text-muted fw-600 d-block mb-1" style="font-size:13px;">Order Hari Ini</span>
                    <h2 class="text-dark fw-800 mb-0">{{ $todayOrders }}</h2>
                </div>
                <div class="p-3 rounded-circle" style="background-color: var(--secondary-container);">
                    <i class="fa-solid fa-cart-shopping text-primary" style="font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="glass-card d-flex align-items-center justify-content-between p-4">
                <div>
                    <span class="text-muted fw-600 d-block mb-1" style="font-size:13px;">Pesanan Pending</span>
                    <h2 class="text-warning fw-800 mb-0">{{ $pendingOrders }}</h2>
                </div>
                <div class="p-3 rounded-circle" style="background-color: #fef3c7;">
                    <i class="fa-solid fa-clock text-warning" style="font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="glass-card d-flex align-items-center justify-content-between p-4">
                <div>
                    <span class="text-muted fw-600 d-block mb-1" style="font-size:13px;">Transaksi Lunas (Paid)</span>
                    <h2 class="text-success fw-800 mb-0">{{ $paidTransactions }}</h2>
                </div>
                <div class="p-3 rounded-circle" style="background-color: #d1fae5;">
                    <i class="fa-solid fa-cash-register text-success" style="font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="glass-card d-flex align-items-center justify-content-between p-4">
                <div>
                    <span class="text-muted fw-600 d-block mb-1" style="font-size:13px;">Persediaan Tipis (&lt; 5)</span>
                    <h2 class="text-danger fw-800 mb-0">{{ $lowStockProducts->count() }}</h2>
                </div>
                <div class="p-3 rounded-circle" style="background-color: #fee2e2;">
                    <i class="fa-solid fa-triangle-exclamation text-danger" style="font-size: 24px;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access and Alerts -->
    <div class="row mb-4">
        <!-- Quick Action Card -->
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="glass-card h-100 d-flex flex-column justify-content-center align-items-center text-center py-5">
                <i class="fa-solid fa-cart-plus text-primary mb-3" style="font-size: 50px;"></i>
                <h3 class="text-dark fw-800 mb-2">Kasir (POS)</h3>
                <p class="text-muted mb-4" style="max-width: 350px; font-size:14px;">Input pesanan barang pelanggan baru, hitung total tagihan belanja, dan catat metode pembayaran secara instan.</p>
                <a href="{{ route('staff.orders.create') }}" class="btn btn-glow-primary btn-lg">
                    <i class="fa-solid fa-plus me-2"></i> Buat Order Baru
                </a>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="col-lg-6">
            <div class="glass-card h-100" style="max-height: 350px; overflow-y: auto;">
                <h4 class="text-dark fw-700 mb-3 text-warning"><i class="fa-solid fa-circle-exclamation me-2"></i>Status Peringatan Stok</h4>
                <ul class="list-group list-group-flush bg-transparent">
                    @forelse($lowStockProducts as $prod)
                        <li class="list-group-item bg-transparent text-dark border-secondary px-0 d-flex justify-content-between align-items-center" style="border-color: var(--surface-container) !important;">
                            <div>
                                <span class="fw-600 d-block" style="font-size: 14px;">{{ $prod->name }}</span>
                                <small class="text-muted">SKU: {{ $prod->sku }}</small>
                            </div>
                            <span class="badge bg-danger rounded-pill">{{ $prod->stock }} {{ $prod->unit }}</span>
                        </li>
                    @empty
                        <div class="text-center text-muted py-5">
                            <i class="fa-regular fa-circle-check text-success mb-2" style="font-size: 32px;"></i>
                            <p class="mb-0" style="font-size: 13px;">Tidak ada produk dengan stok menipis saat ini.</p>
                        </div>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Recent Orders table -->
    <div class="row">
        <div class="col-12">
            <div class="glass-card">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h4 class="text-dark fw-700 mb-0"><i class="fa-solid fa-clock-rotate-left me-2 text-primary"></i>Daftar Transaksi Terkini</h4>
                    <a href="{{ route('staff.orders.index') }}" class="btn btn-sm btn-glow-outline">Tampilkan Seluruh Transaksi</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-glass text-dark mb-0">
                        <thead>
                            <tr>
                                <th>ID Order</th>
                                <th>Nama Pelanggan</th>
                                <th>Tanggal Pesan</th>
                                <th>Total Tagihan</th>
                                <th>Status Order</th>
                                <th>Status Bayar</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td class="font-monospace fw-600">#{{ $order->id }}</td>
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
                                                <span class="badge bg-success" style="font-size: 11px;">PAID</span>
                                            @else
                                                <span class="badge bg-warning text-dark" style="font-size: 11px;">UNPAID</span>
                                            @endif
                                        @else
                                            <span class="badge bg-danger" style="font-size: 11px;">NO DATA</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('staff.orders.show', $order->id) }}" class="btn btn-sm btn-outline-dark" style="border-radius: 8px;">
                                            <i class="fa-solid fa-cash-register me-1"></i> Detail / Proses
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada transaksi terekam saat ini.</td>
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
