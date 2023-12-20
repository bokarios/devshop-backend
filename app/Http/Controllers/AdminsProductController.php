<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminAddProductRequest;
use App\Http\Requests\AdminEditProductRequest;
use App\Models\Product;
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

            return $this->success(['status' => 'success', 'products' => $products]);
        } catch (\Throwable $th) {
            throw $th;
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

            return $this->success(['status' => 'success', 'product' => $product]);
        } catch (\Throwable $th) {
            throw $th;
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
                    return $this->success(['status' => 'success', 'product' => $product], 201);
                }
            }

            return $this->fail('Failed to add', 400);
        } catch (\Throwable $th) {
            throw $th;
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
                return $this->success(['status' => 'success', 'product' => $product->refresh()]);
            }

            return $this->fail('Failed to edit', 400);
        } catch (\Throwable $th) {
            throw $th;
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
                return $this->success(['status' => 'success']);
            }

            return $this->fail('Failed to delete', 400);
        } catch (\Throwable $th) {
            throw $th;
            return $this->serverError($th->getMessage());
        }
    }
}
