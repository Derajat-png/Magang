@extends('layouts.app')

@section('title', 'Buat Transaksi Baru')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-dark fw-800 mb-1"><i class="fa-solid fa-cart-plus text-primary me-2"></i>Buat Transaksi Baru</h2>
            <p class="text-muted">Input item pesanan barang belanja pelanggan dan kalkulasikan total tagihan otomatis</p>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="background-color: #fee2e2; border-color: #fecaca; color: #991b1b; border-radius: 12px;">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- POS Selection / Item Adder -->
        <div class="col-lg-7">
            <div class="glass-card mb-4">
                <h4 class="text-dark fw-700 mb-4"><i class="fa-solid fa-list me-2 text-primary"></i>Pilih Produk Belanja</h4>
                
                <div class="row g-3 align-items-end mb-4">
                    <div class="col-md-8">
                        <label class="form-label" for="product_select">Pilih Produk</label>
                        <select id="product_select" class="form-select form-control-glass">
                            <option value="" disabled selected>Pilih produk...</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" 
                                        data-name="{{ $product->name }}" 
                                        data-price="{{ $product->price }}" 
                                        data-stock="{{ $product->stock }}"
                                        data-unit="{{ $product->unit }}">
                                    {{ $product->name }} (SKU: {{ $product->sku }}) — Rp {{ number_format($product->price, 0, ',', '.') }} [Stok: {{ $product->stock }}]
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-grid">
                        <button type="button" id="add-item-btn" class="btn btn-glow-primary">
                            <i class="fa-solid fa-plus me-1"></i> Tambah Item
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-glass text-dark mb-0" id="order-items-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga Satuan</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="order-items-list">
                            <!-- JS will inject items here -->
                            <tr id="empty-row">
                                <td colspan="5" class="text-center text-muted py-4">Belum ada item belanja ditambahkan.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Checkout Form & Customer Details -->
        <div class="col-lg-5">
            <div class="glass-card">
                <h4 class="text-dark fw-700 mb-4"><i class="fa-solid fa-receipt text-secondary me-2"></i>Ringkasan Tagihan</h4>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="text-dark mb-0">Total Tagihan:</h5>
                    <h2 class="text-primary fw-800 mb-0" id="grand-total-label">Rp 0</h2>
                </div>

                <form action="{{ route('staff.orders.store') }}" method="POST" id="order-form">
                    @csrf
                    <!-- Hidden inputs injected by JS -->
                    <div id="hidden-inputs-container"></div>

                    <div class="mb-3">
                        <label class="form-label" for="customer_name">Nama Pelanggan <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control form-control-glass @error('customer_name') is-invalid @enderror" value="{{ old('customer_name') }}" required placeholder="Nama lengkap pembeli">
                        @error('customer_name')
                            <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="customer_phone">Nomor Telepon / HP</label>
                        <input type="text" name="customer_phone" id="customer_phone" class="form-control form-control-glass @error('customer_phone') is-invalid @enderror" value="{{ old('customer_phone') }}" placeholder="Contoh: 0812XXXXXXXX">
                        @error('customer_phone')
                            <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="notes">Catatan Transaksi</label>
                        <textarea name="notes" id="notes" rows="3" class="form-control form-control-glass" placeholder="Keterangan tambahan untuk pesanan..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-glow-primary btn-lg w-100 py-3" id="submit-btn" disabled>
                        <i class="fa-solid fa-save me-2"></i> Simpan Transaksi (Pending)
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let orderItems = [];

    document.addEventListener('DOMContentLoaded', () => {
        const addBtn = document.getElementById('add-item-btn');
        addBtn.addEventListener('click', addItem);
        
        document.getElementById('order-form').addEventListener('submit', prepareFormSubmission);
    });

    function addItem() {
        const select = document.getElementById('product_select');
        const selectedOption = select.options[select.selectedIndex];
        
        if (select.value === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Pilih produk terlebih dahulu!',
                background: '#ffffff',
                color: '#0b1c30'
            });
            return;
        }

        const id = select.value;
        const name = selectedOption.getAttribute('data-name');
        const price = parseFloat(selectedOption.getAttribute('data-price'));
        const stock = parseInt(selectedOption.getAttribute('data-stock'));
        const unit = selectedOption.getAttribute('data-unit');

        // Check if item already in order list
        const existing = orderItems.find(item => item.id === id);
        if (existing) {
            if (existing.qty >= stock) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Terbatas',
                    text: `Stok produk ${name} hanya tersedia ${stock} ${unit}.`,
                    background: '#ffffff',
                    color: '#0b1c30'
                });
                return;
            }
            existing.qty++;
        } else {
            orderItems.push({ id, name, price, stock, unit, qty: 1 });
        }

        renderItems();
        select.selectedIndex = 0; // reset select
    }

    function updateQty(id, newQty) {
        const item = orderItems.find(i => i.id === id);
        if (!item) return;

        const qty = parseInt(newQty);
        if (isNaN(qty) || qty <= 0) {
            removeItem(id);
            return;
        }

        if (qty > item.stock) {
            Swal.fire({
                icon: 'warning',
                title: 'Stok Kurang',
                text: `Stok produk ${item.name} hanya tersedia ${item.stock} ${item.unit}.`,
                background: '#ffffff',
                color: '#0b1c30'
            });
            item.qty = item.stock;
        } else {
            item.qty = qty;
        }

        renderItems();
    }

    function removeItem(id) {
        orderItems = orderItems.filter(item => item.id !== id);
        renderItems();
    }

    function renderItems() {
        const tbody = document.getElementById('order-items-list');
        const grandTotalLabel = document.getElementById('grand-total-label');
        const submitBtn = document.getElementById('submit-btn');

        if (orderItems.length === 0) {
            tbody.innerHTML = `
                <tr id="empty-row">
                    <td colspan="5" class="text-center text-muted py-4">Belum ada item belanja ditambahkan.</td>
                </tr>
            `;
            grandTotalLabel.innerText = 'Rp 0';
            submitBtn.disabled = true;
            return;
        }

        tbody.innerHTML = '';
        let total = 0;
        submitBtn.disabled = false;

        orderItems.forEach(item => {
            const subtotal = item.price * item.qty;
            total += subtotal;

            const tr = `
                <tr>
                    <td>
                        <span class="fw-700 text-dark d-block">${item.name}</span>
                        <small class="text-muted">Maksimal stok: ${item.stock} ${item.unit}</small>
                    </td>
                    <td>Rp ${formatNumber(item.price)}</td>
                    <td style="width: 120px;">
                        <input type="number" class="form-control form-control-glass py-1" value="${item.qty}" min="1" max="${item.stock}" onchange="updateQty('${item.id}', this.value)">
                    </td>
                    <td class="text-primary fw-700">Rp ${formatNumber(subtotal)}</td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem('${item.id}')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += tr;
        });

        grandTotalLabel.innerText = 'Rp ' + formatNumber(total);
    }

    function prepareFormSubmission(e) {
        if (orderItems.length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Pesanan harus memiliki minimal 1 produk!',
                background: '#ffffff',
                color: '#0b1c30'
            });
            return;
        }

        const container = document.getElementById('hidden-inputs-container');
        container.innerHTML = '';

        orderItems.forEach((item, index) => {
            container.innerHTML += `
                <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                <input type="hidden" name="items[${index}][qty]" value="${item.qty}">
            `;
        });
    }

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
</script>
@endsection
