<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Helper to return success JSON response
     * 
     * @param mixed $data
     * @param int $code
     */
    public function success($data = [], $code = 200)
    {
        return response()->json($data, $code);
    }

    /**
     * Helper to return fail JSON response
     * 
     * @param mixed $msg
     * @param int $code
     */
    public function fail($msg, $code = 400)
    {
        return response()->json(['status' => 'fail', 'message' => $msg], $code);
    }

    /**
     * Helper to return server error JSON response
     * 
     * @param string $msg
     */
    public function serverError($msg)
    {
        return response()->json(['status' => 'error', 'message' => $msg], 500);
    }

    /**
     * Helper to upload images
     * 
     * @param mixed $image
     * @param string $path
     */
    public function uploadImage($image, $path)
    {
        if (!$image) return false;

        $name = $image->getClientOriginalName();
        $image_path = $image->store($path);
        Storage::move($image_path, 'public/products/images/' . $name);

        return $name;
    }
}
