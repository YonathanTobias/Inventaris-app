<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::withCount('barangs')->latest()->get();
        $stats = [
            'total_kategori' => $kategoris->count(),
            'total_barang'   => \App\Models\Barang::count(),
            'kategori_terbanyak' => $kategoris->sortByDesc('barangs_count')->first(),
        ];
        return view('kategori.index', compact('kategoris', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:150|unique:kategoris,nama_kategori',
        ]);

        Kategori::create($validated);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:150|unique:kategoris,nama_kategori,' . $id,
        ]);

        $kategori->update($validated);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);

        // Proteksi integritas: Cek apakah masih ada aset yang terkait kategori ini
        if ($kategori->barangs()->count() > 0) {
            return redirect()->back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh beberapa aset.');
        }

        $kategori->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }
}