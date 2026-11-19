<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login &mdash; UMKM Dusun 01 Purwodadi</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #d4edda 0%, #a8d5b5 40%, #6db88a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .auth-wrapper {
            width: 100%;
            padding: 1rem;
        }

        .auth-card {
            max-width: 450px;
            margin: 0 auto;
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.18);
        }

        .auth-card .card-body {
            padding: 2.5rem 2.5rem;
        }

        .brand-emoji {
            font-size: 3.5rem;
            line-height: 1;
        }

        .brand-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a5c38;
        }

        .brand-subtitle {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
            color: #6c757d;
        }

        .input-group .form-control {
            border-left: none;
        }

        .input-group .form-control:focus {
            box-shadow: none;
            border-color: #198754;
        }

        .input-group:focus-within .input-group-text {
            border-color: #198754;
        }

        .btn-login {
            background-color: #198754;
            border-color: #198754;
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 0.65rem;
            font-size: 1rem;
            transition: background-color 0.2s ease;
        }

        .btn-login:hover {
            background-color: #146c43;
            border-color: #146c43;
        }

        .divider-text {
            font-size: 0.8rem;
            color: #adb5bd;
        }

        .register-link {
            color: #198754;
            font-weight: 600;
            text-decoration: none;
        }

        .register-link:hover {
            color: #146c43;
            text-decoration: underline;
        }

        .form-check-input:checked {
            background-color: #198754;
            border-color: #198754;
        }

        .show-password-btn {
            border-left: none;
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #6c757d;
            cursor: pointer;
        }

        .show-password-btn:hover {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card card">
        <div class="card-body">

            {{-- Brand / Logo --}}
            <div class="text-center mb-4">
                <div class="brand-emoji mb-2">🌾</div>
                <div class="brand-title">UMKM Dusun 01 Purwodadi</div>
                <div class="brand-subtitle">Sistem Informasi Pemasaran</div>
            </div>

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
                    <i class="bi bi-check-circle-fill me-1"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Login Form --}}
            <form action="{{ route('login') }}" method="POST" novalidate>
                @csrf

                {{-- Username --}}
                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold small">Username</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person-fill"></i>
                        </span>
                        <input
                            type="text"
                            class="form-control @error('username') is-invalid @enderror"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            placeholder="Masukkan username"
                            autocomplete="username"
                            autofocus
                        >
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold small">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                        >
                        <button
                            type="button"
                            class="btn show-password-btn border"
                            id="togglePassword"
                            tabindex="-1"
                            aria-label="Tampilkan password"
                        >
                            <i class="bi bi-eye-fill" id="togglePasswordIcon"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Remember Me --}}
                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                        <label class="form-check-label small text-muted" for="rememberMe">
                            Ingat saya
                        </label>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-login btn-success text-white">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                    </button>
                </div>
            </form>

            {{-- Divider --}}
            <div class="text-center divider-text my-3">
                <span>Belum punya akun?</span>
            </div>

            {{-- Register Link --}}
            <div class="text-center">
                <a href="{{ route('register') }}" class="register-link">
                    <i class="bi bi-person-plus-fill me-1"></i> Daftar Sekarang
                </a>
            </div>

        </div>
    </div>

    <div class="text-center mt-3">
        <small class="text-white" style="text-shadow: 0 1px 3px rgba(0,0,0,0.3);">
            &copy; {{ date('Y') }} UMKM Dusun 01 Purwodadi. All rights reserved.
        </small>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput  = document.getElementById('password');
    const toggleIcon     = document.getElementById('togglePasswordIcon');

    if (togglePassword) {
        togglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            toggleIcon.classList.toggle('bi-eye-fill', !isPassword);
            toggleIcon.classList.toggle('bi-eye-slash-fill', isPassword);
        });
    }
</script>

</body>
</html>
