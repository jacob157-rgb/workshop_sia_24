<?php

use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\{Gate, Route};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan rute web untuk aplikasi Anda. Semua rute
| ini dimuat oleh RouteServiceProvider dan semuanya akan ditugaskan ke grup
| middleware "web". Buat sesuatu yang hebat!
|
*/

// Route untuk halaman utama
Route::get('/', function () {
    return view('index');
});

// Route untuk halaman login
Route::get('/login', function () {
    return view('auth.login');
});

// Route untuk halaman registrasi
Route::get('/register', function () {
    return view('auth.register');
});

// Route untuk autentikasi login
Route::post('/login', [UserController::class, 'authenticate']);

// Route untuk registrasi user baru
Route::post('/register', [UserController::class, 'register']);

// Route untuk logout user
Route::get('/logout', [UserController::class, 'logout']);

// Middleware 'auth' akan memaksa pengguna untuk terautentikasi sebelum mengakses rute-rute di bawah ini
Route::middleware(['auth'])->group(function () {
    // Route untuk menampilkan daftar laporan keuangan
    Route::get('/laporan', [LaporanController::class, 'index']);

    // Route untuk menampilkan halaman tambah laporan
    Route::get('/laporan/create', [LaporanController::class, 'create']);

    // Route untuk menampilkan halaman edit laporan
    Route::get('/laporan/edit/{id}', [LaporanController::class, 'edit']);

    // Route untuk update laporan keuangan
    Route::put('/laporan/update/{id}', [LaporanController::class, 'update']);

    // Route untuk menyimpan laporan baru
    Route::post('/laporan/create', [LaporanController::class, 'store']);

    // Route untuk menghapus laporan
    Route::delete('/laporan/delete/{id}', [LaporanController::class, 'destroy'])
        ->middleware('can:delete-laporan,id');
});

/*
|--------------------------------------------------------------------------
| Gate Authorization
|--------------------------------------------------------------------------
|
| Menentukan kebijakan akses menggunakan Gate untuk mengatur siapa yang dapat
| menghapus laporan. Di sini menggunakan Gate untuk memverifikasi bahwa user
| yang sedang login memiliki akses sebagai admin.
|
*/
Gate::define('delete-laporan', function ($user, $laporanId) {
    return $user->role === 'admin';
});
