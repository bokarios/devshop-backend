<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        // Required models
        Category::factory(3)->create();
        Product::factory(5)->create();
    }

    /**
     * Can get all products
     */
    public function test_admin_can_get_all_products(): void
    {
        $this->withoutExceptionHandling();

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

        $response = $this->get('/api/admin/products/3');

        $response->assertStatus(200);
        $this->assertArrayHasKey('product', $response->json());
    }

    /**
     * Can add new product
     */
    public function test_admin_can_add_new_product(): void
    {
        $this->withoutExceptionHandling();

        $data = [
            'name'          => 'Test Product',
            'description'   => 'This is a description',
            'price'         => 150,
            'image'         => UploadedFile::fake()->image('default.png'),
            'featured'      => false,
            'category_id'   => 2
        ];

        $response = $this->post('/api/admin/products', $data);

        $response->assertStatus(201);
        $this->assertDatabaseCount('products', 6);
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

        $data = [
            'name'              => 'Test Updated Product',
            'price'             => 300,
            'featured'          => true
        ];

        $response = $this->put('/api/admin/products/4', $data);

        $product = Product::find(4);

        $response->assertStatus(200);
        $this->assertDatabaseCount('products', 5);

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

        $response = $this->delete('/api/admin/products/2');

        $response->assertStatus(200);
        $this->assertDatabaseCount('products', 4);
    }

    /**
     * Can get all of product variations
     */
    public function test_admin_can_get_all_of_product_variations(): void
    {
        $this->withoutExceptionHandling();

        Color::create(['name' => 'red', 'value' => '#ff0000']);
        ProductVariation::factory(4)->create(['product_id' => 2]);

        $response = $this->get('/api/admin/products/2/variations');

        $response->assertStatus(200);
        $this->assertCount(4, $response->json('productVariations'));
    }

    /**
     * Can add new product variation
     */
    public function test_admin_can_add_new_product_variation(): void
    {
        $this->withoutExceptionHandling();

        Color::create(['name' => 'red', 'value' => '#ff0000']);

        $data = [
            'price'             => 140,
            'image'             => UploadedFile::fake()->image('product-1-var-1.png'),
            'sizes'             => json_encode(['sm', 'lg', 'xl', '2xl']),
            'color_id'          => 1,
            'product_id'        => rand(1, 5)
        ];

        $response = $this->post('/api/admin/products/variation', $data);

        $response->assertStatus(201);
        $this->assertDatabaseCount('product_variations', 1);

        // cleanup
        Storage::delete('public/products/images/product-1-var-1.png');
    }

    /**
     * Can update existing product variation
     */
    public function test_admin_can_update_exist_product_variation(): void
    {
        $this->withoutExceptionHandling();

        Color::create(['name' => 'red', 'value' => '#ff0000']);
        Color::create(['name' => 'blue', 'value' => '#0000ff']);
        $product_variation = ProductVariation::factory()->create(['price' => 320, 'color_id' => 1]);

        $data = [
            'price'             => 315,
            'color_id'          => 2
        ];

        $response = $this->put('/api/admin/products/variation/1', $data);

        $product_variation = $product_variation->refresh();

        $response->assertStatus(200);
        $this->assertDatabaseCount('product_variations', 1);
        $this->assertEquals(315, $product_variation->price);
        $this->assertEquals('blue', $product_variation->color->name);
    }

    /**
     * Can delete existing product variation
     */
    public function test_admin_can_delete_exist_product_variation(): void
    {
        $this->withoutExceptionHandling();

        Color::create(['name' => 'red', 'value' => '#ff0000']);
        ProductVariation::factory()->create();

        $response = $this->delete('/api/admin/products/variation/1');

        $response->assertStatus(200);
        $this->assertDatabaseCount('product_variations', 0);
    }
}
