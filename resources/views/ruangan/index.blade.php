@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
            <span class="p-2 rounded-3 bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                <i class="fa-solid fa-door-open"></i>
            </span>
            <span>Master Data Ruangan & Lokasi</span>
        </h4>
        <p class="text-muted small mb-0 ms-md-5">Kelola data ruangan, penempatan fisik inventaris, dan penanggung jawab aset.</p>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Total Ruangan</div>
                    <div class="stat-value mt-1">{{ number_format($stats['total_ruangan'] ?? $ruangans->count()) }}</div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;"><i class="fa-solid fa-building text-primary me-1"></i>Lokasi Terdaftar</div>
                </div>
                <div class="stat-icon primary">
                    <i class="fa-solid fa-door-open"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Ruangan Terisi</div>
                    <div class="stat-value mt-1 text-success">{{ number_format($stats['ruangan_terisi'] ?? $ruangans->where('barangs_count', '>', 0)->count()) }}</div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;"><i class="fa-solid fa-boxes-stacked text-success me-1"></i>Ada Aset Fisik</div>
                </div>
                <div class="stat-icon success">
                    <i class="fa-solid fa-square-check"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Total Aset Tersebar</div>
                    <div class="stat-value mt-1 text-info">{{ number_format($stats['total_aset'] ?? 0) }}</div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;"><i class="fa-solid fa-cubes text-info me-1"></i>Unit Barang</div>
                </div>
                <div class="stat-icon info">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Aset Terbanyak</div>
                    <div class="stat-value mt-1 text-truncate" style="font-size: 1.15rem; max-width: 140px;" title="{{ $stats['ruangan_terbanyak']->nama_ruangan ?? '-' }}">
                        {{ $stats['ruangan_terbanyak']->nama_ruangan ?? '-' }}
                    </div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-trophy text-warning me-1"></i>{{ $stats['ruangan_terbanyak']->barangs_count ?? 0 }} Jenis Aset
                    </div>
                </div>
                <div class="stat-icon warning">
                    <i class="fa-solid fa-crown"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Form Tambah Ruangan -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header-modern">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-plus text-primary"></i>
                    <span>Tambah Ruangan Baru</span>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('ruangan.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Ruangan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-door-open"></i></span>
                            <input type="text" id="inputNamaRuangan" name="nama_ruangan" class="form-control" placeholder="Misal: Lab Kebidanan & Komunitas" required oninput="autoGenerateKodeRuangan(this.value)">
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label mb-0">Kode Ruangan</label>
                            <button type="button" class="btn btn-link btn-sm text-decoration-none p-0 fw-semibold text-primary" style="font-size: 0.78rem;" onclick="triggerAutoKodeRuangan()">
                                <i class="fa-solid fa-wand-magic-sparkles me-1"></i>Generate Ulang
                            </button>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-hashtag"></i></span>
                            <input type="text" id="inputKodeRuangan" name="kode_ruangan" class="form-control font-monospace" placeholder="Otomatis (atau ketik manual)">
                            <button class="btn btn-outline-secondary" type="button" onclick="triggerAutoKodeRuangan()" title="Otomatis Buat Kode">
                                <i class="fa-solid fa-bolt text-warning"></i> Auto
                            </button>
                        </div>
                        <small class="text-muted d-block mt-1" style="font-size: 0.73rem;">
                            <i class="fa-solid fa-circle-check text-success me-1"></i>Kode otomatis terisi mengikuti nama ruangan & bisa diedit jika perlu.
                        </small>
                    </div>

                    <div class="mb-4 p-3 bg-light rounded-3 border">
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="bisa_dipinjam" value="1" id="switchBisaDipinjamTambah" checked>
                            <label class="form-check-label fw-bold small text-dark" for="switchBisaDipinjamTambah">
                                Izinkan Ruangan Ini Dipinjam Publik
                            </label>
                            <small class="text-muted d-block mt-0.5" style="font-size: 0.72rem;">
                                Dosen dan Mahasiswa dapat mengajukan peminjaman ruangan ini di portal publik.
                            </small>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-modern-primary w-100 justify-content-center">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Ruangan</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Ruangan -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header-modern">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-building text-primary"></i>
                    <span>Daftar Ruangan Terdaftar</span>
                    <span class="badge bg-primary-subtle text-primary rounded-pill ms-2">{{ $ruangans->count() }} Ruangan</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern align-middle">
                        <thead>
                            <tr>
                                <th class="ps-4" style="width: 140px;">Kode Ruangan</th>
                                <th>Nama Ruangan</th>
                                <th class="text-center" style="width: 150px;">Koleksi Aset</th>
                                <th class="text-center pe-4" style="width: 130px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ruangans as $r)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge-room-code">{{ $r->kode_ruangan }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $r->nama_ruangan }}</div>
                                    <div class="mt-1">
                                        @if($r->bisa_dipinjam)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle py-0.5 px-1.5" style="font-size: 0.68rem;" title="Ruangan ini diizinkan untuk dipinjam publik">
                                                Bisa Dipinjam
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-muted border border-secondary-subtle py-0.5 px-1.5" style="font-size: 0.68rem;" title="Ruangan khusus internal">
                                                Khusus Internal
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info-subtle text-info-emphasis border px-2.5 py-1 fw-bold rounded-pill" style="font-size: 0.8rem;">
                                        {{ $r->barangs_count }} Jenis
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-inline-flex gap-1">
                                        <!-- Tombol Edit -->
                                        <button type="button" class="btn-icon btn-icon-primary" data-bs-toggle="modal" data-bs-target="#modalEditRuangan{{ $r->id }}" data-bs-toggle="tooltip" title="Edit Ruangan">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('ruangan.destroy', $r->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event, this, 'ruangan {{ $r->nama_ruangan }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon btn-icon-danger" data-bs-toggle="tooltip" title="Hapus Ruangan">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- MODAL EDIT RUANGAN -->
                                    <div class="modal fade text-start" id="modalEditRuangan{{ $r->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title fw-bold">
                                                        <i class="fa-solid fa-pen-to-square me-2"></i>Edit Data Ruangan
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('ruangan.update', $r->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Kode Ruangan <span class="text-danger">*</span></label>
                                                            <input type="text" name="kode_ruangan" class="form-control font-monospace" value="{{ $r->kode_ruangan }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Ruangan <span class="text-danger">*</span></label>
                                                            <input type="text" name="nama_ruangan" class="form-control" value="{{ $r->nama_ruangan }}" required>
                                                        </div>
                                                        <div class="mb-3 p-3 bg-light rounded-3 border">
                                                            <div class="form-check form-switch mb-0">
                                                                <input class="form-check-input" type="checkbox" name="bisa_dipinjam" value="1" id="switchEditBisaDipinjam{{ $r->id }}" {{ $r->bisa_dipinjam ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-bold small text-dark" for="switchEditBisaDipinjam{{ $r->id }}">
                                                                    Izinkan Ruangan Ini Dipinjam Publik
                                                                </label>
                                                                <small class="text-muted d-block mt-0.5" style="font-size: 0.72rem;">
                                                                    Aktifkan agar ruangan muncul di pilihan booking publik.
                                                                </small>
                                                            </div>
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
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted mb-2">
                                        <i class="fa-solid fa-door-closed fa-3x opacity-50"></i>
                                    </div>
                                    <h6 class="fw-bold text-secondary mb-1">Belum Ada Data Ruangan</h6>
                                    <p class="text-muted small mb-0">Tambahkan ruangan baru menggunakan form di sebelah kiri.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>

<script>
    let manualKodeEdited = false;

    document.getElementById('inputKodeRuangan')?.addEventListener('input', function() {
        manualKodeEdited = this.value.trim().length > 0;
    });

    function autoGenerateKodeRuangan(nama) {
        if (manualKodeEdited) return;
        generateKodeFromText(nama);
    }

    function triggerAutoKodeRuangan() {
        manualKodeEdited = false;
        let nama = document.getElementById('inputNamaRuangan').value;
        generateKodeFromText(nama);
    }

    function generateKodeFromText(nama) {
        if (!nama || nama.trim() === '') {
            document.getElementById('inputKodeRuangan').value = '';
            return;
        }

        let clean = nama.trim().toUpperCase();
        let prefix = 'RNG';
        let sub = '';

        if (clean.includes('LAB')) {
            prefix = 'LAB';
            let words = clean.replace(/LABORATORIUM|LAB/g, '').trim().split(/[\s\-_]+/);
            if (words.length > 0 && words[0]) {
                sub = words[0].substring(0, 3);
            }
        } else if (clean.includes('KULIAH') || clean.includes('KELAS') || clean.includes('TEORI')) {
            prefix = 'RK';
            let words = clean.replace(/RUANG|KULIAH|KELAS|TEORI/g, '').trim().split(/[\s\-_]+/);
            if (words.length > 0 && words[0]) {
                sub = words[0].substring(0, 3);
            }
        } else if (clean.includes('GUDANG')) {
            prefix = 'GDG';
        } else if (clean.includes('PERPUS')) {
            prefix = 'PERPUS';
        } else if (clean.includes('AULA')) {
            prefix = 'AULA';
        } else {
            let words = clean.replace(/RUANG|RUANGAN|KANTOR/g, '').trim().split(/[\s\-_]+/);
            if (words.length > 0 && words[0]) {
                sub = words[0].substring(0, 3);
            }
        }

        let count = {{ $ruangans->count() + 1 }};
        let seq = count < 10 ? '0' + count : count;
        let finalCode = sub ? `${prefix}-${sub}-${seq}` : `${prefix}-${seq}`;
        document.getElementById('inputKodeRuangan').value = finalCode;
    }
</script>
@endsection