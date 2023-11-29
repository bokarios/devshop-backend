<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCartItemRequest;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Get all cart items
     */
    public function getItems(Request $request)
    {
        try {
            $cart =  Cart::where('user_id', $request->user()->id)->first();

            if (!$cart) {
                // Create new cart for the user
                $cart = new Cart;
                $cart->user_id = $request->user()->id;
                $cart->save();
            }

            $cart_items = CartItem::where('cart_id', $cart->id)->get();

            return response()->json(['status' => 'success', 'cartItems' => $cart_items]);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Add item to user cart
     */
    public function addItem(AddCartItemRequest $request)
    {
        try {
            $cart =  Cart::where('user_id', $request->user()->id)->first();

            if (!$cart) {
                // Create new cart for the user
                $cart = new Cart;
                $cart->user_id = $request->user()->id;
                $cart->save();
            }

            // Check if item already exists
            $cart_item = CartItem::where(['cart_id' => $cart->id, 'product_id' => $request->product_id])->first();

            if ($cart_item) {
                return response()->json(['status' => 'fail', 'message' => 'The item already exists'], 400);
            }

            $cart_item = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);

            return response()->json(['status' => 'success', 'cartItem' => $cart_item], 201);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Update an item of user cart
     */
    public function updateItem($id, Request $request)
    {
        try {
            $cart_item = CartItem::find($id);

            if (!$cart_item) return response()->json([], 404);

            $cart_item->quantity = $request->quantity;
            $cart_item->save();

            return response()->json(['status' => 'success', 'cartItem' => $cart_item->refresh()]);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Delete an item of user cart
     */
    public function deleteItem($id)
    {
        try {
            $cart_item = CartItem::find($id);

            if (!$cart_item) return response()->json([], 404);

            $cart_item->delete();

            return response()->json(['status' => 'success']);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Reset user cart
     */
    public function resetCart($id)
    {
        try {
            $cart_items = CartItem::where('cart_id', $id);
            $cart_items->delete();

            return response()->json(['status' => 'success']);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }
}
