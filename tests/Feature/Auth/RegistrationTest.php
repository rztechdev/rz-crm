<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_registration_is_disabled(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(404);

        $postResponse = $this->post('/register', [
            'name' => 'Public Hacker',
            'email' => 'hacker@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $postResponse->assertStatus(404);
        $this->assertGuest();
    }

    public function test_admin_can_create_internal_user(): void
    {
        $admin = User::factory()->create([
            'email' => 'rzcompanyidn@gmail.com',
        ]);

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Staff Baru',
            'email' => 'staff@rzdigitalcreative.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'email' => 'staff@rzdigitalcreative.com',
        ]);
    }
}
