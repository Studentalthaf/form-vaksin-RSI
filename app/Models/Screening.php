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
        'admin_id',
        'dokter_id',
        'hasil_screening',
        'status_vaksinasi',
        'tanggal_vaksinasi',
        'catatan',
        'tekanan_darah_sistol',
        'tekanan_darah_diastol',
        'nadi',
        'suhu',
        'berat_badan',
        'tinggi_badan',
        'saturasi_oksigen',
        'catatan_pemeriksaan',
        'catatan_dokter',
        'tanda_tangan_pasien',
        'tanda_tangan_keluarga',
        'tanda_tangan_dokter',
        'tanda_tangan_admin',
        'tanggal_konfirmasi',
        'status_konfirmasi',
    ];

    protected $casts = [
        'tanggal_screening' => 'datetime',
        'tanggal_vaksinasi' => 'datetime',
        'tanggal_konfirmasi' => 'datetime',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function vaccineRequest()
    {
        return $this->belongsTo(VaccineRequest::class, 'vaccine_request_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    public function answers()
    {
        return $this->hasMany(ScreeningAnswer::class, 'screening_id');
    }

    // Alias untuk backward compatibility
    public function screeningAnswers()
    {
        return $this->answers();
    }

    public function nilaiScreening()
    {
        return $this->hasOne(NilaiScreening::class, 'screening_id');
    }

    // Alias untuk backward compatibility
    public function penilaian()
    {
        return $this->nilaiScreening();
    }
}
