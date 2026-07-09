<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Umkm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::create([
            'name' => 'Pemilik Toko',
            'email' => 'owner_test@mail.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'email' => 'owner_test@mail.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/owner/dashboard');
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = User::create([
            'name' => 'Staf Nonaktif',
            'email' => 'staff_test@mail.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
            'status' => 'inactive',
        ]);

        $response = $this->post('/login', [
            'email' => 'staff_test@mail.com',
            'password' => 'password',
        ]);

        $this->assertGuest();
    }
}
