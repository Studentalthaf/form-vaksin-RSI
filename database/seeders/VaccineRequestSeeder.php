<?php

namespace Database\Seeders;

use App\Models\Pasien;
use App\Models\VaccineRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class VaccineRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Negara tujuan populer untuk vaksinasi
        $negaraTujuan = [
            'Arab Saudi',
            'Turki',
            'Malaysia',
            'Singapura',
            'Thailand',
            'Jepang',
            'Korea Selatan',
            'Amerika Serikat',
            'Inggris',
            'Perancis',
            'Jerman',
            'Australia',
            'Belanda',
            'Mesir',
            'Uni Emirat Arab',
        ];

        // Jenis vaksin
        $jenisVaksin = [
            'Meningitis',
            'Yellow Fever',
            'Hepatitis A',
            'Hepatitis B',
            'Typhoid',
            'Japanese Encephalitis',
            'Rabies',
            'Influenza',
            'MMR (Measles, Mumps, Rubella)',
            'Polio',
            'Tetanus',
            'Meningitis + Yellow Fever',
            'Hepatitis A + B',
        ];

        // Nama travel agent
        $namaTravel = [
            'PT. Arminareka Perdana',
            'PT. Patuna Tour & Travel',
            'PT. Sentosa Wisata',
            'PT. Al-Hijrah Tour',
            'PT. Mitra Haji Indonesia',
            'PT. Garuda Orient Holidays',
            'PT. Anugerah Haji Indonesia',
            'PT. Sahara Wisata',
            'PT. Bahagia Tour & Travel',
            'PT. Mulia Haji & Umroh',
        ];

        // Alamat travel di berbagai kota
        $alamatTravel = [
            'Jl. Thamrin No. 15, Jakarta Pusat',
            'Jl. Malioboro No. 88, Yogyakarta',
            'Jl. Veteran No. 123, Surabaya',
            'Jl. Asia Afrika No. 45, Bandung',
            'Jl. Ahmad Yani No. 67, Semarang',
            'Jl. Sultan Hasanuddin No. 90, Makassar',
            'Jl. Sudirman No. 156, Medan',
            'Jl. Diponegoro No. 78, Palembang',
            'Jl. Gajah Mada No. 234, Tangerang',
            'Jl. Margonda Raya No. 345, Depok',
        ];

        $pasiens = Pasien::all();

        foreach ($pasiens as $pasien) {
            $isPerjalanan = $faker->boolean(70); // 70% perjalanan, 30% vaksin biasa
            
            // Update nomor paspor pasien jika perjalanan
            if ($isPerjalanan && !$pasien->nomor_paspor) {
                $pasien->update([
                    'nomor_paspor' => strtoupper($faker->bothify('??######'))
                ]);
            } elseif (!$isPerjalanan) {
                // Set null untuk vaksin biasa
                $pasien->update([
                    'nomor_paspor' => null
                ]);
            }
            
            VaccineRequest::create([
                'pasien_id' => $pasien->id,
                'is_perjalanan' => $isPerjalanan,
                'jenis_vaksin' => $faker->randomElement($jenisVaksin),
                'negara_tujuan' => $isPerjalanan ? $faker->randomElement($negaraTujuan) : null,
                'tanggal_berangkat' => $isPerjalanan ? $faker->dateTimeBetween('now', '+6 months')->format('Y-m-d') : null,
                'nama_travel' => $isPerjalanan ? $faker->randomElement($namaTravel) : null,
                'alamat_travel' => $isPerjalanan ? $faker->randomElement($alamatTravel) : null,
                'disetujui' => $faker->boolean(70), // 70% disetujui
            ]);
        }

        $this->command->info('✓ Vaccine Request seeder berhasil! ' . $pasiens->count() . ' permohonan vaksinasi dibuat.');
    }
}
