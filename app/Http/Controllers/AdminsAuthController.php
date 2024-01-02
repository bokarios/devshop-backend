<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminChangePasswordRequest;
use App\Http\Requests\AdminLoginRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminsAuthController extends Controller
{
    /**
     * Login admin using email and password
     */
    public function login(AdminLoginRequest $request)
    {
        try {
            // Check if admin exist
            $admin = Admin::where('email', $request->email)->first();

            if ($admin) {
                // Check if password match
                if (Hash::check($request->password, $admin->password)) {
                    $token = $admin->createToken('admin-token')->plainTextToken;
                    return response()->json(['status' => 'success', 'admin' => $admin, 'token' => $token]);
                }
            }

            return response()->json(['status' => 'fail', 'message' => 'Invalid email or password'], 401);
        } catch (\Throwable $th) {
            // throw $th;
            $this->serverError($th->getMessage());
        }
    }

    /**
     * Logout an admin
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
        } catch (\Throwable $th) {
            // throw $th;
            $this->serverError($th->getMessage());
        }

        return $this->success([]);
    }

    /**
     * Change an admin password
     */
    public function changePassword(AdminChangePasswordRequest $request)
    {
        try {
            $admin = $request->user();
            $admin->password = Hash::make($request->password);
            if ($admin->save()) {
                return $this->success([]);
            }

            return $this->fail('Password change failed');
        } catch (\Throwable $th) {
            // throw $th;
            $this->serverError($th->getMessage());
        }
    }
}
