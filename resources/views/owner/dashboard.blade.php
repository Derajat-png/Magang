@extends('layouts.app')

@section('title', 'Ikhtisar Operasional')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-dark fw-800 mb-1"><i class="fa-solid fa-chart-line text-primary me-2"></i>Ikhtisar Operasional</h2>
            <p class="text-muted">Pantau grafik omzet harian, performa produk, dan peringatan level stok toko Anda</p>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="glass-card d-flex align-items-center justify-content-between p-4">
                <div>
                    <span class="text-muted fw-600 d-block mb-1" style="font-size:13px;">Produk Aktif</span>
                    <h2 class="text-dark fw-800 mb-0">{{ $totalActiveProducts }}</h2>
                </div>
                <div class="p-3 rounded-circle" style="background-color: var(--secondary-container);">
                    <i class="fa-solid fa-boxes-stacked text-primary" style="font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="glass-card d-flex align-items-center justify-content-between p-4">
                <div>
                    <span class="text-muted fw-600 d-block mb-1" style="font-size:13px;">Order Hari Ini</span>
                    <h2 class="text-dark fw-800 mb-0">{{ $todayOrdersCount }}</h2>
                </div>
                <div class="p-3 rounded-circle" style="background-color: var(--secondary-container);">
                    <i class="fa-solid fa-receipt text-primary" style="font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="glass-card d-flex align-items-center justify-content-between p-4">
                <div>
                    <span class="text-muted fw-600 d-block mb-1" style="font-size:13px;">Order Pending</span>
                    <h2 class="text-warning fw-800 mb-0">{{ $pendingOrdersCount }}</h2>
                </div>
                <div class="p-3 rounded-circle" style="background-color: #fef3c7;">
                    <i class="fa-solid fa-clock text-warning" style="font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="glass-card d-flex align-items-center justify-content-between p-4">
                <div>
                    <span class="text-muted fw-600 d-block mb-1" style="font-size:13px;">Omzet Bulan Ini</span>
                    <h4 class="text-primary fw-800 mb-0">Rp {{ number_format($revenueMonth, 0, ',', '.') }}</h4>
                </div>
                <div class="p-3 rounded-circle" style="background-color: #d1fae5;">
                    <i class="fa-solid fa-wallet text-success" style="font-size: 24px;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Sales Performance Graph -->
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="glass-card h-100">
                <h4 class="text-dark fw-700 mb-4"><i class="fa-solid fa-chart-area text-primary me-2"></i>Kinerja Omzet 7 Hari Terakhir</h4>
                <div style="height: 300px; position: relative;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Inventory Alerts -->
        <div class="col-lg-4">
            <div class="glass-card h-100" style="max-height: 375px; overflow-y: auto;">
                <h4 class="text-dark fw-700 mb-3 text-warning"><i class="fa-solid fa-triangle-exclamation me-2"></i>Stok Menipis (&lt; 5)</h4>
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
                            <p class="mb-0" style="font-size: 13px;">Semua produk memiliki stok yang cukup.</p>
                        </div>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="row">
        <div class="col-12">
            <div class="glass-card">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h4 class="text-dark fw-700 mb-0"><i class="fa-solid fa-receipt me-2 text-primary"></i>Transaksi Terkini</h4>
                    <a href="{{ route('owner.orders.index') }}" class="btn btn-sm btn-glow-outline">Lihat Seluruh Transaksi</a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-glass text-dark mb-0">
                        <thead>
                            <tr>
                                <th>ID Order</th>
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
                                                <span class="badge bg-success" style="font-size: 11px;">PAID ({{ strtoupper($order->payment->payment_method) }})</span>
                                            @else
                                                <span class="badge bg-warning text-dark" style="font-size: 11px;">UNPAID ({{ strtoupper($order->payment->payment_method) }})</span>
                                            @endif
                                        @else
                                            <span class="badge bg-danger" style="font-size: 11px;">BELUM DIBAYAR</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Belum ada transaksi terekam saat ini.</td>
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

@section('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Prepare chart data from Controller variables
        const chartLabels = {!! json_encode($chartLabels) !!};
        const chartValues = {!! json_encode($chartData) !!};

        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(177, 14, 107, 0.35)');
        gradient.addColorStop(1, 'rgba(177, 14, 107, 0.00)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Omzet Harian (Rp)',
                    data: chartValues,
                    borderColor: '#b10e6b',
                    borderWidth: 3,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#b10e6b',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0b1c30',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        grid: {
                            color: '#e5eeff'
                        },
                        ticks: {
                            color: '#765469',
                            font: {
                                family: 'Inter',
                                size: 11
                            },
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(value);
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#765469',
                            font: {
                                family: 'Inter',
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
