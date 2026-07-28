@extends('layouts.app')

@section('content')
<div class="row g-4">
    <!-- Form Tambah Ruangan -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fa-solid fa-plus-circle me-1 text-primary"></i> Tambah Ruangan Baru
            </div>
            <div class="card-body">
                <form action="{{ route('ruangan.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Ruangan</label>
                        <input type="text" name="kode_ruangan" class="form-control" placeholder="Contoh: R-001, LAB-01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Ruangan</label>
                        <input type="text" name="nama_ruangan" class="form-control" placeholder="Contoh: Ruang Perpustakaan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Penanggung Jawab (PJ)</label>
                        <input type="text" name="penanggung_jawab" class="form-control" placeholder="Nama Kepala Ruangan / PJ">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Simpan Ruangan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Ruangan -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fa-solid fa-door-open me-1 text-primary"></i> Daftar Master Ruangan
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Kode</th>
                            <th>Nama Ruangan</th>
                            <th>Penanggung Jawab</th>
                            <th class="text-center">Total Barang</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ruangans as $r)
                        <tr>
                            <td class="ps-3 fw-bold"><span class="badge bg-secondary">{{ $r->kode_ruangan }}</span></td>
                            <td class="fw-semibold">{{ $r->nama_ruangan }}</td>
                            <td>{{ $r->penanggung_jawab ?? '-' }}</td>
                            <td class="text-center"><span class="badge bg-info text-dark">{{ $r->barangs_count }} Aset</span></td>
                            <td class="text-center">
                                <form action="{{ route('ruangan.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Hapus ruangan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada data ruangan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection