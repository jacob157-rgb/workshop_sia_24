<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8"> <!-- Menentukan karakter encoding dokumen -->
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Mengatur viewport untuk perangkat mobile -->

    <title>Register</title> <!-- Judul halaman -->

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net"> <!-- Mempercepat koneksi ke bunny.net untuk font -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" /> <!-- Menyertakan font Figtree -->

    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> <!-- Menyertakan Tailwind CSS -->

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'figtree', sans-serif; <!-- Mengatur font default ke Figtree -->
        }
    </style>
</head>

<body class="flex h-screen items-center justify-center bg-gray-100">
    <!-- Form Registrasi -->
    <form action="/register" method="post" class="w-full max-w-sm rounded bg-white p-8 shadow-md"> <!-- Formulir dengan metode POST ke /register -->
        @csrf <!-- Menyertakan token CSRF untuk keamanan -->
        <h1 class="mb-4 text-4xl font-extrabold">Register</h1> <!-- Judul form -->

        @if ($errors->any()) <!-- Jika ada error -->
            <div class="mb-4">
                <div class="text-sm text-red-500">
                    <ul>
                        @foreach ($errors->all() as $error) <!-- Menampilkan semua error -->
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Input Nama -->
        <div class="mb-4">
            <label for="name" class="mb-2 block text-sm font-bold text-gray-700">Name</label> <!-- Label untuk nama -->
            <input type="text" name="name" id="name" placeholder="Masukan Nama"
                class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"> <!-- Input nama -->
        </div>

        <!-- Input Email -->
        <div class="mb-4">
            <label for="email" class="mb-2 block text-sm font-bold text-gray-700">Email</label> <!-- Label untuk email -->
            <input type="email" name="email" id="email" placeholder="Masukan Email"
                class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"> <!-- Input email -->
        </div>

        <!-- Input Password -->
        <div class="mb-4">
            <label for="password" class="mb-2 block text-sm font-bold text-gray-700">Password</label> <!-- Label untuk password -->
            <input type="password" name="password" id="password" placeholder="Masukan Password"
                class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"> <!-- Input password -->
        </div>

        <!-- Input Konfirmasi Password -->
        <div class="mb-6">
            <label for="password_confirmation" class="mb-2 block text-sm font-bold text-gray-700">Ulangi Password</label> <!-- Label untuk konfirmasi password -->
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi Password"
                class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"> <!-- Input konfirmasi password -->
        </div>

        <!-- Tombol Daftar -->
        <div class="flex items-center justify-between">
            <button type="submit"
                class="focus:shadow-outline rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700 focus:outline-none">Daftar</button> <!-- Tombol submit -->
        </div>
    </form>
</body>

</html>
