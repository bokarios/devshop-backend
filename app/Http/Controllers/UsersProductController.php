<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddFavoriteRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class UsersProductController extends Controller
{
    /**
     * Get all products
     */
    public function getAllProducts(Request $request)
    {
        try {
            $products = Product::all();

            $request->featured && $products = $products->where('featured', '=', (int) $request->featured ?? 0);

            return $this->success(['status' => 'success', 'products' => ProductResource::collection($products)]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->serverError($th->getMessage());
        }
    }

    /**
     * Get one product by id
     */
    public function getOneProduct($id)
    {
        try {
            $product = Product::findOrFail($id);

            return $this->success(['status' => 'success', 'product' => new ProductResource($product)]);
        } catch (NotFoundResourceException $ex) {
            // throw $ex;
            return $this->fail('Not found', 404);
        } catch (\Throwable $th) {
            // throw $th;
            return $this->serverError($th->getMessage());
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
            // throw $th;
            return $this->serverError($th->getMessage());
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
            // throw $th;
            $this->serverError($th->getMessage());
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
            // throw $th;
            $this->serverError($th->getMessage());
        }
    }
}
