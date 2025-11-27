<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pasiens', function (Blueprint $table) {
            // Add new columns
            $table->string('passport_halaman_pertama')->nullable()->after('foto_ktp')->comment('Path file foto paspor halaman pertama');
            $table->string('passport_halaman_kedua')->nullable()->after('passport_halaman_pertama')->comment('Path file foto paspor halaman kedua');
        });
        
        // Copy data from foto_paspor to passport_halaman_pertama
        DB::statement('UPDATE pasiens SET passport_halaman_pertama = foto_paspor WHERE foto_paspor IS NOT NULL');
        
        // Drop old column
        Schema::table('pasiens', function (Blueprint $table) {
            $table->dropColumn('foto_paspor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pasiens', function (Blueprint $table) {
            // Add back foto_paspor
            $table->string('foto_paspor')->nullable()->after('foto_ktp')->comment('Path file foto paspor');
        });
        
        // Copy data back
        DB::statement('UPDATE pasiens SET foto_paspor = passport_halaman_pertama WHERE passport_halaman_pertama IS NOT NULL');
        
        // Drop new columns
        Schema::table('pasiens', function (Blueprint $table) {
            $table->dropColumn(['passport_halaman_pertama', 'passport_halaman_kedua']);
        });
    }
};
