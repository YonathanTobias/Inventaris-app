<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('SPARTA-PW');
    }

    public function test_user_can_login_using_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@stikes.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'sarpras',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@stikes.ac.id',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('barang.index'));
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@stikes.ac.id',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'admin@stikes.ac.id',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }

    public function test_guest_redirected_when_accessing_protected_route(): void
    {
        $response = $this->get('/barang');
        $response->assertRedirect('/login');
    }
}
