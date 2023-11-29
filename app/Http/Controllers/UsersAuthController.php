<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersAuthController extends Controller
{
    /**
     * Login users using email and password
     */
    public function login(UserLoginRequest $request)
    {
        try {
            // Check if user exist
            $user = User::where('email', $request->email)->first();
            if ($user) {
                // Check if password match
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken('user-token')->plainTextToken;
                    return response()->json(['status' => 'success', 'user' => $user, 'token' => $token]);
                }
            }
            // if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            //     $user = User::where('email', $request->email)->first();
            //     $token = $user->createToken('user-token')->plainTextToken;

            //     return response()->json(['status' => 'success', 'user' => $user, 'token' => $token]);
            // }

            return response()->json(['status' => 'fail', 'message' => 'Invalid email or password'], 401);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Register new user
     */
    public function register(UserRegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->validated('name'),
                'email' => $request->validated('email'),
                'gender' => $request->validated('gender'),
                'password' => Hash::make($request->validated('password')),
            ]);

            if ($user) {
                $token = $user->createToken('user-token')->plainTextToken;
                return response()->json(['status' => 'success', 'user' => $user, 'token' => $token], 201);
            }

            return response()->json(['status' => 'fail', 'message' => 'Something wrong happened'], 400);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Logout a login user
     */
    public function logout()
    {
        try {
            auth()->user()->tokens()->delete();
        } catch (\Throwable $th) {
            throw $th;
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }

        return response()->json();
    }

    /**
     * Change user password
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user = auth()->user();
            $user->password = Hash::make($request->password);
            if ($user->save()) {
                return response()->json();
            }

            return response()->json(['status' => 'fail', 'message' => 'Something wrong happened'], 400);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }
}
