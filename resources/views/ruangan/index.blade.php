@extends('layouts.app')

@section('content')
@php
    $masterGedungLantai = $masterGedungLantai ?? [
        'Gedung A' => ['Lantai Dasar', 'Lantai 1', 'Lantai 2', 'Lantai 3', 'Lantai 4', 'Lantai 5'],
        'Gedung B' => [],
    ];
@endphp
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.02em;">Master Data Ruangan & Lokasi</h4>
        <p class="text-muted small mb-0">Kelola data gedung, lantai, penempatan fisik inventaris, dan penanggung jawab ruangan.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <button type="button" class="btn btn-outline-primary d-inline-flex align-items-center gap-2 px-3 py-2" data-bs-toggle="modal" data-bs-target="#modalKelolaGedungLantai">
            <i class="fa-solid fa-building"></i>
            <span>Kelola Gedung & Lantai</span>
        </button>
        <a href="{{ route('ruangan.export') }}" class="btn btn-modern-primary d-inline-flex align-items-center gap-2 px-3 py-2">
            <i class="fa-solid fa-file-excel"></i>
            <span>Export Excel (.xlsx)</span>
        </a>
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
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;">Lokasi Terdaftar</div>
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
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;">Ada Aset Fisik</div>
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
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;">Unit Barang</div>
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
                        {{ $stats['ruangan_terbanyak']->barangs_count ?? 0 }} Jenis Aset
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
                <span class="fw-bold">Tambah Ruangan Baru</span>
                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2.5" style="font-size: 0.74rem;" data-bs-toggle="modal" data-bs-target="#modalKelolaGedungLantai">
                    + Gedung / Lantai
                </button>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('ruangan.store') }}" method="POST">
                    @csrf
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Gedung <span class="text-danger">*</span></label>
                            <select id="selectGedungTambah" name="gedung" class="form-select" required onchange="onChangeGedungTambah()">
                                @foreach($masterGedungLantai as $namaGedung => $daftarLantai)
                                    <option value="{{ $namaGedung }}">{{ $namaGedung }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Lantai</label>
                            <select id="selectLantaiTambah" name="lantai" class="form-select" onchange="triggerAutoKodeRuangan()">
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Ruangan <span class="text-danger">*</span></label>
                        <input type="text" id="inputNamaRuangan" name="nama_ruangan" class="form-control" placeholder="Misal: Lab Kebidanan" required oninput="autoGenerateKodeRuangan()">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kode Ruangan</label>
                        <div class="input-group">
                            <input type="text" id="inputKodeRuangan" name="kode_ruangan" class="form-control font-monospace" placeholder="Otomatis (misal: A1-LAB-01)">
                            <button class="btn btn-outline-secondary px-3" type="button" onclick="triggerAutoKodeRuangan()" title="Buat Ulang Kode Otomatis">
                                Auto
                            </button>
                        </div>
                        <small class="text-muted d-block mt-1" style="font-size: 0.73rem;">
                            Otomatis terisi sesuai Gedung, Lantai, dan Nama Ruangan.
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Penanggung Jawab Ruangan (PJ)</label>
                        <input type="text" name="penanggung_jawab" class="form-control" placeholder="Nama Lengkap & Gelar PJ">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NIP / NIDN Penanggung Jawab</label>
                        <input type="text" name="nip_penanggung_jawab" class="form-control font-monospace" placeholder="Contoh: 198501012010011001">
                        <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">
                            Tercantum otomatis pada cetak Kartu Inventaris Ruangan (KIR).
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
                    <span class="fw-bold">Daftar Ruangan Terdaftar</span>
                    <span class="badge bg-primary-subtle text-primary rounded-pill ms-1">{{ $ruangans->count() }} Ruangan</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern align-middle">
                        <thead>
                            <tr>
                                <th class="ps-4" style="width: 145px;">Kode Ruangan</th>
                                <th>Nama Ruangan & Lokasi</th>
                                <th>Penanggung Jawab (PJ)</th>
                                <th class="text-center" style="width: 125px;">Koleksi Aset</th>
                                <th class="text-center pe-4" style="width: 110px;">Aksi</th>
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
                                    <div class="d-flex flex-wrap align-items-center gap-1 mt-1">
                                        @if($r->gedung)
                                            <span class="badge bg-primary-subtle" style="font-size: 0.7rem;">
                                                {{ $r->gedung }}{{ $r->lantai && $r->lantai !== '-' ? ' • ' . $r->lantai : '' }}
                                            </span>
                                        @endif
                                        @if($r->bisa_dipinjam)
                                            <span class="badge bg-success-subtle" style="font-size: 0.7rem;" title="Ruangan ini diizinkan untuk dipinjam publik">
                                                Bisa Dipinjam
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle" style="font-size: 0.7rem;" title="Ruangan khusus internal">
                                                Khusus Internal
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($r->penanggung_jawab)
                                        <div class="fw-semibold text-dark small">{{ $r->penanggung_jawab }}</div>
                                        @if($r->nip_penanggung_jawab)
                                            <div class="text-muted font-monospace" style="font-size: 0.73rem;">{{ $r->nip_penanggung_jawab }}</div>
                                        @endif
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info-subtle" style="font-size: 0.76rem;">
                                        {{ $r->barangs_count }} Jenis
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-inline-flex gap-1">
                                        <!-- Tombol Edit -->
                                        <button type="button" class="btn-icon btn-icon-primary" data-bs-toggle="modal" data-bs-target="#modalEditRuangan{{ $r->id }}" title="Edit Ruangan">
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
                                                    <h5 class="modal-title fw-bold">Edit Data Ruangan</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('ruangan.update', $r->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="row g-2 mb-3">
                                                            <div class="col-6">
                                                                <label class="form-label">Gedung</label>
                                                                <select name="gedung" id="selectGedungEdit{{ $r->id }}" class="form-select" onchange="onChangeGedungEdit({{ $r->id }})">
                                                                    <option value="">-- Pilih Gedung --</option>
                                                                    @foreach($masterGedungLantai as $namaGedung => $daftarLantai)
                                                                        <option value="{{ $namaGedung }}" {{ $r->gedung === $namaGedung ? 'selected' : '' }}>{{ $namaGedung }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="form-label">Lantai</label>
                                                                <select name="lantai" id="selectLantaiEdit{{ $r->id }}" class="form-select" data-current-lantai="{{ $r->lantai }}" onchange="updateKodeEditRuangan({{ $r->id }})">
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Ruangan <span class="text-danger">*</span></label>
                                                            <input type="text" id="inputNamaEdit{{ $r->id }}" name="nama_ruangan" class="form-control" value="{{ $r->nama_ruangan }}" required oninput="updateKodeEditRuangan({{ $r->id }})">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Kode Ruangan <span class="text-danger">*</span></label>
                                                            <div class="input-group">
                                                                <input type="text" id="inputKodeEdit{{ $r->id }}" name="kode_ruangan" class="form-control font-monospace" value="{{ $r->kode_ruangan }}" required>
                                                                <button class="btn btn-outline-secondary px-3" type="button" onclick="updateKodeEditRuangan({{ $r->id }}, true)" title="Sesuaikan Kode dengan Gedung & Lantai">
                                                                    Auto
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Penanggung Jawab Ruangan (PJ)</label>
                                                            <input type="text" name="penanggung_jawab" class="form-control" value="{{ $r->penanggung_jawab }}" placeholder="Nama Lengkap & Gelar PJ Ruangan">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">NIP / NIDN Penanggung Jawab</label>
                                                            <input type="text" name="nip_penanggung_jawab" class="form-control font-monospace" value="{{ $r->nip_penanggung_jawab }}" placeholder="Contoh: NIP. 19850101... / NIDN. 07...">
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
                                                            Simpan Perubahan
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
</div>

<!-- MODAL KELOLA GEDUNG & LANTAI -->
<div class="modal fade" id="modalKelolaGedungLantai" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Kelola Master Gedung & Lantai</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-3">
                    Atur daftar Gedung dan Lantai untuk pilihan penempatan ruangan. Jika ke depannya ada pembangunan lantai baru atau gedung baru, Anda dapat menambahkannya di sini.
                </p>

                <!-- Daftar Gedung & Lantai Saat Ini -->
                <div class="mb-4">
                    <h6 class="fw-bold small text-uppercase text-secondary mb-2">Daftar Gedung & Lantai Saat Ini</h6>
                    <div class="vstack gap-2">
                        @foreach($masterGedungLantai as $namaGedung => $daftarLantai)
                            <div class="p-3 rounded-3 border bg-light d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                                <div>
                                    <div class="fw-bold text-dark mb-1">{{ $namaGedung }}</div>
                                    <div class="d-flex flex-wrap gap-1">
                                        @forelse($daftarLantai as $lt)
                                            <span class="badge bg-white text-dark border d-inline-flex align-items-center gap-1 px-2 py-1">
                                                <span>{{ $lt }}</span>
                                                <form action="{{ route('ruangan.gedung-lantai.store') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="action" value="delete_lantai">
                                                    <input type="hidden" name="gedung_target" value="{{ $namaGedung }}">
                                                    <input type="hidden" name="nama_lantai" value="{{ $lt }}">
                                                    <button type="submit" class="btn btn-link p-0 text-danger text-decoration-none ms-1" style="font-size: 0.7rem; line-height: 1;" title="Hapus {{ $lt }}">
                                                        &times;
                                                    </button>
                                                </form>
                                            </span>
                                        @empty
                                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">
                                                1 Lantai (Tanpa Nomor Lantai pada Kode Ruangan)
                                            </span>
                                        @endforelse
                                    </div>
                                </div>
                                @if(count($masterGedungLantai ?? []) > 1)
                                    <form action="{{ route('ruangan.gedung-lantai.store') }}" method="POST" onsubmit="return confirm('Hapus {{ $namaGedung }} dari daftar pilihan?')">
                                        @csrf
                                        <input type="hidden" name="action" value="delete_gedung">
                                        <input type="hidden" name="gedung_target" value="{{ $namaGedung }}">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Hapus Gedung
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="row g-3">
                    <!-- Form Tambah Lantai ke Gedung yang Ada -->
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border h-100">
                            <h6 class="fw-bold text-dark mb-2">Tambah Lantai pada Gedung</h6>
                            <form action="{{ route('ruangan.gedung-lantai.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="add_lantai">
                                <div class="mb-2">
                                    <label class="form-label">Pilih Gedung</label>
                                    <select name="gedung_target" class="form-select form-select-sm" required>
                                        @foreach($masterGedungLantai as $namaGedung => $daftarLantai)
                                            <option value="{{ $namaGedung }}">{{ $namaGedung }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama / Nomor Lantai Baru</label>
                                    <input type="text" name="nama_lantai" class="form-control form-control-sm" placeholder="Contoh: Lantai 6, Lantai 2, atau Dasar" required>
                                </div>
                                <button type="submit" class="btn btn-sm btn-modern-primary w-100 justify-content-center">
                                    + Tambah Lantai
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Form Tambah Gedung Baru -->
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border h-100">
                            <h6 class="fw-bold text-dark mb-2">Tambah Gedung Baru</h6>
                            <form action="{{ route('ruangan.gedung-lantai.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="add_gedung">
                                <div class="mb-2">
                                    <label class="form-label">Nama Gedung Baru</label>
                                    <input type="text" name="nama_gedung" class="form-control form-control-sm" placeholder="Contoh: Gedung C" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Daftar Lantai (Pisahkan dengan koma)</label>
                                    <input type="text" name="lantai_awal" class="form-control form-control-sm" placeholder="Kosongkan jika 1 lantai, atau: Dasar, 1, 2, 3">
                                </div>
                                <button type="submit" class="btn btn-sm btn-modern-primary w-100 justify-content-center">
                                    + Tambah Gedung Baru
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    const masterGedungLantai = {!! json_encode($masterGedungLantai) !!};

    let manualKodeEdited = false;

    document.getElementById('inputKodeRuangan')?.addEventListener('input', function() {
        manualKodeEdited = this.value.trim().length > 0;
    });

    function populateLantaiOptions(selectGedungEl, selectLantaiEl, selectedLantai = '') {
        if (!selectGedungEl || !selectLantaiEl) return;
        const gedung = selectGedungEl.value;
        const lantaiList = masterGedungLantai[gedung] || [];

        selectLantaiEl.innerHTML = '';

        if (lantaiList.length === 0) {
            const opt = document.createElement('option');
            opt.value = '';
            opt.textContent = '1 Lantai (Tanpa Lantai)';
            selectLantaiEl.appendChild(opt);
            selectLantaiEl.disabled = true;
        } else {
            selectLantaiEl.disabled = false;
            lantaiList.forEach((lt) => {
                const opt = document.createElement('option');
                opt.value = lt;
                opt.textContent = lt;
                if (selectedLantai && selectedLantai === lt) {
                    opt.selected = true;
                }
                selectLantaiEl.appendChild(opt);
            });
        }
    }

    function extractGedungLantaiCode(gedung, lantai) {
        let gedungCode = '';
        if (gedung) {
            let gClean = gedung.toUpperCase().replace(/\bGEDUNG\b/gi, '').trim().replace(/[^A-Z0-9]/g, '');
            gedungCode = gClean || gedung.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 1);
        }

        const lantaiList = masterGedungLantai[gedung] || [];
        if (lantaiList.length === 0 || !lantai || lantai.trim() === '' || lantai.trim() === '-') {
            return gedungCode;
        }

        let lUpper = lantai.toUpperCase().trim();
        let lantaiCode = '';
        if (lUpper.includes('DASAR') || lUpper.includes('GROUND') || lUpper === '0' || lUpper === 'LANTAI 0') {
            lantaiCode = '0';
        } else {
            let match = lUpper.match(/(\d+)/);
            if (match) {
                lantaiCode = match[1];
            } else {
                lantaiCode = lUpper.replace(/\bLANTAI\b/gi, '').trim().replace(/[^A-Z0-9]/g, '').substring(0, 2);
            }
        }

        return gedungCode + lantaiCode;
    }

    function buildRoomTypeCode(nama) {
        let clean = (nama || '').trim().toUpperCase();
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

        return sub ? `${prefix}-${sub}` : prefix;
    }

    function onChangeGedungTambah() {
        const selectGedung = document.getElementById('selectGedungTambah');
        const selectLantai = document.getElementById('selectLantaiTambah');
        populateLantaiOptions(selectGedung, selectLantai);
        triggerAutoKodeRuangan();
    }

    function autoGenerateKodeRuangan() {
        if (manualKodeEdited) return;
        const nama = document.getElementById('inputNamaRuangan')?.value || '';
        generateKodeFromText(nama);
    }

    function triggerAutoKodeRuangan() {
        manualKodeEdited = false;
        const nama = document.getElementById('inputNamaRuangan')?.value || '';
        generateKodeFromText(nama);
    }

    function generateKodeFromText(nama) {
        const kodeInput = document.getElementById('inputKodeRuangan');
        if (!kodeInput) return;

        if (!nama || nama.trim() === '') {
            kodeInput.value = '';
            return;
        }

        const gedung = document.getElementById('selectGedungTambah')?.value || '';
        const lantai = document.getElementById('selectLantaiTambah')?.value || '';
        const locPrefix = extractGedungLantaiCode(gedung, lantai);
        const typeBase = buildRoomTypeCode(nama);

        let count = {{ $ruangans->count() + 1 }};
        let seq = count < 10 ? '0' + count : count;
        let finalCode = locPrefix ? `${locPrefix}-${typeBase}-${seq}` : `${typeBase}-${seq}`;
        kodeInput.value = finalCode;
    }

    function onChangeGedungEdit(roomId) {
        const selectGedung = document.getElementById('selectGedungEdit' + roomId);
        const selectLantai = document.getElementById('selectLantaiEdit' + roomId);
        const currentLantai = selectLantai?.getAttribute('data-current-lantai') || '';
        populateLantaiOptions(selectGedung, selectLantai, currentLantai);
        updateKodeEditRuangan(roomId, true);
    }

    function updateKodeEditRuangan(roomId, forceUpdate = false) {
        const selectGedung = document.getElementById('selectGedungEdit' + roomId);
        const selectLantai = document.getElementById('selectLantaiEdit' + roomId);
        const inputNama = document.getElementById('inputNamaEdit' + roomId);
        const inputKode = document.getElementById('inputKodeEdit' + roomId);
        if (!inputKode || !inputNama) return;

        const gedung = selectGedung?.value || '';
        const lantai = selectLantai?.value || '';
        const locPrefix = extractGedungLantaiCode(gedung, lantai);
        const typeBase = buildRoomTypeCode(inputNama.value);

        // Pertahankan nomor urut di bagian akhir kode jika ada
        let existingParts = inputKode.value.trim().split('-');
        let seq = existingParts.length > 0 && /^\d+$/.test(existingParts[existingParts.length - 1])
            ? existingParts[existingParts.length - 1]
            : '01';

        inputKode.value = locPrefix ? `${locPrefix}-${typeBase}-${seq}` : `${typeBase}-${seq}`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const selectGedungTambah = document.getElementById('selectGedungTambah');
        const selectLantaiTambah = document.getElementById('selectLantaiTambah');
        if (selectGedungTambah && selectLantaiTambah) {
            populateLantaiOptions(selectGedungTambah, selectLantaiTambah);
        }

        @foreach($ruangans as $r)
            (function() {
                const gEl = document.getElementById('selectGedungEdit{{ $r->id }}');
                const lEl = document.getElementById('selectLantaiEdit{{ $r->id }}');
                if (gEl && lEl) {
                    populateLantaiOptions(gEl, lEl, {!! json_encode($r->lantai ?? '') !!});
                }
            })();
        @endforeach
    });
</script>
@endsection
