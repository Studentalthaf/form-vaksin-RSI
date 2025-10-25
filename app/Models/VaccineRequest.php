<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccineRequest extends Model
{
    use HasFactory;

    protected $table = 'vaccine_requests';

    protected $fillable = [
        'pasien_id',
        'negara_tujuan',
        'tanggal_berangkat',
        'jenis_vaksin',
        'nama_travel',
        'alamat_travel',
        'disetujui',
    ];

    protected $casts = [
        'tanggal_berangkat' => 'date',
        'disetujui' => 'boolean',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }
}
