@extends('layouts.app')

@section('title', 'Transaksi Order Toko')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="text-dark fw-800 mb-1"><i class="fa-solid fa-receipt text-primary me-2"></i>Transaksi Order</h2>
            <p class="text-muted">Daftar transaksi pesanan barang toko Anda serta filter pencarian laporan</p>
        </div>
        <div>
            <a href="{{ route('owner.orders.export', request()->query()) }}" class="btn btn-success" style="border-radius:10px; font-weight:600; padding:10px 20px;">
                <i class="fa-solid fa-file-excel me-2"></i> Ekspor Laporan CSV
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="glass-card mb-4">
        <form action="{{ route('owner.orders.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Nama Pelanggan</label>
                <input type="text" name="customer" class="form-control form-control-glass" value="{{ request('customer') }}" placeholder="Cari nama...">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Transaksi</label>
                <input type="date" name="date" class="form-control form-control-glass" value="{{ request('date') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Status Order</label>
                <select name="status" class="form-select form-control-glass">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processed" {{ request('status') === 'processed' ? 'selected' : '' }}>Processed</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Metode Pembayaran</label>
                <select name="payment_method" class="form-select form-control-glass">
                    <option value="">Semua Metode</option>
                    <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="transfer" {{ request('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                    <option value="qris" {{ request('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
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
                        <th>Tanggal Transaksi</th>
                        <th>Total Pembayaran</th>
                        <th>Pencatat Transaksi</th>
                        <th>Status Order</th>
                        <th>Status Bayar</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="font-monospace fw-600">#{{ $order->id }}</td>
                            <td>
                                <span class="fw-700 text-dark d-block" style="font-size:14px;">{{ $order->customer_name }}</span>
                                <span class="text-muted" style="font-size:12px;">{{ $order->customer_phone ?? 'Tidak ada nomor telepon' }}</span>
                            </td>
                            <td>{{ $order->order_date->format('d M Y H:i') }}</td>
                            <td><span class="text-primary fw-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></td>
                            <td>
                                <span>{{ $order->creator ? $order->creator->name : 'Pelanggan Mandiri' }}</span>
                            </td>
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
                                    @elseif($order->payment->payment_status === 'refund')
                                        <span class="badge bg-danger" style="font-size: 11px;">REFUNDED</span>
                                    @elseif($order->payment->payment_status === 'failed')
                                        <span class="badge bg-dark" style="font-size: 11px;">FAILED</span>
                                    @else
                                        <span class="badge bg-warning text-dark" style="font-size: 11px;">UNPAID</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary text-white" style="font-size: 11px;">BELUM DIBAYAR</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('owner.orders.show', $order->id) }}" class="btn btn-sm btn-glow-outline" style="border-radius: 8px;">
                                    <i class="fa-solid fa-eye me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada data transaksi pesanan terdaftar.</td>
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
