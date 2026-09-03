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
            'ruangan_dipinjam'  => $ruangans->where('bisa_dipinjam', true)->count(),
            'total_aset'        => \App\Models\Barang::sum('jumlah'),
            'ruangan_terbanyak' => $ruangans->sortByDesc('barangs_count')->first(),
        ];
        return view('ruangan.index', compact('ruangans', 'stats'));
    }

    public function store(Request $request)
    {
        // Otomatis generate kode jika tidak diisi manual oleh user
        if (!$request->filled('kode_ruangan')) {
            $request->merge(['kode_ruangan' => self::generateAutoKodeRuangan($request->nama_ruangan)]);
        }

        $validated = $request->validate([
            'kode_ruangan'   => 'required|string|max:50|unique:ruangans,kode_ruangan',
            'nama_ruangan'   => 'required|string|max:255',
            'bisa_dipinjam'  => 'nullable',
        ]);

        $validated['bisa_dipinjam'] = $request->boolean('bisa_dipinjam', true);

        Ruangan::create($validated);

        return redirect()->back()->with('success', "Ruangan {$validated['nama_ruangan']} ({$validated['kode_ruangan']}) berhasil ditambahkan!");
    }

    // Helper: Otomatis Men-generate Kode Ruangan Unik
    public static function generateAutoKodeRuangan($namaRuangan)
    {
        $namaClean = strtoupper(trim($namaRuangan ?? ''));
        $prefix = 'RNG';
        $sub = '';

        if (str_contains($namaClean, 'LAB')) {
            $prefix = 'LAB';
            $words = preg_split('/[\s\-_]+/', str_replace(['LAB', 'LABORATORIUM'], '', $namaClean));
            $words = array_values(array_filter($words));
            if (!empty($words)) {
                $sub = strtoupper(substr($words[0], 0, 3));
            }
        } elseif (str_contains($namaClean, 'KULIAH') || str_contains($namaClean, 'KELAS') || str_contains($namaClean, 'TEORI')) {
            $prefix = 'RK';
            $words = preg_split('/[\s\-_]+/', str_replace(['RUANG', 'KULIAH', 'KELAS', 'TEORI'], '', $namaClean));
            $words = array_values(array_filter($words));
            if (!empty($words)) {
                $sub = strtoupper(substr($words[0], 0, 3));
            }
        } elseif (str_contains($namaClean, 'GUDANG')) {
            $prefix = 'GDG';
        } elseif (str_contains($namaClean, 'KANTOR') || str_contains($namaClean, 'RUANG')) {
            $prefix = 'RNG';
            $words = preg_split('/[\s\-_]+/', str_replace(['RUANG', 'RUANGAN', 'KANTOR'], '', $namaClean));
            $words = array_values(array_filter($words));
            if (!empty($words)) {
                $sub = strtoupper(substr($words[0], 0, 3));
            }
        }

        $base = $sub ? "{$prefix}-{$sub}" : $prefix;

        $count = Ruangan::where('kode_ruangan', 'like', "{$base}-%")->count();
        $next = sprintf('%02d', $count + 1);
        $candidate = "{$base}-{$next}";

        $attempt = 1;
        while (Ruangan::where('kode_ruangan', $candidate)->exists()) {
            $next = sprintf('%02d', $count + 1 + $attempt);
            $candidate = "{$base}-{$next}";
            $attempt++;
        }

        return $candidate;
    }

    public function update(Request $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $validated = $request->validate([
            'kode_ruangan'  => 'required|string|max:50|unique:ruangans,kode_ruangan,' . $id,
            'nama_ruangan'  => 'required|string|max:255',
            'bisa_dipinjam' => 'nullable',
        ]);

        $validated['bisa_dipinjam'] = $request->boolean('bisa_dipinjam', false);

        $ruangan->update($validated);

        return redirect()->back()->with('success', 'Data ruangan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $ruangan = Ruangan::withCount(['barangs', 'peminjamanRuangans'])->findOrFail($id);

        if ($ruangan->barangs_count > 0) {
            return redirect()->back()->with('error', "Ruangan [{$ruangan->nama_ruangan}] tidak dapat dihapus karena masih memiliki {$ruangan->barangs_count} item aset!");
        }

        $ruangan->delete();
        return redirect()->back()->with('success', 'Ruangan berhasil dihapus!');
    }
}