<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;

    protected $table = 'pasiens';

    protected $fillable = [
        'nik',
        'nama',
        'nama_keluarga',
        'email',
        'nomor_rm',
        'nomor_paspor',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'pekerjaan',
        'alamat',
        'no_telp',
        'foto_ktp',
        'foto_paspor',
        'status_pasien',
    ];

    protected $appends = ['sim_rs'];

    /**
     * Accessor untuk SIM RS (menggunakan NIK sebagai identitas)
     */
    public function getSimRsAttribute()
    {
        return $this->nik ?? 'N/A';
    }

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
