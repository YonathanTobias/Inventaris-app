@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="fa-solid fa-file-excel text-success me-2"></i> Laporan & Kartu Inventaris Ruangan (KIR)</h4>
</div>

<!-- Card Area Export Excel -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-3"><i class="fa-solid fa-download me-2 text-primary"></i> Download Laporan Excel Aset</h6>
        <form action="{{ route('laporan.export') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label fw-bold">Pilih Ruangan / Jenis Laporan</label>
                <select name="ruangan_id" class="form-select form-select-lg">
                    <option value="">-- SEMUA RUANGAN (GLOBAL) --</option>
                    @foreach($ruangans as $r)
                        <option value="{{ $r->id }}" {{ request('ruangan_id') == $r->id ? 'selected' : '' }}>
                            KIR - {{ $r->nama_ruangan }} ({{ $r->kode_ruangan }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success btn-lg w-100 fw-bold">
                    <i class="fa-solid fa-file-excel me-2"></i> Export Excel
                </button>
            </div>
            <div class="col-md-3">
                <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary btn-lg w-100">
                    <i class="fa-solid fa-rotate-left me-1"></i> Reset Filter
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Preview Data Laporan -->
<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                <span><i class="fa-solid fa-list me-2 text-primary"></i> Preview Data Aset
                @if(request('ruangan_id'))
                    <span class="badge bg-primary ms-2">{{ $ruangans->firstWhere('id', request('ruangan_id'))->nama_ruangan ?? '' }}</span>
                @else
                    <span class="badge bg-dark ms-2">Global (Semua Ruangan)</span>
                @endif
                </span>
                <span class="badge bg-secondary">{{ $barangs->count() }} Item Aset</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Kode</th>
                                <th>Nama Barang</th>
                                <th>Ruangan</th>
                                <th class="text-center">Jumlah</th>
                                <th>Kondisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($barangs as $b)
                            <tr>
                                <td class="ps-3 fw-bold">{{ $b->kode_barang }}</td>
                                <td class="fw-semibold">{{ $b->nama_barang }}</td>
                                <td>{{ $b->ruangan->nama_ruangan }}</td>
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
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Data tidak ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Log Riwayat Mutasi / Pengurangan -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fa-solid fa-clock-rotate-left me-2 text-primary"></i> Riwayat Mutasi & Kerusakan
            </div>
            <div class="card-body p-0" style="max-height: 500px; overflow-y: auto;">
                <ul class="list-group list-group-flush">
                    @forelse($mutasis as $m)
                    <list-group-item class="list-group-item p-3">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1 fw-bold text-primary">{{ $m->barang->nama_barang ?? 'Aset Dihapus' }}</h6>
                            <small class="text-muted">{{ $m->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1 small">
                            @if($m->jenis_mutasi == 'Pindah Ruangan')
                                <span class="badge bg-info text-white">Dipindah</span> {{ $m->jumlah }} unit: 
                                <strong>{{ $m->ruanganAsal->nama_ruangan ?? '-' }}</strong> ➔ <strong>{{ $m->ruanganTujuan->nama_ruangan ?? '-' }}</strong>
                            @else
                                <span class="badge bg-danger">Berkurang</span> {{ $m->jumlah }} unit
                            @endif
                        </p>
                        <small class="text-muted italic">Ket: {{ $m->keterangan }}</small>
                    </list-group-item>
                    @empty
                    <li class="list-group-item text-center text-muted py-4">Belum ada riwayat mutasi.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection