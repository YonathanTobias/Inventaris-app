<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public static function getMasterGedungLantai(): array
    {
        $default = [
            'Gedung A' => ['Lantai Dasar', 'Lantai 1', 'Lantai 2', 'Lantai 3', 'Lantai 4', 'Lantai 5'],
            'Gedung B' => [],
        ];

        $saved = Pengaturan::get('master_gedung_lantai');
        if ($saved) {
            $decoded = json_decode($saved, true);
            if (is_array($decoded) && !empty($decoded)) {
                return $decoded;
            }
        }

        return $default;
    }

    public function index()
    {
        $ruangans = Ruangan::withCount('barangs')->latest()->get();
        $masterGedungLantai = self::getMasterGedungLantai();
        $stats = [
            'total_ruangan'     => $ruangans->count(),
            'ruangan_terisi'    => $ruangans->where('barangs_count', '>', 0)->count(),
            'ruangan_dipinjam'  => $ruangans->where('bisa_dipinjam', true)->count(),
            'total_aset'        => \App\Models\Barang::sum('jumlah'),
            'ruangan_terbanyak' => $ruangans->sortByDesc('barangs_count')->first(),
        ];
        return view('ruangan.index', compact('ruangans', 'stats', 'masterGedungLantai'));
    }

    public function storeGedungLantai(Request $request)
    {
        $action = $request->input('action', 'add_lantai');
        $master = self::getMasterGedungLantai();

        if ($action === 'add_gedung') {
            $request->validate([
                'nama_gedung' => 'required|string|max:100',
                'lantai_awal' => 'nullable|string|max:255',
            ]);

            $namaGedung = trim($request->nama_gedung);
            if (!str_starts_with(strtoupper($namaGedung), 'GEDUNG')) {
                $namaGedung = 'Gedung ' . strtoupper($namaGedung);
            }

            $lantaiInput = trim($request->lantai_awal ?? '');
            $lantaiList = [];
            if ($lantaiInput !== '') {
                $lantaiList = array_values(array_filter(array_map(function ($item) {
                    $item = trim($item);
                    if ($item === '' || $item === '-') return null;
                    if (strtolower($item) === 'dasar' || strtolower($item) === '0') {
                        return 'Lantai Dasar';
                    }
                    if (!str_starts_with(strtolower($item), 'lantai')) {
                        return 'Lantai ' . $item;
                    }
                    return ucfirst($item);
                }, explode(',', $lantaiInput))));
            }

            if (!isset($master[$namaGedung])) {
                $master[$namaGedung] = $lantaiList;
            } else {
                $master[$namaGedung] = array_values(array_unique(array_merge($master[$namaGedung], $lantaiList)));
            }

            Pengaturan::set('master_gedung_lantai', json_encode($master));
            return redirect()->back()->with('success', "{$namaGedung} beserta daftar lantainya berhasil ditambahkan!");
        }

        if ($action === 'add_lantai') {
            $request->validate([
                'gedung_target' => 'required|string|max:100',
                'nama_lantai'   => 'required|string|max:100',
            ]);

            $gedung = $request->gedung_target;
            $lantaiRaw = trim($request->nama_lantai);
            if (strtolower($lantaiRaw) === 'dasar' || $lantaiRaw === '0') {
                $namaLantai = 'Lantai Dasar';
            } elseif (!str_starts_with(strtolower($lantaiRaw), 'lantai')) {
                $namaLantai = 'Lantai ' . $lantaiRaw;
            } else {
                $namaLantai = ucwords(strtolower($lantaiRaw));
            }

            if (!isset($master[$gedung])) {
                $master[$gedung] = [];
            }

            if (!in_array($namaLantai, $master[$gedung], true)) {
                $master[$gedung][] = $namaLantai;
            }

            Pengaturan::set('master_gedung_lantai', json_encode($master));
            return redirect()->back()->with('success', "{$namaLantai} berhasil ditambahkan ke {$gedung}!");
        }

        if ($action === 'delete_lantai') {
            $gedung = $request->input('gedung_target');
            $lantai = $request->input('nama_lantai');
            if (isset($master[$gedung])) {
                $master[$gedung] = array_values(array_filter($master[$gedung], fn($l) => $l !== $lantai));
                Pengaturan::set('master_gedung_lantai', json_encode($master));
                return redirect()->back()->with('success', "{$lantai} pada {$gedung} berhasil dihapus dari daftar pilihan.");
            }
        }

        if ($action === 'delete_gedung') {
            $gedung = $request->input('gedung_target');
            if (isset($master[$gedung]) && count($master) > 1) {
                unset($master[$gedung]);
                Pengaturan::set('master_gedung_lantai', json_encode($master));
                return redirect()->back()->with('success', "{$gedung} berhasil dihapus dari daftar pilihan.");
            }
            return redirect()->back()->with('error', 'Minimal harus ada 1 gedung terdaftar.');
        }

        return redirect()->back();
    }

    public function store(Request $request)
    {
        // Otomatis generate kode jika tidak diisi manual oleh user
        if (!$request->filled('kode_ruangan')) {
            $request->merge([
                'kode_ruangan' => self::generateAutoKodeRuangan(
                    $request->nama_ruangan,
                    $request->gedung,
                    $request->lantai
                )
            ]);
        }

        $validated = $request->validate([
            'kode_ruangan'         => 'required|string|max:50|unique:ruangans,kode_ruangan',
            'nama_ruangan'         => 'required|string|max:255',
            'gedung'               => 'nullable|string|max:100',
            'lantai'               => 'nullable|string|max:100',
            'penanggung_jawab'     => 'nullable|string|max:255',
            'nip_penanggung_jawab' => 'nullable|string|max:100',
            'bisa_dipinjam'        => 'nullable',
        ]);

        $validated['bisa_dipinjam'] = $request->boolean('bisa_dipinjam', true);

        Ruangan::create($validated);

        return redirect()->back()->with('success', "Ruangan {$validated['nama_ruangan']} ({$validated['kode_ruangan']}) berhasil ditambahkan!");
    }

    public static function extractGedungLantaiPrefix(?string $gedung, ?string $lantai): string
    {
        $gedungCode = '';
        if (!empty($gedung)) {
            $gClean = strtoupper(trim(preg_replace('/\bGEDUNG\b/i', '', $gedung)));
            $gClean = preg_replace('/[^A-Z0-9]/', '', $gClean);
            $gedungCode = $gClean ?: substr(strtoupper(preg_replace('/[^A-Z0-9]/', '', $gedung)), 0, 1);
        }

        // Jika gedung tidak memiliki lantai bertingkat (misal Gedung B hanya 1 lantai) atau lantai kosong/'-'
        $master = self::getMasterGedungLantai();
        if (!empty($gedung) && isset($master[$gedung]) && empty($master[$gedung])) {
            return $gedungCode;
        }

        $lantaiCode = '';
        if (!empty($lantai) && trim($lantai) !== '-') {
            $lUpper = strtoupper(trim($lantai));
            if (str_contains($lUpper, 'DASAR') || str_contains($lUpper, 'GROUND') || $lUpper === '0' || $lUpper === 'LANTAI 0') {
                $lantaiCode = '0';
            } elseif (preg_match('/(\d+)/', $lUpper, $matches)) {
                $lantaiCode = $matches[1];
            } else {
                $lClean = trim(preg_replace('/\bLANTAI\b/i', '', $lUpper));
                $lantaiCode = substr(preg_replace('/[^A-Z0-9]/', '', $lClean), 0, 2);
            }
        }

        return $gedungCode . $lantaiCode;
    }

    // Helper: Otomatis Men-generate Kode Ruangan Unik (Format: [GedungLantai]-[Prefix]-[Sub]-[Seq], misal A0-LAB-KEB-01 atau A1-LAB-KEB-01)
    public static function generateAutoKodeRuangan($namaRuangan, $gedung = null, $lantai = null)
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
        } elseif (str_contains($namaClean, 'PERPUS')) {
            $prefix = 'PERPUS';
        } elseif (str_contains($namaClean, 'AULA')) {
            $prefix = 'AULA';
        } elseif (str_contains($namaClean, 'KANTOR') || str_contains($namaClean, 'RUANG')) {
            $prefix = 'RNG';
            $words = preg_split('/[\s\-_]+/', str_replace(['RUANG', 'RUANGAN', 'KANTOR'], '', $namaClean));
            $words = array_values(array_filter($words));
            if (!empty($words)) {
                $sub = strtoupper(substr($words[0], 0, 3));
            }
        } else {
            $words = preg_split('/[\s\-_]+/', $namaClean);
            $words = array_values(array_filter($words));
            if (!empty($words)) {
                $sub = strtoupper(substr($words[0], 0, 3));
            }
        }

        $typeBase = $sub ? "{$prefix}-{$sub}" : $prefix;
        $locPrefix = self::extractGedungLantaiPrefix($gedung, $lantai);
        $base = $locPrefix !== '' ? "{$locPrefix}-{$typeBase}" : $typeBase;

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
            'kode_ruangan'         => 'required|string|max:50|unique:ruangans,kode_ruangan,' . $id,
            'nama_ruangan'         => 'required|string|max:255',
            'gedung'               => 'nullable|string|max:100',
            'lantai'               => 'nullable|string|max:100',
            'penanggung_jawab'     => 'nullable|string|max:255',
            'nip_penanggung_jawab' => 'nullable|string|max:100',
            'bisa_dipinjam'        => 'nullable',
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

    public function exportExcel()
    {
        $filename = 'Master_Data_Ruangan_' . date('Ymd_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\RuanganExport(), $filename);
    }
}