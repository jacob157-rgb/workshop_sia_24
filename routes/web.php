<?php

use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
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
    return view('index');
});
Route::get('/login', function () {
    return view('auth.login');
});
Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'authenticate']);
Route::post('/register', [UserController::class, 'register']);

Route::get('/laporan', [LaporanController::class, 'index']);
Route::get('/laporan/create', [LaporanController::class, 'create']);
Route::get('/laporan/edit/{id}', [LaporanController::class, 'edit']);
Route::put('/laporan/update/{id}', [LaporanController::class, 'update']);
Route::post('/laporan/create', [LaporanController::class, 'store']);
Route::delete('/laporan/delete/{id}', [LaporanController::class, 'destroy']);

