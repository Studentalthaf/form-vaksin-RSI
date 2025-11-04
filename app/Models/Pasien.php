<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;

    protected $table = 'pasiens';

    protected $fillable = [
        'sim_rs',
        'nama',
        'nomor_paspor',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'pekerjaan',
        'alamat',
        'no_telp',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function vaccineRequests()
    {
        return $this->hasMany(VaccineRequest::class, 'pasien_id');
    }

    public function screenings()
    {
        return $this->hasMany(Screening::class, 'pasien_id');
    }
}
