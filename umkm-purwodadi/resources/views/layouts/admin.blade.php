<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - UMKM Purwodadi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-bg: #1e3a5f;
            --sidebar-hover: #2a4f80;
            --sidebar-active: #3a6faf;
            --topbar-height: 60px;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* ===== SIDEBAR ===== */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-brand {
            padding: 20px 20px 15px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-decoration: none;
        }

        .sidebar-brand h5 {
            color: #fff;
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .sidebar-brand small {
            color: rgba(255,255,255,0.5);
            font-size: 0.72rem;
        }

        .sidebar-nav {
            padding: 15px 0;
            flex: 1;
        }

        .sidebar-nav .nav-label {
            padding: 8px 20px 4px;
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.4);
            font-weight: 600;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            color: rgba(255,255,255,0.78);
            text-decoration: none;
            font-size: 0.88rem;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-nav a:hover {
            background-color: var(--sidebar-hover);
            color: #fff;
            border-left-color: rgba(255,255,255,0.4);
        }

        .sidebar-nav a.active {
            background-color: var(--sidebar-active);
            color: #fff;
            border-left-color: #7ecbff;
            font-weight: 600;
        }

        .sidebar-nav a .nav-icon {
            font-size: 1.1rem;
            width: 22px;
            text-align: center;
        }

        .sidebar-user {
            padding: 15px 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            background-color: rgba(0,0,0,0.15);
        }

        .sidebar-user .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #7ecbff;
            color: #1e3a5f;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .sidebar-user .user-name {
            color: #fff;
            font-size: 0.82rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 130px;
        }

        .sidebar-user .user-role {
            color: rgba(255,255,255,0.5);
            font-size: 0.7rem;
        }

        .sidebar-user .btn-logout {
            background: rgba(255,255,255,0.1);
            border: none;
            color: rgba(255,255,255,0.7);
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
            margin-top: 8px;
            width: 100%;
            text-align: center;
        }

        .sidebar-user .btn-logout:hover {
            background: rgba(220, 53, 69, 0.6);
            color: #fff;
        }

        /* ===== MAIN CONTENT ===== */
        #main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===== TOP NAVBAR ===== */
        #topbar {
            position: sticky;
            top: 0;
            z-index: 999;
            background: #fff;
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            padding: 0 24px;
            border-bottom: 1px solid #e3e6ea;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }

        #topbar .page-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1e3a5f;
            margin: 0;
        }

        #topbar .breadcrumb {
            margin: 0;
            font-size: 0.78rem;
        }

        /* ===== CONTENT AREA ===== */
        #content {
            padding: 24px;
            flex: 1;
        }

        /* ===== UTILITY ===== */
        .card {
            border: none;
            box-shadow: 0 1px 6px rgba(0,0,0,0.08);
            border-radius: 10px;
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            font-weight: 600;
            padding: 14px 20px;
        }

        .table th {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            border-top: none;
        }

        .badge {
            font-size: 0.72rem;
            padding: 4px 8px;
        }

        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            #sidebar.show {
                transform: translateX(0);
            }
            #main-wrapper {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- ===== SIDEBAR ===== -->
    <nav id="sidebar">
        <!-- Brand -->
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand d-block">
            <h5>🌾 UMKM Admin</h5>
            <small>Purwodadi Management</small>
        </a>

        <!-- Navigation -->
        <div class="sidebar-nav">
            <div class="nav-label">Main Menu</div>

            <a href="{{ route('admin.dashboard') }}"
               class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">📊</span>
                Dashboard
            </a>

            <a href="{{ route('admin.produk.index') }}"
               class="{{ request()->routeIs('admin.produk.*') ? 'active' : '' }}">
                <span class="nav-icon">📦</span>
                Produk
            </a>

            <a href="{{ route('admin.booking.index') }}"
               class="{{ request()->routeIs('admin.booking.*') ? 'active' : '' }}">
                <span class="nav-icon">🛒</span>
                Pesanan
            </a>

            <a href="{{ route('admin.pembayaran.index') }}"
               class="{{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                <span class="nav-icon">💳</span>
                Pembayaran
            </a>

            <div class="nav-label mt-2">Lainnya</div>

            <a href="{{ route('admin.laporan.index') }}"
               class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                <span class="nav-icon">📄</span>
                Laporan
            </a>

            <a href="{{ route('admin.infoweb.edit') }}"
               class="{{ request()->routeIs('admin.infoweb.*') ? 'active' : '' }}">
                <span class="nav-icon">⚙️</span>
                Pengaturan Web
            </a>
        </div>

        <!-- User Info & Logout -->
        <div class="sidebar-user">
            @php
                $pengguna = session('pengguna');
                $namaAwal = $pengguna ? strtoupper(substr($pengguna['nama_pengguna'] ?? 'A', 0, 1)) : 'A';
            @endphp
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="user-avatar">{{ $namaAwal }}</div>
                <div>
                    <div class="user-name">{{ $pengguna['nama_pengguna'] ?? 'Admin' }}</div>
                    <div class="user-role">Administrator</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    🚪 Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- ===== MAIN WRAPPER ===== -->
    <div id="main-wrapper">

        <!-- Top Navbar -->
        <div id="topbar">
            <div class="d-flex align-items-center gap-3 w-100">
                <div>
                    <h6 class="page-title">@yield('page-title', 'Dashboard')</h6>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto d-flex align-items-center gap-2">
                    <span class="badge bg-success">
                        <i class="bi bi-circle-fill" style="font-size:0.5rem;"></i>
                        Online
                    </span>
                    <span class="text-muted" style="font-size:0.8rem;">
                        {{ now()->isoFormat('dddd, D MMMM Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div id="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="text-center text-muted py-3" style="font-size:0.76rem; border-top:1px solid #e3e6ea; background:#fff;">
            &copy; {{ date('Y') }} UMKM Purwodadi &mdash; Admin Panel. All rights reserved.
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
