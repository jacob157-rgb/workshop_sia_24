<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> <!-- Menentukan bahasa dokumen berdasarkan locale aplikasi -->

<head>
    <meta charset="utf-8"> <!-- Menentukan karakter encoding dokumen -->
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Mengatur viewport untuk perangkat mobile -->
    <title>Dashboard</title> <!-- Judul halaman -->

    <!-- Menyertakan font dari Bunny Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Menyertakan Tailwind CSS dari CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Gaya Kustom -->
    <style>
        body {
            font-family: 'figtree', sans-serif; <!-- Mengatur font default ke Figtree -->
        }
    </style>
</head>

<body class="bg-gray-100"> <!-- Warna latar belakang halaman -->
    <div class="flex min-h-screen flex-col"> <!-- Flex container untuk mengatur layout halaman -->
        <!-- Bar Navigasi -->
        <nav class="bg-white shadow-md">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between"> <!-- Flex container untuk konten navigasi -->
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold">Dashboard</h1> <!-- Judul dashboard -->
                    </div>
                    <div class="flex items-center">
                        <p class="font-medium">Halo, {{ auth()->user()->name }}</p> <!-- Menampilkan nama pengguna yang sedang login -->
                        <a href="/logout" class="ml-4 text-gray-700 hover:text-gray-900">Logout</a> <!-- Tautan logout -->
                    </div>
                </div>
            </div>
        </nav>

        <!-- Konten Utama -->
        <div class="container mx-auto flex-grow px-4 py-8 sm:px-6 lg:px-8">
            <div class="mx-auto w-full max-w-2xl rounded-lg bg-white p-8 shadow-md">
                <h2 class="mb-6 text-2xl font-bold">Edit Laporan</h2> <!-- Judul form edit laporan -->
                <form action="/laporan/update/{{ $laporan->id }}" method="post"> <!-- Formulir dengan metode POST ke endpoint /laporan/update/{id} -->
                    @csrf <!-- Direktif Blade untuk menyertakan token CSRF -->
                    @method('PUT') <!-- Direktif Blade untuk mengubah metode form menjadi PUT -->

                    <!-- Input Tanggal -->
                    <div class="mb-4">
                        <label for="tanggal" class="mb-2 block text-sm font-bold text-gray-700">Tanggal</label> <!-- Label untuk input tanggal -->
                        <input type="date" name="tanggal" id="tanggal" value="{{ $laporan->tanggal }}"
                            class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"> <!-- Input tipe tanggal dengan nilai default dari laporan -->
                    </div>

                    <!-- Input Keterangan -->
                    <div class="mb-4">
                        <label for="keterangan" class="mb-2 block text-sm font-bold text-gray-700">Keterangan</label> <!-- Label untuk input keterangan -->
                        <input type="text" name="keterangan" id="keterangan" placeholder="Masukkan Keterangan" value="{{ $laporan->keterangan }}"
                            class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"> <!-- Input tipe teks dengan nilai default dari laporan -->
                    </div>

                    <!-- Input Nominal -->
                    <div class="mb-4">
                        <label for="nominal" class="mb-2 block text-sm font-bold text-gray-700">Nominal</label> <!-- Label untuk input nominal -->
                        <input type="text" name="formatted_nominal" id="formatted_nominal" placeholder="Masukkan Nominal"
                            value="Rp. {{ number_format($laporan->nominal, 0, ',', '.') }}"
                            class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"> <!-- Input nominal terformat dengan nilai default dari laporan -->
                        <input type="hidden" name="nominal" id="nominal" value="{{ $laporan->nominal }}"> <!-- Input tersembunyi untuk nilai nominal tanpa format -->
                    </div>

                    <!-- Input Tipe Laporan -->
                    <div class="mb-4">
                        <label for="tipe" class="mb-2 block text-sm font-bold text-gray-700">Tipe</label> <!-- Label untuk pilihan tipe laporan -->
                        <select name="tipe" id="tipe"
                            class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"> <!-- Pilihan dropdown -->
                            <option value="pemasukan" {{ $laporan->tipe == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option> <!-- Opsi pemasukan dengan kondisi terpilih -->
                            <option value="pengeluaran" {{ $laporan->tipe == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option> <!-- Opsi pengeluaran dengan kondisi terpilih -->
                        </select>
                    </div>

                    <!-- Tombol Submit dan Batal -->
                    <div class="flex items-center justify-between">
                        <button type="submit"
                            class="focus:shadow-outline rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700 focus:outline-none">Simpan</button> <!-- Tombol submit -->
                        <a href="/laporan" class="text-gray-500 hover:text-gray-700">Batal</a> <!-- Tautan batal -->
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white shadow-md">
            <div class="mx-auto max-w-7xl px-4 py-4 text-center sm:px-6 lg:px-8">
                <p class="text-gray-600">© 2024 Laporan Keuangan. All rights reserved.</p> <!-- Informasi hak cipta -->
            </div>
        </footer>
    </div>

    <!-- Script untuk Format Nominal Input -->
    <script>
        document.getElementById('formatted_nominal').addEventListener('input', function (e) {
            // Menghapus karakter non-numerik
            let value = e.target.value.replace(/\D/g, '');

            // Mengupdate input tersembunyi dengan nilai numerik
            document.getElementById('nominal').value = value;

            // Memformat angka dengan pemisah ribuan
            let formattedValue = new Intl.NumberFormat('id-ID').format(value);

            // Menampilkan nilai terformat
            e.target.value = 'Rp. ' + formattedValue;
        });
    </script>
</body>

</html>
