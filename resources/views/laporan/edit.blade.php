<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Laporan</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'figtree', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen flex-col">
        <!-- Navbar -->
        <nav class="bg-white shadow-md">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold">Edit Laporan</h1>
                    </div>
                    <div>
                        <p>Halo, Admin!</p>
                        <a href="/logout" class="ml-4 text-gray-700 hover:text-gray-900">Logout</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container mx-auto flex-grow px-4 py-8 sm:px-6 lg:px-8">
            <div class="mx-auto w-full max-w-2xl rounded-lg bg-white p-8 shadow-md">
                <h2 class="mb-6 text-2xl font-bold">Edit Laporan</h2>
                <form action="/laporan/update/{{ $laporan->id }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="tanggal" class="mb-2 block text-sm font-bold text-gray-700">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ $laporan->tanggal }}"
                            class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none">
                    </div>
                    <div class="mb-4">
                        <label for="keterangan" class="mb-2 block text-sm font-bold text-gray-700">Keterangan</label>
                        <input type="text" name="keterangan" id="keterangan" placeholder="Masukkan Keterangan" value="{{ $laporan->keterangan }}"
                            class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none">
                    </div>
                    <div class="mb-4">
                        <label for="nominal" class="mb-2 block text-sm font-bold text-gray-700">Nominal</label>
                        <input type="text" name="formatted_nominal" id="formatted_nominal" placeholder="Masukkan Nominal"
                            value="Rp. {{ number_format($laporan->nominal, 0, ',', '.') }}"
                            class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none">
                        <input type="hidden" name="nominal" id="nominal" value="{{ $laporan->nominal }}">
                    </div>
                    <div class="mb-4">
                        <label for="tipe" class="mb-2 block text-sm font-bold text-gray-700">Tipe</label>
                        <select name="tipe" id="tipe"
                            class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none">
                            <option value="pemasukan" {{ $laporan->tipe == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="pengeluaran" {{ $laporan->tipe == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit"
                            class="focus:shadow-outline rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700 focus:outline-none">Simpan</button>
                        <a href="/laporan" class="text-gray-500 hover:text-gray-700">Batal</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white shadow-md">
            <div class="mx-auto max-w-7xl px-4 py-4 text-center sm:px-6 lg:px-8">
                <p class="text-gray-600">© 2024 Laporan Keuangan. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <script>
        document.getElementById('formatted_nominal').addEventListener('input', function (e) {
            // Remove non-numeric characters
            let value = e.target.value.replace(/\D/g, '');

            // Update the hidden input with numeric value
            document.getElementById('nominal').value = value;

            // Format number with thousands separator
            let formattedValue = new Intl.NumberFormat('id-ID').format(value);

            // Display formatted value
            e.target.value = 'Rp. ' + formattedValue;
        });
    </script>
</body>

</html>
