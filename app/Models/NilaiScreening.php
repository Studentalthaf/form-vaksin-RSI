<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiScreening extends Model
{
    use HasFactory;

    protected $table = 'nilai_screening';

    protected $fillable = [
        'screening_id',
        'admin_id',
        'alergi_obat',
        'alergi_vaksin',
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
        'hasil_screening',
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

    /**
     * Relasi ke Admin yang memberi nilai
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}