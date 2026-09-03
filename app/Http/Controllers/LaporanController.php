<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Ruangan;
use App\Models\Mutasi;
use App\Models\Peminjaman;
use App\Models\PeminjamanRuangan;
use App\Models\Pengaturan;
use App\Exports\AsetExport;
use App\Exports\PeminjamanAsetExport;
use App\Exports\PeminjamanRuanganExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $ruangans = Ruangan::all();
        
        // --- 1. DATA ASET & KIR ---
        $queryAset = Barang::with(['kategori', 'ruangan']);
        if ($request->ruangan_id) {
            $queryAset->where('ruangan_id', $request->ruangan_id);
        }
        $barangs = $queryAset->latest()->get();

        // Riwayat Mutasi / Pindah / Kerusakan
        $mutasis = Mutasi::with(['barang', 'ruanganAsal', 'ruanganTujuan'])->latest()->take(30)->get();

        // --- 2. DATA LAPORAN PEMINJAMAN ASET ---
        $queryPinjamAset = Peminjaman::with(['barang', 'details.barang', 'user']);
        if ($request->tgl_mulai_aset && $request->tgl_selesai_aset) {
            $queryPinjamAset->whereBetween('tanggal_pinjam', [$request->tgl_mulai_aset, $request->tgl_selesai_aset]);
        } elseif ($request->tgl_mulai_aset) {
            $queryPinjamAset->whereDate('tanggal_pinjam', '>=', $request->tgl_mulai_aset);
        } elseif ($request->tgl_selesai_aset) {
            $queryPinjamAset->whereDate('tanggal_pinjam', '<=', $request->tgl_selesai_aset);
        }
        if ($request->status_aset) {
            $queryPinjamAset->where('status', $request->status_aset);
        }
        $peminjamanAsets = $queryPinjamAset->latest()->get();

        // --- 3. DATA LAPORAN PEMINJAMAN RUANGAN ---
        $queryPinjamRuangan = PeminjamanRuangan::with(['ruangan', 'approver', 'petugasSerah']);
        if ($request->tgl_mulai_ruangan && $request->tgl_selesai_ruangan) {
            $queryPinjamRuangan->whereBetween('tanggal_pemakaian', [$request->tgl_mulai_ruangan, $request->tgl_selesai_ruangan]);
        } elseif ($request->tgl_mulai_ruangan) {
            $queryPinjamRuangan->whereDate('tanggal_pemakaian', '>=', $request->tgl_mulai_ruangan);
        } elseif ($request->tgl_selesai_ruangan) {
            $queryPinjamRuangan->whereDate('tanggal_pemakaian', '<=', $request->tgl_selesai_ruangan);
        }
        if ($request->ruangan_id_filter) {
            $queryPinjamRuangan->where('ruangan_id', $request->ruangan_id_filter);
        }
        if ($request->status_ruangan) {
            $queryPinjamRuangan->where('status', $request->status_ruangan);
        }
        $peminjamanRuangans = $queryPinjamRuangan->latest()->get();

        $stats = [
            'total_aset_tercatat' => $barangs->count(),
            'total_unit_tercatat' => $barangs->sum('jumlah'),
            'total_ruangan'       => $ruangans->count(),
            'total_mutasi'        => Mutasi::count(),
            'total_pinjam_aset'   => $peminjamanAsets->count(),
            'total_pinjam_ruangan'=> $peminjamanRuangans->count(),
        ];

        $pejabat = [
            'nama_ketua'         => Pengaturan::get('nama_ketua', 'apt. Wida Padminingsih, S.Farm., M.Farm.'),
            'nip_ketua'          => Pengaturan::get('nip_ketua', 'NIDN. 0725048201'),
            'nama_kabag_sarpras' => Pengaturan::get('nama_kabag_sarpras', 'Petrus Tobias, S.Kom.'),
            'nip_kabag_sarpras'  => Pengaturan::get('nip_kabag_sarpras', 'NIK. 2021.08.045'),
            'kota_dokumen'       => Pengaturan::get('kota_dokumen', 'Malang'),
        ];

        return view('laporan.index', compact(
            'barangs', 
            'ruangans', 
            'mutasis', 
            'stats',
            'peminjamanAsets',
            'peminjamanRuangans',
            'pejabat'
        ));
    }

    // Update Pejabat Penandatangan Laporan (Disimpan di Database)
    public function updateTtd(Request $request)
    {
        $request->validate([
            'nama_ketua'         => 'required|string|max:255',
            'nip_ketua'          => 'nullable|string|max:100',
            'nama_kabag_sarpras' => 'required|string|max:255',
            'nip_kabag_sarpras'  => 'nullable|string|max:100',
            'kota_dokumen'       => 'nullable|string|max:100',
        ]);

        Pengaturan::set('nama_ketua', $request->nama_ketua);
        Pengaturan::set('nip_ketua', $request->nip_ketua ?? '');
        Pengaturan::set('nama_kabag_sarpras', $request->nama_kabag_sarpras);
        Pengaturan::set('nip_kabag_sarpras', $request->nip_kabag_sarpras ?? '');
        if ($request->filled('kota_dokumen')) {
            Pengaturan::set('kota_dokumen', $request->kota_dokumen);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Pejabat penandatangan berhasil disimpan secara permanen di database!'
            ]);
        }

        return redirect()->back()->with('success', 'Pejabat penandatangan berhasil disimpan secara permanen di database!');
    }

    // Export Excel Data Aset / KIR
    public function exportExcel(Request $request)
    {
        $ruangan_id = $request->ruangan_id;
        
        if ($ruangan_id) {
            $ruangan = Ruangan::find($ruangan_id);
            $filename = 'KIR_Laporan_Aset_' . str_replace(' ', '_', $ruangan->nama_ruangan) . '_' . date('Ymd') . '.xlsx';
        } else {
            $filename = 'Laporan_Aset_Global_Semua_Ruangan_' . date('Ymd') . '.xlsx';
        }

        return Excel::download(new AsetExport($ruangan_id), $filename);
    }

    // Export Excel Laporan Peminjaman Aset
    public function exportPeminjamanAset(Request $request)
    {
        $tgl_mulai = $request->tgl_mulai_aset;
        $tgl_selesai = $request->tgl_selesai_aset;
        $status = $request->status_aset;

        $filename = 'Laporan_Peminjaman_Aset_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new PeminjamanAsetExport($tgl_mulai, $tgl_selesai, $status), $filename);
    }

    // Export Excel Laporan Peminjaman Ruangan
    public function exportPeminjamanRuangan(Request $request)
    {
        $tgl_mulai = $request->tgl_mulai_ruangan;
        $tgl_selesai = $request->tgl_selesai_ruangan;
        $ruangan_id = $request->ruangan_id_filter;
        $status = $request->status_ruangan;

        $filename = 'Laporan_Peminjaman_Ruangan_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new PeminjamanRuanganExport($tgl_mulai, $tgl_selesai, $ruangan_id, $status), $filename);
    }
}