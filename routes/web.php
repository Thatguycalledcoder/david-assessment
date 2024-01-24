<?php

use App\Http\Controllers\FileController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return "Hello to the world";
});

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get("/users", function () {
    return User::all();
});

Route::get("/read_cpu", [FileController::class, "read_cpu_info"]);
Route::get("/read_mem", [FileController::class, "read_mem_info"]);