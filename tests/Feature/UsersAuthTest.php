<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertNull;

class UsersAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Basic setup
     */
    protected function setUp(): void
    {
        parent::setUp();
        User::factory()->create();
    }

    /**
     * Login test user using Sanctum
     */
    private function testUser(): User
    {
        // Login test user using Sanctum
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        return $user;
    }

    /**
     * User can login using email and password
     */
    public function test_user_can_login(): void
    {
        $this->withoutExceptionHandling();

        $data = ['email' => User::find(1)->email, 'password' => 'password'];
        $response = $this->post('api/auth/login', $data);

        $response->assertStatus(200);
    }

    /**
     * New user can register
     */
    public function test_user_can_register(): void
    {
        $data = [
            'name'              => 'Jane William',
            'email'             => 'jane@gmail.com',
            'gender'            => 'female',
            'password'          => 'password123',
            'password_confirm'  => 'password123'
        ];

        $response = $this->post('api/auth/register', $data);

        $response->assertStatus(201);
        assertCount(2, User::all());
    }

    /**
     * User can logout
     */
    public function test_user_can_logout(): void
    {
        $this->withoutExceptionHandling();

        $user = $this->testUser();
        $response = $this->post('api/auth/logout');

        $response->assertStatus(200);
        $this->assertCount(0, $user->tokens);
    }

    /**
     * User can change password
     */
    public function test_user_can_change_password(): void
    {
        $this->withoutExceptionHandling();

        $user = $this->testUser();
        $data = ['password' => 'password321', 'password_confirm' => 'password321'];

        $response = $this->post('api/auth/password/change', $data);

        $response->assertStatus(200);
        $this->assertEquals(false, Hash::check('password', $user->password));
        $this->assertEquals(true, Hash::check('password321', $user->password));
    }
}
