<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Judul halaman -->
        <title>SPK</title>

        <!-- Menghubungkan font dari Bunny -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Menghubungkan Tailwind CSS dari CDN -->
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

        <!-- Gaya kustom -->
        <style>
            body {
                font-family: 'figtree', sans-serif;
            }
        </style>
    </head>
    <body class="flex items-center justify-center h-screen bg-gray-100">
        <!-- Konten utama halaman -->
        <div class="text-center">
            <!-- Judul utama halaman -->
            <h1 class="text-5xl font-extrabold mb-8">Selamat Datang di Sistem Pencatatan Laporan Keuangan</h1>
            <!-- Tautan navigasi ke halaman login dan register -->
            <div class="space-x-4">
                <a href="/login" class="bg-blue-500 hover:bg-blue-700 text-white font-bold text-xl py-3 px-4 rounded focus:outline-none focus:shadow-outline">Login</a>
                <a href="/register" class="bg-green-500 hover:bg-green-700 text-white font-bold text-xl py-3 px-4 rounded focus:outline-none focus:shadow-outline">Register</a>
            </div>
        </div>
    </body>
</html>
