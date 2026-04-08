<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_access_users_index()
    {
        // Create admin user with 'admin' role (exists in ENUM)
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get(route('users.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function non_admin_cannot_access_users_index()
    {
        // Create cashier user with 'cashier' role (exists in ENUM)
        $user = User::factory()->create([
            'role' => 'cashier',
        ]);

        $response = $this->actingAs($user)->get(route('users.index'));

        $response->assertStatus(403);
    }
}