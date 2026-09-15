<?php

namespace Tests\Feature;

use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use App\Models\User;
use Tests\TestCase;

class PeminjamanRuanganWorkflowTest extends TestCase
{
    protected User $petugas;
    protected Ruangan $ruangan;

    protected function setUp(): void
    {
        parent::setUp();
        $this->petugas = User::factory()->create(['role' => 'sarpras']);
        $this->ruangan = Ruangan::factory()->create([
            'kode_ruangan' => 'LAB-KEB-01',
            'nama_ruangan' => 'Lab Kebidanan Fisiologi',
            'bisa_dipinjam' => true,
        ]);
    }

    public function test_petugas_can_view_room_bookings_list(): void
    {
        $booking = PeminjamanRuangan::factory()->create([
            'ruangan_id' => $this->ruangan->id,
            'nama_peminjam' => 'Dwi Handayani',
            'status' => 'Menunggu',
        ]);

        $response = $this->actingAs($this->petugas)->get(route('peminjaman-ruangan.index'));
        $response->assertStatus(200);
        $response->assertSee('Dwi Handayani');
    }

    public function test_workflow_approve_serahkan_selesai_booking_ruangan(): void
    {
        // 1. Pengajuan booking
        $booking = PeminjamanRuangan::factory()->create([
            'ruangan_id' => $this->ruangan->id,
            'status' => 'Menunggu',
        ]);

        // 2. Approve oleh Sarpras
        $responseApprove = $this->actingAs($this->petugas)->post(route('peminjaman-ruangan.approve', $booking->id));
        $responseApprove->assertRedirect();
        $this->assertDatabaseHas('peminjaman_ruangans', [
            'id' => $booking->id,
            'status' => 'Disetujui',
        ]);

        // 3. Serahkan kunci / Masuk Ruangan (Digunakan)
        $responseSerahkan = $this->actingAs($this->petugas)->post(route('peminjaman-ruangan.serahkan', $booking->id));
        $responseSerahkan->assertRedirect();
        $this->assertDatabaseHas('peminjaman_ruangans', [
            'id' => $booking->id,
            'status' => 'Digunakan',
        ]);

        // 4. Pengembalian kunci / Selesai
        $responseSelesai = $this->actingAs($this->petugas)->post(route('peminjaman-ruangan.selesai', $booking->id), [
            'catatan_kondisi' => 'Ruangan bersih, AC dan lampu sudah dimatikan.',
        ]);
        $responseSelesai->assertRedirect();
        $this->assertDatabaseHas('peminjaman_ruangans', [
            'id' => $booking->id,
            'status' => 'Selesai',
        ]);
    }

    public function test_workflow_reject_booking_ruangan(): void
    {
        $booking = PeminjamanRuangan::factory()->create([
            'ruangan_id' => $this->ruangan->id,
            'status' => 'Menunggu',
        ]);

        $responseReject = $this->actingAs($this->petugas)->post(route('peminjaman-ruangan.reject', $booking->id), [
            'alasan_penolakan' => 'Ruangan sedang dalam proses sterilisasi rutin',
        ]);

        $responseReject->assertRedirect();
        $this->assertDatabaseHas('peminjaman_ruangans', [
            'id' => $booking->id,
            'status' => 'Ditolak',
            'alasan_penolakan' => 'Ruangan sedang dalam proses sterilisasi rutin',
        ]);
    }
}
