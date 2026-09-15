<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    public function test_it_admin_can_view_user_management(): void
    {
        $itUser = User::factory()->create(['role' => 'it']);

        $response = $this->actingAs($itUser)->get(route('user.index'));
        $response->assertStatus(200);
        $response->assertSee('Manajemen Pengguna');
    }

    public function test_non_it_user_forbidden_from_user_management(): void
    {
        $sarprasUser = User::factory()->create(['role' => 'sarpras']);

        $response = $this->actingAs($sarprasUser)->get(route('user.index'));
        $response->assertStatus(403);
    }

    public function test_it_admin_can_create_update_and_delete_user(): void
    {
        $itUser = User::factory()->create(['role' => 'it']);

        // Create
        $responseCreate = $this->actingAs($itUser)->post(route('user.store'), [
            'name' => 'Petugas Sarpras Baru',
            'email' => 'sarpras.baru@stikes.ac.id',
            'password' => 'password123',
            'role' => 'sarpras',
        ]);
        $responseCreate->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'sarpras.baru@stikes.ac.id']);

        $newUser = User::where('email', 'sarpras.baru@stikes.ac.id')->first();

        // Update
        $responseUpdate = $this->actingAs($itUser)->put(route('user.update', $newUser->id), [
            'name' => 'Petugas Sarpras Updated',
            'email' => 'sarpras.updated@stikes.ac.id',
            'role' => 'sarpras',
        ]);
        $responseUpdate->assertRedirect();
        $this->assertDatabaseHas('users', ['name' => 'Petugas Sarpras Updated']);

        // Delete
        $responseDelete = $this->actingAs($itUser)->delete(route('user.destroy', $newUser->id));
        $responseDelete->assertRedirect();
        $this->assertDatabaseMissing('users', ['id' => $newUser->id]);
    }
}
