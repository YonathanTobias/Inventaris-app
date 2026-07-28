@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="fa-solid fa-boxes-stacked text-primary me-2"></i> Kelola Data Aset Inventaris</h4>
    <button class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambahBarang">
        <i class="fa-solid fa-plus me-1"></i> Tambah Aset Baru
    </button>
</div>

<!-- Filter Card -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('barang.index') }}" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Cari Kode atau Nama Aset..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="ruangan_id" class="form-select">
                    <option value="">-- Semua Ruangan --</option>
                    @foreach($ruangans as $r)
                        <option value="{{ $r->id }}" {{ request('ruangan_id') == $r->id ? 'selected' : '' }}>
                            {{ $r->nama_ruangan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                <a href="{{ route('barang.index') }}" class="btn btn-secondary" title="Reset"><i class="fa-solid fa-rotate-left"></i></a>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Aset -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Kode Aset</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Ruangan / Lokasi</th>
                        <th class="text-center">Jumlah</th>
                        <th>Kondisi</th>
                        <th class="text-center">Aksi / Mutasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $b)
                    <tr>
                        <td class="ps-3 fw-bold"><span class="badge bg-dark">{{ $b->kode_barang }}</span></td>
                        <td class="fw-semibold">{{ $b->nama_barang }}</td>
                        <td><span class="badge bg-secondary">{{ $b->kategori->nama_kategori }}</span></td>
                        <td><i class="fa-solid fa-door-open text-primary me-1"></i>{{ $b->ruangan->nama_ruangan }}</td>
                        <td class="text-center fw-bold">{{ $b->jumlah }}</td>
                        <td>
                            @if($b->kondisi == 'Baik')
                                <span class="badge bg-success">Baik</span>
                            @elseif($b->kondisi == 'Rusak Ringan')
                                <span class="badge bg-warning text-dark">Rusak Ringan</span>
                            @else
                                <span class="badge bg-danger">Rusak Berat</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <!-- Tombol Pindah Ruangan -->
                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalPindah{{ $b->id }}" title="Pindah Ruangan">
                                    <i class="fa-solid fa-right-left"></i>
                                </button>
                                <!-- Tombol Kurangi Stok -->
                                <button type="button" class="btn btn-sm btn-outline-warning text-dark" data-bs-toggle="modal" data-bs-target="#modalKurang{{ $b->id }}" title="Kurangi Stok / Rusak">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                                <!-- Tombol Hapus -->
                                <form action="{{ route('barang.destroy', $b->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus aset ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus Data"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>

                            <!-- MODAL PINDAH RUANGAN -->
                            <div class="modal fade text-start" id="modalPindah{{ $b->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info text-white">
                                            <h5 class="modal-title fw-bold"><i class="fa-solid fa-right-left me-2"></i>Pindah Ruangan Aset</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('barang.pindah', $b->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <p class="mb-2"><strong>Aset:</strong> {{ $b->nama_barang }} ({{ $b->kode_barang }})</p>
                                                <p class="mb-3"><strong>Lokasi Saat Ini:</strong> {{ $b->ruangan->nama_ruangan }} (Stok: {{ $b->jumlah }})</p>
                                                <hr>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Pilih Ruangan Tujuan</label>
                                                    <select name="ruangan_tujuan_id" class="form-select" required>
                                                        @foreach($ruangans->where('id', '!=', $b->ruangan_id) as $r)
                                                            <option value="{{ $r->id }}">{{ $r->nama_ruangan }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Jumlah Dipindahkan</label>
                                                    <input type="number" name="jumlah" class="form-control" max="{{ $b->jumlah }}" min="1" value="1" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Keterangan / Alasan</label>
                                                    <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Instruksi Kepala Lab">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-info text-white fw-bold">Proses Pemindahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- MODAL KURANGI STOK -->
                            <div class="modal fade text-start" id="modalKurang{{ $b->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning text-dark">
                                            <h5 class="modal-title fw-bold"><i class="fa-solid fa-minus-circle me-2"></i>Pengurangan / Kerusakan Aset</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('barang.kurangi', $b->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <p class="mb-2"><strong>Aset:</strong> {{ $b->nama_barang }} ({{ $b->kode_barang }})</p>
                                                <p class="mb-3"><strong>Stok Saat Ini:</strong> {{ $b->jumlah }}</p>
                                                <hr>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Jumlah Berkurang</label>
                                                    <input type="number" name="jumlah" class="form-control" max="{{ $b->jumlah }}" min="1" value="1" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Alasan / Keterangan</label>
                                                    <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Rusak Berat / Hilang / Afkir" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-warning fw-bold">Simpan Pengurangan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada data aset.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH BARANG BARU -->
<div class="modal fade" id="modalTambahBarang" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-plus-circle me-2"></i>Tambah Aset Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('barang.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kode Aset / Barcode</label>
                            <input type="text" name="kode_barang" class="form-control" placeholder="Contoh: AST-LAB-001" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Barang / Aset</label>
                            <input type="text" name="nama_barang" class="form-control" placeholder="Contoh: Proyektor Epson EB-E500" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kategori</label>
                            <select name="kategori_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Lokasi / Ruangan</label>
                            <select name="ruangan_id" class="form-select" required>
                                <option value="">-- Pilih Ruangan --</option>
                                @foreach($ruangans as $r)
                                    <option value="{{ $r->id }}">{{ $r->nama_ruangan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Jumlah Awal</label>
                            <input type="number" name="jumlah" class="form-control" value="1" min="1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Kondisi</label>
                            <select name="kondisi" class="form-select">
                                <option value="Baik">Baik</option>
                                <option value="Rusak Ringan">Rusak Ringan</option>
                                <option value="Rusak Berat">Rusak Berat</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Tahun Pengadaan</label>
                            <input type="text" name="tahun_pengadaan" class="form-control" placeholder="Contoh: 2024">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold"><i class="fa-solid fa-save me-1"></i> Simpan Aset</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection