<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('screening_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('screening_id')->constrained('screenings')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('screening_questions')->onDelete('cascade');
            $table->text('jawaban')->nullable(); // Changed from enum to text to support all answer types
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screening_answers');
    }
};
