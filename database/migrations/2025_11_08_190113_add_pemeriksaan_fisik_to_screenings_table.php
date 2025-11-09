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
            $table->string('tekanan_darah_sistol')->nullable()->after('catatan')->comment('Tekanan darah sistol (mmHg)');
            $table->string('tekanan_darah_diastol')->nullable()->after('tekanan_darah_sistol')->comment('Tekanan darah diastol (mmHg)');
            $table->string('nadi')->nullable()->after('tekanan_darah_diastol')->comment('Denyut nadi (bpm)');
            $table->decimal('suhu', 4, 1)->nullable()->after('nadi')->comment('Suhu tubuh (Celsius)');
            $table->decimal('berat_badan', 5, 2)->nullable()->after('suhu')->comment('Berat badan (kg)');
            $table->decimal('tinggi_badan', 5, 2)->nullable()->after('berat_badan')->comment('Tinggi badan (cm)');
            $table->integer('saturasi_oksigen')->nullable()->after('tinggi_badan')->comment('Saturasi oksigen SpO2 (%)');
            $table->text('catatan_pemeriksaan')->nullable()->after('saturasi_oksigen')->comment('Catatan hasil pemeriksaan fisik oleh admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('screenings', function (Blueprint $table) {
            $table->dropColumn([
                'tekanan_darah_sistol',
                'tekanan_darah_diastol',
                'nadi',
                'suhu',
                'berat_badan',
                'tinggi_badan',
                'saturasi_oksigen',
                'catatan_pemeriksaan'
            ]);
        });
    }
};
