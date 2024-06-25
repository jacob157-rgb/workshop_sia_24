<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
 * Menampilkan daftar laporan untuk user terautentikasi.
 *
 * @return \Illuminate\View\View
 */
public function index()
{
    // Mendapatkan ID user yang sedang login
    $user = auth()->user()->id;

    // Mengambil semua laporan milik user, diurutkan berdasarkan tanggal secara ascending
    $laporan = Laporan::where('user_id', $user)->orderBy('id', 'asc')->get();

    // Menghitung total pemasukan dan pengeluaran
    $totalPemasukan = Laporan::where('user_id', $user)->where('tipe', 'pemasukan')->sum('nominal');
    $totalPengeluaran = Laporan::where('user_id', $user)->where('tipe', 'pengeluaran')->sum('nominal');

    // Mengambil saldo terakhir berdasarkan tanggal terbaru
    $saldoTerakhir = Laporan::where('user_id', $user)->orderBy('tanggal', 'desc')->first();
    $totalSaldo = $saldoTerakhir ? $saldoTerakhir->saldo : 0;

    // Menyiapkan data yang akan dikirim ke view
    $data = [
        'laporan' => $laporan,
        'totalPemasukan' => $totalPemasukan,
        'totalPengeluaran' => $totalPengeluaran,
        'totalSaldo' => $totalSaldo,
    ];

    // Mengirim data ke view 'laporan.index'
    return view('laporan.index', $data);
}

    /**
     * Menampilkan form untuk membuat laporan baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('laporan.create');
    }

    /**
 * Menampilkan form untuk mengedit laporan.
 *
 * @param  int  $id ID laporan yang akan diedit
 * @return \Illuminate\View\View View yang menampilkan form edit laporan
 *
 * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika laporan dengan ID tersebut tidak ditemukan
 */
public function edit($id)
{
    // Membuat array data yang berisi laporan yang akan diedit
    $data = [
        'laporan' => Laporan::find($id),
    ];

    // Mengembalikan view 'laporan.edit' dengan data yang dibutuhkan
    return view('laporan.edit', $data);
}

    /**
 * Menyimpan laporan baru ke database.
 *
 * @param  \Illuminate\Http\Request  $request Request yang berisi data laporan baru
 * @return \Illuminate\Http\RedirectResponse Redirect response ke halaman daftar laporan dengan pesan sukses
 *
 * @throws \Illuminate\Validation\ValidationException Jika validasi gagal
 */
public function store(Request $request)
{
    // Melakukan validasi input
    $request->validate([
        'tanggal' => 'required|date',
        'keterangan' => 'required|string',
        'nominal' => 'required|numeric',
        'tipe' => 'required|in:pemasukan,pengeluaran',
    ]);

    // Mendapatkan ID user yang sedang login
    $user = auth()->user()->id;
    $nominal = $request->nominal;

    // Mengambil saldo terakhir untuk user
    $latestReport = Laporan::where('user_id', $user)->orderBy('id', 'desc')->first();
    $currentSaldo = $latestReport ? $latestReport->saldo : 0;

    // Menghitung saldo baru berdasarkan tipe laporan
    if ($request->tipe == 'pemasukan') {
        $currentSaldo += $nominal;
    } elseif ($request->tipe == 'pengeluaran') {
        $currentSaldo -= $nominal;
    }

    // Simpan laporan baru ke database
    Laporan::create([
        'tanggal' => $request->tanggal,
        'keterangan' => $request->keterangan,
        'user_id' => $user,
        'nominal' => $nominal,
        'tipe' => $request->tipe,
        'saldo' => $currentSaldo,
    ]);

    // Redirect ke halaman daftar laporan dengan pesan sukses
    return redirect('/laporan')->with('success', 'Laporan berhasil ditambahkan.');
}

    /**
 * Mengupdate laporan yang sudah ada di database.
 *
 * @param  \Illuminate\Http\Request  $request Request yang berisi data yang diperlukan untuk update laporan
 * @param  int  $id ID laporan yang akan diupdate
 * @return \Illuminate\Http\RedirectResponse Redirect response ke halaman daftar laporan dengan pesan sukses jika update berhasil
 *
 * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika laporan dengan ID tersebut tidak ditemukan
 */
public function update(Request $request, $id)
{
    // Melakukan validasi input
    $request->validate([
        'tanggal' => 'required|date',
        'keterangan' => 'required|string',
        'nominal' => 'required|numeric',
        'tipe' => 'required|in:pemasukan,pengeluaran',
    ]);

    // Mendapatkan ID user yang sedang login
    $user = auth()->user()->id;

    // Mencari laporan yang akan diupdate
    $laporan = Laporan::findOrFail($id);

    // Ambil nominal dan tipe asli laporan
    $originalNominal = $laporan->nominal;
    $originalTipe = $laporan->tipe;

    // Update field laporan
    $laporan->tanggal = $request->tanggal;
    $laporan->keterangan = $request->keterangan;
    $laporan->nominal = $request->nominal;
    $laporan->tipe = $request->tipe;

    // Hitung perubahan saldo
    $latestReport = Laporan::where('user_id', $user)->orderBy('id', 'desc')->first();
    $currentSaldo = $latestReport ? $latestReport->saldo : 0;

    // Menghitung saldo berdasarkan perubahan nominal dan tipe laporan
    if ($originalTipe == 'pemasukan') {
        $currentSaldo -= $originalNominal;
    } elseif ($originalTipe == 'pengeluaran') {
        $currentSaldo += $originalNominal;
    }

    if ($request->tipe == 'pemasukan') {
        $currentSaldo += $request->nominal;
    } elseif ($request->tipe == 'pengeluaran') {
        $currentSaldo -= $request->nominal;
    }

    // Menyimpan saldo yang dihitung ke dalam laporan
    $laporan->saldo = $currentSaldo;
    $laporan->save();

    // Mengupdate saldo laporan berikutnya
    $subsequentReports = Laporan::where('user_id', $user)->where('id', '>', $id)->orderBy('id', 'asc')->get();
    foreach ($subsequentReports as $report) {
        if ($report->tipe == 'pemasukan') {
            $currentSaldo += $report->nominal;
        } elseif ($report->tipe == 'pengeluaran') {
            $currentSaldo -= $report->nominal;
        }
        // Menyimpan saldo yang dihitung ke dalam laporan berikutnya
        $report->saldo = $currentSaldo;
        $report->save();
    }

    // Redirect ke halaman daftar laporan dengan pesan sukses
    return redirect('/laporan')->with('success', 'Laporan berhasil diperbarui.');
}

    /**
 * Menghapus laporan dari database.
 *
 * @param int $id ID laporan yang akan dihapus
 * @return \Illuminate\Http\RedirectResponse Redirect response ke halaman daftar laporan dengan pesan sukses jika penghapusan berhasil
 *
 * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika laporan dengan ID tersebut tidak ditemukan
 */
public function destroy($id)
{
    // Mencari laporan yang akan dihapus
    $laporan = Laporan::find($id);

    // Menghapus laporan dari database
    $laporan->delete();

    // Redirect ke halaman daftar laporan dengan pesan sukses
    return redirect('/laporan')->with('success', 'Laporan berhasil dihapus.');
}
}
