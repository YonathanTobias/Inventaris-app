<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Ruangan;
use App\Models\User;
use Tests\TestCase;

class MasterDataKategoriRuanganTest extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'sarpras']);
    }

    public function test_kategori_index_accessible_by_authenticated_user(): void
    {
        Kategori::factory()->create(['nama_kategori' => 'Alat Laboratorium']);

        $response = $this->actingAs($this->user)->get(route('kategori.index'));

        $response->assertStatus(200);
        $response->assertSee('Alat Laboratorium');
    }

    public function test_kategori_can_be_created_updated_and_deleted(): void
    {
        // Store
        $response = $this->actingAs($this->user)->post(route('kategori.store'), [
            'nama_kategori' => 'Elektronik Medis',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('kategoris', ['nama_kategori' => 'Elektronik Medis']);

        $kategori = Kategori::where('nama_kategori', 'Elektronik Medis')->first();

        // Update
        $responseUpdate = $this->actingAs($this->user)->put(route('kategori.update', $kategori->id), [
            'nama_kategori' => 'Peralatan Medis Digital',
        ]);
        $responseUpdate->assertRedirect();
        $this->assertDatabaseHas('kategoris', ['nama_kategori' => 'Peralatan Medis Digital']);

        // Destroy
        $responseDelete = $this->actingAs($this->user)->delete(route('kategori.destroy', $kategori->id));
        $responseDelete->assertRedirect();
        $this->assertDatabaseMissing('kategoris', ['id' => $kategori->id]);
    }

    public function test_ruangan_index_accessible_by_authenticated_user(): void
    {
        Ruangan::factory()->create([
            'kode_ruangan' => 'LAB-KEP-01',
            'nama_ruangan' => 'Lab Keperawatan Dasar',
        ]);

        $response = $this->actingAs($this->user)->get(route('ruangan.index'));

        $response->assertStatus(200);
        $response->assertSee('Lab Keperawatan Dasar');
        $response->assertSee('LAB-KEP-01');
    }

    public function test_ruangan_can_be_created_updated_and_deleted(): void
    {
        // Store
        $response = $this->actingAs($this->user)->post(route('ruangan.store'), [
            'kode_ruangan' => 'RNG-FARM-01',
            'nama_ruangan' => 'Laboratorium Farmasi',
            'bisa_dipinjam' => '1',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('ruangans', [
            'kode_ruangan' => 'RNG-FARM-01',
            'nama_ruangan' => 'Laboratorium Farmasi',
            'bisa_dipinjam' => 1,
        ]);

        $ruangan = Ruangan::where('kode_ruangan', 'RNG-FARM-01')->first();

        // Update
        $responseUpdate = $this->actingAs($this->user)->put(route('ruangan.update', $ruangan->id), [
            'kode_ruangan' => 'RNG-FARM-01-REV',
            'nama_ruangan' => 'Laboratorium Farmakologi & Herbal',
            'bisa_dipinjam' => '0',
        ]);
        $responseUpdate->assertRedirect();
        $this->assertDatabaseHas('ruangans', [
            'kode_ruangan' => 'RNG-FARM-01-REV',
            'bisa_dipinjam' => 0,
        ]);

        // Destroy
        $responseDelete = $this->actingAs($this->user)->delete(route('ruangan.destroy', $ruangan->id));
        $responseDelete->assertRedirect();
        $this->assertDatabaseMissing('ruangans', ['id' => $ruangan->id]);
    }

    public function test_ruangan_export_excel_can_be_downloaded(): void
    {
        Ruangan::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('ruangan.export'));

        $response->assertStatus(200);
        $response->assertHeader('content-disposition');
    }

    public function test_ruangan_label_can_be_viewed(): void
    {
        $ruangan = Ruangan::factory()->create();

        $response = $this->actingAs($this->user)->get(route('ruangan.label', $ruangan->id));

        $response->assertStatus(200);
    }
}
