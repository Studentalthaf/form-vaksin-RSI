<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianDokter extends Model
{
    use HasFactory;

    protected $table = 'penilaian_dokter';

    protected $fillable = [
        'screening_id',
        'alergi_obat',
        'alergi_vasin',
        'sudah_vaksin_covid',
        'jenis_vaksin',
        'negara_tujuan',
        'nama_vaksin_covid',
        'dimana',
        'kapan',
        'tanggal_berangkat_umroh',
        'td',
        'nadi',
        'suhu',
        'tb',
        'bb',
        'catatan',
    ];

    protected $casts = [
        'tanggal_berangkat_umroh' => 'date',
    ];

    /**
     * Relasi ke Screening
     */
    public function screening()
    {
        return $this->belongsTo(Screening::class);
    }
}