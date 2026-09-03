<?php

namespace App\Exports;

use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PeminjamanRuanganExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $tgl_mulai;
    protected $tgl_selesai;
    protected $ruangan_id;
    protected $status;
    protected $ruanganNama;
    protected $rowNumber = 0;

    public function __construct($tgl_mulai = null, $tgl_selesai = null, $ruangan_id = null, $status = null)
    {
        $this->tgl_mulai = $tgl_mulai;
        $this->tgl_selesai = $tgl_selesai;
        $this->ruangan_id = $ruangan_id;
        $this->status = $status;

        if ($ruangan_id) {
            $r = Ruangan::find($ruangan_id);
            $this->ruanganNama = $r ? $r->nama_ruangan : 'Per Ruangan';
        } else {
            $this->ruanganNama = 'Semua Ruangan';
        }
    }

    public function collection()
    {
        $query = PeminjamanRuangan::with(['ruangan', 'approver', 'petugasSerah']);

        if ($this->tgl_mulai && $this->tgl_selesai) {
            $query->whereBetween('tanggal_pemakaian', [$this->tgl_mulai, $this->tgl_selesai]);
        } elseif ($this->tgl_mulai) {
            $query->whereDate('tanggal_pemakaian', '>=', $this->tgl_mulai);
        } elseif ($this->tgl_selesai) {
            $query->whereDate('tanggal_pemakaian', '<=', $this->tgl_selesai);
        }

        if ($this->ruangan_id) {
            $query->where('ruangan_id', $this->ruangan_id);
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
            ['LAPORAN REKAPITULASI PEMINJAMAN & BOOKING RUANGAN'],
            ['SEKOLAH TINGGI ILMU KESEHATAN PANTI WALUYA MALANG'],
            ['BAGIAN SARANA DAN PRASARANA (SARPRAS)'],
            ['LOKASI / RUANGAN : ' . strtoupper($this->ruanganNama)],
            ['PERIODE TANGGAL : ' . $periodeText],
            ['STATUS FILTER   : ' . $statusText],
            ['TANGGAL EKSPOR  : ' . date('d-m-Y H:i') . ' WIB'],
            [],
            [
                'No',
                'Kode Booking',
                'Nama Pemohon',
                'Kategori',
                'NIM / NIDN',
                'Prodi / Unit',
                'No. WhatsApp',
                'Ruangan Kegiatan',
                'Kode Ruangan',
                'Tgl Pemakaian',
                'Jam Mulai',
                'Jam Selesai',
                'Waktu Dibuka Petugas',
                'Waktu Dikunci Petugas',
                'Status',
                'Keperluan / Acara',
                'Catatan Kondisi'
            ]
        ];
    }

    public function map($p): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $p->kode_booking,
            $p->nama_peminjam,
            $p->kategori_peminjam ?? 'Mahasiswa',
            $p->nomor_identitas,
            $p->prodi_unit ?? '-',
            $p->kontak_peminjam ?? '-',
            $p->ruangan->nama_ruangan ?? '-',
            $p->ruangan->kode_ruangan ?? '-',
            date('d/m/Y', strtotime($p->tanggal_pemakaian)),
            date('H:i', strtotime($p->jam_mulai)) . ' WIB',
            date('H:i', strtotime($p->jam_selesai)) . ' WIB',
            $p->waktu_masuk ? $p->waktu_masuk->format('d/m/Y H:i') : '-',
            $p->waktu_selesai ? $p->waktu_selesai->format('d/m/Y H:i') : '-',
            $p->status,
            $p->keperluan ?? '-',
            $p->catatan_kondisi ?? '-',
        ];
    }

    public function title(): string
    {
        return 'Laporan Peminjaman Ruangan';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:A7')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(13);
        $sheet->getStyle('A9:Q9')->getFont()->setBold(true);
        return [];
    }
}