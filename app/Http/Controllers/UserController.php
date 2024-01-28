<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make(request()->all(), [
            "id" => "required|integer|exists:users,id",
            "new_email" => "required|email|unique:users,email|max:255"
        ]);

        $status = SessionController::checkValidation($validator);
        if ($status) {
            return $status;
        }
        
        $data = request()->only(["id", "new_email"]);

        $user = User::find($data["id"]);
        if(!$user) 
            return response()->json([
                "success" => false,
                "message" => "User not found"
            ], 200);

        $user["email"] = $data["new_email"];    

        $user->save();

        return response()->json([
            "success" => true,
            "message" => "Email changed successfully"
        ], 200);
    }

    public function update_password() {
        $validator = Validator::make(request()->all(), [
            "id" => "required|integer|exists:users,id",
            "old_password" => "required|string|min:7|regex:/^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/|max:255|exists:users,password",
            "new_password" => "required|string|min:7|regex:/^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/|max:255"
        ]);

        $status = SessionController::checkValidation($validator);
        if ($status) {
            return $status;
        }
        
        $data = request()->only(["id", "old_password", "new_password"]);

        $user = User::where('id', $data["id"])->where('password', $data["old_password"]);
        if(!$user) 
            return response()->json([
                "success" => false,
                "message" => "User not found"
            ], 200);

        $user["password"] = $data["new_password"];    

        $user->save();

        return response()->json([
            "success" => true,
            "message" => "Password changed successfully"
        ], 200);
    }

    public function remove() {
        $validator = Validator::make(request()->all(), [
            "id" => "required|integer|exists:users,id",
        ]);

        $status = SessionController::checkValidation($validator);
        if ($status) {
            return $status;
        }

        $data = request()->only(["id"]);

        User::destroy($data["id"]);

        return response()->json([
            "success" => true,
            "message" => "User account deleted successfully."
        ], 200);
    }
}
