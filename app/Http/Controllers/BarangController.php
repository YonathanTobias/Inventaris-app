<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Ruangan;
use App\Models\Kategori;
use App\Models\Mutasi;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with(['kategori', 'ruangan']);

        // Filter Pencarian & Ruangan & Kondisi & Kategori
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', '%' . $search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $search . '%')
                  ->orWhere('tahun_pengadaan', 'like', '%' . $search . '%');
            });
        }
        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        $barangs = $query->latest()->get();
        $ruangans = Ruangan::all();
        $kategoris = Kategori::all();

        $stats = [
            'total_jenis'   => Barang::count(),
            'total_unit'    => Barang::sum('jumlah'),
            'total_baik'    => Barang::where('kondisi', 'Baik')->sum('jumlah'),
            'total_rusak'   => Barang::whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat'])->sum('jumlah'),
            'total_ruangan' => Ruangan::count(),
            'total_kategori'=> Kategori::count(),
        ];

        $allBarangs = Barang::with('ruangan')->where('jumlah', '>', 0)->orderBy('nama_barang')->get();

        $nextAssetCodes = [];
        foreach ($ruangans as $r) {
            $nextAssetCodes[$r->id] = self::generateKodeBarangBaru($r);
        }

        return view('barang.index', compact('barangs', 'ruangans', 'kategoris', 'stats', 'allBarangs', 'nextAssetCodes'));
    }

    public function store(Request $request)
    {
        // Otomatis generate kode aset jika tidak diisi manual oleh user
        if (!$request->filled('kode_barang') && $request->filled('ruangan_id')) {
            $ruangan = Ruangan::find($request->ruangan_id);
            if ($ruangan) {
                $request->merge(['kode_barang' => self::generateKodeBarangBaru($ruangan)]);
            }
        }

        $validated = $request->validate([
            'kode_barang'     => 'required|string|max:100|unique:barangs,kode_barang',
            'nama_barang'     => 'required|string|max:255',
            'kategori_id'     => 'required|exists:kategoris,id',
            'ruangan_id'      => 'required|exists:ruangans,id',
            'jumlah'          => 'required|integer|min:1',
            'kondisi'         => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'bisa_dipinjam'   => 'nullable|boolean',
            'tahun_pengadaan' => 'nullable|string|max:20',
            'keterangan'      => 'nullable|string|max:1000',
        ]);

        $validated['bisa_dipinjam'] = $request->has('bisa_dipinjam');

        Barang::create($validated);

        return redirect()->back()->with('success', "Aset baru {$validated['nama_barang']} ({$validated['kode_barang']}) berhasil ditambahkan!");
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        $validated = $request->validate([
            'kode_barang'     => 'required|string|max:100|unique:barangs,kode_barang,' . $id,
            'nama_barang'     => 'required|string|max:255',
            'kategori_id'     => 'required|exists:kategoris,id',
            'ruangan_id'      => 'required|exists:ruangans,id',
            'jumlah'          => 'required|integer|min:0',
            'kondisi'         => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'bisa_dipinjam'   => 'nullable|boolean',
            'tahun_pengadaan' => 'nullable|string|max:20',
            'keterangan'      => 'nullable|string|max:1000',
        ]);

        $validated['bisa_dipinjam'] = $request->has('bisa_dipinjam');

        $barang->update($validated);

        return redirect()->back()->with('success', 'Data aset berhasil diperbarui!');
    }

    // Fitur Mutasi: Pindah Ruangan (Otomatis Menyesuaikan Kode Aset dengan Ruangan Tujuan)
    public function pindahRuangan(Request $request, $id = null)
    {
        $targetId = $id ?: $request->input('barang_id');

        $request->validate([
            'barang_id'         => $id ? 'nullable' : 'required|exists:barangs,id',
            'ruangan_tujuan_id' => 'required|exists:ruangans,id',
            'jumlah'            => 'required|integer|min:1',
            'kode_baru'         => 'nullable|string|max:100',
            'keterangan'        => 'nullable|string|max:500',
        ]);

        $barangAsal = Barang::with('ruangan')->findOrFail($targetId);

        if ($request->jumlah > $barangAsal->jumlah) {
            return redirect()->back()->with('error', "Jumlah pemindahan ({$request->jumlah}) melebihi stok yang ada ({$barangAsal->jumlah})!");
        }

        if ($request->ruangan_tujuan_id == $barangAsal->ruangan_id) {
            return redirect()->back()->with('error', 'Ruangan tujuan tidak boleh sama dengan ruangan asal!');
        }

        $ruanganAsalId = $barangAsal->ruangan_id;
        $ruanganAsalNama = $barangAsal->ruangan->nama_ruangan ?? 'Ruangan Asal';
        $ruanganTujuan = Ruangan::findOrFail($request->ruangan_tujuan_id);

        // Buat Kode Aset Baru yang otomatis menyesuaikan dengan kode Ruangan Tujuan
        $kodeBaru = $request->filled('kode_baru') 
            ? trim($request->kode_baru) 
            : $this->generateKodeBarangBaru($ruanganTujuan);

        // Pastikan kode baru tidak tabrakan dengan aset lain di database
        $cekBentrok = Barang::where('kode_barang', $kodeBaru)->where('id', '!=', $barangAsal->id)->exists();
        if ($cekBentrok) {
            $kodeBaru = $this->generateKodeBarangBaru($ruanganTujuan);
        }

        $oldKode = $barangAsal->kode_barang;

        \DB::beginTransaction();
        try {
            $catatanMutasi = $request->filled('keterangan') 
                ? $request->keterangan 
                : "Pemindahan dari {$ruanganAsalNama} ke {$ruanganTujuan->nama_ruangan}";

            // SKENARIO 1: Pindah SELURUH Unit (Relokasi Penuh Aset)
            if ($request->jumlah >= $barangAsal->jumlah) {
                $barangAsal->ruangan_id = $request->ruangan_tujuan_id;
                $barangAsal->kode_barang = $kodeBaru; // Kode aset otomatis disesuaikan!
                $barangAsal->save();

                Mutasi::create([
                    'barang_id'         => $barangAsal->id,
                    'jenis_mutasi'      => 'Pindah Ruangan',
                    'ruangan_asal_id'   => $ruanganAsalId,
                    'ruangan_tujuan_id' => $request->ruangan_tujuan_id,
                    'jumlah'            => $request->jumlah,
                    'keterangan'        => "{$catatanMutasi} (Kode Aset diperbarui: {$oldKode} → {$kodeBaru})",
                ]);
            } else {
                // SKENARIO 2: Pindah SEBAGIAN Unit (Pecah / Split Stok)
                $barangAsal->decrement('jumlah', $request->jumlah);

                $barangBaru = Barang::create([
                    'kode_barang'     => $kodeBaru, // Kode aset baru di ruangan tujuan!
                    'nama_barang'     => $barangAsal->nama_barang,
                    'kategori_id'     => $barangAsal->kategori_id,
                    'ruangan_id'      => $request->ruangan_tujuan_id,
                    'jumlah'          => $request->jumlah,
                    'kondisi'         => $barangAsal->kondisi,
                    'tahun_pengadaan' => $barangAsal->tahun_pengadaan,
                    'keterangan'      => ($barangAsal->keterangan ? $barangAsal->keterangan . ' | ' : '') . "Pindahan dari {$ruanganAsalNama} (Eks {$oldKode})",
                ]);

                Mutasi::create([
                    'barang_id'         => $barangAsal->id,
                    'jenis_mutasi'      => 'Pindah Ruangan',
                    'ruangan_asal_id'   => $ruanganAsalId,
                    'ruangan_tujuan_id' => $request->ruangan_tujuan_id,
                    'jumlah'            => $request->jumlah,
                    'keterangan'        => "{$catatanMutasi} (Kode Aset Baru: {$kodeBaru})",
                ]);
            }

            \DB::commit();
            return redirect()->back()->with('success', "Aset {$barangAsal->nama_barang} berhasil dipindahkan ke {$ruanganTujuan->nama_ruangan}! Kode aset telah otomatis diperbarui menjadi [{$kodeBaru}].");
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memindahkan aset: ' . $e->getMessage());
        }
    }

    // Helper: Generate Kode Aset Unik Otomatis Berdasarkan Ruangan Tujuan
    public static function generateKodeBarangBaru(Ruangan $ruangan)
    {
        $parts = explode('-', $ruangan->kode_ruangan);
        $seg = '';
        if (count($parts) >= 2) {
            if (in_array(strtoupper($parts[0]), ['LAB', 'R', 'RK', 'RUANG'])) {
                $seg = strtoupper(substr($parts[1], 0, 4));
            } else {
                $seg = strtoupper(substr($parts[0], 0, 4));
            }
        } else {
            $seg = strtoupper(substr($ruangan->kode_ruangan, 0, 3));
        }

        $prefix = "AST-PW-{$seg}";

        $lastBarangs = Barang::where('kode_barang', 'like', "{$prefix}-%")->get();
        $maxNum = 0;
        foreach ($lastBarangs as $lb) {
            if (preg_match('/-(\d+)$/', $lb->kode_barang, $m)) {
                $num = (int)$m[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }

        $nextNum = $maxNum + 1;
        $candidate = sprintf('%s-%03d', $prefix, $nextNum);
        while (Barang::where('kode_barang', $candidate)->exists()) {
            $nextNum++;
            $candidate = sprintf('%s-%03d', $prefix, $nextNum);
        }

        return $candidate;
    }

    // Fitur Pengurangan / Kerusakan Barang
    public function kurangiStok(Request $request, $id)
    {
        $request->validate([
            'jumlah'     => 'required|numeric|min:1',
            'keterangan' => 'required',
        ]);

        $barang = Barang::findOrFail($id);

        if ($request->jumlah > $barang->jumlah) {
            return redirect()->back()->with('error', 'Jumlah pengurangan melebihi stok yang ada!');
        }

        $barang->decrement('jumlah', $request->jumlah);

        Mutasi::create([
            'barang_id'       => $barang->id,
            'jenis_mutasi'    => 'Pengurangan/Rusak',
            'ruangan_asal_id' => $barang->ruangan_id,
            'jumlah'          => $request->jumlah,
            'keterangan'      => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Stok aset berhasil dikurangi!');
    }

    public function destroy($id)
    {
        Barang::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data aset berhasil dihapus!');
    }

    // Fitur Cetak Label Stiker Aset (Ukuran 5cm x 2.5cm)
    public function cetakLabel(Request $request, $id)
    {
        $barang = Barang::with(['kategori', 'ruangan'])->findOrFail($id);
        $mode = $request->get('mode', 'jenis'); // 'jenis' (1 stiker) atau 'unit' (sesuai jumlah stok)
        $type = $request->get('type', 'qr'); // 'qr' (QR Code) atau 'barcode' (Barcode 128)
        
        $barangsList = collect([$barang]);
        if ($mode === 'unit') {
            $expanded = collect();
            $qty = max(1, (int)$barang->jumlah);
            for ($i = 1; $i <= $qty; $i++) {
                $itemCopy = clone $barang;
                $itemCopy->unit_seq = $i;
                $itemCopy->total_seq = $qty;
                $itemCopy->unique_print_id = $barang->id . '-' . $i;
                $expanded->push($itemCopy);
            }
            $barangsList = $expanded;
        }

        $barangs = $barangsList;
        $allRuangans = Ruangan::all();
        $title = "Cetak Label {$barang->kode_barang}";

        return view('barang.label', compact('barangs', 'title', 'allRuangans', 'mode', 'type'));
    }

    // Cetak Label Seluruh Aset dalam 1 Ruangan Tertentu Menjadi 1 Dokumen
    public function cetakLabelRuangan(Request $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $rawBarangs = Barang::with(['kategori', 'ruangan'])->where('ruangan_id', $id)->latest()->get();
        $mode = $request->get('mode', 'jenis');
        $type = $request->get('type', 'qr');

        if ($mode === 'unit') {
            $expanded = collect();
            foreach ($rawBarangs as $b) {
                $qty = max(1, (int)$b->jumlah);
                for ($i = 1; $i <= $qty; $i++) {
                    $itemCopy = clone $b;
                    $itemCopy->unit_seq = $i;
                    $itemCopy->total_seq = $qty;
                    $itemCopy->unique_print_id = $b->id . '-' . $i;
                    $expanded->push($itemCopy);
                }
            }
            $barangs = $expanded;
        } else {
            $barangs = $rawBarangs;
        }

        $allRuangans = Ruangan::all();
        $title = "Label Aset Ruang: {$ruangan->nama_ruangan} ({$ruangan->kode_ruangan})";

        return view('barang.label', compact('barangs', 'title', 'ruangan', 'allRuangans', 'mode', 'type'));
    }

    public function cetakLabelMassal(Request $request)
    {
        $query = Barang::with(['kategori', 'ruangan']);

        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', '%' . $search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $search . '%');
            });
        }

        $rawBarangs = $query->latest()->get();
        $mode = $request->get('mode', 'jenis');
        $type = $request->get('type', 'qr');

        if ($mode === 'unit') {
            $expanded = collect();
            foreach ($rawBarangs as $b) {
                $qty = max(1, (int)$b->jumlah);
                for ($i = 1; $i <= $qty; $i++) {
                    $itemCopy = clone $b;
                    $itemCopy->unit_seq = $i;
                    $itemCopy->total_seq = $qty;
                    $itemCopy->unique_print_id = $b->id . '-' . $i;
                    $expanded->push($itemCopy);
                }
            }
            $barangs = $expanded;
        } else {
            $barangs = $rawBarangs;
        }

        $allRuangans = Ruangan::all();
        $selectedRuangan = $request->filled('ruangan_id') ? Ruangan::find($request->ruangan_id) : null;
        $title = $selectedRuangan 
            ? "Label Aset Ruangan: {$selectedRuangan->nama_ruangan}" 
            : "Cetak Label Stiker Massal (" . $barangs->count() . " Label)";

        return view('barang.label', [
            'barangs'     => $barangs,
            'title'       => $title,
            'ruangan'     => $selectedRuangan,
            'allRuangans' => $allRuangans,
            'mode'        => $mode,
            'type'        => $type,
        ]);
    }

    // Halaman Publik Verifikasi Aset dari Scan QR Code
    public function cekAsetPublik($kode_barang)
    {
        $barang = Barang::with(['kategori', 'ruangan', 'mutasis' => function($q) {
            $q->with(['ruanganAsal', 'ruanganTujuan'])->latest();
        }])->where('kode_barang', $kode_barang)->firstOrFail();

        return view('barang.cek_publik', compact('barang'));
    }
}