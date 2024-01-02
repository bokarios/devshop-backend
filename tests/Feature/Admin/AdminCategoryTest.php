<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminCategoryTest extends TestCase
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

        Category::factory(4)->create();
    }

    /**
     * Can get all categories
     */
    public function test_admin_can_get_all_categories(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->get('/api/admin/categories');

        $response->assertStatus(200);
        $this->assertCount(4, $response->json('categories'));
    }

    /**
     * Can get one category
     */
    public function test_admin_can_get_one_category(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->get('/api/admin/categories/1');

        $response->assertStatus(200);
        $this->assertArrayHasKey('category', $response->json());
    }

    /**
     * Can add new category
     */
    public function test_admin_can_add_new_category(): void
    {
        $this->withoutExceptionHandling();

        $data = [
            'name'          => 'New Category',
            'description'   => 'New description',
            'parent_id'     => 1
        ];

        $response = $this->post('/api/admin/categories', $data);

        $response->assertStatus(201);
        $this->assertDatabaseCount('categories', 5);
    }

    /**
     * Can update exiting category
     */
    public function test_admin_can_update_exist_category(): void
    {
        $this->withoutExceptionHandling();

        $data = [
            'name'          => 'Updated Category',
            'description'   => 'New description',
            'parent_id'     => 1
        ];

        $response = $this->put('/api/admin/categories/2', $data);

        $response->assertStatus(200);
        $this->assertDatabaseCount('categories', 4);
        $this->assertEquals('Updated Category', Category::find(2)->name);
        $this->assertEquals(1, Category::find(2)->parent->id);
    }

    /**
     * Can delete existing category
     */
    public function test_admin_can_delete_exist_category(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->delete('/api/admin/categories/3');

        $response->assertStatus(200);
        $this->assertDatabaseCount('categories', 3);
    }
}
