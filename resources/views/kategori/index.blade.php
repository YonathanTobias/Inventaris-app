@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
            <span class="p-2 rounded-3 bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                <i class="fa-solid fa-tags"></i>
            </span>
            <span>Master Data Kategori Barang</span>
        </h4>
        <p class="text-muted small mb-0 ms-md-5">Kelola klasifikasi dan pengelompokan jenis aset dalam sistem inventaris.</p>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Total Kategori</div>
                    <div class="stat-value mt-1">{{ number_format($stats['total_kategori'] ?? $kategoris->count()) }}</div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;"><i class="fa-solid fa-layer-group text-primary me-1"></i>Klasifikasi Aktif</div>
                </div>
                <div class="stat-icon primary">
                    <i class="fa-solid fa-tags"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Barang Terklasifikasi</div>
                    <div class="stat-value mt-1 text-info">{{ number_format($stats['total_barang'] ?? 0) }}</div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;"><i class="fa-solid fa-boxes-stacked text-info me-1"></i>Aset Terhubung</div>
                </div>
                <div class="stat-icon info">
                    <i class="fa-solid fa-box-open"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Kategori Terpopuler</div>
                    <div class="stat-value mt-1 text-truncate" style="font-size: 1.25rem; max-width: 180px;" title="{{ $stats['kategori_terbanyak']->nama_kategori ?? '-' }}">
                        {{ $stats['kategori_terbanyak']->nama_kategori ?? '-' }}
                    </div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-fire text-danger me-1"></i>{{ $stats['kategori_terbanyak']->barangs_count ?? 0 }} Barang Terdaftar
                    </div>
                </div>
                <div class="stat-icon danger">
                    <i class="fa-solid fa-crown"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Form Tambah Kategori -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header-modern">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-plus text-primary"></i>
                    <span>Tambah Kategori Baru</span>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('kategori.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Kategori <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-tag"></i></span>
                            <input type="text" name="nama_kategori" id="inputNamaKategoriTambah" class="form-control" placeholder="Misal: Elektronik, Mebel, Alat Medis" required oninput="autoGenerateKodeKategori(this.value)">
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold mb-0">Kode Kategori (Singkatan)</label>
                            <button type="button" class="btn btn-link btn-sm text-decoration-none p-0 fw-semibold text-primary" style="font-size: 0.78rem;" onclick="triggerAutoKodeKategori()">
                                <i class="fa-solid fa-wand-magic-sparkles me-1"></i>Generate Ulang
                            </button>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-barcode"></i></span>
                            <input type="text" name="kode_kategori" id="inputKodeKategoriTambah" class="form-control font-monospace text-uppercase" placeholder="Otomatis (atau ketik manual)" maxlength="10">
                            <button class="btn btn-outline-secondary" type="button" onclick="triggerAutoKodeKategori()" title="Otomatis Buat Kode">
                                <i class="fa-solid fa-bolt text-warning"></i> Auto
                            </button>
                        </div>
                        <small class="text-muted d-block mt-1" style="font-size: 0.73rem;">
                            <i class="fa-solid fa-circle-check text-success me-1"></i>Kode otomatis terisi mengikuti nama kategori (contoh: <code>ELK</code>, <code>MBL</code>, <code>MED</code>, <code>ATK</code>).
                        </small>
                    </div>
                    <button type="submit" class="btn btn-modern-primary w-100 justify-content-center">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Kategori</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Kategori -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header-modern">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-tags text-primary"></i>
                    <span>Daftar Kategori Terdaftar</span>
                    <span class="badge bg-primary-subtle text-primary rounded-pill ms-2">{{ $kategoris->count() }} Kategori</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4" style="width: 60px;">No</th>
                                <th>Nama Kategori</th>
                                <th class="text-center" style="width: 140px;">Kode Singkatan</th>
                                <th class="text-center" style="width: 160px;">Jumlah Aset</th>
                                <th class="text-center pe-4" style="width: 110px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategoris as $index => $k)
                            <tr>
                                <td class="ps-4 text-muted fw-semibold">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="p-1.5 rounded-2 bg-light border text-primary">
                                            <i class="fa-solid fa-tag" style="font-size: 0.85rem;"></i>
                                        </div>
                                        <span class="fw-bold text-dark">{{ $k->nama_kategori }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary-subtle text-primary border border-primary px-2.5 py-1 font-monospace fw-bold rounded-pill">
                                        {{ $k->kode_kategori ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info-subtle text-info-emphasis border px-3 py-1.5 fw-bold rounded-pill" style="font-size: 0.8rem;">
                                        <i class="fa-solid fa-box me-1"></i>{{ $k->barangs_count }} Item
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-inline-flex gap-1">
                                        <!-- Tombol Edit -->
                                        <button type="button" class="btn-icon btn-icon-primary" data-bs-toggle="modal" data-bs-target="#modalEditKategori{{ $k->id }}" title="Edit Kategori">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('kategori.destroy', $k->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event, this, 'kategori {{ $k->nama_kategori }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon btn-icon-danger" title="Hapus Kategori">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- MODAL EDIT KATEGORI -->
                                    <div class="modal fade text-start" id="modalEditKategori{{ $k->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title fw-bold">
                                                        <i class="fa-solid fa-pen-to-square me-2"></i>Edit Kategori
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('kategori.update', $k->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Nama Kategori <span class="text-danger">*</span></label>
                                                            <input type="text" id="inputNamaKategoriEdit{{ $k->id }}" name="nama_kategori" class="form-control" value="{{ $k->nama_kategori }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                                <label class="form-label fw-bold mb-0">Kode Kategori (Singkatan)</label>
                                                                <button type="button" class="btn btn-link btn-sm text-decoration-none p-0 fw-semibold text-primary" style="font-size: 0.78rem;" onclick="triggerAutoKodeKategoriEdit({{ $k->id }})">
                                                                    <i class="fa-solid fa-wand-magic-sparkles me-1"></i>Generate Ulang
                                                                </button>
                                                            </div>
                                                            <div class="input-group">
                                                                <span class="input-group-text bg-light"><i class="fa-solid fa-barcode"></i></span>
                                                                <input type="text" id="inputKodeKategoriEdit{{ $k->id }}" name="kode_kategori" class="form-control font-monospace text-uppercase" value="{{ $k->kode_kategori }}" placeholder="Misal: ELK, MBL, MED" maxlength="10">
                                                                <button class="btn btn-outline-secondary" type="button" onclick="triggerAutoKodeKategoriEdit({{ $k->id }})" title="Otomatis Buat Kode">
                                                                    <i class="fa-solid fa-bolt text-warning"></i> Auto
                                                                </button>
                                                            </div>
                                                            <small class="text-muted">Kode singkatan yang dipakai pada barcode & format kode aset.</small>
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
                                        <i class="fa-solid fa-tags fa-3x opacity-50"></i>
                                    </div>
                                    <h6 class="fw-bold text-secondary mb-1">Belum Ada Kategori</h6>
                                    <p class="text-muted small mb-0">Tambahkan kategori pertama Anda melalui form di samping.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let manualKodeKategoriEdited = false;

    document.getElementById('inputKodeKategoriTambah')?.addEventListener('input', function() {
        manualKodeKategoriEdited = this.value.trim().length > 0;
    });

    function generateKategoriCode(nama) {
        if (!nama || nama.trim() === '') return '';
        let str = nama.trim().toUpperCase();

        if (str.includes('ELEKTRONIK') || str.includes('KOMPUTER') || str.includes('IT') || str.includes('MULTIMEDIA') || str.includes('AUDIO') || str.includes('PROYEKTOR')) {
            return 'ELK';
        } else if (str.includes('MEBEL') || str.includes('FURNITUR') || str.includes('FURNITURE') || str.includes('MEJA') || str.includes('KURSI') || str.includes('LEMARI')) {
            return 'MBL';
        } else if (str.includes('MEDIS') || str.includes('KESEHATAN') || str.includes('ALKES') || str.includes('KEPERAWATAN') || str.includes('KEBIDANAN') || str.includes('FARMASI')) {
            return 'MED';
        } else if (str.includes('KANTOR') || str.includes('ATK') || str.includes('TULIS') || str.includes('KERTAS')) {
            return 'ATK';
        } else if (str.includes('LAB') || str.includes('PRAKTIKUM') || str.includes('ANATOMI')) {
            return 'LAB';
        } else if (str.includes('BUKU') || str.includes('PUSTAKA') || str.includes('LITERATUR')) {
            return 'BUK';
        } else if (str.includes('KENDARAAN') || str.includes('TRANSPORTASI') || str.includes('MOBIL') || str.includes('MOTOR')) {
            return 'KND';
        } else if (str.includes('BERSIH') || str.includes('SANITASI') || str.includes('JANITOR')) {
            return 'SBS';
        } else {
            // Algoritma singkatan kata: pisahkan kata atau ambil 3 huruf konsonan / huruf awal
            let words = str.split(/[\s\-_/&,.]+/).filter(w => w.length > 0);
            if (words.length >= 2) {
                let acronym = words.map(w => w[0]).join('').substring(0, 4);
                return acronym;
            } else {
                let word = words[0] || str;
                // Ambil huruf konsonan dulu atau 3 karakter awal
                let consonants = word.replace(/[AIUEO]/g, '');
                if (consonants.length >= 3) {
                    return consonants.substring(0, 3);
                }
                return word.substring(0, 3);
            }
        }
    }

    function autoGenerateKodeKategori(nama) {
        if (manualKodeKategoriEdited) return;
        let code = generateKategoriCode(nama);
        let inputKode = document.getElementById('inputKodeKategoriTambah');
        if (inputKode) {
            inputKode.value = code;
        }
    }

    function triggerAutoKodeKategori() {
        manualKodeKategoriEdited = false;
        let nama = document.getElementById('inputNamaKategoriTambah')?.value || '';
        let code = generateKategoriCode(nama);
        let inputKode = document.getElementById('inputKodeKategoriTambah');
        if (inputKode) {
            inputKode.value = code;
        }
    }

    function triggerAutoKodeKategoriEdit(id) {
        let nama = document.getElementById('inputNamaKategoriEdit' + id)?.value || '';
        let code = generateKategoriCode(nama);
        let inputKode = document.getElementById('inputKodeKategoriEdit' + id);
        if (inputKode) {
            inputKode.value = code;
        }
    }
</script>
@endpush