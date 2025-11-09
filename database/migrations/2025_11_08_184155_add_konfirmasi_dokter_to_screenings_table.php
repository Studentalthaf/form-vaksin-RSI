<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('screenings', function (Blueprint $table) {
            $table->text('catatan_dokter')->nullable()->after('catatan');
            $table->string('tanda_tangan_dokter')->nullable()->after('catatan_dokter');
            $table->timestamp('tanggal_konfirmasi')->nullable()->after('tanda_tangan_dokter');
            $table->string('status_konfirmasi')->default('belum_dikonfirmasi')->after('tanggal_konfirmasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('screenings', function (Blueprint $table) {
            $table->dropColumn(['catatan_dokter', 'tanda_tangan_dokter', 'tanggal_konfirmasi', 'status_konfirmasi']);
        });
    }
};
