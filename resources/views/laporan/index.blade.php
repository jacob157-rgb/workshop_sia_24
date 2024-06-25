<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'figtree', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen flex-col">
        <!-- Navigasi -->
        <nav class="bg-white shadow-md">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold">Dashboard</h1>
                    </div>
                    <div class="flex items-center">
                        <!-- Menampilkan nama pengguna yang sedang login -->
                        <p class="font-medium">Halo, {{ auth()->user()->name }}</p>
                        <!-- Tautan logout -->
                        <a href="/logout" class="ml-4 text-gray-700 hover:text-gray-900">Logout</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Konten Utama -->
        <div class="container mx-auto flex-grow px-4 py-8 sm:px-6 lg:px-8">
            <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Menampilkan total pemasukan -->
                <div class="rounded-lg bg-white p-6 shadow-md">
                    <h2 class="mb-4 text-xl font-bold">Total Pemasukan</h2>
                    <p class="text-2xl">Rp.{{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                </div>
                <!-- Menampilkan total pengeluaran -->
                <div class="rounded-lg bg-white p-6 shadow-md">
                    <h2 class="mb-4 text-xl font-bold">Total Pengeluaran</h2>
                    <p class="text-2xl">Rp.{{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                </div>
                <!-- Menampilkan total saldo -->
                <div class="rounded-lg bg-white p-6 shadow-md">
                    <h2 class="mb-4 text-xl font-bold">Total Saldo</h2>
                    <p class="text-2xl">Rp.{{ number_format($totalSaldo, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Tabel Laporan Keuangan -->
            <div class="rounded-lg bg-white p-6 shadow-md">
                <div class="flex justify-between items-center mb-5">
                    <h2 class="mb-4 text-xl font-bold">Laporan Keuangan</h2>
                    <!-- Tautan untuk menambah laporan -->
                    <a href="/laporan/create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded focus:outline-none focus:shadow-outline">Tambah Laporan</a>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="bg-gray-50 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">No.</th>
                            <th class="bg-gray-50 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Tanggal</th>
                            <th class="bg-gray-50 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Keterangan</th>
                            <th class="bg-gray-50 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Pemasukan</th>
                            <th class="bg-gray-50 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Pengeluaran</th>
                            <th class="bg-gray-50 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Saldo</th>
                            <!-- Menampilkan kolom Aksi hanya untuk pengguna dengan peran admin -->
                            @if(auth()->user()->role == 'admin')
                            <th class="bg-gray-50 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <!-- Iterasi melalui setiap laporan -->
                        @foreach ($laporan as $row)
                            <tr>
                                <td class="whitespace-nowrap px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{ $row->tanggal }}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{ $row->keterangan }}</td>
                                <!-- Menampilkan nominal pemasukan atau tanda '-' jika bukan pemasukan -->
                                <td class="whitespace-nowrap px-6 py-4">{{ $row->tipe == 'pemasukan' ? 'Rp.' . number_format($row->nominal, 0, ',', '.') : '-' }}</td>
                                <!-- Menampilkan nominal pengeluaran atau tanda '-' jika bukan pengeluaran -->
                                <td class="whitespace-nowrap px-6 py-4">{{ $row->tipe == 'pengeluaran' ? 'Rp.' . number_format($row->nominal, 0, ',', '.') : '-' }}</td>
                                <td class="whitespace-nowrap px-6 py-4">Rp.{{ number_format($row->saldo, 0, ',', '.') }}</td>
                                <!-- Menampilkan aksi Edit dan Delete hanya untuk admin -->
                                @if(auth()->user()->role == 'admin')
                                <td class="whitespace-nowrap px-6 py-4">
                                    <!-- Tautan untuk mengedit laporan -->
                                    <a class="px-8 py-3 font-semibold rounded-full dark:bg-blue-800 dark:text-blue-100" href="/laporan/edit/{{ $row->id }}">Edit</a>
                                    <!-- Form untuk menghapus laporan -->
                                    <form action="/laporan/delete/{{ $row->id }}" method="post" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-8 py-3 font-semibold rounded-full dark:bg-red-800 dark:text-red-100" type="submit">Delete</button>
                                    </form>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white shadow-md">
            <div class="mx-auto max-w-7xl px-4 py-4 text-center sm:px-6 lg:px-8">
                <p class="text-gray-600">© 2024 Laporan Keuangan. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>

</html>
