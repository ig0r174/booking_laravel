<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class UserController extends Controller
{
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        $token = auth()->attempt(["email" => $request->email, "password" => $request->password]);

        return response()->json([
            "status" => true,
            "message" => "User successfully registered",
            "access_token" => $token
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        if (!$token = auth()->attempt(["email" => $request->email, "password" => $request->password])) {
            return response()->json([
                "status" => false,
                "message" => "Invalid credentials"
            ]);
        }

        return response()->json([
            "status" => true,
            "message" => "Logged in successfully",
            "access_token" => $token
        ]);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();
        return response()->json([
            "status" => true,
            "message" => "User logged out"
        ]);
    }
}
