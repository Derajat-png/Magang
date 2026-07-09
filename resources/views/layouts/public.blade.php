<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - UMKM Hub</title>
    
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
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        /* Navbar Styling */
        .client-navbar {
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--surface-container);
            padding: 16px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }

        .navbar-brand {
            font-size: 22px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--tertiary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link-custom {
            color: var(--text-muted);
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
        }

        .nav-link-custom:hover, .nav-link-custom.active {
            color: var(--primary);
            background-color: var(--secondary-container);
        }

        /* Cards */
        .glass-card {
            background-color: var(--surface);
            border: 1px solid var(--surface-container);
            border-radius: 16px;
            box-shadow: var(--shadow-soft);
            padding: 24px;
            transition: transform 0.25s, box-shadow 0.25s;
        }

        .glass-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
        }

        /* Buttons */
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

        /* Hero Section */
        .hero-section {
            background: radial-gradient(circle at top right, rgba(210, 50, 132, 0.05), transparent 60%),
                        radial-gradient(circle at bottom left, rgba(177, 14, 107, 0.03), transparent 50%);
            padding: 80px 0;
            border-bottom: 1px solid var(--surface-container);
        }

        /* Footer styling */
        footer {
            margin-top: auto;
            background-color: var(--surface);
            border-top: 1px solid var(--surface-container);
            padding: 30px 0;
            color: var(--text-muted);
            font-size: 13px;
        }

        /* Form elements */
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
            font-weight: 600;
            font-size: 13px;
            color: var(--text-main);
            margin-bottom: 6px;
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
    </style>
    @yield('styles')
</head>
<body>

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg client-navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('landing') }}">
                <i class="fa-solid fa-layer-group text-primary me-2"></i> UMKM Hub
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#clientNavbar" aria-controls="clientNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="clientNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item">
                        <a class="nav-link-custom {{ Request::routeIs('landing') ? 'active' : '' }}" href="{{ route('landing') }}">Direktori Usaha</a>
                    </li>
                </ul>
                <div class="d-flex gap-3 align-items-center">
                    @auth
                        <a href="{{ auth()->user()->isSuperAdmin() ? route('admin.dashboard') : (auth()->user()->isOwner() ? route('owner.dashboard') : route('staff.dashboard')) }}" class="btn btn-glow-primary">
                            <i class="fa-solid fa-gauge me-2"></i> Dashboard
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger" style="border-radius: 10px; padding: 10px 16px;">
                                <i class="fa-solid fa-power-off"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-glow-outline">
                            <i class="fa-solid fa-circle-user me-2"></i> Portal Bisnis
                        </a>
                        <a href="{{ route('register-umkm') }}" class="btn btn-glow-primary">
                            <i class="fa-solid fa-store me-2"></i> Daftarkan Usaha
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Content Area -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p class="mb-1">&copy; 2026 UMKM Hub. Dikembangkan untuk efisiensi bisnis lokal.</p>
            <p class="mb-0 text-muted" style="font-size:11px;">Mendukung pertumbuhan ekonomi mandiri melalui digitalisasi sistem kasir & inventori.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- App Script -->
    <script>
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
    </script>
    @yield('scripts')
</body>
</html>
