<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;

class SessionController extends Controller
{
    public static function checkValidation($validator) {
        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errors" => $validator->errors()
            ], 200);
        }
    }

    public function login() {
        $validator = Validator::make(request()->all(), [
            "email" => "required|email|exists:users,email|max:255",
            "password" => "required|string|min:7|regex:/^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/|max:255"
        ]);

        $status = SessionController::checkValidation($validator);
        if ($status) {
            return $status;
        }

        $data = request()->only(["email", "password"]);
    
        if (auth()->attempt($data)) {
            $user = User::where('email', $data['email'])->first();
            $token = $user->createToken('authToken', ['server:read', 'server:write']);
    
            return response()->json([
                'success' => true, 
                'token' => $token->plainTextToken,
                "id" => $user->id,
                "user" => $user
            ], 200);
        }
    
        return response()->json(['success' => false, 'message' => 'Login failed. Invalid credentials.'], 200);
    }

    public function logout()
    {
        $validator = Validator::make(request()->all(), [
            "id" => "required|integer|exists:users,id|max:255"
        ]);

        $status = SessionController::checkValidation($validator);
        if ($status) {
            return $status;
        }

        $id = request()->only("id");

        $user = User::where("id", $id)->first();

        auth()->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ], 200);
    }

    public function register() {
        $validator = Validator::make(request()->all(), [
            "email" => "required|email|unique:users,email|max:255",
            "password" => "required|string|min:7|regex:/^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/|max:255"
        ]);

        $status = SessionController::checkValidation($validator);
        if ($status) {
            return $status;
        }

        $input = request()->only(["email", "password"]);

        User::create($input);

        return response()->json([
            "success" => true,
            "message" => "Account created successfully"
        ],200);
    }
}
