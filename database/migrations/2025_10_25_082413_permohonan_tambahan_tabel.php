<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vaccine_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->string('negara_tujuan', 100)->nullable();
            $table->date('tanggal_berangkat')->nullable();
            $table->string('jenis_vaksin', 100)->nullable();
            $table->string('nama_travel', 100)->nullable();
            $table->string('alamat_travel', 255)->nullable();
            $table->boolean('disetujui')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vaccine_requests');
    }
};
