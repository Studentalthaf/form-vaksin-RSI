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
        'is_perjalanan',
        'negara_tujuan',
        'tanggal_berangkat',
        'jenis_vaksin',
        'nama_travel',
        'alamat_travel',
        'disetujui',
    ];

    protected $casts = [
        'tanggal_berangkat' => 'date',
        'is_perjalanan' => 'boolean',
        'disetujui' => 'boolean',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }
}
