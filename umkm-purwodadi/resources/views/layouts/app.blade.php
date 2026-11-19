<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'UMKM Dusun 01 Purwodadi')</title>

    <!-- Google Font (Plus Jakarta Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            color: #1e293b;
            background-color: #f8fafc;
        }

        main {
            flex: 1;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.35rem;
            letter-spacing: -0.5px;
        }

        .footer-bg {
            background: linear-gradient(135deg, #331505 0%, #421b07 100%);
            color: #fff;
            border-top: 3px solid #C8900A;
        }

        .footer-bg a {
            color: #F0D090;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-bg a:hover {
            color: #FFD166;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #3d1906 0%, #52230b 50%, #632d0f 100%) !important;
            border-bottom: 2px solid #C8900A;
            box-shadow: 0 4px 18px rgba(51, 21, 5, 0.35);
        }

        .navbar-custom .navbar-brand {
            color: #FFFDF5;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .navbar-custom .nav-link {
            color: rgba(255, 253, 245, 0.85);
            transition: all 0.2s ease;
        }

        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {
            color: #FFD166 !important;
            text-shadow: 0 0 12px rgba(255, 209, 102, 0.4);
        }

        .navbar-custom .btn-outline-light {
            border-color: rgba(255, 209, 102, 0.6);
            color: #FFFDF5;
            transition: all 0.2s ease;
        }

        .navbar-custom .btn-outline-light:hover {
            background-color: #C8900A;
            border-color: #C8900A;
            color: #2D1206;
            box-shadow: 0 2px 10px rgba(200, 144, 10, 0.4);
        }

        .navbar-custom .btn-daftar {
            background: linear-gradient(135deg, #E5A812, #C8900A);
            color: #2D1206 !important;
            font-weight: 700;
            border: none;
            box-shadow: 0 2px 8px rgba(200, 144, 10, 0.3);
            transition: all 0.2s ease;
        }

        .navbar-custom .btn-daftar:hover {
            background: linear-gradient(135deg, #FFD166, #E5A812);
            color: #1A0A02 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(200, 144, 10, 0.5);
        }
    </style>
    @yield('styles')
    @stack('styles')
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('beranda') }}">🌾 UMKM Purwodadi</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
                aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('beranda') ? 'active fw-semibold' : '' }}"
                            href="{{ route('beranda') }}">
                            <i class="bi bi-house-door me-1"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('katalog*') ? 'active fw-semibold' : '' }}"
                            href="{{ route('katalog.index') }}">
                            <i class="bi bi-grid me-1"></i>Katalog Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('tracking*') ? 'active fw-semibold' : '' }}"
                            href="{{ route('booking.tracking') }}">
                            <i class="bi bi-search me-1"></i>Cek Pesanan
                        </a>
                    </li>
                </ul>

                <!-- Right side auth -->
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                    @if(session('pengguna'))
                        @php
                            $userSession = session('pengguna');
                            $namaUser = is_array($userSession) ? ($userSession['nama_pengguna'] ?? $userSession['nama'] ?? 'Pengguna') : 'Pengguna';
                        @endphp

                        <!-- Keranjang Icon -->
                        <li class="nav-item me-2">
                            <a class="nav-link position-relative" href="{{ route('keranjang.index') }}">
                                <i class="bi bi-cart3 fs-5"></i>
                                @if(session('keranjang') && count(session('keranjang')) > 0)
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ count(session('keranjang')) }}
                                    </span>
                                @endif
                            </a>
                        </li>

                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i>Hi, {{ $namaUser }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                @if(isset($userSession['level']) && $userSession['level'] === 'admin')
                                    <li>
                                        <a class="dropdown-item text-primary fw-bold" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i>Dashboard Admin
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                @endif
                                <li>
                                    <a class="dropdown-item" href="{{ route('booking.riwayat') }}">
                                        <i class="bi bi-bag-check me-2"></i>Pesanan Saya
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item me-2">
                            <a class="btn btn-outline-light btn-sm" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-daftar btn-sm" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i>Daftar
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                <div>{{ session('info') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                <div>{{ session('warning') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-bg mt-5 py-4">
        <div class="container">
            <div class="row gy-3">
                <div class="col-md-4">
                    <h5 class="fw-bold mb-2">🌾 UMKM Dusun 01 Purwodadi</h5>
                    <p class="mb-0 small opacity-75">
                        Produsen makanan ringan tradisional berkualitas.<br>
                        Kelanting, Untir-untir, dan Keripik Singkong.
                    </p>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-semibold mb-2">Alamat</h6>
                    <address class="mb-0 small opacity-75">
                        <i class="bi bi-geo-alt me-1"></i>
                        Dusun 01 Purwodadi, Trimurjo,<br>
                        Lampung Tengah, Lampung
                    </address>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-semibold mb-2">Navigasi</h6>
                    <ul class="list-unstyled mb-0 small">
                        <li><a href="{{ route('beranda') }}"><i class="bi bi-chevron-right me-1"></i>Beranda</a></li>
                        <li><a href="{{ route('katalog.index') }}"><i class="bi bi-chevron-right me-1"></i>Katalog
                                Produk</a></li>
                        <li><a href="{{ route('booking.tracking') }}"><i class="bi bi-chevron-right me-1"></i>Cek
                                Pesanan</a></li>
                    </ul>
                </div>
            </div>
            <hr class="border-light opacity-25 my-3">
            <div class="text-center small opacity-75">
                &copy; 2025 UMKM Dusun 01 Purwodadi, Trimurjo, Lampung Tengah. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>