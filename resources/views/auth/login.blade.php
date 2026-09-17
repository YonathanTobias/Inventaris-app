<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SPARTA-PW - STIKES Panti Waluya Malang</title>
    
    <!-- Theme Script (Instant execution to prevent flash) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('sparta_theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-square.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & FontAwesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom Login CSS -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

    <!-- Theme Switch Button -->
    <button type="button" class="theme-toggle-btn-login" id="btnThemeToggleLogin" onclick="toggleLoginTheme()" title="Ganti Mode Gelap / Terang" aria-label="Toggle theme">
        <i class="fa-solid fa-moon" id="themeIconLogin"></i>
    </button>

    <div class="login-card">
        <!-- Brand Header -->
        <div class="brand-header">
            <img src="{{ asset('images/logo-stikes.png') }}" alt="Logo STIKES Panti Waluya" style="height: 75px; width: auto; object-fit: contain; margin-bottom: 0.75rem;">
            <h4 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.02em;">SPARTA-PW</h4>
            <p class="text-muted small mb-0">Sistem Peminjaman Aset & Ruangan Terpadu<br><strong class="text-dark">STIKES Panti Waluya Malang</strong></p>
        </div>

        <!-- Form Area -->
        <div class="p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 py-2 small mb-3" role="alert">
                    <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger rounded-3 py-2 small mb-3" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary mb-1">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0"><i class="fa-solid fa-envelope"></i></span>
                        <input type="email" name="email" id="email" class="form-control border-start-0 ps-0" placeholder="nama@pantiwaluya.ac.id" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary mb-1">Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control border-start-0 border-end-0 ps-0" placeholder="Masukkan password" required>
                        <button type="button" class="input-group-text border-start-0 bg-white" onclick="togglePasswordVisibility()">
                            <i class="fa-regular fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small text-muted" for="remember">
                            Ingat Saya
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-login">
                    <span>Masuk ke Sistem</span>
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>

                <div class="text-center mt-3 pt-2">
                    <a href="{{ url('/') }}" class="text-decoration-none small text-muted fw-semibold">
                        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Portal Peminjaman Aset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>