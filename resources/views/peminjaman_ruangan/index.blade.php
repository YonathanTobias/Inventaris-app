@extends('layouts.app')

@section('title', 'Manajemen Booking Ruangan')

@section('content')
<div class="row g-3 mb-4 align-items-center">
    <div class="col-md-7">
        <h3 class="fw-bold text-dark mb-1" style="letter-spacing: -0.02em;">
            <i class="fa-solid fa-door-open text-primary me-2"></i>Manajemen Peminjaman & Booking Ruangan
        </h3>
        <p class="text-muted small mb-0">Kelola persetujuan (approval) Kepala Sarpras, serah terima kunci ruangan, dan pemantauan jadwal pemakaian.</p>
    </div>
    <div class="col-md-5 text-md-end d-flex gap-2 justify-content-md-end">
        <a href="{{ url('/#tab-ruangan') }}" target="_blank" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka Form Publik
        </a>
        <button class="btn btn-modern-primary" data-bs-toggle="modal" data-bs-target="#modalTambahBookingInternal">
            <i class="fa-solid fa-calendar-plus me-1"></i>
            <span>Catat Booking Internal</span>
        </button>
    </div>
</div>

<!-- KARTU STATISTIK TAHAPAN BOOKING RUANGAN -->
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-md-4 col-sm-6">
        <a href="{{ route('peminjaman-ruangan.index') }}" class="text-decoration-none">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body p-3 text-center">
                    <div class="text-muted small fw-semibold mb-1">Semua Permohonan</div>
                    <div class="fs-4 fw-bold text-dark">{{ number_format($stats['total']) }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <a href="{{ route('peminjaman-ruangan.index', ['status' => 'Menunggu']) }}" class="text-decoration-none">
            <div class="card stat-card border-0 shadow-sm h-100 {{ request('status') === 'Menunggu' ? 'border-warning border-2' : '' }}">
                <div class="card-body p-3 text-center">
                    <div class="text-warning small fw-bold mb-1"><i class="fa-solid fa-clock me-1"></i>Menunggu Approval</div>
                    <div class="fs-4 fw-bold text-warning">{{ number_format($stats['menunggu']) }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <a href="{{ route('peminjaman-ruangan.index', ['status' => 'Disetujui']) }}" class="text-decoration-none">
            <div class="card stat-card border-0 shadow-sm h-100 {{ request('status') === 'Disetujui' ? 'border-primary border-2' : '' }}">
                <div class="card-body p-3 text-center">
                    <div class="text-primary small fw-bold mb-1"><i class="fa-solid fa-circle-check me-1"></i>Terkunci (Siap Pakai)</div>
                    <div class="fs-4 fw-bold text-primary">{{ number_format($stats['disetujui']) }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <a href="{{ route('peminjaman-ruangan.index', ['status' => 'Digunakan']) }}" class="text-decoration-none">
            <div class="card stat-card border-0 shadow-sm h-100 {{ request('status') === 'Digunakan' ? 'border-info border-2' : '' }}">
                <div class="card-body p-3 text-center">
                    <div class="text-info small fw-bold mb-1"><i class="fa-solid fa-key me-1"></i>Sedang Digunakan</div>
                    <div class="fs-4 fw-bold text-info">{{ number_format($stats['digunakan']) }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <a href="{{ route('peminjaman-ruangan.index', ['status' => 'Selesai']) }}" class="text-decoration-none">
            <div class="card stat-card border-0 shadow-sm h-100 {{ request('status') === 'Selesai' ? 'border-success border-2' : '' }}">
                <div class="card-body p-3 text-center">
                    <div class="text-success small fw-bold mb-1"><i class="fa-solid fa-check-double me-1"></i>Selesai</div>
                    <div class="fs-4 fw-bold text-success">{{ number_format($stats['selesai']) }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <a href="{{ route('peminjaman-ruangan.index', ['status' => 'Ditolak']) }}" class="text-decoration-none">
            <div class="card stat-card border-0 shadow-sm h-100 {{ request('status') === 'Ditolak' ? 'border-danger border-2' : '' }}">
                <div class="card-body p-3 text-center">
                    <div class="text-danger small fw-bold mb-1"><i class="fa-solid fa-ban me-1"></i>Ditolak</div>
                    <div class="fs-4 fw-bold text-danger">{{ number_format($stats['ditolak']) }}</div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- FILTER STATUS & PENCARIAN -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('peminjaman-ruangan.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama pemohon, NIM/NIP, prodi, kode booking, atau nama ruangan..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="Menunggu" {{ request('status') === 'Menunggu' ? 'selected' : '' }}>1. Menunggu Approval Kepala Sarpras</option>
                    <option value="Disetujui" {{ request('status') === 'Disetujui' ? 'selected' : '' }}>2. Disetujui (Jadwal Terkunci)</option>
                    <option value="Digunakan" {{ request('status') === 'Digunakan' ? 'selected' : '' }}>3. Sedang Digunakan</option>
                    <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>4. Selesai Pemakaian</option>
                    <option value="Ditolak" {{ request('status') === 'Ditolak' ? 'selected' : '' }}>Ditolak oleh Kepala Sarpras</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}" onchange="this.form.submit()" title="Filter Berdasarkan Tanggal Pemakaian">
            </div>
            <div class="col-md-2 text-md-end d-flex gap-1 justify-content-md-end">
                <button type="submit" class="btn btn-sm btn-primary px-3">
                    <i class="fa-solid fa-filter me-1"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'tanggal']))
                    <a href="{{ route('peminjaman-ruangan.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TABEL DAFTAR TRANSAKSI BOOKING RUANGAN -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0 text-dark">
            <i class="fa-solid fa-list-check text-primary me-2"></i>Daftar Pengajuan & Booking Ruangan
        </h6>
        <span class="badge bg-light text-secondary border font-monospace">{{ $peminjamans->count() }} Data</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-muted small text-uppercase">
                <tr>
                    <th class="ps-4" style="width: 40px;">No</th>
                    <th>Pemohon & Identitas</th>
                    <th>Ruangan</th>
                    <th>Jadwal & Waktu Pemakaian</th>
                    <th>Status Booking</th>
                    <th class="text-center pe-4" style="width: 220px;">Tindakan & Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamans as $index => $p)
                <tr>
                    <td class="ps-4 fw-semibold text-muted">{{ $index + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-1.5">
                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-0.5" style="font-size: 0.7rem;">{{ $p->kategori_peminjam ?? 'Mahasiswa / Ormawa' }}</span>
                            <span class="fw-bold text-dark">{{ $p->nama_peminjam }}</span>
                        </div>
                        <div class="text-muted small font-monospace mt-0.5">
                            {{ $p->nomor_identitas }}
                            @if($p->prodi_unit)
                                &bull; <span class="text-secondary">{{ $p->prodi_unit }}</span>
                            @endif
                        </div>
                        @if($p->kontak_peminjam)
                            <div class="text-muted small" style="font-size: 0.75rem;">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $p->kontak_peminjam) }}" target="_blank" class="text-success text-decoration-none">
                                    {{ $p->kontak_peminjam }}
                                </a>
                            </div>
                        @endif
                        <div class="mt-1">
                            <span class="badge bg-light text-dark border font-monospace" style="font-size: 0.68rem;">{{ $p->kode_booking }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark fs-6">{{ $p->ruangan->nama_ruangan ?? 'Ruangan Telah Dihapus' }}</div>
                        <div class="mt-0.5">
                            <span class="badge bg-light text-dark border font-monospace" style="font-size: 0.75rem;">{{ $p->ruangan->kode_ruangan ?? '-' }}</span>
                        </div>
                        @if($p->keperluan)
                            <div class="text-muted small text-truncate mt-1" style="max-width: 250px; font-size: 0.72rem;" title="{{ $p->keperluan }}">
                                {{ $p->keperluan }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold text-dark small">
                            {{ \Carbon\Carbon::parse($p->tanggal_pemakaian)->isoFormat('dddd, D MMM Y') }}
                        </div>
                        <div class="small text-primary font-monospace mt-0.5">
                            {{ date('H:i', strtotime($p->jam_mulai)) }} - {{ date('H:i', strtotime($p->jam_selesai)) }} WIB
                        </div>
                        @if($p->waktu_masuk)
                            <div class="small text-info mt-0.5" style="font-size: 0.72rem;">
                                Dibuka: {{ $p->waktu_masuk->isoFormat('D MMM, HH:mm') }}
                            </div>
                        @endif
                        @if($p->waktu_selesai)
                            <div class="small text-success mt-0.5" style="font-size: 0.72rem;">
                                Selesai: {{ $p->waktu_selesai->isoFormat('D MMM, HH:mm') }}
                            </div>
                        @endif
                    </td>
                    <td>
                        @if($p->status === 'Menunggu')
                            <span class="badge bg-warning text-dark border border-warning px-2.5 py-1 rounded-pill">
                                <i class="fa-solid fa-clock me-1"></i>Menunggu Persetujuan
                            </span>
                        @elseif($p->status === 'Disetujui')
                            <span class="badge bg-primary text-white border border-primary px-2.5 py-1 rounded-pill">
                                <i class="fa-solid fa-circle-check me-1"></i>Disetujui (Terkunci)
                            </span>
                        @elseif($p->status === 'Digunakan')
                            <span class="badge bg-info text-dark border border-info px-2.5 py-1 rounded-pill">
                                <i class="fa-solid fa-door-open me-1"></i>Sedang Digunakan
                            </span>
                        @elseif($p->status === 'Selesai')
                            <span class="badge bg-success-subtle text-success border border-success px-2.5 py-1 rounded-pill">
                                <i class="fa-solid fa-check-double me-1"></i>Selesai Pemakaian
                            </span>
                        @elseif($p->status === 'Ditolak')
                            <span class="badge bg-danger text-white border border-danger px-2.5 py-1 rounded-pill">
                                <i class="fa-solid fa-ban me-1"></i>Ditolak
                            </span>
                            @if($p->alasan_penolakan)
                                <div class="small text-danger mt-0.5" style="font-size: 0.72rem;">Alasan: {{ $p->alasan_penolakan }}</div>
                            @endif
                        @endif
                    </td>
                    <td class="text-center pe-4">
                        <div class="d-flex justify-content-center align-items-center gap-1.5 flex-wrap">
                            <!-- AKSI TAHAP 1: APPROVAL KEPALA SARPRAS -->
                            @if($p->status === 'Menunggu')
                                <form action="{{ route('peminjaman-ruangan.setujui', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Setujui permohonan booking ruangan ini? Jadwal akan dikunci untuk pemohon.')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary fw-bold shadow-sm" title="Setujui Booking">
                                        <i class="fa-solid fa-check me-1"></i>Setujui
                                    </button>
                                </form>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalTolakRuangan{{ $p->id }}" title="Tolak Booking">
                                    <i class="fa-solid fa-xmark"></i> Tolak
                                </button>

                            <!-- AKSI TAHAP 2: PETUGAS MEMBUKA RUANGAN (RUANGAN MULAI DIGUNAKAN) -->
                            @elseif($p->status === 'Disetujui')
                                <form action="{{ route('peminjaman-ruangan.serahkan', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Konfirmasi ruangan telah dibuka untuk kegiatan pemohon? Status akan berubah menjadi [Sedang Digunakan].')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-info text-white fw-bold shadow-sm" title="Buka Ruangan untuk Kegiatan">
                                        <i class="fa-solid fa-door-open me-1"></i>Buka Ruangan
                                    </button>
                                </form>

                            <!-- AKSI TAHAP 3: SELESAI & RUANGAN DIKUNCI KEMBALI OLEH PETUGAS -->
                            @elseif($p->status === 'Digunakan')
                                <button type="button" class="btn btn-sm btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#modalSelesaiRuangan{{ $p->id }}" title="Selesai & Kunci Ruangan">
                                    <i class="fa-solid fa-check-circle me-1"></i>Selesai & Kunci
                                </button>
                            @endif

                            <!-- Tombol Tiket Bukti Publik -->
                            <a href="{{ route('publik.ruangan.sukses', $p->kode_booking) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Lihat E-Ticket Digital">
                                <i class="fa-solid fa-receipt"></i>
                            </a>

                            <!-- Tombol Hapus Transaksi -->
                            <form action="{{ route('peminjaman-ruangan.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event, this, 'data booking ruangan {{ $p->kode_booking }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Data">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>

                        <!-- MODAL TOLAK BOOKING -->
                        @if($p->status === 'Menunggu')
                        <div class="modal fade text-start" id="modalTolakRuangan{{ $p->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title fw-bold">
                                            <i class="fa-solid fa-ban me-2"></i>Tolak Permohonan Booking Ruangan
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('peminjaman-ruangan.reject', $p->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body p-4">
                                            <p class="small text-muted mb-3">
                                                Anda akan menolak pengajuan booking <strong>{{ $p->kode_booking }}</strong> atas nama <strong>{{ $p->nama_peminjam }}</strong> untuk ruangan <strong>{{ $p->ruangan->nama_ruangan ?? '-' }}</strong>.
                                            </p>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                                                <textarea name="alasan_penolakan" class="form-control" rows="3" placeholder="Misal: Ruangan sedang dalam pemeliharaan/perbaikan AC / Dipakai untuk acara resmi rektorat..." required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger fw-bold">
                                                <i class="fa-solid fa-ban me-1"></i> Konfirmasi Tolak
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- MODAL SELESAI PEMAKAIAN -->
                        @if($p->status === 'Digunakan')
                        <div class="modal fade text-start" id="modalSelesaiRuangan{{ $p->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title fw-bold">
                                            <i class="fa-solid fa-check-circle me-2"></i>Konfirmasi Selesai Pemakaian Ruangan
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('peminjaman-ruangan.selesai', $p->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body p-4">
                                            <div class="p-3 bg-light rounded-3 border mb-3">
                                                <div class="fw-bold text-dark fs-6">{{ $p->ruangan->nama_ruangan ?? '-' }}</div>
                                                <div class="text-muted small mt-1">
                                                    Pemohon: <strong>{{ $p->nama_peminjam }}</strong> ({{ $p->nomor_identitas }})
                                                </div>
                                                <div class="text-muted small">
                                                    Waktu: {{ \Carbon\Carbon::parse($p->tanggal_pemakaian)->isoFormat('D MMM Y') }} ({{ date('H:i', strtotime($p->jam_mulai)) }} - {{ date('H:i', strtotime($p->jam_selesai)) }} WIB)
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Catatan Pemeriksaan Ruangan (Opsional)</label>
                                                <textarea name="catatan_kondisi" class="form-control" rows="3" placeholder="Catatan kebersihan, kondisi AC/lampu/proyektor setelah selesai dipakai..."></textarea>
                                            </div>

                                            <div class="alert alert-info py-2 px-3 small mb-0 rounded-3">
                                                <i class="fa-solid fa-circle-info me-1"></i>
                                                Setelah dikonfirmasi, status booking akan selesai dan ruangan tercatat telah dikunci kembali oleh petugas Sarpras.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-success fw-bold px-4">
                                                <i class="fa-solid fa-check-circle me-1"></i> Selesai & Kunci Ruangan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="text-muted mb-2">
                            <i class="fa-solid fa-door-open fa-3x opacity-50"></i>
                        </div>
                        <h6 class="fw-bold text-secondary mb-1">Belum Ada Data Booking Ruangan</h6>
                        <p class="text-muted small mb-3">Pengajuan dari dosen atau mahasiswa di portal publik akan masuk ke sini.</p>
                        <a href="{{ url('/#tab-ruangan') }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka Portal Booking Ruangan
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL CATAT BOOKING INTERNAL OLEH PETUGAS -->
<div class="modal fade" id="modalTambahBookingInternal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-door-open text-primary me-2"></i>Catat Booking Ruangan Internal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('publik.ruangan.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Kategori Pemohon <span class="text-danger">*</span></label>
                            <select name="kategori_peminjam" class="form-select" required>
                                <option value="Dosen">Dosen</option>
                                <option value="Mahasiswa / Ormawa" selected>Mahasiswa / Ormawa</option>
                                <option value="Staf / Tendik">Staf / Tendik</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Nama Pemohon <span class="text-danger">*</span></label>
                            <input type="text" name="nama_peminjam" class="form-control" placeholder="Nama lengkap" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">NIM / NIP <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_identitas" class="form-control font-monospace" placeholder="Nomor identitas" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Prodi / Unit Kerja <span class="text-danger">*</span></label>
                            <input type="text" name="prodi_unit" class="form-control" placeholder="Misal: S1 Keperawatan / BEM" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">No. WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" name="kontak_peminjam" class="form-control" placeholder="081234567890" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Ruangan yang Dipinjam <span class="text-danger">*</span></label>
                            <select name="ruangan_id" class="form-select" required>
                                <option value="">-- Pilih Ruangan (Hanya yang Bisa Dipinjam) --</option>
                                @foreach($ruanganBisaDipinjam as $r)
                                    <option value="{{ $r->id }}">
                                        {{ $r->nama_ruangan }} [{{ $r->kode_ruangan }}]
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Tanggal Pemakaian <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pemakaian" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" name="jam_mulai" class="form-control" value="08:00" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" name="jam_selesai" class="form-control" value="12:00" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Keperluan / Acara <span class="text-danger">*</span></label>
                            <textarea name="keperluan" class="form-control" rows="2" placeholder="Keperluan pemakaian ruangan..." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="fa-solid fa-paper-plane me-1"></i> Simpan Booking Ruangan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
