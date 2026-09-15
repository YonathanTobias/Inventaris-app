<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use Tests\TestCase;

class PortalPublikTest extends TestCase
{
    protected Ruangan $ruangan;
    protected Kategori $kategori;
    protected Barang $barang;

    protected function setUp(): void
    {
        parent::setUp();
        $this->kategori = Kategori::factory()->create();
        $this->ruangan = Ruangan::factory()->create(['kode_ruangan' => 'LAB-KEP-01', 'bisa_dipinjam' => true]);
        $this->barang = Barang::factory()->create([
            'ruangan_id' => $this->ruangan->id,
            'kategori_id' => $this->kategori->id,
            'bisa_dipinjam' => true,
            'jumlah' => 10,
        ]);
    }

    public function test_portal_publik_homepage_accessible(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('SPARTA-PW');
        $response->assertSee($this->barang->nama_barang);
        $response->assertSee($this->ruangan->nama_ruangan);
    }

    public function test_publik_can_submit_asset_loan_request(): void
    {
        $cartData = json_encode([
            [
                'barang_id' => $this->barang->id,
                'jumlah' => 2,
            ]
        ]);

        $response = $this->post(route('publik.store'), [
            'kategori_peminjam' => 'Mahasiswa',
            'nama_peminjam' => 'Andi Wijaya',
            'nomor_identitas' => '202401001',
            'prodi_unit' => 'S1 Keperawatan',
            'kontak_peminjam' => '081234567890',
            'tanggal_pinjam' => now()->toDateString(),
            'tenggat_kembali' => now()->addDays(2)->toDateString(),
            'keperluan' => 'Praktik Mandiri OSCE',
            'cart_data' => $cartData,
        ]);

        $this->assertDatabaseHas('peminjamans', [
            'nama_peminjam' => 'Andi Wijaya',
            'nomor_identitas' => '202401001',
            'status' => 'Menunggu',
        ]);

        $peminjaman = Peminjaman::where('nama_peminjam', 'Andi Wijaya')->first();
        $response->assertRedirect(route('publik.sukses', $peminjaman->kode_peminjaman));

        $this->assertDatabaseHas('peminjaman_details', [
            'peminjaman_id' => $peminjaman->id,
            'barang_id' => $this->barang->id,
            'jumlah' => 2,
        ]);
    }

    public function test_publik_can_submit_room_booking_request(): void
    {
        $response = $this->post(route('publik.ruangan.store'), [
            'ruangan_id' => $this->ruangan->id,
            'kategori_peminjam' => 'Dosen',
            'nama_peminjam' => 'Ns. Budi Santoso, M.Kep',
            'nomor_identitas' => '198501012010011001',
            'prodi_unit' => 'S1 Keperawatan',
            'kontak_peminjam' => '081298765432',
            'tanggal_pemakaian' => now()->addDays(1)->toDateString(),
            'jam_mulai' => '09:00',
            'jam_selesai' => '12:00',
            'keperluan' => 'Ujian Praktik Keperawatan Medikal Bedah',
        ]);

        $this->assertDatabaseHas('peminjaman_ruangans', [
            'ruangan_id' => $this->ruangan->id,
            'nama_peminjam' => 'Ns. Budi Santoso, M.Kep',
            'status' => 'Menunggu',
        ]);

        $booking = PeminjamanRuangan::where('nama_peminjam', 'Ns. Budi Santoso, M.Kep')->first();
        $response->assertRedirect(route('publik.ruangan.sukses', $booking->kode_booking));
    }

    public function test_publik_can_track_submission_status(): void
    {
        $peminjaman = Peminjaman::factory()->create([
            'nama_peminjam' => 'Citra Lestari',
            'kode_peminjaman' => 'PJ-TEST-TRACK-01',
        ]);

        $response = $this->get(route('publik.lacak', ['keyword' => 'PJ-TEST-TRACK-01']));
        $response->assertStatus(200);
        $response->assertSee('Citra Lestari');
    }

    public function test_api_jadwal_ruangan_returns_active_bookings(): void
    {
        $targetDate = now()->addDays(2)->toDateString();
        $booking = PeminjamanRuangan::factory()->create([
            'ruangan_id' => $this->ruangan->id,
            'tanggal_pemakaian' => $targetDate,
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'nama_peminjam' => 'Dokter Penguji',
            'status' => 'Disetujui',
        ]);

        $response = $this->getJson(route('publik.ruangan.jadwal', [
            'ruangan_id' => $this->ruangan->id,
            'tanggal' => $targetDate,
        ]));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'nama_peminjam' => 'Dokter Penguji',
            'status' => 'Disetujui',
        ]);
    }

    public function test_publik_verifikasi_qr_scan_asset(): void
    {
        $response = $this->get(route('aset.cek', $this->barang->kode_barang));
        $response->assertStatus(200);
        $response->assertSee($this->barang->nama_barang);
        $response->assertSee($this->barang->kode_barang);
    }
}
