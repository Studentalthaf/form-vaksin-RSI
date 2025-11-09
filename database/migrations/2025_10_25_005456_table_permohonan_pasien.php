<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasiens', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 20)->unique()->nullable()->comment('NIK Pasien');
            $table->string('nama', 100);
            $table->string('nomor_rm', 50)->nullable()->comment('Nomor Rekam Medis RSI');
            $table->string('nomor_paspor', 50)->nullable();
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->string('alamat', 255)->nullable();
            $table->string('no_telp', 20)->nullable();
            $table->string('foto_ktp')->nullable()->comment('Path file foto KTP');
            $table->string('foto_paspor')->nullable()->comment('Path file foto paspor');
            $table->enum('status_pasien', ['baru', 'lama'])->default('baru')->comment('Status pasien baru atau lama');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
