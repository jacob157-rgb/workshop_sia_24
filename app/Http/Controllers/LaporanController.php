<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $user = auth()->user()->id;

        // Mengambil semua laporan milik user, diurutkan berdasarkan tanggal secara ascending
        $laporan = Laporan::where('user_id', $user)->orderBy('tanggal', 'asc')->get();

        // Menghitung total pemasukan dan pengeluaran
        $totalPemasukan = Laporan::where('user_id', $user)->where('tipe', 'pemasukan')->sum('nominal');
        $totalPengeluaran = Laporan::where('user_id', $user)->where('tipe', 'pengeluaran')->sum('nominal');

        // Mengambil saldo terakhir berdasarkan tanggal terbaru
        $saldoTerakhir = Laporan::where('user_id', $user)->orderBy('tanggal', 'desc')->first();
        $totalSaldo = $saldoTerakhir ? $saldoTerakhir->saldo : 0;

        // Menyiapkan data untuk dikirim ke view
        $data = [
            'laporan' => $laporan,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'totalSaldo' => $totalSaldo,
        ];

        // Mengirim data ke view 'laporan.index'
        return view('laporan.index', $data);
    }

    public function create()
    {
        return view('laporan.create');
    }

    public function edit($id)
    {
        $data = [
            'laporan' => Laporan::find($id),
        ];
        return view('laporan.edit', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
            'nominal' => 'required|numeric',
            'tipe' => 'required|in:pemasukan,pengeluaran',
        ]);

        $user = auth()->user()->id;
        $nominal = $request->nominal;

        // Fetch the latest saldo for the user
        $latestReport = Laporan::where('user_id', $user)->orderBy('id', 'desc')->first();
        $currentSaldo = $latestReport ? $latestReport->saldo : 0;

        if ($request->tipe == 'pemasukan') {
            $currentSaldo += $nominal;
        } elseif ($request->tipe == 'pengeluaran') {
            $currentSaldo -= $nominal;
        }

        Laporan::create([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'user_id' => $user,
            'nominal' => $nominal,
            'tipe' => $request->tipe,
            'saldo' => $currentSaldo,
        ]);

        return redirect('/laporan')->with('success', 'Laporan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
            'nominal' => 'required|numeric',
            'tipe' => 'required|in:pemasukan,pengeluaran',
        ]);

        $user = auth()->user()->id;
        $laporan = Laporan::findOrFail($id);

        // Fetch the original nominal and tipe
        $originalNominal = $laporan->nominal;
        $originalTipe = $laporan->tipe;

        // Update laporan fields
        $laporan->tanggal = $request->tanggal;
        $laporan->keterangan = $request->keterangan;
        $laporan->nominal = $request->nominal;
        $laporan->tipe = $request->tipe;

        // Calculate saldo changes
        $latestReport = Laporan::where('user_id', $user)->orderBy('id', 'desc')->first();
        $currentSaldo = $latestReport ? $latestReport->saldo : 0;

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

        $laporan->saldo = $currentSaldo;
        $laporan->save();

        // Update subsequent laporan saldos
        $subsequentReports = Laporan::where('user_id', $user)->where('id', '>', $id)->orderBy('id', 'asc')->get();
        foreach ($subsequentReports as $report) {
            if ($report->tipe == 'pemasukan') {
                $currentSaldo += $report->nominal;
            } elseif ($report->tipe == 'pengeluaran') {
                $currentSaldo -= $report->nominal;
            }
            $report->saldo = $currentSaldo;
            $report->save();
        }

        return redirect('/laporan')->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $laporan = Laporan::find($id);
        $laporan->delete();
        return redirect('/laporan')->with('success', 'Laporan berhasil dihapus.');
    }
}
