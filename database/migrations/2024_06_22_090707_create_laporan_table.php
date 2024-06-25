<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * Metode ini dipanggil saat menjalankan migrasi kelas ini.
     * Ini membuat tabel baru bernama 'laporan' di database.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id(); // Menambahkan kolom id auto-increment
            $table->date('tanggal'); // Menambahkan kolom date 'tanggal'
            $table->text('keterangan'); // Menambahkan kolom text 'keterangan'
            $table->unsignedBigInteger('user_id'); // Menambahkan kolom unsignedBigInteger 'user_id' sebagai foreign key
            $table->double('nominal', 10, 2); // Menambahkan kolom double 'nominal' dengan total 10 digit dan 2 digit desimal
            $table->enum('tipe', ['pemasukan', 'pengeluaran']); // Menambahkan kolom enum 'tipe' dengan nilai 'pemasukan' atau 'pengeluaran'
            $table->double('saldo', 10, 2); // Menambahkan kolom double 'saldo' dengan total 10 digit dan 2 digit desimal
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade'); // Menambahkan foreign key constraint pada kolom 'user_id' yang mengacu ke kolom 'id' di tabel 'users' dengan aksi cascade pada delete dan update
            $table->timestamps(); // Menambahkan kolom timestamps 'created_at' dan 'updated_at'
        });
    }

    /**
     * Membalikkan migrasi.
     *
     * Metode ini dipanggil saat membatalkan migrasi kelas ini.
     * Ini menghapus tabel 'laporan' dari database.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan'); // Menghapus tabel 'laporan' jika ada
    }
};
