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
            $table->foreignId('petugas_id')->constrained('users')->onDelete('cascade'); // petugas RS
            $table->datetime('tanggal_screening')->nullable();
            $table->enum('hasil_screening', ['aman', 'perlu_perhatian', 'pending'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screenings');
    }
};
