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
        Schema::create('penilaian_dokter', function (Blueprint $table) {
            $table->id();
            $table->foreignId('screening_id')->constrained('screenings')->onDelete('cascade');
            
            // Data vaksinasi dari vaccine_request
            $table->string('jenis_vaksin')->nullable()->comment('Jenis vaksin yang diminta');
            $table->string('negara_tujuan')->nullable()->comment('Negara tujuan (jika perjalanan)');
            
            // Field penilaian sesuai gambar
            $table->string('alergi_obat')->nullable(); // "Ada / tidak *"
            $table->string('alergi_vasin')->nullable(); // "Ada / tidak *"
            $table->enum('sudah_vaksin_covid', ['1', '2', 'booster'])->nullable(); // "1 / 2 / booster"
            $table->string('nama_vaksin_covid')->nullable(); // "Nama vaksin covid 19"
            $table->text('dimana')->nullable(); // Dimana
            $table->text('kapan')->nullable(); // Kapan
            $table->date('tanggal_berangkat_umroh')->nullable();
            
            // Tanda vital
            $table->string('td')->nullable(); // Tekanan Darah
            $table->string('nadi')->nullable();
            $table->string('suhu')->nullable();
            $table->string('tb')->nullable(); // Tinggi Badan
            $table->string('bb')->nullable(); // Berat Badan
            
            // Catatan tambahan
            $table->text('catatan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_dokter');
    }
};
