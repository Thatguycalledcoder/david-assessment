<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;

class SessionController extends Controller
{
    public function login() {
        $input = request()->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $data = request()->all();
    
        if (auth()->attempt($data)) {
            $user = User::where('email', $data['email'])->first();
            $token = $user->createToken('authToken', ['server:read', 'server:write']);
    
            return response()->json(['success' => true, 'token' => $token->plainTextToken], 200);
        }
    
        return response()->json(['success' => false, 'message' => 'Login failed. Invalid credentials.'], 401);
    }

    public function logout()
    {
        $id = request()->validate([
            "id" => "required|integer|exists:id, users"
        ]);

        $user = User::find($id);

        $user->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function register() {
        $input = request()->validate([
            "email" => "required|email|unique:users,email",
            "password" => "required|string|min:7|regex:/^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/"
        ]);

        User::create($input);

        return response()->json([
            "success" => true,
            "message" => "User created successfully"
        ],200);
    }
}
