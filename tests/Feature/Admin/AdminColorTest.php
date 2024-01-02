<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Color;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminColorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Basic setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin and authenticate
        $admin = Admin::factory()->create();
        Sanctum::actingAs($admin);

        Color::create(['name' => 'Red', 'value' => '#ff0000']);
        Color::create(['name' => 'Green', 'value' => '#00ff00']);
        Color::create(['name' => 'Blue', 'value' => '#0000ff']);
        Color::create(['name' => 'Black', 'value' => '#000000']);
    }

    /**
     * Can get all colors
     */
    public function test_admin_can_get_all_colors(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->get('/api/admin/colors');

        $response->assertStatus(200);
        $this->assertCount(4, $response->json('colors'));
    }

    /**
     * Can get one color
     */
    public function test_admin_can_get_one_color(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->get('/api/admin/colors/2');

        $response->assertStatus(200);
        $this->assertArrayHasKey('color', $response->json());
    }

    /**
     * Can add new color
     */
    public function test_admin_can_add_new_color(): void
    {
        $this->withoutExceptionHandling();

        $data = ['name' => 'Yellow', 'value' => '#f3e301'];

        $response = $this->post('/api/admin/colors', $data);

        $response->assertStatus(201);
        $this->assertDatabaseCount('colors', 5);
    }

    /**
     * Can update existing color
     */
    public function test_admin_can_update_exist_color(): void
    {
        $this->withoutExceptionHandling();

        $data = ['value' => '#212121'];

        $response = $this->put('/api/admin/colors/4', $data);

        $response->assertStatus(200);
        $this->assertDatabaseCount('colors', 4);
        $this->assertEquals('#212121', Color::find(4)->value);
    }

    /**
     * Can delete existing color
     */
    public function test_admin_can_delete_exist_color(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->delete('/api/admin/colors/3');

        $response->assertStatus(200);
        $this->assertDatabaseCount('colors', 3);
    }
}
