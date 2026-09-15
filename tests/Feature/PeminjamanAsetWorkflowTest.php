<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\Ruangan;
use App\Models\User;
use Tests\TestCase;

class PeminjamanAsetWorkflowTest extends TestCase
{
    protected User $petugas;
    protected Ruangan $ruangan;
    protected Kategori $kategori;
    protected Barang $barang;

    protected function setUp(): void
    {
        parent::setUp();
        $this->petugas = User::factory()->create(['role' => 'sarpras']);
        $this->kategori = Kategori::factory()->create();
        $this->ruangan = Ruangan::factory()->create(['kode_ruangan' => 'LAB-KEP-01']);
        $this->barang = Barang::factory()->create([
            'ruangan_id' => $this->ruangan->id,
            'kategori_id' => $this->kategori->id,
            'jumlah' => 10,
            'bisa_dipinjam' => true,
        ]);
    }

    public function test_petugas_can_view_peminjaman_list(): void
    {
        $peminjaman = Peminjaman::factory()->create([
            'barang_id' => $this->barang->id,
            'nama_peminjam' => 'Rina Marlina',
            'status' => 'Menunggu',
        ]);

        $response = $this->actingAs($this->petugas)->get(route('peminjaman.index'));
        $response->assertStatus(200);
        $response->assertSee('Rina Marlina');
    }

    public function test_workflow_approve_serahkan_kembalikan_aset(): void
    {
        // 1. Pengajuan baru
        $peminjaman = Peminjaman::factory()->create([
            'barang_id' => $this->barang->id,
            'jumlah' => 3,
            'status' => 'Menunggu',
        ]);
        PeminjamanDetail::factory()->create([
            'peminjaman_id' => $peminjaman->id,
            'barang_id' => $this->barang->id,
            'jumlah' => 3,
        ]);

        // 2. Approve oleh Sarpras
        $responseApprove = $this->actingAs($this->petugas)->post(route('peminjaman.approve', $peminjaman->id));
        $responseApprove->assertRedirect();
        $this->assertDatabaseHas('peminjamans', [
            'id' => $peminjaman->id,
            'status' => 'Disetujui',
        ]);

        // 3. Serahkan barang fisik ke peminjam (Stok terpotong 10 -> 7)
        $responseSerahkan = $this->actingAs($this->petugas)->post(route('peminjaman.serahkan', $peminjaman->id));
        $responseSerahkan->assertRedirect();
        $this->assertDatabaseHas('peminjamans', [
            'id' => $peminjaman->id,
            'status' => 'Diambil',
        ]);
        $this->assertDatabaseHas('barangs', [
            'id' => $this->barang->id,
            'jumlah' => 7,
        ]);

        // 4. Pengembalian barang (Stok dipulihkan 7 -> 10)
        $responseKembalikan = $this->actingAs($this->petugas)->post(route('peminjaman.kembalikan', $peminjaman->id), [
            'tanggal_kembali' => now()->toDateString(),
            'kondisi_kembali' => 'Baik',
            'catatan' => 'Barang kembali dalam kondisi lengkap dan bersih',
        ]);
        $responseKembalikan->assertRedirect();
        $this->assertDatabaseHas('peminjamans', [
            'id' => $peminjaman->id,
            'status' => 'Kembali',
        ]);
        $this->assertDatabaseHas('barangs', [
            'id' => $this->barang->id,
            'jumlah' => 10,
        ]);
    }

    public function test_workflow_reject_peminjaman_aset(): void
    {
        $peminjaman = Peminjaman::factory()->create([
            'barang_id' => $this->barang->id,
            'status' => 'Menunggu',
        ]);

        $responseReject = $this->actingAs($this->petugas)->post(route('peminjaman.reject', $peminjaman->id), [
            'alasan_penolakan' => 'Aset sedang dialokasikan untuk praktikum akreditasi',
        ]);

        $responseReject->assertRedirect();
        $this->assertDatabaseHas('peminjamans', [
            'id' => $peminjaman->id,
            'status' => 'Ditolak',
            'alasan_penolakan' => 'Aset sedang dialokasikan untuk praktikum akreditasi',
        ]);
    }
}
