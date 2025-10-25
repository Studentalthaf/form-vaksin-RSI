<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('screening_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('screening_question_categories')->onDelete('set null');
            $table->text('pertanyaan');
            $table->enum('tipe_jawaban', ['ya_tidak', 'pilihan_ganda', 'text'])->default('ya_tidak');
            $table->text('pilihan_jawaban')->nullable(); // JSON untuk pilihan ganda
            $table->integer('urutan')->default(0);
            $table->boolean('wajib')->default(true);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screening_questions');
    }
};
