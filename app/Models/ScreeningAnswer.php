<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreeningAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'screening_id',
        'question_id',
        'jawaban',
        'keterangan',
    ];

    public function screening()
    {
        return $this->belongsTo(Screening::class, 'screening_id');
    }

    public function question()
    {
        return $this->belongsTo(ScreeningQuestion::class, 'question_id');
    }
}
