@extends('layouts.app')

@section('title', 'Laporan Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-dark fw-800 mb-1"><i class="fa-solid fa-money-bill-transfer text-primary me-2"></i>Laporan Arus Kas Pembayaran</h2>
            <p class="text-muted">Riwayat pencatatan uang masuk, status transaksi, dan metode pembayaran pelanggan</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="glass-card mb-4">
        <form action="{{ route('owner.payments.index') }}" method="GET" class="row g-3">
            <div class="col-md-5">
                <label class="form-label">Status Keuangan</label>
                <select name="payment_status" class="form-select form-control-glass">
                    <option value="">Semua Status</option>
                    <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid (Belum Lunas)</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid (Lunas)</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed (Gagal)</option>
                    <option value="refund" {{ request('payment_status') === 'refund' ? 'selected' : '' }}>Refund (Dikembalikan)</option>
                </select>
            </div>
            <div class="col-md-5">
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
                        <th>ID Transaksi</th>
                        <th>ID Order</th>
                        <th>Pelanggan</th>
                        <th>Waktu Bayar</th>
                        <th>Metode</th>
                        <th>Jumlah Pembayaran</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $pay)
                        <tr>
                            <td>#TRX-{{ $pay->id }}</td>
                            <td class="font-monospace fw-600">#{{ $pay->order_id }}</td>
                            <td>{{ $pay->order->customer_name }}</td>
                            <td>{{ $pay->paid_at ? $pay->paid_at->format('d M Y H:i') : '-' }}</td>
                            <td><span class="text-uppercase fw-600">{{ $pay->payment_method }}</span></td>
                            <td><span class="text-primary fw-700">Rp {{ number_format($pay->amount, 0, ',', '.') }}</span></td>
                            <td>
                                @if($pay->payment_status === 'paid')
                                    <span class="badge bg-success" style="font-size: 11px;">PAID</span>
                                @elseif($pay->payment_status === 'refund')
                                    <span class="badge bg-danger" style="font-size: 11px;">REFUNDED</span>
                                @elseif($pay->payment_status === 'failed')
                                    <span class="badge bg-dark" style="font-size: 11px;">FAILED</span>
                                @else
                                    <span class="badge bg-warning text-dark" style="font-size: 11px;">UNPAID</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada catatan transaksi keuangan terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4 pagination-glass">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection
