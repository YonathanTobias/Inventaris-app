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

        // Filter Pencarian & Ruangan
        if ($request->search) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $request->search . '%');
        }
        if ($request->ruangan_id) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        $barangs = $query->latest()->get();
        $ruangans = Ruangan::all();
        $kategoris = Kategori::all();

        return view('barang.index', compact('barangs', 'ruangans', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang',
            'nama_barang' => 'required',
            'kategori_id' => 'required',
            'ruangan_id'  => 'required',
            'jumlah'      => 'required|numeric|min:1',
            'kondisi'     => 'required',
        ]);

        Barang::create($request->all());

        return redirect()->back()->with('success', 'Aset baru berhasil ditambahkan!');
    }

    // Fitur Mutasi: Pindah Ruangan
    public function pindahRuangan(Request $request, $id)
    {
        $request->validate([
            'ruangan_tujuan_id' => 'required',
            'jumlah'            => 'required|numeric|min:1',
        ]);

        $barangAsal = Barang::findOrFail($id);

        if ($request->jumlah > $barangAsal->jumlah) {
            return redirect()->back()->with('error', 'Jumlah pemindahan melebihi stok yang ada!');
        }

        // 1. Kurangi stok di ruangan asal
        $barangAsal->decrement('jumlah', $request->jumlah);

        // 2. Cek apakah barang yang sama sudah ada di ruangan tujuan
        $barangTujuan = Barang::where('kode_barang', $barangAsal->kode_barang)
            ->where('ruangan_id', $request->ruangan_tujuan_id)
            ->first();

        if ($barangTujuan) {
            $barangTujuan->increment('jumlah', $request->jumlah);
        } else {
            Barang::create([
                'kode_barang'    => $barangAsal->kode_barang,
                'nama_barang'    => $barangAsal->nama_barang,
                'kategori_id'    => $barangAsal->kategori_id,
                'ruangan_id'     => $request->ruangan_tujuan_id,
                'jumlah'         => $request->jumlah,
                'kondisi'        => $barangAsal->kondisi,
                'tahun_pengadaan'=> $barangAsal->tahun_pengadaan,
            ]);
        }

        // 3. Catat Riwayat Mutasi
        Mutasi::create([
            'barang_id'         => $barangAsal->id,
            'jenis_mutasi'      => 'Pindah Ruangan',
            'ruangan_asal_id'   => $barangAsal->ruangan_id,
            'ruangan_tujuan_id' => $request->ruangan_tujuan_id,
            'jumlah'            => $request->jumlah,
            'keterangan'        => $request->keterangan ?? 'Pemindahan Lokasi Aset',
        ]);

        return redirect()->back()->with('success', 'Berhasil memindahkan aset ke ruangan tujuan!');
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
}