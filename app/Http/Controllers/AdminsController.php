<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class AdminsController extends Controller
{
    /**
     * Get All Services
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
     * Get Single Service
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

    /**
     * Add New Service
     * 
     */
    public function addService(Request $request)
    {
        // Validate data
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required'
        ], $request->all());

        try {
            $service = new Service();
            $service->name = $validated['name'];
            $service->description = $validated['description'];
            $service->save();

            return response()->json(['status' => 'success', 'service' => $service], 201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'fail', 'message' => $th->getMessage()]);
        }
    }

    /**
     * Edit Service
     * 
     */
    public function editService(Request $request, $id)
    {
        // Validate data
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required'
        ], $request->all());

        try {
            $service = Service::find($id);
            $service->name = $validated['name'];
            $service->description = $validated['description'];
            $service->save();

            return response()->json(['status' => 'success', 'service' => $service->fresh()]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'fail', 'message' => $th->getMessage()]);
        }
    }

    /**
     * Delete Service
     * 
     */
    public function deleteService($id)
    {
        try {
            $service = Service::find($id);
            $service->delete();

            return response()->json(['status' => 'success']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'fail', 'message' => $th->getMessage()]);
        }
    }
}
