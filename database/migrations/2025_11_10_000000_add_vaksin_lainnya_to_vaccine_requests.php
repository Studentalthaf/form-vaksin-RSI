<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vaccine_requests', function (Blueprint $table) {
            $table->text('vaksin_lainnya')->nullable()->after('jenis_vaksin')->comment('Jika pasien memilih "Lainnya", simpan teks custom di sini');
        });
    }

    public function down(): void
    {
        Schema::table('vaccine_requests', function (Blueprint $table) {
            $table->dropColumn('vaksin_lainnya');
        });
    }
};
