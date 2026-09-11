<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SPARTA-PW - STIKES Panti Waluya Malang</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-stikes.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & FontAwesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --color-navy: #0F2C59;
            --primary: #1E3A8A;
            --primary-hover: #0F2C59;
            --body-bg: #F1F5F9;
            --border-color: #E2E8F0;
            --text-main: #0F172A;
            --text-muted: #475569;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--body-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            color: var(--text-main);
            letter-spacing: -0.01em;
            -webkit-font-smoothing: antialiased;
        }

        :focus-visible {
            outline: 2px solid #2563EB !important;
            outline-offset: 2px !important;
        }

        .login-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.08), 0 2px 4px -2px rgba(15, 23, 42, 0.04);
            border: 1px solid var(--border-color);
            overflow: hidden;
            width: 100%;
            max-width: 440px;
        }

        .brand-header {
            text-align: center;
            padding: 2.25rem 2rem 1.25rem;
            background-color: #FFFFFF;
            border-bottom: 1px solid var(--border-color);
        }

        .form-control {
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            padding: 0.65rem 0.9rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-main);
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-control:focus {
            border-color: #2563EB;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .input-group-text {
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            background-color: #F8FAFC;
            color: #475569;
        }

        .btn-login {
            background-color: #1E3A8A;
            border: 1px solid #0F2C59;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
            transition: background-color 0.15s ease;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-login:hover {
            background-color: #0F2C59;
            color: #ffffff;
        }
    </style>
</head>
<body>

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
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>