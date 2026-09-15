<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Ruangan;
use App\Models\User;
use Tests\TestCase;

class MasterDataBarangTest extends TestCase
{
    protected User $user;
    protected Kategori $kategori;
    protected Ruangan $ruangan;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'sarpras']);
        $this->kategori = Kategori::factory()->create();
        $this->ruangan = Ruangan::factory()->create(['kode_ruangan' => 'LAB-KEP-01']);
    }

    public function test_barang_index_accessible_and_filters_work(): void
    {
        $barang = Barang::factory()->create([
            'nama_barang' => 'Tensimeter Digital OMRON',
            'ruangan_id' => $this->ruangan->id,
            'kategori_id' => $this->kategori->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('barang.index'));
        $response->assertStatus(200);
        $response->assertSee('Tensimeter Digital OMRON');

        // Filter search
        $responseSearch = $this->actingAs($this->user)->get(route('barang.index', ['search' => 'Tensimeter']));
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('Tensimeter Digital OMRON');
    }

    public function test_barang_can_be_created_updated_and_deleted(): void
    {
        // Store
        $response = $this->actingAs($this->user)->post(route('barang.store'), [
            'nama_barang' => 'Stetoskop Littmann Classic',
            'kategori_id' => $this->kategori->id,
            'ruangan_id' => $this->ruangan->id,
            'jumlah' => 10,
            'kondisi' => 'Baik',
            'bisa_dipinjam' => '1',
            'tahun_pengadaan' => '2024',
            'keterangan' => 'Pengadaan Laboratorium Keperawatan',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('barangs', [
            'nama_barang' => 'Stetoskop Littmann Classic',
            'jumlah' => 10,
            'bisa_dipinjam' => 1,
        ]);

        $barang = Barang::where('nama_barang', 'Stetoskop Littmann Classic')->first();

        // Update
        $responseUpdate = $this->actingAs($this->user)->put(route('barang.update', $barang->id), [
            'kode_barang' => $barang->kode_barang,
            'nama_barang' => 'Stetoskop Littmann Classic III Updated',
            'kategori_id' => $this->kategori->id,
            'ruangan_id' => $this->ruangan->id,
            'jumlah' => 12,
            'kondisi' => 'Baik',
            'bisa_dipinjam' => '1',
            'tahun_pengadaan' => '2024',
        ]);
        $responseUpdate->assertRedirect();
        $this->assertDatabaseHas('barangs', [
            'id' => $barang->id,
            'nama_barang' => 'Stetoskop Littmann Classic III Updated',
            'jumlah' => 12,
        ]);

        // Destroy
        $responseDelete = $this->actingAs($this->user)->delete(route('barang.destroy', $barang->id));
        $responseDelete->assertRedirect();
        $this->assertDatabaseMissing('barangs', ['id' => $barang->id]);
    }

    public function test_barang_pindah_ruangan_mutasi(): void
    {
        $ruanganTujuan = Ruangan::factory()->create(['kode_ruangan' => 'LAB-BID-02', 'nama_ruangan' => 'Lab Kebidanan']);
        $barang = Barang::factory()->create([
            'ruangan_id' => $this->ruangan->id,
            'jumlah' => 10,
        ]);

        $response = $this->actingAs($this->user)->post(route('barang.pindah', $barang->id), [
            'ruangan_tujuan_id' => $ruanganTujuan->id,
            'jumlah' => 4,
            'keterangan' => 'Mutasi praktikum terpadu',
        ]);

        $response->assertRedirect();

        // Stok asal berkurang menjadi 6
        $this->assertDatabaseHas('barangs', [
            'id' => $barang->id,
            'jumlah' => 6,
        ]);

        // Ada aset baru di ruangan tujuan dengan jumlah 4
        $this->assertDatabaseHas('barangs', [
            'ruangan_id' => $ruanganTujuan->id,
            'nama_barang' => $barang->nama_barang,
            'jumlah' => 4,
        ]);

        // Riwayat mutasi tercatat
        $this->assertDatabaseHas('mutasis', [
            'barang_id' => $barang->id,
            'ruangan_asal_id' => $this->ruangan->id,
            'ruangan_tujuan_id' => $ruanganTujuan->id,
            'jumlah' => 4,
        ]);
    }

    public function test_barang_kurangi_stok_records_damage_or_disposal(): void
    {
        $barang = Barang::factory()->create([
            'ruangan_id' => $this->ruangan->id,
            'jumlah' => 10,
        ]);

        $response = $this->actingAs($this->user)->post(route('barang.kurangi', $barang->id), [
            'jumlah' => 2,
            'keterangan' => 'Pecah saat praktikum',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('barangs', [
            'id' => $barang->id,
            'jumlah' => 8,
        ]);

        $this->assertDatabaseHas('mutasis', [
            'barang_id' => $barang->id,
            'jenis_mutasi' => 'Pengurangan/Rusak',
            'jumlah' => 2,
        ]);
    }

    public function test_barang_label_cetak_single_and_massal(): void
    {
        $barang = Barang::factory()->create(['ruangan_id' => $this->ruangan->id]);

        $responseSingle = $this->actingAs($this->user)->get(route('barang.label', $barang->id));
        $responseSingle->assertStatus(200);

        $responseMassal = $this->actingAs($this->user)->get(route('barang.label.massal'));
        $responseMassal->assertStatus(200);
    }
}
