<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Admin can login
     */
    public function test_admin_can_login(): void
    {
        Admin::factory()->create(['email' => 'admin@dev.test']);

        $data = ['email' => 'admin@dev.test', 'password' => 'password'];

        $response = $this->post('/api/admin/auth/login', $data);

        $response->assertStatus(200);
    }

    /**
     * Admin can logout
     */
    public function test_admin_can_logout(): void
    {
        $this->withoutExceptionHandling();

        $admin = Admin::factory()->create(['email' => 'admin@dev.test']);
        Sanctum::actingAs($admin);

        $response = $this->post('api/admin/auth/logout');

        $response->assertStatus(200);
        $this->assertCount(0, $admin->tokens);
    }

    /**
     * Admin can change password
     */
    public function test_admin_can_change_password(): void
    {
        $this->withoutExceptionHandling();

        $admin = Admin::factory()->create(['email' => 'admin@dev.test']);
        Sanctum::actingAs($admin);

        $data = ['password' => 'password321', 'password_confirm' => 'password321'];

        $response = $this->post('api/admin/auth/password/change', $data);

        $response->assertStatus(200);
        $this->assertEquals(false, Hash::check('password', $admin->password));
        $this->assertEquals(true, Hash::check('password321', $admin->password));
    }
}
