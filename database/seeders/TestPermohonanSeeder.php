<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pasien;
use App\Models\VaccineRequest;

class TestPermohonanSeeder extends Seeder
{
    public function run(): void
    {
        // Buat pasien test
        $pasien = Pasien::create([
            'nama' => 'Ahmad Hidayat',
            'nomor_paspor' => 'A1234567',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-05-15',
            'jenis_kelamin' => 'L',
            'pekerjaan' => 'Karyawan Swasta',
            'alamat' => 'Jl. Merdeka No. 123, Jakarta Pusat',
            'no_telp' => '081234567890',
        ]);

        // Buat permohonan vaksin
        VaccineRequest::create([
            'pasien_id' => $pasien->id,
            'negara_tujuan' => 'Arab Saudi',
            'tanggal_berangkat' => '2025-12-15',
            'jenis_vaksin' => 'Meningitis ACWY',
            'nama_travel' => 'Al-Hijrah Travel',
            'alamat_travel' => 'Jl. Raya Condet, Jakarta Timur',
            'disetujui' => false,
        ]);

        echo "✓ Test permohonan berhasil dibuat!\n";
    }
}
