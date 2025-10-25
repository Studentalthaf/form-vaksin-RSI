<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ScreeningAnswer;
use App\Models\ScreeningQuestionCategory;


class ScreeningQuestion extends Model
{
    protected $fillable = [
        'category_id',
        'pertanyaan',
        'tipe_jawaban',
        'pilihan_jawaban',
        'urutan',
        'wajib',
        'aktif',
    ];

    protected $casts = [
        'pilihan_jawaban' => 'array',
        'wajib' => 'boolean',
        'aktif' => 'boolean',
    ];

    /**
     * Relasi ke kategori
     */
    public function category()
    {
        return $this->belongsTo(ScreeningQuestionCategory::class, 'category_id');
    }

    /**
     * Relasi ke jawaban screening
     */
    public function answers()
    {
        return $this->hasMany(ScreeningAnswer::class, 'question_id');
    }
}
