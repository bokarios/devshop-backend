<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Basic setup
     */
    protected function setUp(): void
    {
        parent::setUp();

        Category::factory(3)->create();
        Product::factory(5)->create();

        User::create([
            'name'      => 'John Doe',
            'email'     => 'joe@gmail.com',
            'gender'    => 'male',
            'password'  => Hash::make('password123')
        ]);
    }

    /**
     * User can show all products
     */
    public function test_user_can_show_all_products(): void
    {
        $response = $this->get('/api/products');

        $response->assertStatus(200);
        $this->assertNotEmpty($response->json('products'));
    }

    /**
     * User can show a product details
     */
    public function test_user_can_show_a_product_details(): void
    {
        // $this->withoutExceptionHandling();
        $response = $this->get('/api/products/2');

        $response->assertStatus(200);
        $this->assertNotEmpty($response->json('product'));
    }

    /**
     * User can add product to favorites
     */
    public function test_user_can_add_product_to_favorites(): void
    {
        auth()->loginUsingId(1);
        $response = $this->post('/api/products/favorites', ['product_id' => 2]);

        $response->assertStatus(200);
    }

    /**
     * User can remove product from favorites
     */
    public function test_user_can_remove_product_from_favorites(): void
    {
        auth()->loginUsingId(1);
        Favorite::create(['user_id' => 1, 'product_id' => 2]);
        Favorite::create(['user_id' => 1, 'product_id' => 3]);

        $this->assertCount(2, Favorite::all());

        $response = $this->delete('/api/products/favorites/2');

        $response->assertStatus(200);
        $this->assertCount(1, Favorite::all());
    }

    /**
     * User can show favorites
     */
    public function test_user_can_show_favorites(): void
    {
        $this->withoutExceptionHandling();
        auth()->loginUsingId(1);
        Favorite::create(['user_id' => 1, 'product_id' => 2]);
        Favorite::create(['user_id' => 1, 'product_id' => 3]);

        $response = $this->get('/api/products/favorites/show');

        $response->assertStatus(200);
        $this->assertCount(Favorite::count(), $response->json('favorites'));
    }
}
