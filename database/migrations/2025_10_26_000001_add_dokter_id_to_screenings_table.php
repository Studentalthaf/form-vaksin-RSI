<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('screenings', function (Blueprint $table) {
            $table->foreignId('admin_id')->nullable()->after('petugas_id')->constrained('users')->onDelete('set null')->comment('Admin yang review screening');
            $table->foreignId('dokter_id')->nullable()->after('admin_id')->constrained('users')->onDelete('set null')->comment('Dokter yang assigned untuk vaksinasi');
            $table->enum('status_vaksinasi', ['belum_divaksin', 'proses_vaksinasi', 'dijadwalkan', 'sudah_divaksin'])->default('belum_divaksin')->after('hasil_screening');
            $table->datetime('tanggal_vaksinasi')->nullable()->after('status_vaksinasi');
        });
    }

    public function down(): void
    {
        Schema::table('screenings', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropForeign(['dokter_id']);
            $table->dropColumn(['admin_id', 'dokter_id', 'status_vaksinasi', 'tanggal_vaksinasi']);
        });
    }
};
