<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\PeminjamanRuangan;
use App\Models\Pengaturan;
use App\Models\Ruangan;
use App\Models\User;
use Tests\TestCase;

class LaporanDanExportTest extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'sarpras']);
    }

    public function test_laporan_index_accessible_by_authenticated_user(): void
    {
        $ruangan = Ruangan::factory()->create();
        Barang::factory()->create(['ruangan_id' => $ruangan->id]);

        $response = $this->actingAs($this->user)->get(route('laporan.index'));
        $response->assertStatus(200);
        $response->assertSee('Laporan');
    }

    public function test_export_excel_aset(): void
    {
        Barang::factory()->count(2)->create();

        $response = $this->actingAs($this->user)->get(route('laporan.export'));
        $response->assertStatus(200);
        $response->assertHeader('content-disposition');
    }

    public function test_export_excel_peminjaman_aset(): void
    {
        Peminjaman::factory()->count(2)->create();

        $response = $this->actingAs($this->user)->get(route('laporan.peminjaman-aset.export'));
        $response->assertStatus(200);
        $response->assertHeader('content-disposition');
    }

    public function test_export_excel_peminjaman_ruangan(): void
    {
        PeminjamanRuangan::factory()->count(2)->create();

        $response = $this->actingAs($this->user)->get(route('laporan.peminjaman-ruangan.export'));
        $response->assertStatus(200);
        $response->assertHeader('content-disposition');
    }

    public function test_update_ttd_pengaturan(): void
    {
        $response = $this->actingAs($this->user)->post(route('laporan.ttd.update'), [
            'nama_ketua' => 'Dr. Maria Goretti, M.Kes',
            'nip_ketua' => '197001011995032001',
            'nama_kabag_sarpras' => 'Yohanes Bosco, S.Kom',
            'nip_kabag_sarpras' => '198005052005011002',
            'kota_dokumen' => 'Malang',
        ]);

        $response->assertRedirect();
        $this->assertEquals('Dr. Maria Goretti, M.Kes', Pengaturan::get('nama_ketua'));
        $this->assertEquals('Yohanes Bosco, S.Kom', Pengaturan::get('nama_kabag_sarpras'));
    }
}
