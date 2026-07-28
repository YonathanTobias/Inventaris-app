@extends('layouts.app')

@section('content')
<div class="row g-4">
    <!-- Form Tambah Kategori -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fa-solid fa-plus-circle me-1 text-primary"></i> Tambah Kategori Baru
            </div>
            <div class="card-body">
                <form action="{{ route('kategori.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Elektronik, Mebel, Buku" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Simpan Kategori</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Kategori -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fa-solid fa-tags me-1 text-primary"></i> Daftar Kategori Barang
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">No</th>
                            <th>Nama Kategori</th>
                            <th class="text-center">Jumlah Barang Terkait</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategoris as $index => $k)
                        <tr>
                            <td class="ps-3">{{ $index + 1 }}</td>
                            <td class="fw-semibold">{{ $k->nama_kategori }}</td>
                            <td class="text-center"><span class="badge bg-info text-dark">{{ $k->barangs_count }} Barang</span></td>
                            <td class="text-center">
                                <form action="{{ route('kategori.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada data kategori.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection