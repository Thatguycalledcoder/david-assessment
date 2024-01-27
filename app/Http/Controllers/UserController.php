<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        $id = request()->validate([
            "id" => "required|integer|exists:id, users"
        ]);

        $user = User::find($id);

        return response()->json([
            "success" => true,
            "user" => $user
        ], 200);
    }

    public function update_email() {
        $data = request()->validate([
            "id" => "required|integer|exists:users,id",
            "old_email" => "required|email|exists:users,email",
            "new_email" => "required|email"
        ]);

        $user = User::where('id', $data["id"])->where('email', $data["old_email"])->first();

        $user["email"] = $data["new_email"];    

        $user->save();
    }

    public function update_password() {
        $data = request()->validate([
            "id" => "required|integer|exists:users,id",
            "old_password" => "required|string|exists:users,password",
            "new_password" => "required|string"
        ]);

        $user = User::where('id', $data["id"])->where('password', $data["old_password"])->first();

        $user["password"] = $data["new_password"];    

        $user->save();
    }

    public function remove() {}
}
