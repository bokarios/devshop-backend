<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddFavoriteRequest;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ProductsController extends Controller
{
    /**
     * Get all products
     */
    public function getAllProducts(Request $request)
    {
        $products = Product::all();

        // Filter by q (query)
        if ($request->q && $request->q !== '') {
            $products->where('name', $request->q)
                ->orWhere('description', $request->q);
        }

        // Filter by price minimum
        if ($request->min && $request->min !== '') {
            $products->where('price', '>=', $request->min);
        }

        // Filter by price maximum
        if ($request->max && $request->max !== '') {
            $products->where('price', '<=', $request->max);
        }

        return response()->json(['status' => 'success', 'products' => $products]);
    }

    /**
     * Get one product by id
     */
    public function getOneProduct($id)
    {
        try {
            $product = Product::findOrFail($id);

            return response()->json(['status' => 'success', 'product' => $product]);
        } catch (NotFoundResourceException $ex) {
            throw $ex;
            return response()->json([], 404);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Get user favorites
     */
    public function getFavorites(Request $request)
    {
        try {
            $favorites = Favorite::where('user_id', $request->user()->id)->get();

            if ($favorites) {
                return response()->json(['status' => 'success', 'favorites' => $favorites]);
            }

            return response()->json(['status' => 'fail', 'message' => 'Something wrong happened'], 400);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json(['status' => 'error', 'message' => $th->getMessage(), 500]);
        }
    }

    /**
     * Add product to user favorites
     */
    public function addFavorite(AddFavoriteRequest $request)
    {
        try {
            // Check if product already favored
            $favorite = Favorite::where(['user_id' => $request->user()->id, 'product_id' => $request->product_id])->first();

            if ($favorite) {
                return response()->json(['status' => 'fail', 'message' => 'Already favored'], 400);
            }

            // Add to favorites
            Favorite::create(['product_id' => $request->product_id, 'user_id' => $request->user()->id]);
            return response()->json(['status' => 'success']);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove product from user favorites
     */
    public function removeFavorite($id)
    {
        try {
            // Check if favorite exists
            $favorite = Favorite::findOrFail($id);

            if ($favorite) {
                if ($favorite->delete()) return response()->json();

                return response()->json(['status' => 'fail', 'message' => 'Could not remove favorite'], 400);
            }

            return response()->json([], 404);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }
}
