<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Ruangan;
use App\Models\Mutasi;
use App\Exports\AsetExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $ruangans = Ruangan::all();
        
        $query = Barang::with(['kategori', 'ruangan']);
        if ($request->ruangan_id) {
            $query->where('ruangan_id', $request->ruangan_id);
        }
        $barangs = $query->latest()->get();

        // Riwayat Mutasi / Pindah / Kerusakan
        $mutasis = Mutasi::with(['barang', 'ruanganAsal', 'ruanganTujuan'])->latest()->take(30)->get();

        $stats = [
            'total_aset_tercatat' => $barangs->count(),
            'total_unit_tercatat' => $barangs->sum('jumlah'),
            'total_ruangan'       => $ruangans->count(),
            'total_mutasi'        => Mutasi::count(),
        ];

        return view('laporan.index', compact('barangs', 'ruangans', 'mutasis', 'stats'));
    }

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
}