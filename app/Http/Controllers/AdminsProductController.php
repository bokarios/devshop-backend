<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminAddProductRequest;
use App\Http\Requests\AdminAddProductVariationRequest;
use App\Http\Requests\AdminEditProductRequest;
use App\Http\Requests\AdminEditProductVariationRequest;
use App\Http\Resources\AdminProductResource;
use App\Http\Resources\AdminProductVariationResource;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class AdminsProductController extends Controller
{
    private $image_path = '/public/products/images';

    /**
     * Get all products
     */
    public function getAllProducts()
    {
        try {
            $products = Product::all();

            return $this->success(['status' => 'success', 'products' => AdminProductResource::collection($products)]);
        } catch (\Throwable $th) {
            // throw $th;
            return $this->serverError($th->getMessage());
        }
    }

    /**
     * Get one product
     */
    public function getOneProduct($id)
    {
        try {
            $product = Product::findOrFail($id);

            return $this->success(['status' => 'success', 'product' => new AdminProductResource($product)]);
        } catch (\Throwable $th) {
            // throw $th;
            return $this->serverError($th->getMessage());
        }
    }

    /**
     * Add new product
     */
    public function addProduct(AdminAddProductRequest $request)
    {
        try {
            $validated = $request->validated();

            // Upload image
            $image = $this->uploadImage($request->file('image'), $this->image_path);

            if ($image) {
                $validated['image'] = $image;
                $product = Product::create($validated);

                if ($product) {
                    return $this->success(['status' => 'success', 'product' => new AdminProductResource($product)], 201);
                }
            }

            return $this->fail('Failed to add', 400);
        } catch (\Throwable $th) {
            // throw $th;
            return $this->serverError($th->getMessage());
        }
    }

    /**
     * Edit existing product
     */
    public function editProduct($id, AdminEditProductRequest $request)
    {
        try {
            $validated = $request->validated();

            // Check for image file
            if ($request->file('image')) {
                // Upload image
                $image = $this->uploadImage($request->file('image'), $this->image_path);

                if ($image) {
                    $validated['image'] = $image;
                }
            }

            $product = Product::findOrFail($id);
            $updated = $product->update($validated);

            if ($updated) {
                return $this->success(['status' => 'success', 'product' => new AdminProductResource($product->refresh())]);
            }

            return $this->fail('Failed to edit', 400);
        } catch (\Throwable $th) {
            // throw $th;
            return $this->serverError($th->getMessage());
        }
    }

    /**
     * Delete existing product
     */
    public function deleteProduct($id)
    {
        try {
            $product = Product::findOrFail($id);

            if ($product->delete()) {
                return $this->success();
            }

            return $this->fail('Failed to delete', 400);
        } catch (\Throwable $th) {
            // throw $th;
            return $this->serverError($th->getMessage());
        }
    }

    /**
     * Get all of product variations
     */
    public function getAllProductVariations(Product $product)
    {
        try {
            $product_variations = ProductVariation::where('product_id', $product->id)->get();

            return $this->success(['status' => 'success', 'productVariations' => AdminProductVariationResource::collection($product_variations)]);
        } catch (\Throwable $th) {
            // throw $th;
            return $this->serverError($th->getMessage());
        }
    }

    /**
     * Add new product variation
     */
    public function addProductVariation(AdminAddProductVariationRequest $request)
    {
        try {
            $validated = $request->validated();

            // Upload image
            $image = $this->uploadImage($request->file('image'), $this->image_path);

            if ($image) {
                $validated['image'] = $image;
                $product_variation = ProductVariation::create($validated);

                if ($product_variation) {
                    return $this->success(['status' => 'success', 'productVariation' => new AdminProductVariationResource($product_variation)], 201);
                }
            }

            return $this->fail('Failed to add product variation');
        } catch (\Throwable $th) {
            // throw $th;
            return $this->serverError($th->getMessage());
        }
    }

    /**
     * Edit existing product variation
     */
    public function editProductVariation(ProductVariation $variation, AdminEditProductVariationRequest $request)
    {
        try {
            $validated = $request->validated();

            // Check for image file
            if ($request->file('image')) {
                // Upload image
                $image = $this->uploadImage($request->file('image'), $this->image_path);

                if ($image) {
                    $validated['image'] = $image;
                }
            }

            $product_variation = ProductVariation::findOrFail($variation->id);
            $updated = $product_variation->update($validated);

            if ($updated) {
                return $this->success(['status' => 'success', 'productVariation' => new AdminProductVariationResource($product_variation->refresh())]);
            }

            return $this->fail('Failed to edit', 400);
        } catch (\Throwable $th) {
            // throw $th;
            return $this->serverError($th->getMessage());
        }
    }

    /**
     * Delete existing product variation
     * 
     * @param int $id
     */
    public function deleteProductVariation(ProductVariation $variation)
    {
        try {
            $product_variation = ProductVariation::findOrFail($variation->id);

            if ($product_variation) {
                if ($product_variation->delete()) {
                    return $this->success();
                }
            }

            return $this->fail('Failed to delete');
        } catch (\Throwable $th) {
            // throw $th;
            return $this->serverError($th->getMessage());
        }
    }
}
