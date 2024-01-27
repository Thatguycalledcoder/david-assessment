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

    public function update_email() {}

    public function update_password() {}

    public function remove() {}
}
