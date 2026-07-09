<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - POS & UMKM Hub</title>
    
    <!-- Google Fonts: Plus Jakarta Sans (Headings) & Inter (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- CSS Theme: Soft Enterprise -->
    <style>
        :root {
            --primary: #b10e6b;
            --on-primary: #ffffff;
            --primary-container: #d23284;
            --secondary: #765469;
            --on-secondary: #ffffff;
            --secondary-container: #fdd0ea;
            --on-secondary-container: #79576c;
            --tertiary: #ab2457;
            
            --background: #f8f9ff;
            --on-background: #0b1c30;
            
            --surface: #ffffff;
            --surface-dim: #cbdbf5;
            --surface-container: #e5eeff;
            --surface-container-low: #eff4ff;
            
            --text-main: #0b1c30;
            --text-muted: #765469;
            --outline: #8b7079;
            
            --shadow-soft: 0px 4px 20px rgba(177, 14, 107, 0.06);
            --shadow-hover: 0px 8px 30px rgba(177, 14, 107, 0.12);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .sidebar-brand {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            background-color: var(--surface);
            border-right: 1px solid var(--surface-container);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            transition: all 0.3s;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.01);
        }

        .sidebar-brand {
            padding: 24px;
            font-size: 20px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--tertiary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--surface-container-low);
        }

        .sidebar-menu {
            padding: 20px 12px;
            list-style: none;
            margin: 0;
        }

        .sidebar-item {
            margin-bottom: 6px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 18px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
        }

        .sidebar-link:hover, .sidebar-item.active .sidebar-link {
            color: var(--primary);
            background-color: var(--secondary-container);
            border-left: 4px solid var(--primary);
            padding-left: 14px;
        }

        .sidebar-link i {
            font-size: 16px;
            transition: transform 0.2s;
        }

        .sidebar-link:hover i {
            transform: scale(1.1);
        }

        /* Top Navigation Styling */
        .top-navbar {
            height: 70px;
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--surface-container);
            margin-left: 260px;
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        /* Content Area */
        .main-content {
            margin-left: 260px;
            padding: 40px 30px;
            min-height: calc(100vh - 70px);
        }

        /* Soft UI Card */
        .glass-card {
            background-color: var(--surface);
            border: 1px solid var(--surface-container);
            border-radius: 16px;
            box-shadow: var(--shadow-soft);
            padding: 24px;
            margin-bottom: 24px;
            transition: transform 0.25s, box-shadow 0.25s;
        }

        .glass-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        /* Soft Buttons */
        .btn-glow-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-container));
            border: none;
            color: var(--on-primary);
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(177, 14, 107, 0.2);
            transition: all 0.25s;
        }

        .btn-glow-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(177, 14, 107, 0.35);
            color: var(--on-primary);
            background: linear-gradient(135deg, #cc2382, #e34598);
        }

        .btn-glow-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
            font-weight: 600;
            padding: 8px 22px;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .btn-glow-outline:hover {
            background-color: var(--secondary-container);
            color: var(--primary);
            transform: translateY(-1px);
        }

        /* Soft Role Badges */
        .badge-glass-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            padding: 6px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-glass-danger {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
            padding: 6px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-glass-warning {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
            padding: 6px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-glass-info {
            background-color: #e0f2fe;
            color: #075985;
            border: 1px solid #bae6fd;
            padding: 6px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Form Controls */
        .form-control-glass {
            background-color: var(--background);
            border: 1px solid var(--surface-dim);
            color: var(--text-main);
            border-radius: 10px;
            padding: 12px 16px;
            transition: all 0.2s;
        }

        .form-control-glass:focus {
            background-color: var(--surface);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(177, 14, 107, 0.15);
            color: var(--text-main);
        }

        .form-label {
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 13px;
            color: var(--text-main);
            margin-bottom: 6px;
        }

        /* Clean Table */
        .table-glass {
            color: var(--text-main);
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .table-glass th {
            border: none;
            color: var(--text-muted);
            font-weight: 700;
            font-size: 13px;
            padding: 12px 16px;
            background-color: transparent !important;
        }

        .table-glass td {
            background-color: var(--surface) !important;
            border-top: 1px solid var(--surface-container) !important;
            border-bottom: 1px solid var(--surface-container) !important;
            padding: 16px;
            vertical-align: middle;
            font-size: 14px;
        }

        .table-glass tr td:first-child {
            border-left: 1px solid var(--surface-container) !important;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }

        .table-glass tr td:last-child {
            border-right: 1px solid var(--surface-container) !important;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        .table-glass tr:hover td {
            background-color: var(--surface-container-low) !important;
        }

        .pagination-glass .page-link {
            background-color: var(--surface);
            border: 1px solid var(--surface-container);
            color: var(--text-main);
            border-radius: 8px;
            margin: 0 4px;
            transition: all 0.2s;
        }

        .pagination-glass .page-link:hover, .pagination-glass .active .page-link {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--background);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--surface-dim);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }
        
        /* Mobile Layout */
        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: -260px;
            }
            .sidebar.active {
                margin-left: 0;
            }
            .top-navbar, .main-content {
                margin-left: 0;
            }
            .top-navbar {
                padding: 0 15px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-layer-group text-primary"></i> UMKM Hub
        </div>
        <ul class="sidebar-menu">
            @if(auth()->user()->isSuperAdmin())
                <li class="sidebar-item {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                        <i class="fa-solid fa-chart-pie"></i> Ringkasan Global
                    </a>
                </li>
                <li class="sidebar-item {{ Request::routeIs('admin.umkms.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.umkms.index') }}" class="sidebar-link">
                        <i class="fa-solid fa-shop"></i> Mitra Bisnis (UMKM)
                    </a>
                </li>
                <li class="sidebar-item {{ Request::routeIs('admin.users.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}" class="sidebar-link">
                        <i class="fa-solid fa-user-gear"></i> Kontrol Akun
                    </a>
                </li>
            @elseif(auth()->user()->isOwner())
                <li class="sidebar-item {{ Request::routeIs('owner.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('owner.dashboard') }}" class="sidebar-link">
                        <i class="fa-solid fa-chart-pie"></i> Analisis Usaha
                    </a>
                </li>
                <li class="sidebar-item {{ Request::routeIs('owner.profile.edit') ? 'active' : '' }}">
                    <a href="{{ route('owner.profile.edit') }}" class="sidebar-link">
                        <i class="fa-solid fa-store"></i> Profil Usaha
                    </a>
                </li>
                <li class="sidebar-item {{ Request::routeIs('owner.categories.*') ? 'active' : '' }}">
                    <a href="{{ route('owner.categories.index') }}" class="sidebar-link">
                        <i class="fa-solid fa-tags"></i> Kategori Produk
                    </a>
                </li>
                <li class="sidebar-item {{ Request::routeIs('owner.products.*') ? 'active' : '' }}">
                    <a href="{{ route('owner.products.index') }}" class="sidebar-link">
                        <i class="fa-solid fa-boxes-stacked"></i> Inventori Produk
                    </a>
                </li>
                <li class="sidebar-item {{ Request::routeIs('owner.staff.*') ? 'active' : '' }}">
                    <a href="{{ route('owner.staff.index') }}" class="sidebar-link">
                        <i class="fa-solid fa-user-group"></i> Anggota Kasir
                    </a>
                </li>
                <li class="sidebar-item {{ Request::routeIs('owner.orders.*') ? 'active' : '' }}">
                    <a href="{{ route('owner.orders.index') }}" class="sidebar-link">
                        <i class="fa-solid fa-receipt"></i> Transaksi Toko
                    </a>
                </li>
                <li class="sidebar-item {{ Request::routeIs('owner.payments.*') ? 'active' : '' }}">
                    <a href="{{ route('owner.payments.index') }}" class="sidebar-link">
                        <i class="fa-solid fa-money-bill-transfer"></i> Arus Kas Pembayaran
                    </a>
                </li>
            @elseif(auth()->user()->isStaff())
                <li class="sidebar-item {{ Request::routeIs('staff.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('staff.dashboard') }}" class="sidebar-link">
                        <i class="fa-solid fa-house-laptop"></i> Dashboard Kasir
                    </a>
                </li>
                <li class="sidebar-item {{ Request::routeIs('staff.orders.create') ? 'active' : '' }}">
                    <a href="{{ route('staff.orders.create') }}" class="sidebar-link">
                        <i class="fa-solid fa-cash-register"></i> Mesin Kasir (POS)
                    </a>
                </li>
                <li class="sidebar-item {{ Request::routeIs('staff.orders.index') || Request::routeIs('staff.orders.show') ? 'active' : '' }}">
                    <a href="{{ route('staff.orders.index') }}" class="sidebar-link">
                        <i class="fa-solid fa-list-check"></i> Pesanan Terkini
                    </a>
                </li>
            @endif
        </ul>
    </div>

    <!-- Top Navbar -->
    <div class="top-navbar">
        <button class="btn btn-outline-dark d-lg-none" id="sidebarToggle">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="d-none d-md-block">
            <span class="text-dark fw-600" style="font-size: 15px;">
                @if(auth()->user()->umkm)
                    <i class="fa-solid fa-building-user text-primary me-2"></i> {{ auth()->user()->umkm->name }}
                @else
                    <i class="fa-solid fa-shield-halved text-primary me-2"></i> Pengendali Sistem Global
                @endif
            </span>
        </div>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2 border" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius:10px;">
                <i class="fa-solid fa-circle-user text-primary"></i> {{ auth()->user()->name }} 
                <span class="badge" style="font-size:10px; background-color: var(--secondary); color: white;">
                    {{ strtoupper(auth()->user()->role) }}
                </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu" style="border-radius:12px; border:1px solid var(--surface-container);">
                <li>
                    <a class="dropdown-item" href="{{ route('landing') }}" target="_blank">
                        <i class="fa-solid fa-globe me-2 text-primary"></i> Portal Pengunjung
                    </a>
                </li>
                <li><hr class="dropdown-divider" style="border-color: var(--surface-container-low);"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fa-solid fa-power-off me-2"></i> Keluar Sesi
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- App Script -->
    <script>
        // Sidebar Toggle for Mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
        }

        // Flash Notifications
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 3500,
                showConfirmButton: false,
                background: '#ffffff',
                color: '#0b1c30',
                iconColor: '#b10e6b'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                timer: 4500,
                showConfirmButton: false,
                background: '#ffffff',
                color: '#0b1c30',
                iconColor: '#ba1a1a'
            });
        @endif

        @if(session('info'))
            Swal.fire({
                icon: 'info',
                title: 'Info',
                text: '{{ session('info') }}',
                timer: 3500,
                showConfirmButton: false,
                background: '#ffffff',
                color: '#0b1c30',
                iconColor: '#765469'
            });
        @endif

        // Confirm action
        function confirmDelete(event, text = "Data akan dihapus secara permanen!") {
            event.preventDefault();
            const form = event.target.closest('form');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#b10e6b',
                cancelButtonColor: '#765469',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal',
                background: '#ffffff',
                color: '#0b1c30'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
