<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Screening extends Model
{
    use HasFactory;

    protected $fillable = [
        'pasien_id',
        'vaccine_request_id',
        'tanggal_screening',
        'petugas_id',
        'hasil_screening',
        'catatan',
    ];

    protected $casts = [
        'tanggal_screening' => 'datetime',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function vaccineRequest()
    {
        return $this->belongsTo(VaccineRequest::class, 'vaccine_request_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function answers()
    {
        return $this->hasMany(ScreeningAnswer::class, 'screening_id');
    }
}
