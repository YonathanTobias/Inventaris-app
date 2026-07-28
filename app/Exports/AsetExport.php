<?php

namespace App\Exports;

use App\Models\Barang;
use App\Models\Ruangan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AsetExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $ruangan_id;
    protected $ruanganNama;
    protected $rowNumber = 0;

    public function __construct($ruangan_id = null)
    {
        $this->ruangan_id = $ruangan_id;
        if ($ruangan_id) {
            $r = Ruangan::find($ruangan_id);
            $this->ruanganNama = $r ? $r->nama_ruangan : 'Per Ruangan';
        } else {
            $this->ruanganNama = 'Global (Semua Ruangan)';
        }
    }

    public function collection()
    {
        $query = Barang::with(['kategori', 'ruangan']);

        if ($this->ruangan_id) {
            $query->where('ruangan_id', $this->ruangan_id);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            ['LAPORAN INVENTARIS ASET BARANG'],
            ['LOKASI / RUANGAN: ' . strtoupper($this->ruanganNama)],
            ['TANGGAL CETAK: ' . date('d-m-Y H:i')],
            [], // Baris kosong
            [
                'No',
                'Kode Aset',
                'Nama Barang',
                'Kategori',
                'Ruangan / Lokasi',
                'Jumlah',
                'Kondisi',
                'Tahun Pengadaan',
                'Keterangan'
            ]
        ];
    }

    public function map($barang): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $barang->kode_barang,
            $barang->nama_barang,
            $barang->kategori->nama_kategori ?? '-',
            $barang->ruangan->nama_ruangan ?? '-',
            $barang->jumlah,
            $barang->kondisi,
            $barang->tahun_pengadaan ?? '-',
            $barang->keterangan ?? '-',
        ];
    }

    public function title(): string
    {
        return 'Laporan Aset';
    }

    public function styles(Worksheet $sheet)
    {
        // Bold untuk Judul Header Laporan
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(14);
        
        // Bold untuk Heading Tabel (Baris ke-5)
        $sheet->getStyle('A5:I5')->getFont()->setBold(true);

        return [];
    }
}