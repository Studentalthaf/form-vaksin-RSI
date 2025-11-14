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
        // MySQL tidak support ALTER ENUM langsung, jadi kita perlu modify column
        DB::statement("ALTER TABLE `screenings` MODIFY COLUMN `hasil_screening` ENUM('aman', 'perlu_perhatian', 'tidak_layak', 'pending') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `screenings` MODIFY COLUMN `hasil_screening` ENUM('aman', 'perlu_perhatian', 'pending') DEFAULT 'pending'");
    }
};
