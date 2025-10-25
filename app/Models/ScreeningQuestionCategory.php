<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScreeningQuestionCategory extends Model
{
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'urutan',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    /**
     * Relasi ke pertanyaan
     */
    public function questions()
    {
        return $this->hasMany(ScreeningQuestion::class, 'category_id');
    }
}
