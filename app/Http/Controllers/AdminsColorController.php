<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminAddColorRequest;
use App\Http\Requests\AdminEditColorRequest;
use App\Models\Color;
use Illuminate\Http\Request;

class AdminsColorController extends Controller
{
    /**
     * Get all colors
     */
    public function getAllColors()
    {
        try {
            $colors = Color::all();

            return $this->success(['status' => 'success', 'colors' => $colors]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->serverError($th->getMessage());
        }
    }

    /**
     * Get one color
     */
    public function getOneColor(Color $color)
    {
        try {
            $color = Color::findOrFail($color->id);

            return $this->success(['status' => 'success', 'color' => $color]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->serverError($th->getMessage());
        }
    }

    /**
     * Add new color
     */
    public function addColor(AdminAddColorRequest $request)
    {
        try {
            $color = Color::create($request->validated());

            if ($color) {
                return $this->success(['status' => 'success', 'color' => $color], 201);
            }

            return $this->fail('Failed to add new');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->serverError($th->getMessage());
        }
    }

    /**
     * Edit existing color
     */
    public function editColor(Color $color, AdminEditColorRequest $request)
    {
        try {
            $updated = $color->update($request->validated());

            if ($updated) {
                return $this->success(['status' => 'success', 'color' => $color->refresh()]);
            }

            return $this->fail('Failed to update');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->serverError($th->getMessage());
        }
    }

    /**
     * Delete existing color
     */
    public function deleteColor(Color $color)
    {
        try {
            $deleted = $color->delete();

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
