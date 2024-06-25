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
 * Ini membuat tabel baru bernama 'users' di database.
 *
 * @return void
 */
public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id(); // Menambahkan kolom id auto-increment
        $table->string('name'); // Menambahkan kolom string 'name'
        $table->string('email')->unique(); // Menambahkan kolom string 'email' yang unik
        $table->string('password'); // Menambahkan kolom string 'password'
        $table->enum('role', ["admin", "user"]); // Menambahkan kolom enum 'role' dengan nilai 'admin' atau 'user'
        $table->timestamps(); // Menambahkan kolom timestamps 'created_at' dan 'updated_at'
    });
}

    /**
 * Membalikkan migrasi.
 *
 * Metode ini dipanggil saat membatalkan migrasi kelas ini.
 * Ini menghapus tabel 'users' dari database.
 *
 * @return void
 */
public function down(): void
{
    Schema::dropIfExists('users');
}
};
