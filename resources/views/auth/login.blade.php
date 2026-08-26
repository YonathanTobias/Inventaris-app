<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - STIKES Panti Waluya Malang</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-stikes.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & FontAwesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            --dark: #0f172a;
            --card-bg: #ffffff;
            --body-bg: #0b0f19;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--body-bg);
            background-image: 
                radial-gradient(at 0% 0%, rgba(79, 70, 229, 0.25) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(14, 165, 233, 0.2) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(15, 23, 42, 0.8) 0px, transparent 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            color: #1e293b;
        }

        .login-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 460px;
            position: relative;
            z-index: 10;
        }

        .brand-header {
            text-align: center;
            padding: 2.25rem 2rem 1.25rem;
        }

        .brand-icon-large {
            width: 58px;
            height: 58px;
            background: var(--primary-gradient);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.65rem;
            margin-bottom: 1rem;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.35);
        }

        .form-control {
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
        }

        .input-group-text {
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            background-color: #f8fafc;
            color: #64748b;
        }

        .btn-login {
            background: var(--primary-gradient);
            border: none;
            color: #ffffff;
            font-weight: 700;
            font-size: 1rem;
            padding: 0.85rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(79, 70, 229, 0.35);
            transition: all 0.2s ease;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(79, 70, 229, 0.45);
            color: #ffffff;
        }

        .demo-credential-box {
            background-color: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 14px;
            padding: 1rem;
            margin-top: 1.5rem;
        }

        .demo-btn {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 0.45rem 0.75rem;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            text-align: left;
            margin-bottom: 0.4rem;
        }

        .demo-btn:hover {
            background: #eef2ff;
            border-color: #c7d2fe;
            color: var(--primary);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Brand Header -->
        <div class="brand-header">
            <img src="{{ asset('images/logo-stikes.png') }}" alt="Logo STIKES Panti Waluya" style="height: 85px; width: auto; object-fit: contain; filter: drop-shadow(0 6px 14px rgba(0,0,0,0.12)); margin-bottom: 0.85rem;">
            <h4 class="fw-bold mb-1" style="letter-spacing: -0.02em;">STIKES PANTI WALUYA</h4>
            <p class="text-muted small mb-0">Sistem Inventaris & Aset Laboratorium</p>
        </div>

        <!-- Form Area -->
        <div class="px-4 pb-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 py-2 small mb-3" role="alert">
                    <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
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
            </form>

            <!-- Quick Demo Credentials Box -->
            <div class="demo-credential-box">
                <div class="d-flex align-items-center gap-1.5 mb-2">
                    <i class="fa-solid fa-circle-info text-primary" style="font-size: 0.85rem;"></i>
                    <span class="small fw-bold text-dark">Pilih Akun Login (Klik Cepat):</span>
                </div>
                
                <button type="button" class="demo-btn" onclick="fillCredentials('adminit@pantiwaluya.ac.id', 'password123')">
                    <div>
                        <span class="badge bg-primary me-1">Admin IT</span>
                        <span class="text-secondary font-monospace">adminit@pantiwaluya.ac.id</span>
                    </div>
                    <i class="fa-solid fa-arrow-turn-down text-muted" style="font-size: 0.75rem;"></i>
                </button>

                <button type="button" class="demo-btn mb-0" onclick="fillCredentials('sarpras@pantiwaluya.ac.id', 'password123')">
                    <div>
                        <span class="badge bg-success me-1">Admin SARPRAS</span>
                        <span class="text-secondary font-monospace">sarpras@pantiwaluya.ac.id</span>
                    </div>
                    <i class="fa-solid fa-arrow-turn-down text-muted" style="font-size: 0.75rem;"></i>
                </button>
            </div>
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

        function fillCredentials(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>
