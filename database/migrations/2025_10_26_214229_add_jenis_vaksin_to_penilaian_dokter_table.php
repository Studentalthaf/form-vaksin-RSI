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
        Schema::table('penilaian_dokter', function (Blueprint $table) {
            $table->string('jenis_vaksin')->nullable()->after('sudah_vaksin_covid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian_dokter', function (Blueprint $table) {
            $table->dropColumn('jenis_vaksin');
        });
    }
};
