@extends('layouts.app')

@section('title', 'Proses Order #' . $order->id)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <a href="{{ route('staff.orders.index') }}" class="text-primary text-decoration-none mb-2 d-inline-block fw-600">
                    <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke daftar order
                </a>
                <h2 class="text-dark fw-800 mb-0">Proses Order #{{ $order->id }}</h2>
            </div>
            <div>
                @if($order->status === 'completed')
                    <span class="badge bg-success py-2 px-3 fs-6" style="border-radius:10px;">Completed</span>
                @elseif($order->status === 'cancelled')
                    <span class="badge bg-danger py-2 px-3 fs-6" style="border-radius:10px;">Cancelled</span>
                @else
                    <span class="badge bg-warning text-dark py-2 px-3 fs-6 text-capitalize" style="border-radius:10px;">{{ $order->status }}</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Alert notifications -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="background-color: #fee2e2; border-color: #fecaca; color: #991b1b; border-radius: 12px;">
            <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Main order details -->
        <div class="col-lg-8 mb-4 mb-lg-0">
            <!-- Items Card -->
            <div class="glass-card mb-4">
                <h4 class="text-dark fw-700 mb-4"><i class="fa-solid fa-basket-shopping text-primary me-2"></i>Daftar Item Belanja</h4>
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

            <!-- Payment Process Card -->
            <div class="glass-card">
                <h4 class="text-dark fw-700 mb-4"><i class="fa-solid fa-cash-register text-primary me-2"></i>Pencatatan Pembayaran</h4>
                
                @if(!$order->payment)
                    <!-- Form to create Payment -->
                    <form action="{{ route('staff.orders.payment', $order->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="amount">Jumlah Uang Dibayar (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="amount" id="amount" class="form-control form-control-glass @error('amount') is-invalid @enderror" value="{{ old('amount', intval($order->total_amount)) }}" required min="{{ $order->total_amount }}">
                                <small class="text-muted mt-1 d-block">Minimal bayar: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</small>
                                @error('amount')
                                    <div class="text-danger mt-1" style="font-size:13px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="payment_method">Metode Pembayaran <span class="text-danger">*</span></label>
                                <select name="payment_method" id="payment_method" class="form-select form-control-glass @error('payment_method') is-invalid @enderror" required>
                                    <option value="cash" selected>Cash (Tunai)</option>
                                    <option value="transfer">Transfer Bank</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="payment_status">Status Pembayaran <span class="text-danger">*</span></label>
                            <select name="payment_status" id="payment_status" class="form-select form-control-glass" required>
                                <option value="paid">PAID (Lunas)</option>
                                <option value="unpaid">UNPAID (Belum Lunas)</option>
                                <option value="failed">FAILED (Gagal)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-glow-primary w-100">
                            <i class="fa-solid fa-save me-1"></i> Simpan Transaksi Pembayaran
                        </button>
                    </form>
                @else
                    <!-- Display & edit existing Payment -->
                    <form action="{{ route('staff.payments.update', $order->payment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="amount">Jumlah Uang Dibayar (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="amount" id="amount" class="form-control form-control-glass" value="{{ old('amount', intval($order->payment->amount)) }}" required min="{{ $order->total_amount }}">
                                <small class="text-muted mt-1 d-block">Minimal bayar: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="payment_method">Metode Pembayaran <span class="text-danger">*</span></label>
                                <select name="payment_method" id="payment_method" class="form-select form-control-glass" required>
                                    <option value="cash" {{ $order->payment->payment_method === 'cash' ? 'selected' : '' }}>Cash (Tunai)</option>
                                    <option value="transfer" {{ $order->payment->payment_method === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                    <option value="qris" {{ $order->payment->payment_method === 'qris' ? 'selected' : '' }}>QRIS</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="payment_status">Status Pembayaran <span class="text-danger">*</span></label>
                            <select name="payment_status" id="payment_status" class="form-select form-control-glass" required>
                                <option value="paid" {{ $order->payment->payment_status === 'paid' ? 'selected' : '' }}>PAID (Lunas)</option>
                                <option value="unpaid" {{ $order->payment->payment_status === 'unpaid' ? 'selected' : '' }}>UNPAID (Belum Lunas)</option>
                                <option value="failed" {{ $order->payment->payment_status === 'failed' ? 'selected' : '' }}>FAILED (Gagal)</option>
                                <option value="refund" {{ $order->payment->payment_status === 'refund' ? 'selected' : '' }}>REFUNDED (Dikembalikan)</option>
                            </select>
                            @if($order->payment->paid_at)
                                <small class="text-success mt-1 d-block">
                                    <i class="fa-solid fa-clock me-1"></i> Telah lunas pada: {{ $order->payment->paid_at->format('d M Y H:i') }}
                                </small>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-glow-outline w-100">
                            <i class="fa-solid fa-edit me-1"></i> Perbarui Pembayaran
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Sidebar Info and Status updates -->
        <div class="col-lg-4">
            <!-- Customer Card -->
            <div class="glass-card mb-4">
                <h4 class="text-dark fw-700 mb-4"><i class="fa-solid fa-user text-secondary me-2"></i>Data Pelanggan</h4>
                <div class="mb-3">
                    <span class="text-muted d-block" style="font-size: 12px;">Nama Customer:</span>
                    <strong class="text-dark" style="font-size: 15px;">{{ $order->customer_name }}</strong>
                </div>
                <div class="mb-3">
                    <span class="text-muted d-block" style="font-size: 12px;">No HP / Telepon:</span>
                    <strong class="text-dark" style="font-size: 15px;">{{ $order->customer_phone ?? 'Tidak dicantumkan' }}</strong>
                </div>
                <div class="mb-3">
                    <span class="text-muted d-block" style="font-size: 12px;">Dibuat Oleh:</span>
                    <strong class="text-dark" style="font-size: 14px;">{{ $order->creator ? $order->creator->name : 'Pelanggan Mandiri' }}</strong>
                </div>
                <div class="mb-3">
                    <span class="text-muted d-block" style="font-size: 12px;">Waktu Order:</span>
                    <strong class="text-dark" style="font-size: 15px;">{{ $order->order_date->format('d M Y H:i') }}</strong>
                </div>
                <div class="mb-0">
                    <span class="text-muted d-block" style="font-size: 12px;">Catatan:</span>
                    <span class="text-dark" style="font-size: 14px;">{{ $order->notes ?? 'Tidak ada catatan.' }}</span>
                </div>
            </div>

            <!-- Status update Card (Staff can only update if current status is pending) -->
            <div class="glass-card">
                <h4 class="text-dark fw-700 mb-4"><i class="fa-solid fa-traffic-light text-primary me-2"></i>Status Pesanan</h4>
                
                @if(Gate::allows('updateStatus', $order))
                    <form action="{{ route('staff.orders.status', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label class="form-label" for="status">Pilih Status Baru</label>
                            <select name="status" id="status" class="form-select form-control-glass" required>
                                <option value="processed" {{ $order->status === 'processed' ? 'selected' : '' }}>Processed (Sedang Diproses)</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed (Selesai & Kurangi Stok)</option>
                            </select>
                            <small class="text-muted mt-2 d-block">
                                <i class="fa-solid fa-circle-info me-1"></i> Sebagai kasir, Anda hanya dapat memproses pesanan berstatus "Pending".
                            </small>
                        </div>

                        <button type="submit" class="btn btn-glow-primary w-100">
                            <i class="fa-solid fa-save me-1"></i> Update Status
                        </button>
                    </form>
                @else
                    <div class="text-center py-3">
                        <span class="text-muted d-block">Status Saat Ini:</span>
                        <strong class="text-dark text-uppercase" style="font-size: 18px;">{{ $order->status }}</strong>
                        <small class="text-muted d-block mt-2">
                            <i class="fa-solid fa-lock me-1"></i> Status terkunci untuk kasir. Perubahan hanya bisa dilakukan oleh Owner jika status bukan pending.
                        </small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
