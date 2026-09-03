<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Mutasi;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StikesPantiWaluyaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin STIKES Panti Waluya
        User::updateOrCreate(
            ['email' => 'adminit@pantiwaluya.ac.id'],
            [
                'name'     => 'Unit IT & SIM Panti Waluya',
                'password' => Hash::make('password123'),
                'role'     => 'it',
            ]
        );

        User::updateOrCreate(
            ['email' => 'sarpras@pantiwaluya.ac.id'],
            [
                'name'     => 'Bagian Sarana & Prasarana (SARPRAS)',
                'password' => Hash::make('password123'),
                'role'     => 'sarpras',
            ]
        );

        // Akun demo umum
        User::updateOrCreate(
            ['email' => 'adminit@inventaris.com'],
            [
                'name'     => 'Admin IT (Super User)',
                'password' => Hash::make('password123'),
                'role'     => 'it',
            ]
        );

        User::updateOrCreate(
            ['email' => 'sarpras@inventaris.com'],
            [
                'name'     => 'Admin Sarpras Kampus',
                'password' => Hash::make('password123'),
                'role'     => 'sarpras',
            ]
        );

        // 2. Data Master Ruangan STIKES Panti Waluya
        $ruanganData = [
            [
                'kode_ruangan' => 'LAB-KMB-01',
                'nama_ruangan' => 'Lab Keperawatan Medikal Bedah (KMB)',
            ],
            [
                'kode_ruangan' => 'LAB-MAT-02',
                'nama_ruangan' => 'Lab Maternitas & Anak',
            ],
            [
                'kode_ruangan' => 'LAB-GDR-03',
                'nama_ruangan' => 'Lab Gawat Darurat & Kritis (GADAR)',
            ],
            [
                'kode_ruangan' => 'LAB-FRM-04',
                'nama_ruangan' => 'Lab Farmakologi & Mikrobiologi',
            ],
            [
                'kode_ruangan' => 'CBT-CTR-05',
                'nama_ruangan' => 'CBT Center & Lab Komputer',
            ],
            [
                'kode_ruangan' => 'PERPUS-01',
                'nama_ruangan' => 'Perpustakaan Medis & Kesehatan',
            ],
            [
                'kode_ruangan' => 'RK-TEORI-2A',
                'nama_ruangan' => 'Ruang Kuliah Teori 2A (Lt. 2)',
            ],
            [
                'kode_ruangan' => 'AULA-STIKES',
                'nama_ruangan' => 'Aula Utama Santo Yosef',
            ],
        ];

        $ruangans = [];
        foreach ($ruanganData as $r) {
            $ruangans[$r['kode_ruangan']] = Ruangan::updateOrCreate(
                ['kode_ruangan' => $r['kode_ruangan']],
                $r
            );
        }

        // 3. Data Master Kategori Barang / Aset STIKES
        $kategoriData = [
            'Alat Kesehatan & Medis (Alkes)',
            'Manikin & Phantom Praktikum',
            'Perangkat IT & Multimedia',
            'Mebel & Furnitur Ruang Lab',
            'Peralatan Laboratorium & Mikroskop',
            'Perlengkapan Simulasi Bencana & GADAR',
        ];

        $kategoris = [];
        foreach ($kategoriData as $kat) {
            $kategoris[$kat] = Kategori::updateOrCreate(
                ['nama_kategori' => $kat],
                ['nama_kategori' => $kat]
            );
        }

        // 4. Data Barang Aset Nyata STIKES Panti Waluya
        $barangData = [
            // Lab GADAR
            [
                'kode_barang'     => 'AST-PW-GDR-001',
                'nama_barang'     => 'Manikin Resusitasi Jantung Paru (CPR Adult Laerdal QCPR)',
                'kategori_id'     => $kategoris['Manikin & Phantom Praktikum']->id,
                'ruangan_id'      => $ruangans['LAB-GDR-03']->id,
                'jumlah'          => 4,
                'kondisi'         => 'Baik',
                'tahun_pengadaan' => '2024',
                'keterangan'      => 'Dilengkapi sensor indikator kompresi dada digital dan tas carrier',
            ],
            [
                'kode_barang'     => 'AST-PW-GDR-002',
                'nama_barang'     => 'Automated External Defibrillator (AED) Simulator Trainer',
                'kategori_id'     => $kategoris['Alat Kesehatan & Medis (Alkes)']->id,
                'ruangan_id'      => $ruangans['LAB-GDR-03']->id,
                'jumlah'          => 3,
                'kondisi'         => 'Baik',
                'tahun_pengadaan' => '2023',
                'keterangan'      => 'AED Training Device merk Philips HeartStart FRx + remote control',
            ],
            [
                'kode_barang'     => 'AST-PW-GDR-003',
                'nama_barang'     => 'Brankar Pasien Emergency / Spinal Board Trauma',
                'kategori_id'     => $kategoris['Perlengkapan Simulasi Bencana & GADAR']->id,
                'ruangan_id'      => $ruangans['LAB-GDR-03']->id,
                'jumlah'          => 2,
                'kondisi'         => 'Rusak Ringan',
                'tahun_pengadaan' => '2022',
                'keterangan'      => 'Kunci roda kanan longgar, perlu pengencangan baut hidrolik',
            ],

            // Lab KMB
            [
                'kode_barang'     => 'AST-PW-KMB-001',
                'nama_barang'     => 'Bed Pasien Elektrik 3 Crank dengan Matras Anti Dekubitus',
                'kategori_id'     => $kategoris['Mebel & Furnitur Ruang Lab']->id,
                'ruangan_id'      => $ruangans['LAB-KMB-01']->id,
                'jumlah'          => 8,
                'kondisi'         => 'Baik',
                'tahun_pengadaan' => '2023',
                'keterangan'      => 'Hospital Bed Paramont Medical lengkap dengan tiang infus stainless',
            ],
            [
                'kode_barang'     => 'AST-PW-KMB-002',
                'nama_barang'     => 'Electrocardiograph (EKG) 12-Lead Fukuda Denshi',
                'kategori_id'     => $kategoris['Alat Kesehatan & Medis (Alkes)']->id,
                'ruangan_id'      => $ruangans['LAB-KMB-01']->id,
                'jumlah'          => 2,
                'kondisi'         => 'Baik',
                'tahun_pengadaan' => '2024',
                'keterangan'      => 'Unit mesin EKG beserta kabel elektroda dada (suction bulb) & ekstremitas',
            ],
            [
                'kode_barang'     => 'AST-PW-KMB-003',
                'nama_barang'     => 'Syringe Pump & Infusion Pump Terumo TE-SS700',
                'kategori_id'     => $kategoris['Alat Kesehatan & Medis (Alkes)']->id,
                'ruangan_id'      => $ruangans['LAB-KMB-01']->id,
                'jumlah'          => 6,
                'kondisi'         => 'Baik',
                'tahun_pengadaan' => '2023',
                'keterangan'      => 'Alat pengatur tetesan cairan infus & obat presisi tinggi',
            ],
            [
                'kode_barang'     => 'AST-PW-KMB-004',
                'nama_barang'     => 'Tensimeter Aneroid Mobile Stand Riester Big Ben',
                'kategori_id'     => $kategoris['Alat Kesehatan & Medis (Alkes)']->id,
                'ruangan_id'      => $ruangans['LAB-KMB-01']->id,
                'jumlah'          => 5,
                'kondisi'         => 'Rusak Ringan',
                'tahun_pengadaan' => '2021',
                'keterangan'      => '1 unit selang karet manset bocor halus, 4 unit kondisi normal',
            ],

            // Lab Maternitas & Anak
            [
                'kode_barang'     => 'AST-PW-MAT-001',
                'nama_barang'     => 'Phantom Persalinan & Pelvis Anatomi Lengkap',
                'kategori_id'     => $kategoris['Manikin & Phantom Praktikum']->id,
                'ruangan_id'      => $ruangans['LAB-MAT-02']->id,
                'jumlah'          => 3,
                'kondisi'         => 'Baik',
                'tahun_pengadaan' => '2024',
                'keterangan'      => 'Model peraga mekanisme persalinan normal, fetus, tali pusat & plasenta',
            ],
            [
                'kode_barang'     => 'AST-PW-MAT-002',
                'nama_barang'     => 'Infant Radiant Warmer & Fototerapi Bayi',
                'kategori_id'     => $kategoris['Alat Kesehatan & Medis (Alkes)']->id,
                'ruangan_id'      => $ruangans['LAB-MAT-02']->id,
                'jumlah'          => 2,
                'kondisi'         => 'Baik',
                'tahun_pengadaan' => '2022',
                'keterangan'      => 'Alat pemanas dan penghangat perawatan bayi baru lahir / BBLR',
            ],
            [
                'kode_barang'     => 'AST-PW-MAT-003',
                'nama_barang'     => 'Doppler Fetal Heart Detector Bistos BT-200',
                'kategori_id'     => $kategoris['Alat Kesehatan & Medis (Alkes)']->id,
                'ruangan_id'      => $ruangans['LAB-MAT-02']->id,
                'jumlah'          => 5,
                'kondisi'         => 'Baik',
                'tahun_pengadaan' => '2023',
                'keterangan'      => 'Pendeteksi detak jantung janin portabel dengan display LCD & audio',
            ],

            // Lab Farmasi & Mikrobiologi
            [
                'kode_barang'     => 'AST-PW-FRM-001',
                'nama_barang'     => 'Mikroskop Binokuler Olympus CX23 LED',
                'kategori_id'     => $kategoris['Peralatan Laboratorium & Mikroskop']->id,
                'ruangan_id'      => $ruangans['LAB-FRM-04']->id,
                'jumlah'          => 12,
                'kondisi'         => 'Baik',
                'tahun_pengadaan' => '2023',
                'keterangan'      => 'Lensa objektif 4x, 10x, 40x, 100x oil immersion + cover pelindung debu',
            ],
            [
                'kode_barang'     => 'AST-PW-FRM-002',
                'nama_barang'     => 'Timbangan Analitik Digital Presisi 0.0001g Ohaus',
                'kategori_id'     => $kategoris['Peralatan Laboratorium & Mikroskop']->id,
                'ruangan_id'      => $ruangans['LAB-FRM-04']->id,
                'jumlah'          => 4,
                'kondisi'         => 'Baik',
                'tahun_pengadaan' => '2024',
                'keterangan'      => 'Timbangan kalibrasi farmasi dengan penutup kaca draft shield',
            ],
            [
                'kode_barang'     => 'AST-PW-FRM-003',
                'nama_barang'     => 'Autoclave Sterilisator Basah 50 Liter All American',
                'kategori_id'     => $kategoris['Peralatan Laboratorium & Mikroskop']->id,
                'ruangan_id'      => $ruangans['LAB-FRM-04']->id,
                'jumlah'          => 2,
                'kondisi'         => 'Rusak Berat',
                'tahun_pengadaan' => '2019',
                'keterangan'      => 'Elemen pemanas rusak konslet dan katup tekanan perlu servis pabrikan',
            ],

            // CBT Center & IT
            [
                'kode_barang'     => 'AST-PW-CBT-001',
                'nama_barang'     => 'Laptop CBT Uji Kompetensi Ners ASUS ExpertBook B1400',
                'kategori_id'     => $kategoris['Perangkat IT & Multimedia']->id,
                'ruangan_id'      => $ruangans['CBT-CTR-05']->id,
                'jumlah'          => 50,
                'kondisi'         => 'Baik',
                'tahun_pengadaan' => '2024',
                'keterangan'      => 'Core i5-1135G7, RAM 8GB, SSD 512GB, Terpasang Safe Exam Browser (SEB)',
            ],
            [
                'kode_barang'     => 'AST-PW-CBT-002',
                'nama_barang'     => 'Server CBT Lokal Dell PowerEdge R450 Rackmount',
                'kategori_id'     => $kategoris['Perangkat IT & Multimedia']->id,
                'ruangan_id'      => $ruangans['CBT-CTR-05']->id,
                'jumlah'          => 2,
                'kondisi'         => 'Baik',
                'tahun_pengadaan' => '2023',
                'keterangan'      => 'Dual Xeon Silver, RAM 64GB ECC, RAID 10 SSD + UPS 3000VA',
            ],

            // Ruang Kuliah & Aula
            [
                'kode_barang'     => 'AST-PW-RK-001',
                'nama_barang'     => 'Proyektor Multimedia Epson EB-E500 3300 Lumens',
                'kategori_id'     => $kategoris['Perangkat IT & Multimedia']->id,
                'ruangan_id'      => $ruangans['RK-TEORI-2A']->id,
                'jumlah'          => 3,
                'kondisi'         => 'Baik',
                'tahun_pengadaan' => '2023',
                'keterangan'      => 'Bracket plafon terpasang + kabel HDMI 15 meter & pointer wireless',
            ],
            [
                'kode_barang'     => 'AST-PW-AULA-001',
                'nama_barang'     => 'Sound System Portable Wireless & Mic Conference Yamaha StagePas',
                'kategori_id'     => $kategoris['Perangkat IT & Multimedia']->id,
                'ruangan_id'      => $ruangans['AULA-STIKES']->id,
                'jumlah'          => 2,
                'kondisi'         => 'Baik',
                'tahun_pengadaan' => '2024',
                'keterangan'      => 'Digunakan untuk yudisium, capping day, seminar kesehatan, & kuliah pakar',
            ],
        ];

        $createdBarangs = [];
        foreach ($barangData as $b) {
            $createdBarangs[$b['kode_barang']] = Barang::updateOrCreate(
                ['kode_barang' => $b['kode_barang']],
                $b
            );
        }

        // 5. Riwayat Mutasi Sampel
        $gadarBarang = $createdBarangs['AST-PW-GDR-001'] ?? null;
        if ($gadarBarang) {
            Mutasi::updateOrCreate(
                [
                    'barang_id'   => $gadarBarang->id,
                    'created_at'  => now()->subDays(2),
                ],
                [
                    'jenis_mutasi'      => 'Pindah Ruangan',
                    'ruangan_asal_id'   => $ruangans['LAB-KMB-01']->id,
                    'ruangan_tujuan_id' => $ruangans['LAB-GDR-03']->id,
                    'jumlah'            => 2,
                    'keterangan'        => 'Persiapan OSCE Nasional Uji Kompetensi Keperawatan Gawat Darurat',
                ]
            );
        }

        $cbtBarang = $createdBarangs['AST-PW-CBT-001'] ?? null;
        if ($cbtBarang) {
            Mutasi::updateOrCreate(
                [
                    'barang_id'   => $cbtBarang->id,
                    'created_at'  => now()->subDays(5),
                ],
                [
                    'jenis_mutasi'      => 'Pindah Ruangan',
                    'ruangan_asal_id'   => $ruangans['CBT-CTR-05']->id,
                    'ruangan_tujuan_id' => $ruangans['RK-TEORI-2A']->id,
                    'jumlah'            => 5,
                    'keterangan'        => 'Peminjaman sementara untuk ujian mid-semester Prodi D3 Keperawatan',
                ]
            );
        }

        $autoclave = $createdBarangs['AST-PW-FRM-003'] ?? null;
        if ($autoclave) {
            Mutasi::updateOrCreate(
                [
                    'barang_id'   => $autoclave->id,
                    'created_at'  => now()->subDays(10),
                ],
                [
                    'jenis_mutasi'    => 'Pengurangan/Rusak',
                    'ruangan_asal_id' => $ruangans['LAB-FRM-04']->id,
                    'jumlah'          => 1,
                    'keterangan'      => 'Unit mengalami overheat dan katup bocor saat sterilisasi media mikrobiologi',
                ]
            );
        }
    }
}
