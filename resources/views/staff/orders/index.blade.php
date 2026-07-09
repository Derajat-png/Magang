@extends('layouts.app')

@section('title', 'Transaksi Order UMKM')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="text-dark fw-800 mb-1"><i class="fa-solid fa-receipt text-primary me-2"></i>Daftar Order UMKM</h2>
            <p class="text-muted">Kelola status dan pencatatan transaksi kasir toko</p>
        </div>
        <a href="{{ route('staff.orders.create') }}" class="btn btn-glow-primary">
            <i class="fa-solid fa-cart-plus me-2"></i> Buat Order Baru
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="glass-card mb-4">
        <form action="{{ route('staff.orders.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label" style="font-size:13px;">Nama Customer</label>
                <input type="text" name="customer" class="form-control form-control-glass" value="{{ request('customer') }}" placeholder="Cari nama pelanggan...">
            </div>
            <div class="col-md-3">
                <label class="form-label" style="font-size:13px;">Tanggal Pesanan</label>
                <input type="date" name="date" class="form-control form-control-glass" value="{{ request('date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label" style="font-size:13px;">Status Order</label>
                <select name="status" class="form-select form-control-glass">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processed" {{ request('status') === 'processed' ? 'selected' : '' }}>Processed</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-glow-outline w-100 py-2.5">
                    <i class="fa-solid fa-filter me-2"></i> Filter
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
                        <th>ID Order</th>
                        <th>Nama Pelanggan</th>
                        <th>Tanggal Pesan</th>
                        <th>Total Tagihan</th>
                        <th>Status Order</th>
                        <th>Pembayaran</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="font-monospace fw-600">#{{ $order->id }}</td>
                            <td>
                                <span class="fw-700 text-dark d-block" style="font-size:14px;">{{ $order->customer_name }}</span>
                                <small class="text-muted">{{ $order->customer_phone ?? 'Tidak ada No HP' }}</small>
                            </td>
                            <td>{{ $order->order_date->format('d M Y H:i') }}</td>
                            <td><span class="text-primary fw-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></td>
                            <td>
                                @if($order->status === 'completed')
                                    <span class="badge-glass-success">Completed</span>
                                @elseif($order->status === 'cancelled')
                                    <span class="badge-glass-danger">Cancelled</span>
                                @elseif($order->status === 'pending')
                                    <span class="badge-glass-warning">Pending</span>
                                @elseif($order->status === 'processed')
                                    <span class="badge-glass-info">Processed</span>
                                @else
                                    <span class="badge bg-secondary text-white">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($order->payment)
                                    @if($order->payment->payment_status === 'paid')
                                        <span class="badge bg-success" style="font-size: 11px;">PAID</span>
                                    @elseif($order->payment->payment_status === 'failed')
                                        <span class="badge bg-dark" style="font-size: 11px;">FAILED</span>
                                    @else
                                        <span class="badge bg-warning text-dark" style="font-size: 11px;">UNPAID ({{ strtoupper($order->payment->payment_method) }})</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary text-white" style="font-size: 11px;">BELUM DIBAYAR</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('staff.orders.show', $order->id) }}" class="btn btn-sm btn-glow-outline" style="border-radius: 8px;">
                                    <i class="fa-solid fa-cash-register me-1"></i> Proses / Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada transaksi order terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4 pagination-glass">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
