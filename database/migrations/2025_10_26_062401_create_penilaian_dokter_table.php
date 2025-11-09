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
        Schema::create('nilai_screening', function (Blueprint $table) {
            $table->id();
            $table->foreignId('screening_id')->constrained('screenings')->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade')->comment('Admin yang memberi nilai');
            
            // Data vaksinasi dari vaccine_request
            $table->string('jenis_vaksin')->nullable()->comment('Jenis vaksin yang diminta');
            $table->string('negara_tujuan')->nullable()->comment('Negara tujuan (jika perjalanan)');
            
            // Field penilaian sesuai gambar
            $table->string('alergi_obat')->nullable(); // "Ada / tidak *"
            $table->string('alergi_vaksin')->nullable(); // "Ada / tidak *"
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
            
            // Hasil screening dan status
            $table->enum('hasil_screening', ['aman', 'perlu_perhatian', 'tidak_layak'])->default('aman');
            $table->text('catatan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_screening');
    }
};
