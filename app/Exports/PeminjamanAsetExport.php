<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PeminjamanAsetExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $tgl_mulai;
    protected $tgl_selesai;
    protected $status;
    protected $rowNumber = 0;

    public function __construct($tgl_mulai = null, $tgl_selesai = null, $status = null)
    {
        $this->tgl_mulai = $tgl_mulai;
        $this->tgl_selesai = $tgl_selesai;
        $this->status = $status;
    }

    public function collection()
    {
        $query = Peminjaman::with(['barang', 'details.barang', 'user']);

        if ($this->tgl_mulai && $this->tgl_selesai) {
            $query->whereBetween('tanggal_pinjam', [$this->tgl_mulai, $this->tgl_selesai]);
        } elseif ($this->tgl_mulai) {
            $query->whereDate('tanggal_pinjam', '>=', $this->tgl_mulai);
        } elseif ($this->tgl_selesai) {
            $query->whereDate('tanggal_pinjam', '<=', $this->tgl_selesai);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        $periodeText = ($this->tgl_mulai && $this->tgl_selesai) 
            ? date('d/m/Y', strtotime($this->tgl_mulai)) . ' s/d ' . date('d/m/Y', strtotime($this->tgl_selesai)) 
            : 'SEMUA PERIODE';

        $statusText = $this->status ? strtoupper($this->status) : 'SEMUA STATUS';

        return [
            ['LAPORAN REKAPITULASI PEMINJAMAN ASET & BARANG SARPRAS'],
            ['SEKOLAH TINGGI ILMU KESEHATAN PANTI WALUYA MALANG'],
            ['BAGIAN SARANA DAN PRASARANA (SARPRAS)'],
            ['PERIODE TANGGAL : ' . $periodeText],
            ['STATUS FILTER   : ' . $statusText],
            ['TANGGAL EKSPOR  : ' . date('d-m-Y H:i') . ' WIB'],
            [],
            [
                'No',
                'Kode Pinjam',
                'Nama Peminjam',
                'Kategori',
                'NIM / NIDN',
                'Prodi / Unit',
                'No. WhatsApp',
                'Daftar Aset / Barang',
                'Total Unit',
                'Tgl Pinjam',
                'Tenggat Kembali',
                'Tgl Diambil',
                'Tgl Selesai Kembali',
                'Status',
                'Keperluan',
                'Kondisi Kembali'
            ]
        ];
    }

    public function map($p): array
    {
        $this->rowNumber++;

        $daftarAset = '';
        if ($p->details && $p->details->count() > 0) {
            $daftarAset = $p->details->map(function($d) {
                return ($d->barang->nama_barang ?? 'Aset') . " ({$d->jumlah} unit)";
            })->implode(', ');
        } else {
            $daftarAset = ($p->barang->nama_barang ?? '-') . " ({$p->jumlah} unit)";
        }

        return [
            $this->rowNumber,
            $p->kode_peminjaman,
            $p->nama_peminjam,
            $p->kategori_peminjam ?? 'Mahasiswa',
            $p->nomor_identitas,
            $p->prodi_unit ?? '-',
            $p->kontak_peminjam ?? '-',
            $daftarAset,
            $p->jumlah,
            date('d/m/Y', strtotime($p->tanggal_pinjam)),
            date('d/m/Y', strtotime($p->tenggat_kembali)),
            $p->tanggal_diambil ? $p->tanggal_diambil->format('d/m/Y H:i') : '-',
            $p->tanggal_kembali ? date('d/m/Y', strtotime($p->tanggal_kembali)) : '-',
            $p->status,
            $p->keperluan ?? '-',
            $p->kondisi_kembali ?? '-',
        ];
    }

    public function title(): string
    {
        return 'Laporan Peminjaman Aset';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:A6')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(13);
        $sheet->getStyle('A8:P8')->getFont()->setBold(true);
        return [];
    }
}