<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminAddCategoryRequest;
use App\Http\Requests\AdminEditCategoryRequest;
use App\Models\Category;

class AdminsCategoryController extends Controller
{
    /**
     * Get all categories
     */
    public function getAllCategories()
    {
        try {
            $categories = Category::all();

            return $this->success(['status' => 'success', 'categories' => $categories]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->serverError($th->getMessage());
        }
    }

    /**
     * Get one category
     */
    public function getOneCategory(Category $category)
    {
        try {
            $category = Category::findOrFail($category->id);

            return $this->success(['status' => 'success', 'category' => $category]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->serverError($th->getMessage());
        }
    }

    /**
     * Add new category
     */
    public function addCategory(AdminAddCategoryRequest $request)
    {
        try {
            $category = Category::create($request->validated());

            if ($category) {
                return $this->success(['status' => 'success', 'category' => $category], 201);
            }

            return $this->fail('Failed to add new');
        } catch (\Throwable $th) {
            // throw $th;
            return $this->serverError($th->getMessage());
        }
    }

    /**
     * Edit existing category
     */
    public function editCategory(Category $category, AdminEditCategoryRequest $request)
    {
        try {
            $updated = $category->update($request->validated());

            if ($updated) {
                return $this->success(['status' => 'success', 'category' => $category->refresh()]);
            }

            return $this->fail('Failed to update');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->serverError($th->getMessage());
        }
    }

    /**
     * Delete existing category
     */
    public function deleteCategory(Category $category)
    {
        try {
            $deleted = $category->delete();

            if ($deleted) {
                return $this->success();
            }

            return $this->fail('Failed to delete');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->serverError($th->getMessage());
        }
    }
}
