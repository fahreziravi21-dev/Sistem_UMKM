<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun &mdash; UMKM Dusun 01 Purwodadi</title>

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
            padding: 2rem 1rem;
        }

        .auth-wrapper {
            width: 100%;
        }

        .auth-card {
            max-width: 500px;
            margin: 0 auto;
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.18);
        }

        .auth-card .card-body {
            padding: 2.5rem 2.5rem;
        }

        .brand-emoji {
            font-size: 3rem;
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

        .input-group .form-control,
        .input-group .form-select {
            border-left: none;
        }

        .input-group .form-control:focus,
        .input-group textarea.form-control:focus {
            box-shadow: none;
            border-color: #198754;
        }

        .input-group:focus-within .input-group-text {
            border-color: #198754;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
        }

        .btn-register {
            background-color: #198754;
            border-color: #198754;
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 0.65rem;
            font-size: 1rem;
            transition: background-color 0.2s ease;
        }

        .btn-register:hover {
            background-color: #146c43;
            border-color: #146c43;
        }

        .login-link {
            color: #198754;
            font-weight: 600;
            text-decoration: none;
        }

        .login-link:hover {
            color: #146c43;
            text-decoration: underline;
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

        .section-title {
            font-size: 0.75rem;
            font-weight: 700;
            color: #198754;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-bottom: 2px solid #d4edda;
            padding-bottom: 0.35rem;
            margin-bottom: 1rem;
        }

        .password-strength {
            height: 4px;
            border-radius: 2px;
            transition: width 0.3s ease, background-color 0.3s ease;
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
                <div class="brand-subtitle">Daftar Akun Baru</div>
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

            {{-- Global Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    <strong>Mohon periksa kembali data Anda:</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Registration Form --}}
            <form action="{{ route('register') }}" method="POST" novalidate>
                @csrf

                {{-- Section: Data Diri --}}
                <div class="section-title">
                    <i class="bi bi-person-badge me-1"></i> Data Diri
                </div>

                {{-- Nama Lengkap --}}
                <div class="mb-3">
                    <label for="nama_pengguna" class="form-label fw-semibold small">
                        Nama Lengkap <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input
                            type="text"
                            class="form-control @error('nama_pengguna') is-invalid @enderror"
                            id="nama_pengguna"
                            name="nama_pengguna"
                            value="{{ old('nama_pengguna') }}"
                            placeholder="Masukkan nama lengkap"
                            autocomplete="name"
                            autofocus
                        >
                        @error('nama_pengguna')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Username --}}
                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold small">
                        Username <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-at"></i></span>
                        <input
                            type="text"
                            class="form-control @error('username') is-invalid @enderror"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            placeholder="Masukkan username unik"
                            autocomplete="username"
                        >
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-text small"><i class="bi bi-info-circle me-1"></i>Username tidak dapat diubah setelah didaftarkan.</div>
                </div>

                {{-- No. Telepon --}}
                <div class="mb-3">
                    <label for="no_tlp" class="form-label fw-semibold small">No. Telepon</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                        <input
                            type="tel"
                            class="form-control @error('no_tlp') is-invalid @enderror"
                            id="no_tlp"
                            name="no_tlp"
                            value="{{ old('no_tlp') }}"
                            placeholder="Contoh: 081234567890"
                            autocomplete="tel"
                        >
                        @error('no_tlp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="mb-4">
                    <label for="alamat" class="form-label fw-semibold small">Alamat</label>
                    <div class="input-group align-items-start">
                        <span class="input-group-text pt-2"><i class="bi bi-geo-alt-fill"></i></span>
                        <textarea
                            class="form-control @error('alamat') is-invalid @enderror"
                            id="alamat"
                            name="alamat"
                            rows="2"
                            placeholder="Masukkan alamat lengkap"
                        >{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Section: Keamanan --}}
                <div class="section-title">
                    <i class="bi bi-shield-lock me-1"></i> Keamanan Akun
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold small">
                        Password <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            placeholder="Minimal 6 karakter"
                            autocomplete="new-password"
                            oninput="checkPasswordStrength(this.value)"
                        >
                        <button type="button" class="btn show-password-btn border" id="togglePassword" tabindex="-1" aria-label="Tampilkan password">
                            <i class="bi bi-eye-fill" id="togglePasswordIcon"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Password Strength Bar --}}
                    <div class="mt-1 bg-light rounded" style="height:4px;">
                        <div class="password-strength" id="strengthBar" style="width:0%; background:#dee2e6;"></div>
                    </div>
                    <div class="form-text small" id="strengthText"></div>
                </div>

                {{-- Konfirmasi Password --}}
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold small">
                        Konfirmasi Password <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input
                            type="password"
                            class="form-control"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Ulangi password"
                            autocomplete="new-password"
                        >
                        <button type="button" class="btn show-password-btn border" id="toggleConfirmPassword" tabindex="-1" aria-label="Tampilkan konfirmasi password">
                            <i class="bi bi-eye-fill" id="toggleConfirmPasswordIcon"></i>
                        </button>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-register btn-success text-white">
                        <i class="bi bi-person-check-fill me-1"></i> Daftar Sekarang
                    </button>
                </div>
            </form>

            {{-- Divider --}}
            <div class="text-center text-muted small my-3">Sudah punya akun?</div>

            {{-- Login Link --}}
            <div class="text-center">
                <a href="{{ route('login') }}" class="login-link">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Akun
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
    function setupToggle(btnId, inputId, iconId) {
        const btn   = document.getElementById(btnId);
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);

        if (btn && input && icon) {
            btn.addEventListener('click', function () {
                const isPass = input.type === 'password';
                input.type   = isPass ? 'text' : 'password';
                icon.classList.toggle('bi-eye-fill', !isPass);
                icon.classList.toggle('bi-eye-slash-fill', isPass);
            });
        }
    }

    setupToggle('togglePassword',        'password',              'togglePasswordIcon');
    setupToggle('toggleConfirmPassword', 'password_confirmation', 'toggleConfirmPasswordIcon');

    // Password strength checker
    function checkPasswordStrength(value) {
        const bar  = document.getElementById('strengthBar');
        const text = document.getElementById('strengthText');

        if (!bar || !text) return;

        let strength = 0;
        if (value.length >= 6)  strength++;
        if (value.length >= 10) strength++;
        if (/[A-Z]/.test(value)) strength++;
        if (/[0-9]/.test(value)) strength++;
        if (/[^A-Za-z0-9]/.test(value)) strength++;

        const levels = [
            { pct: '0%',   color: '#dee2e6', label: '' },
            { pct: '25%',  color: '#dc3545', label: '<span class="text-danger">Sangat Lemah</span>' },
            { pct: '50%',  color: '#fd7e14', label: '<span class="text-warning">Lemah</span>' },
            { pct: '75%',  color: '#ffc107', label: '<span class="text-info">Sedang</span>' },
            { pct: '90%',  color: '#20c997', label: '<span class="text-success">Kuat</span>' },
            { pct: '100%', color: '#198754', label: '<span class="text-success fw-bold">Sangat Kuat</span>' },
        ];

        const level = levels[Math.min(strength, 5)];
        bar.style.width           = value.length === 0 ? '0%' : level.pct;
        bar.style.backgroundColor = level.color;
        text.innerHTML            = value.length === 0 ? '' : 'Kekuatan: ' + level.label;
    }
</script>

</body>
</html>
