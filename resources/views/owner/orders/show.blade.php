@extends('layouts.app')

@section('title', 'Detail Transaksi #' . $order->id)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <a href="{{ route('owner.orders.index') }}" class="text-primary text-decoration-none mb-2 d-inline-block fw-600">
                    <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke daftar transaksi
                </a>
                <h2 class="text-dark fw-800 mb-0">Transaksi Pesanan #{{ $order->id }}</h2>
            </div>
            <div>
                @if($order->status === 'completed')
                    <span class="badge bg-success py-2 px-3 fs-6" style="border-radius:10px;">Selesai (Completed)</span>
                @elseif($order->status === 'cancelled')
                    <span class="badge bg-danger py-2 px-3 fs-6" style="border-radius:10px;">Dibatalkan (Cancelled)</span>
                @else
                    <span class="badge bg-warning text-dark py-2 px-3 fs-6 text-capitalize" style="border-radius:10px;">{{ $order->status }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Details -->
        <div class="col-lg-8 mb-4 mb-lg-0">
            <!-- Order Items -->
            <div class="glass-card mb-4">
                <h4 class="text-dark fw-700 mb-4"><i class="fa-solid fa-basket-shopping text-primary me-2"></i>Daftar Item Pembelian</h4>
                <div class="table-responsive">
                    <table class="table table-glass text-dark mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga Satuan</th>
                                <th>Quantity</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <span class="fw-700 text-dark d-block">{{ $item->product->name }}</span>
                                        <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                    </td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->qty }} {{ $item->product->unit }}</td>
                                    <td class="text-end text-primary fw-700">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-end fw-700 text-dark">TOTAL TAGIHAN:</td>
                                <td class="text-end text-primary fw-800 fs-5">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="glass-card">
                <h4 class="text-dark fw-700 mb-4"><i class="fa-solid fa-money-bill-transfer text-primary me-2"></i>Detail Status Pembayaran</h4>
                @if($order->payment)
                    <div class="row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <span class="text-muted d-block" style="font-size:13px;">Metode Pembayaran:</span>
                            <strong class="text-dark text-uppercase" style="font-size:16px;">{{ $order->payment->payment_method }}</strong>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted d-block" style="font-size:13px;">Status Pembayaran:</span>
                            @if($order->payment->payment_status === 'paid')
                                <span class="badge bg-success">PAID</span>
                                <small class="text-muted d-block mt-1">Lunas pada: {{ $order->payment->paid_at->format('d M Y H:i') }}</small>
                            @elseif($order->payment->payment_status === 'refund')
                                <span class="badge bg-danger">REFUNDED</span>
                            @elseif($order->payment->payment_status === 'failed')
                                <span class="badge bg-dark">FAILED</span>
                            @else
                                <span class="badge bg-warning text-dark">UNPAID</span>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fa-solid fa-circle-info mb-2 text-muted" style="font-size:30px;"></i>
                        <p class="mb-0">Transaksi pembayaran belum dicatat.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <!-- Customer Information Card -->
            <div class="glass-card mb-4">
                <h4 class="text-dark fw-700 mb-4"><i class="fa-solid fa-user text-primary me-2"></i>Profil Pelanggan</h4>
                <div class="mb-3">
                    <span class="text-muted d-block" style="font-size: 12px;">Nama Pembeli:</span>
                    <strong class="text-dark" style="font-size: 15px;">{{ $order->customer_name }}</strong>
                </div>
                <div class="mb-3">
                    <span class="text-muted d-block" style="font-size: 12px;">Kontak Telepon:</span>
                    <strong class="text-dark" style="font-size: 15px;">{{ $order->customer_phone ?? 'Tidak dicantumkan' }}</strong>
                </div>
                <div class="mb-3">
                    <span class="text-muted d-block" style="font-size: 12px;">Waktu Transaksi:</span>
                    <strong class="text-dark" style="font-size: 15px;">{{ $order->order_date->format('d M Y H:i') }}</strong>
                </div>
                <div class="mb-0">
                    <span class="text-muted d-block" style="font-size: 12px;">Catatan Tambahan:</span>
                    <span class="text-dark" style="font-size: 14px;">{{ $order->notes ?? 'Tidak ada catatan.' }}</span>
                </div>
            </div>

            <!-- Status Transition Card -->
            <div class="glass-card">
                <h4 class="text-dark fw-700 mb-4"><i class="fa-solid fa-traffic-light text-primary me-2"></i>Perbarui Status Pesanan</h4>
                
                <form action="{{ route('owner.orders.status', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="form-label" for="status">Pilih Status Baru</label>
                        <select name="status" id="status" class="form-select form-control-glass" {{ $order->status === 'completed' && $order->status !== 'cancelled' ? 'disabled' : '' }}>
                            <option value="draft" {{ $order->status === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processed" {{ $order->status === 'processed' ? 'selected' : '' }}>Processed</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed (Selesai & Potong Stok)</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled (Batalkan & Kembalikan Stok)</option>
                        </select>
                        @if($order->status === 'completed')
                            <small class="text-warning d-block mt-2">
                                <i class="fa-solid fa-exclamation-circle me-1"></i> Pesanan sudah selesai. Anda hanya boleh membatalkan pesanan untuk mengembalikan stok.
                            </small>
                        @endif
                    </div>

                    @if($order->status !== 'completed' || $order->status === 'cancelled')
                        <button type="submit" class="btn btn-glow-primary w-100">
                            <i class="fa-solid fa-save me-1"></i> Simpan Status Baru
                        </button>
                    @else
                        <!-- Owner can change from completed to cancelled -->
                        <button type="submit" name="status" value="cancelled" class="btn btn-danger w-100" style="border-radius:10px;" onclick="return confirm('Apakah Anda yakin ingin membatalkan transaksi pesanan ini? Persediaan stok produk terkait akan dikembalikan ke inventori.')">
                            <i class="fa-solid fa-ban me-1"></i> Batalkan Pesanan (Pulihkan Stok)
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
