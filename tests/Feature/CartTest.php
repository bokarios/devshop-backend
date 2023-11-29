<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup settings
     */
    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::factory()->create());
    }

    /**
     * Create new cart
     */
    private function createCart()
    {
        Cart::create(['user_id' => User::first()->id, 'total' => 0]);
        $this->assertCount(1, Cart::all());
    }

    /**
     * Create cart items of n
     */
    private function createCartItems($n)
    {
        CartItem::factory($n)->create(['cart_id' => 1]);
        $this->assertCount($n, CartItem::all());
    }

    /**
     * Will create new cart if no cart exists
     */
    public function test_will_create_new_cart_if_no_cart_exists(): void
    {
        $this->withoutExceptionHandling();

        $this->assertCount(0, Cart::all());
        $response = $this->get('/api/cart');

        $response->assertStatus(200);
        $this->assertCount(1, Cart::all());
    }

    /**
     * Can get all user's cart items
     */
    public function test_user_can_get_all_cart_items(): void
    {
        $this->withoutExceptionHandling();

        $this->createCart();
        $this->createCartItems(3);

        $response = $this->get('/api/cart');

        $response->assertStatus(200);
    }

    /**
     * User can add item to cart
     */
    public function test_user_can_add_item_to_cart(): void
    {
        $this->withoutExceptionHandling();

        $this->createCart();
        $this->assertCount(0, CartItem::all());

        $data = [
            'product_id' => Product::factory()->create()->id,
            'quantity' => 3
        ];
        $response = $this->post('/api/cart/add', $data);

        $response->assertStatus(201);
        $this->assertCount(1, CartItem::all());
    }

    /**
     * User can update a cart Item
     */
    public function test_user_can_update_a_cart_item(): void
    {
        $this->withoutExceptionHandling();

        $this->createCart();
        $this->createCartItems(2);

        $data = ['quantity' => 10];
        $response = $this->put('/api/cart/update/1', $data);

        $response->assertStatus(200);
        $this->assertEquals(10, CartItem::find(1)->quantity);
    }

    /**
     * User can delete a cart Item
     */
    public function test_user_can_delete_a_cart_item(): void
    {
        $this->withoutExceptionHandling();

        $this->createCart();
        $this->createCartItems(2);

        $response = $this->delete('/api/cart/delete/1');

        $response->assertStatus(200);
        $this->assertCount(1, CartItem::all());
    }

    /**
     * User can reset the cart
     */
    public function test_user_can_reset_cart(): void
    {
        $this->withoutExceptionHandling();

        $this->createCart();
        $this->createCartItems(2);

        $response = $this->post('/api/cart/reset/1');

        $response->assertStatus(200);
        $this->assertCount(0, CartItem::all());
    }
}
