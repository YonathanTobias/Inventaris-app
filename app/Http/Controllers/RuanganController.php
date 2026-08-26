<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::withCount('barangs')->latest()->get();
        $stats = [
            'total_ruangan'     => $ruangans->count(),
            'ruangan_terisi'    => $ruangans->where('barangs_count', '>', 0)->count(),
            'total_aset'        => \App\Models\Barang::sum('jumlah'),
            'ruangan_terbanyak' => $ruangans->sortByDesc('barangs_count')->first(),
        ];
        return view('ruangan.index', compact('ruangans', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_ruangan' => 'required|string|max:50|unique:ruangans,kode_ruangan',
            'nama_ruangan' => 'required|string|max:255',
        ]);

        Ruangan::create($validated);

        return redirect()->back()->with('success', 'Data ruangan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $validated = $request->validate([
            'kode_ruangan' => 'required|string|max:50|unique:ruangans,kode_ruangan,' . $id,
            'nama_ruangan' => 'required|string|max:255',
        ]);

        $ruangan->update($validated);

        return redirect()->back()->with('success', 'Data ruangan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        
        // Proteksi integritas data: Cek apakah masih ada aset di ruangan ini
        if ($ruangan->barangs()->count() > 0) {
            return redirect()->back()->with('error', 'Ruangan tidak dapat dihapus karena masih berisi data aset. Silakan pindahkan aset ke ruangan lain terlebih dahulu.');
        }

        $ruangan->delete();

        return redirect()->back()->with('success', 'Data ruangan berhasil dihapus!');
    }
}