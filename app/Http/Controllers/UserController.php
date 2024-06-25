<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
 * Mengautentikasi pengguna berdasarkan email dan kata sandi yang diberikan.
 *
 * @param Request $request Permintaan masuk yang berisi email dan kata sandi.
 * @return RedirectResponse Respons redirect ke halaman yang tepat setelah autentikasi.
 */
public function authenticate(Request $request): RedirectResponse
{
    // Validasi data permintaan masuk
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Ekstrak email dan kata sandi dari kredensial yang divalidasi
    $email = $credentials['email'];
    $password = $credentials['password'];

    // Coba mengautentikasi pengguna menggunakan email dan kata sandi yang diberikan
    if (Auth::attempt(['email' => $email, 'password' => $password])) {
        // Menggenerasi ulang ID sesi untuk mencegah serangan session fixation
        $request->session()->regenerate();

        // Alihkan pengguna yang diautentikasi ke halaman laporan
        return redirect('/laporan');
    }

    // Ambil pengguna berdasarkan email menggunakan penyedia autentikasi
    $user = Auth::getProvider()->retrieveByCredentials(['email' => $email]);

    // Jika pengguna tidak ada, kembalikan pesan kesalahan dengan bidang email
    if (!$user) {
        return back()->withErrors([
            'email' => 'Email tidak terdaftar.'
        ])->withInput();
    }

    // Jika kata sandi salah, kembalikan pesan kesalahan dengan bidang kata sandi
    return back()->withErrors([
        'password' => 'Password salah.'
    ])->withInput();
}

    /**
 * Membuat pengguna baru dengan data yang diberikan.
 *
 * @param Request $request Permintaan masuk yang berisi nama, email, dan password.
 * @return RedirectResponse Respons redirect ke halaman login setelah pendaftaran berhasil.
 */
public function register(Request $request): RedirectResponse
{
    // Membuat validasi data permintaan masuk
    $validator = Validator::make($request->all(), [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan pesan kesalahan dan input yang disimpan
    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    // Buat pengguna baru dengan data yang diberikan
    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => 'user'
    ]);

    // Alihkan pengguna ke halaman login dengan pesan sukses
    return redirect('/login')->with('success', 'Akun berhasil dibuat. Silakan login.');
}

    /**
 * Keluar dari pengguna yang telah diotentikasi.
 *
 * Metode ini melakukan logout pada pengguna yang sedang diotentikasi dengan menginvalidasi
 * sesi, meregenerasi token sesi, dan mengarahkan pengguna ke halaman utama.
 *
 * @param Request $request Permintaan masuk yang masuk.
 * @return RedirectResponse Respons redirect ke halaman utama.
 */
public function logout(Request $request): RedirectResponse
{
    // Keluar dari pengguna yang diotentikasi
    Auth::logout();

    // Invalidasi sesi
    $request->session()->invalidate();

    // Meregenerasi token sesi
    $request->session()->regenerateToken();

    // Alihkan pengguna ke halaman utama
    return redirect('/');
}
}
