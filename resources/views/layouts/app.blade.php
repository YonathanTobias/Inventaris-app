<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Inventaris Aset</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

    <!-- Navbar Minimalis -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="fa-solid fa-boxes-stacked me-2"></i>INVENTARIS ASET</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('barang.index') }}"><i class="fa-solid fa-box me-1"></i> Data Aset</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('ruangan.index') }}"><i class="fa-solid fa-door-open me-1"></i> Ruangan</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('kategori.index') }}"><i class="fa-solid fa-tags me-1"></i> Kategori</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('laporan.index') }}"><i class="fa-solid fa-file-excel me-1"></i> Laporan / KIR</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mb-5">
        <!-- Notification Alert -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>