<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    /**
     * Get all services
     * 
     */
    public function getAllServices()
    {
        try {
            $services = Service::all();

            return response()->json(['status' => 'success', 'services' => $services]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'fail', 'message' => $th->getMessage()]);
        }
    }

    /**
     * Get single service with id
     * 
     */
    public function getSingleService($id)
    {
        try {
            $service = Service::find($id);

            return response()->json(['status' => 'success', 'service' => $service]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'fail', 'message' => $th->getMessage()]);
        }
    }
}
