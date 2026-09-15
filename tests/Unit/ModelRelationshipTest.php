<?php

namespace Tests\Unit;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Mutasi;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use App\Models\User;
use Tests\TestCase;

class ModelRelationshipTest extends TestCase
{
    public function test_barang_relationships(): void
    {
        $kategori = Kategori::factory()->create();
        $ruangan = Ruangan::factory()->create();
        $barang = Barang::factory()->create([
            'kategori_id' => $kategori->id,
            'ruangan_id' => $ruangan->id,
        ]);

        $this->assertInstanceOf(Kategori::class, $barang->kategori);
        $this->assertEquals($kategori->id, $barang->kategori->id);

        $this->assertInstanceOf(Ruangan::class, $barang->ruangan);
        $this->assertEquals($ruangan->id, $barang->ruangan->id);
    }

    public function test_peminjaman_and_detail_relationships(): void
    {
        $user = User::factory()->create();
        $barang = Barang::factory()->create();
        $peminjaman = Peminjaman::factory()->create([
            'petugas_id' => $user->id,
            'barang_id' => $barang->id,
        ]);

        $detail = PeminjamanDetail::factory()->create([
            'peminjaman_id' => $peminjaman->id,
            'barang_id' => $barang->id,
        ]);

        $this->assertInstanceOf(User::class, $peminjaman->petugas);
        $this->assertTrue($peminjaman->details->contains($detail));
        $this->assertEquals($peminjaman->id, $detail->peminjaman->id);
    }

    public function test_peminjaman_ruangan_relationship(): void
    {
        $ruangan = Ruangan::factory()->create();
        $booking = PeminjamanRuangan::factory()->create([
            'ruangan_id' => $ruangan->id,
        ]);

        $this->assertInstanceOf(Ruangan::class, $booking->ruangan);
        $this->assertEquals($ruangan->id, $booking->ruangan->id);
    }
}
