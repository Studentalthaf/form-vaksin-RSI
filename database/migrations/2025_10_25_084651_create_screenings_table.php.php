<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('screenings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->foreignId('vaccine_request_id')->nullable()->constrained('vaccine_requests')->onDelete('cascade');
            $table->datetime('tanggal_screening')->nullable();
            
            // Admin dan Dokter
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null')->comment('Admin yang review screening');
            $table->foreignId('dokter_id')->nullable()->constrained('users')->onDelete('set null')->comment('Dokter yang assigned untuk vaksinasi');
            
            // Hasil dan Status
            $table->enum('hasil_screening', ['aman', 'perlu_perhatian', 'tidak_layak', 'pending'])->default('pending');
            $table->enum('status_vaksinasi', ['belum_divaksin', 'proses_vaksinasi', 'dijadwalkan', 'sudah_divaksin'])->default('belum_divaksin');
            $table->datetime('tanggal_vaksinasi')->nullable();
            
            // Catatan
            $table->text('catatan')->nullable();
            
            // Pemeriksaan Fisik
            $table->string('tekanan_darah_sistol')->nullable()->comment('Tekanan darah sistol (mmHg)');
            $table->string('tekanan_darah_diastol')->nullable()->comment('Tekanan darah diastol (mmHg)');
            $table->string('nadi')->nullable()->comment('Denyut nadi (bpm)');
            $table->decimal('suhu', 4, 1)->nullable()->comment('Suhu tubuh (Celsius)');
            $table->decimal('berat_badan', 5, 2)->nullable()->comment('Berat badan (kg)');
            $table->decimal('tinggi_badan', 5, 2)->nullable()->comment('Tinggi badan (cm)');
            $table->integer('saturasi_oksigen')->nullable()->comment('Saturasi oksigen SpO2 (%)');
            $table->text('catatan_pemeriksaan')->nullable()->comment('Catatan hasil pemeriksaan fisik oleh admin');
            
            // Catatan Dokter
            $table->text('catatan_dokter')->nullable();
            
            // Tanda Tangan
            $table->string('tanda_tangan_pasien')->nullable()->comment('Path file tanda tangan pasien sebagai persetujuan');
            $table->string('tanda_tangan_keluarga')->nullable()->comment('Path file tanda tangan keluarga pasien sebagai persetujuan');
            $table->string('tanda_tangan_admin')->nullable()->comment('Path file tanda tangan admin sebagai verifikasi sebelum dikirim ke dokter');
            $table->string('tanda_tangan_dokter')->nullable()->comment('Path file tanda tangan dokter');
            
            // Konfirmasi Dokter
            $table->timestamp('tanggal_konfirmasi')->nullable();
            $table->string('status_konfirmasi')->default('belum_dikonfirmasi');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screenings');
    }
};
