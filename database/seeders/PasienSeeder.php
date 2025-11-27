<?php

namespace Database\Seeders;

use App\Models\Pasien;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Indonesian locale

        // Data pekerjaan Indonesia
        $pekerjaan = [
            'Pegawai Negeri Sipil',
            'Karyawan Swasta',
            'Wiraswasta',
            'Guru',
            'Dokter',
            'Perawat',
            'Mahasiswa',
            'Dosen',
            'Pengusaha',
            'Pedagang',
            'Buruh',
            'Petani',
            'Nelayan',
            'Ibu Rumah Tangga',
            'Pensiunan',
        ];

        // Kota-kota di Indonesia
        $kota = [
            'Jakarta',
            'Surabaya',
            'Bandung',
            'Medan',
            'Semarang',
            'Makassar',
            'Palembang',
            'Tangerang',
            'Depok',
            'Bekasi',
            'Yogyakarta',
            'Malang',
            'Bogor',
            'Batam',
            'Pekanbaru',
        ];

        for ($i = 1; $i <= 10; $i++) {
            $jenisKelamin = $faker->randomElement(['L', 'P']);
            $tempatLahir = $faker->randomElement($kota);
            
            // Generate NIK 16 digit (simulasi)
            $nik = $faker->numerify('################');
            
            Pasien::create([
                'nik' => $nik,
                'nama' => $jenisKelamin === 'L' ? $faker->name('male') : $faker->name('female'),
                'nomor_rm' => $faker->boolean(50) ? 'RM' . $faker->numerify('######') : null, // 50% punya RM (pasien lama)
                'nomor_paspor' => $faker->boolean(30) ? $faker->bothify('?#######') : null, // 30% punya paspor
                'tempat_lahir' => $tempatLahir,
                'tanggal_lahir' => $faker->date('Y-m-d', '-25 years'), // Umur minimal 25 tahun
                'jenis_kelamin' => $jenisKelamin,
                'pekerjaan' => $faker->randomElement($pekerjaan),
                'alamat' => $faker->address(),
                'no_telp' => $faker->numerify('08##########'),
                'status_pasien' => $faker->randomElement(['baru', 'lama']),
                'foto_ktp' => null, // Akan diisi saat upload
                'passport_halaman_pertama' => null, // Akan diisi saat upload
                'passport_halaman_kedua' => null, // Akan diisi saat upload
            ]);
        }

        $this->command->info('✓ Pasien seeder berhasil! 10 data pasien dibuat.');
    }
}
