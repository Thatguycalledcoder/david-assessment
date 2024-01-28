<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/read_cpu_mem", [FileController::class, "read_cpu_mem_info"]);
Route::get("/read_storage", [FileController::class, "read_storage_info"]);
Route::post("/register", [SessionController::class, "register"]);
Route::post("/login", [SessionController::class, "login"]);
Route::post("/logout", [SessionController::class, "logout"]);
Route::post("/change_email", [UserController::class, "update_email"]);
Route::post("/change_password", [UserController::class, "update_password"]);
Route::post('/delete', [UserController::class, "remove"]);