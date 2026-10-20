<?php

namespace App\Exports;

use App\Models\Mutasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MutasiExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $tgl_mulai;
    protected $tgl_selesai;
    protected $ruangan_asal_id;
    protected $ruangan_tujuan_id;
    protected $rowNumber = 0;

    public function __construct($tgl_mulai = null, $tgl_selesai = null, $ruangan_asal_id = null, $ruangan_tujuan_id = null)
    {
        $this->tgl_mulai = $tgl_mulai;
        $this->tgl_selesai = $tgl_selesai;
        $this->ruangan_asal_id = $ruangan_asal_id;
        $this->ruangan_tujuan_id = $ruangan_tujuan_id;
    }

    public function collection()
    {
        $query = Mutasi::where('jenis_mutasi', 'Pindah Ruangan')
            ->with(['barang.kategori', 'ruanganAsal', 'ruanganTujuan']);

        if ($this->tgl_mulai && $this->tgl_selesai) {
            $query->whereBetween('created_at', [
                $this->tgl_mulai . ' 00:00:00', 
                $this->tgl_selesai . ' 23:59:59'
            ]);
        } elseif ($this->tgl_mulai) {
            $query->whereDate('created_at', '>=', $this->tgl_mulai);
        } elseif ($this->tgl_selesai) {
            $query->whereDate('created_at', '<=', $this->tgl_selesai);
        }

        if ($this->ruangan_asal_id) {
            $query->where('ruangan_asal_id', $this->ruangan_asal_id);
        }

        if ($this->ruangan_tujuan_id) {
            $query->where('ruangan_tujuan_id', $this->ruangan_tujuan_id);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        $periodeText = ($this->tgl_mulai && $this->tgl_selesai) 
            ? date('d/m/Y', strtotime($this->tgl_mulai)) . ' s/d ' . date('d/m/Y', strtotime($this->tgl_selesai)) 
            : 'SEMUA PERIODE';

        return [
            ['LAPORAN REKAPITULASI MUTASI PERPINDAHAN RUANGAN ASET'],
            ['SEKOLAH TINGGI ILMU KESEHATAN PANTI WALUYA MALANG'],
            ['BAGIAN SARANA DAN PRASARANA (SARPRAS)'],
            ['PERIODE TANGGAL : ' . $periodeText],
            ['TANGGAL EKSPOR  : ' . date('d-m-Y H:i') . ' WIB'],
            [],
            [
                'No',
                'Waktu Mutasi',
                'Kode Aset',
                'Nama Barang / Aset',
                'Kategori',
                'Ruangan Asal',
                'Ruangan Tujuan',
                'Jumlah Unit',
                'Keterangan / Berita Acara'
            ]
        ];
    }

    public function map($m): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $m->created_at ? $m->created_at->format('d/m/Y H:i') : '-',
            $m->barang->kode_barang ?? '-',
            $m->barang->nama_barang ?? 'Aset Telah Dihapus',
            $m->barang->kategori->nama_kategori ?? '-',
            $m->ruanganAsal->nama_ruangan ?? '-',
            $m->ruanganTujuan->nama_ruangan ?? '-',
            $m->jumlah . ' Unit',
            $m->keterangan ?? '-',
        ];
    }

    public function title(): string
    {
        return 'Laporan Mutasi Ruangan Aset';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:A5')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(13);
        $sheet->getStyle('A7:I7')->getFont()->setBold(true);
        return [];
    }
}
