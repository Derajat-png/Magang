@extends('layouts.public')

@section('title', 'Katalog ' . $umkm->name)

@section('content')
<div class="container py-5">
    <!-- Header UMKM -->
    <div class="glass-card mb-5 text-center text-md-start">
        <div class="row align-items-center">
            <div class="col-md-8">
                <span class="badge-glass-success text-capitalize mb-2 d-inline-block">{{ $umkm->business_type }}</span>
                <h1 class="text-dark fw-800 mb-2">{{ $umkm->name }}</h1>
                <p class="text-muted mb-3" style="font-size:15px;">{{ $umkm->description ?? 'Selamat datang di katalog produk resmi kami.' }}</p>
                <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-md-start text-muted" style="font-size: 13px;">
                    <span><i class="fa-solid fa-map-pin text-danger me-1"></i> {{ $umkm->address }}</span>
                    <span><i class="fa-solid fa-phone text-success me-1"></i> {{ $umkm->phone }}</span>
                </div>
            </div>
            <div class="col-md-4 text-md-end mt-4 mt-md-0">
                <a href="#cart-section" class="btn btn-glow-primary">
                    <i class="fa-solid fa-shopping-basket me-2"></i> Keranjang (<span id="cart-count">0</span>)
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Products Column -->
        <div class="col-lg-8">
            <!-- Filter Bar -->
            <div class="glass-card mb-4">
                <form action="{{ route('public.umkm.catalog', $umkm->id) }}" method="GET" class="row g-3">
                    <div class="col-md-5">
                        <input type="text" name="keyword" class="form-control form-control-glass" value="{{ request('keyword') }}" placeholder="Cari produk...">
                    </div>
                    <div class="col-md-4">
                        <select name="category_id" class="form-select form-control-glass">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-grid">
                        <button type="submit" class="btn btn-glow-outline">
                            <i class="fa-solid fa-magnifying-glass me-2"></i> Tampilkan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Products Grid -->
            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-md-6 col-xl-4">
                        <div class="glass-card h-100 d-flex flex-column justify-content-between p-3" style="border-radius:16px;">
                            <div>
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded mb-3" style="height: 160px; width: 100%; object-fit: cover; border: 1px solid var(--surface-container);" alt="{{ $product->name }}">
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light text-muted rounded mb-3" style="height: 160px; border: 1px dashed var(--surface-dim);">
                                        <i class="fa-regular fa-image" style="font-size: 36px;"></i>
                                    </div>
                                @endif
                                
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="text-dark fw-700 mb-0" style="font-size:15px;">{{ $product->name }}</h6>
                                    <span class="badge" style="font-size: 9px; background-color: var(--secondary); color: white;">{{ $product->sku }}</span>
                                </div>
                                <p class="text-muted mb-2" style="font-size:12px; line-height:1.4;">{{ Str::limit($product->description, 50) }}</p>
                                <h5 class="text-primary fw-800" style="font-size:16px;">Rp {{ number_format($product->price, 0, ',', '.') }}</h5>
                            </div>
                            
                            <div class="mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-2" style="font-size:12px;">
                                    <span class="text-muted">Tersedia: {{ $product->stock }} {{ $product->unit }}</span>
                                </div>
                                @if($product->stock > 0)
                                    <button class="btn btn-glow-outline btn-sm w-100 add-to-cart-btn" 
                                            data-id="{{ $product->id }}" 
                                            data-name="{{ $product->name }}" 
                                            data-price="{{ $product->price }}" 
                                            data-stock="{{ $product->stock }}">
                                        <i class="fa-solid fa-plus me-1"></i> Masukkan Keranjang
                                    </button>
                                @else
                                    <button class="btn btn-light border btn-sm w-100 text-muted" disabled style="border-radius:10px;">Stok Kosong</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="text-muted">
                            <i class="fa-solid fa-box-open" style="font-size: 36px;"></i>
                            <p class="mt-2" style="font-size:14px;">Belum ada produk untuk kategori ini.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-5 pagination-glass">
                {{ $products->links() }}
            </div>
        </div>

        <!-- Checkout Column -->
        <div class="col-lg-4 mt-5 mt-lg-0" id="cart-section">
            <div class="glass-card sticky-top" style="top: 100px; z-index: 10;">
                <h4 class="text-dark fw-700 mb-4"><i class="fa-solid fa-shopping-basket text-primary me-2"></i>Keranjang Belanja</h4>
                
                <div id="cart-empty-msg" class="text-center text-muted py-4">
                    <i class="fa-solid fa-basket-shopping mb-2 text-muted" style="font-size: 28px;"></i>
                    <p class="mb-0" style="font-size:14px;">Keranjang masih kosong</p>
                </div>

                <div id="cart-content" class="d-none">
                    <div id="cart-items" class="mb-4" style="max-height: 250px; overflow-y: auto;">
                        <!-- JS will inject items here -->
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="text-dark fw-700 mb-0">Total Belanja:</h6>
                        <h4 class="text-primary fw-800 mb-0" id="cart-total">Rp 0</h4>
                    </div>

                    <!-- Order Form -->
                    <form action="{{ route('public.umkm.order', $umkm->id) }}" method="POST" id="checkout-form">
                        @csrf
                        <div id="hidden-cart-inputs"></div>

                        <div class="mb-3">
                            <label class="form-label" for="customer_name">Nama Pemesan <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" id="customer_name" class="form-control form-control-glass" required placeholder="Tuliskan nama Anda">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="customer_phone">Nomor HP / WhatsApp</label>
                            <input type="text" name="customer_phone" id="customer_phone" class="form-control form-control-glass" placeholder="Contoh: 0812XXXXXXXX">
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="notes">Catatan Pesanan</label>
                            <textarea name="notes" id="notes" rows="2" class="form-control form-control-glass" placeholder="Keterangan tambahan (opsional)..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-glow-primary w-100 py-2.5">
                            <i class="fa-solid fa-circle-check me-2"></i> Kirim Pesanan Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Cart management
    let cart = [];

    document.addEventListener('DOMContentLoaded', () => {
        const addBtns = document.querySelectorAll('.add-to-cart-btn');
        addBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                const name = btn.getAttribute('data-name');
                const price = parseFloat(btn.getAttribute('data-price'));
                const stock = parseInt(btn.getAttribute('data-stock'));

                addToCart(id, name, price, stock);
            });
        });

        document.getElementById('checkout-form').addEventListener('submit', prepareSubmit);
    });

    function addToCart(id, name, price, stock) {
        const existing = cart.find(item => item.id === id);
        
        if (existing) {
            if (existing.qty >= stock) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: `Stok produk ${name} tidak mencukupi.`,
                    background: '#ffffff',
                    color: '#0b1c30'
                });
                return;
            }
            existing.qty++;
        } else {
            cart.push({ id, name, price, stock, qty: 1 });
        }

        renderCart();
    }

    function changeQty(id, delta) {
        const item = cart.find(i => i.id === id);
        if (!item) return;

        item.qty += delta;

        if (item.qty <= 0) {
            cart = cart.filter(i => i.id !== id);
        } else if (item.qty > item.stock) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: `Stok produk ${item.name} hanya tersedia ${item.stock} unit.`,
                background: '#ffffff',
                color: '#0b1c30'
            });
            item.qty = item.stock;
        }

        renderCart();
    }

    function renderCart() {
        const cartEmpty = document.getElementById('cart-empty-msg');
        const cartContent = document.getElementById('cart-content');
        const cartItemsDiv = document.getElementById('cart-items');
        const cartCount = document.getElementById('cart-count');
        const cartTotalSpan = document.getElementById('cart-total');

        if (cart.length === 0) {
            cartEmpty.classList.remove('d-none');
            cartContent.classList.add('d-none');
            cartCount.innerText = '0';
            return;
        }

        cartEmpty.classList.add('d-none');
        cartContent.classList.remove('d-none');

        let total = 0;
        let count = 0;
        cartItemsDiv.innerHTML = '';

        cart.forEach(item => {
            const subtotal = item.price * item.qty;
            total += subtotal;
            count += item.qty;

            const itemHtml = `
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <div style="flex-grow:1; max-width: 60%;">
                        <span class="text-dark fw-600 d-block" style="font-size:14px;">${item.name}</span>
                        <span class="text-muted" style="font-size:12px;">Rp ${formatNumber(item.price)}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 text-dark" onclick="changeQty('${item.id}', -1)">-</button>
                        <span class="text-dark font-monospace fw-600" style="font-size:13px;">${item.qty}</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 text-dark" onclick="changeQty('${item.id}', 1)">+</button>
                    </div>
                    <div class="text-end" style="width: 80px;">
                        <span class="text-primary fw-700" style="font-size:13px;">Rp ${formatNumber(subtotal)}</span>
                    </div>
                </div>
            `;
            cartItemsDiv.innerHTML += itemHtml;
        });

        cartCount.innerText = count;
        cartTotalSpan.innerText = 'Rp ' + formatNumber(total);
    }

    function prepareSubmit(e) {
        if (cart.length === 0) {
            e.preventDefault();
            alert('Keranjang belanja masih kosong!');
            return;
        }

        const hiddenInputsDiv = document.getElementById('hidden-cart-inputs');
        hiddenInputsDiv.innerHTML = '';

        cart.forEach((item, index) => {
            hiddenInputsDiv.innerHTML += `
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
