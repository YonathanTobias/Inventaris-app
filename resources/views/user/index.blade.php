@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
            <span class="p-2 rounded-3 bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                <i class="fa-solid fa-users-gear"></i>
            </span>
            <span>Manajemen Pengguna & Hak Akses</span>
        </h4>
        <p class="text-muted small mb-0 ms-md-5">Kelola akun administrator, penugasan hak akses Admin IT & Admin SARPRAS.</p>
    </div>
    <div>
        <button class="btn btn-modern-primary" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
            <i class="fa-solid fa-user-plus"></i>
            <span>Tambah Pengguna Baru</span>
        </button>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Total Akun Terdaftar</div>
                    <div class="stat-value mt-1">{{ number_format($stats['total_user']) }}</div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;"><i class="fa-solid fa-users text-primary me-1"></i>Pengguna Aktif</div>
                </div>
                <div class="stat-icon primary">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Admin IT (Super User)</div>
                    <div class="stat-value mt-1 text-primary">{{ number_format($stats['total_it']) }}</div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;"><i class="fa-solid fa-shield-halved text-primary me-1"></i>Akses Penuh Sistem</div>
                </div>
                <div class="stat-icon primary">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Admin SARPRAS</div>
                    <div class="stat-value mt-1 text-success">{{ number_format($stats['total_sarpras']) }}</div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;"><i class="fa-solid fa-boxes-packing text-success me-1"></i>Akses Inventaris & Aset</div>
                </div>
                <div class="stat-icon success">
                    <i class="fa-solid fa-user-check"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Toolbar -->
<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('user.index') }}" class="row g-2 align-items-center">
            <div class="col-lg-6 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari Nama Pengguna atau Alamat Email..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <select name="role" class="form-select">
                    <option value="">-- Semua Hak Akses / Role --</option>
                    <option value="it" {{ request('role') == 'it' ? 'selected' : '' }}>Admin IT (Super User)</option>
                    <option value="sarpras" {{ request('role') == 'sarpras' ? 'selected' : '' }}>Admin SARPRAS</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100 fw-bold">
                    <i class="fa-solid fa-filter me-1"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'role']))
                    <a href="{{ route('user.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Tabel Pengguna -->
<div class="card">
    <div class="card-header-modern">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-user-group text-primary"></i>
            <span>Daftar Akun Pengguna</span>
            <span class="badge bg-primary-subtle text-primary rounded-pill ms-2">{{ $users->count() }} Akun</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Pengguna</th>
                        <th>Alamat Email</th>
                        <th>Hak Akses (Role)</th>
                        <th>Terdaftar Sejak</th>
                        <th class="text-center pe-4" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 40px; height: 40px; background: {{ $u->role === 'it' ? 'linear-gradient(135deg, #4f46e5, #3b82f6)' : 'linear-gradient(135deg, #10b981, #059669)' }}; font-size: 1rem;">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark d-flex align-items-center gap-1.5">
                                        <span>{{ $u->name }}</span>
                                        @if($u->id === auth()->id())
                                            <span class="badge bg-dark-subtle text-dark border px-2 py-0" style="font-size: 0.68rem;">Anda</span>
                                        @endif
                                    </div>
                                    <small class="text-muted">ID: #USR-{{ str_pad($u->id, 3, '0', STR_PAD_LEFT) }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1.5 text-secondary">
                                <i class="fa-regular fa-envelope text-muted"></i>
                                <span class="font-monospace small">{{ $u->email }}</span>
                            </div>
                        </td>
                        <td>
                            @if($u->role === 'it')
                                <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-1.5 rounded-pill font-monospace" style="font-size: 0.78rem;">
                                    <i class="fa-solid fa-shield-halved me-1"></i> Admin IT (Super User)
                                </span>
                            @else
                                <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle px-3 py-1.5 rounded-pill font-monospace" style="font-size: 0.78rem;">
                                    <i class="fa-solid fa-boxes-packing me-1"></i> Admin SARPRAS
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted small">
                                <i class="fa-regular fa-clock me-1"></i>{{ $u->created_at ? $u->created_at->format('d M Y') : '-' }}
                            </span>
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-inline-flex gap-1">
                                <!-- Tombol Edit -->
                                <button type="button" class="btn-icon btn-icon-primary" data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $u->id }}" data-bs-toggle="tooltip" title="Edit Akun">
                                    <i class="fa-solid fa-user-pen"></i>
                                </button>

                                <!-- Tombol Hapus -->
                                @if($u->id !== auth()->id())
                                    <form action="{{ route('user.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event, this, 'pengguna {{ $u->name }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-icon-danger" data-bs-toggle="tooltip" title="Hapus Pengguna">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn-icon btn-icon-secondary opacity-50" disabled data-bs-toggle="tooltip" title="Tidak dapat menghapus diri sendiri">
                                        <i class="fa-solid fa-ban"></i>
                                    </button>
                                @endif
                            </div>

                            <!-- MODAL EDIT USER -->
                            <div class="modal fade text-start" id="modalEditUser{{ $u->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title fw-bold">
                                                <i class="fa-solid fa-user-pen me-2"></i>Edit Data Pengguna
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('user.update', $u->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                                    <input type="text" name="name" class="form-control" value="{{ $u->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Alamat Email <span class="text-danger">*</span></label>
                                                    <input type="email" name="email" class="form-control" value="{{ $u->email }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Hak Akses (Role) <span class="text-danger">*</span></label>
                                                    <select name="role" class="form-select" required>
                                                        <option value="it" {{ $u->role === 'it' ? 'selected' : '' }}>Admin IT (Super User)</option>
                                                        <option value="sarpras" {{ $u->role === 'sarpras' ? 'selected' : '' }}>Admin SARPRAS</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Ganti Password (Opsional)</label>
                                                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                                                    <div class="form-text small text-muted">Minimal 6 karakter jika ingin mengganti.</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary fw-bold">
                                                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-muted mb-2">
                                <i class="fa-solid fa-users fa-3x opacity-50"></i>
                            </div>
                            <h6 class="fw-bold text-secondary mb-1">Pengguna Tidak Ditemukan</h6>
                            <p class="text-muted small mb-0">Tidak ada data pengguna yang cocok dengan filter pencarian.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH USER BARU -->
<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-user-plus text-primary me-2"></i>Tambah Pengguna Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-user"></i></span>
                            <input type="text" name="name" class="form-control" placeholder="Misal: John Doe" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="nama@inventaris.com" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hak Akses (Role) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-user-shield"></i></span>
                            <select name="role" class="form-select" required>
                                <option value="sarpras" selected>Admin SARPRAS (Kelola Aset & Ruangan)</option>
                                <option value="it">Admin IT (Super User - Akses Penuh)</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kata Sandi (Password) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-modern-primary">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Pengguna</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
