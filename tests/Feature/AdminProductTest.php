<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Login admin user using Sanctum
     */
    protected function setUp(): void
    {
        parent::setUp();
        $admin = Admin::factory()->create();
        Sanctum::actingAs($admin);
    }

    /**
     * Can get all products
     */
    public function test_admin_can_get_all_products(): void
    {
        $this->withoutExceptionHandling();

        Category::factory(3)->create();
        Product::factory(5)->create();

        $response = $this->get('/api/admin/products');

        $response->assertStatus(200);
        $this->assertCount(5, $response->json('products'));
    }

    /**
     * Can get one product
     */
    public function test_admin_can_get_one_product(): void
    {
        $this->withoutExceptionHandling();

        Category::factory()->create();
        $product = Product::factory()->create();

        $response = $this->get('/api/admin/products/' . $product->id);

        $response->assertStatus(200);
        $this->assertArrayHasKey('product', $response->json());
    }

    /**
     * Can add new product
     */
    public function test_admin_can_add_new_product(): void
    {
        $this->withoutExceptionHandling();

        $category = Category::factory()->create(['name' => 'Test']);

        $data = [
            'name'          => 'Test Product',
            'description'   => 'This is a description',
            'price'         => 150,
            'image'         => UploadedFile::fake()->image('default.png'),
            'featured'      => false,
            'category_id'   => $category->id
        ];

        $response = $this->post('/api/admin/products', $data);

        $response->assertStatus(201);
        $this->assertDatabaseCount('products', 1);
        Storage::assertExists('public/products/images/default.png');

        // cleanup
        Storage::delete('public/products/images/default.png');
    }

    /**
     * Can update existing product
     */
    public function test_admin_can_update_exist_product(): void
    {
        $this->withoutExceptionHandling();

        Category::factory()->create(['name' => 'Test']);
        $product = Product::factory()->create(['name' => 'Test Product']);

        $data = [
            'name'              => 'Test Updated Product',
            'price'             => 300,
            'featured'          => true
        ];

        $response = $this->put('/api/admin/products/1', $data);

        $product = $product->refresh();

        $response->assertStatus(200);
        $this->assertDatabaseCount('products', 1);

        $this->assertEquals('Test Updated Product', $product->name);
        $this->assertEquals(300, $product->price);
        $this->assertEquals(true, $product->featured);
    }

    /**
     * Can delete existing product
     */
    public function test_admin_can_delete_exist_product(): void
    {
        $this->withoutExceptionHandling();

        Category::factory()->create(['name' => 'Test']);
        Product::factory()->create(['name' => 'Test Product']);

        $response = $this->delete('/api/admin/products/1');

        $response->assertStatus(200);
        $this->assertDatabaseCount('products', 0);
    }
}
