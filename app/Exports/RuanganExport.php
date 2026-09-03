<?php

namespace App\Exports;

use App\Models\Ruangan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RuanganExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $rowNumber = 0;

    public function collection()
    {
        return Ruangan::with(['barangs'])->withCount('barangs')->orderBy('nama_ruangan', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            ['LAPORAN MASTER DATA RUANGAN & LOKASI ASET'],
            ['SEKOLAH TINGGI ILMU KESEHATAN PANTI WALUYA MALANG'],
            ['BAGIAN SARANA DAN PRASARANA (SARPRAS)'],
            ['TANGGAL EKSPOR: ' . date('d-m-Y H:i') . ' WIB'],
            [], // Baris kosong
            [
                'No',
                'Kode Ruangan',
                'Nama Ruangan',
                'Status Peminjaman',
                'Total Jenis Aset',
                'Total Unit Fisik Aset',
                'Tanggal Ditambahkan'
            ]
        ];
    }

    public function map($ruangan): array
    {
        $this->rowNumber++;
        $totalUnit = $ruangan->barangs ? $ruangan->barangs->sum('jumlah') : 0;

        return [
            $this->rowNumber,
            $ruangan->kode_ruangan ?? '-',
            $ruangan->nama_ruangan,
            $ruangan->bisa_dipinjam ? 'Dapat Dipinjam Publik' : 'Khusus Penyimpanan Aset',
            $ruangan->barangs_count ?? 0,
            $totalUnit,
            $ruangan->created_at ? $ruangan->created_at->format('d-m-Y') : '-',
        ];
    }

    public function title(): string
    {
        return 'Data Ruangan';
    }

    public function styles(Worksheet $sheet)
    {
        // Bold untuk Judul Kop Laporan
        $sheet->getStyle('A1:A4')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(13);
        
        // Bold untuk Heading Tabel (Baris ke-6)
        $sheet->getStyle('A6:G6')->getFont()->setBold(true);

        return [];
    }
}