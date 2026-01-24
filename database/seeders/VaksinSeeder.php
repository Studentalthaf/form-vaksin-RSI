<?php

namespace Database\Seeders;

use App\Models\Vaksin;
use Illuminate\Database\Seeder;

class VaksinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vaksins = [
            [
                'nama_vaksin' => 'Yellow Fever (Demam Kuning)',
                'deskripsi' => 'Vaksin untuk mencegah penyakit demam kuning yang ditularkan oleh nyamuk',
                'urutan' => 1,
                'aktif' => true,
            ],
            [
                'nama_vaksin' => 'Meningitis',
                'deskripsi' => 'Vaksin untuk mencegah penyakit radang selaput otak',
                'urutan' => 2,
                'aktif' => true,
            ],
            [
                'nama_vaksin' => 'Polio',
                'deskripsi' => 'Vaksin untuk mencegah penyakit polio',
                'urutan' => 3,
                'aktif' => true,
            ],
            [
                'nama_vaksin' => 'Hepatitis A',
                'deskripsi' => 'Vaksin untuk mencegah penyakit hepatitis A',
                'urutan' => 4,
                'aktif' => true,
            ],
            [
                'nama_vaksin' => 'Hepatitis B',
                'deskripsi' => 'Vaksin untuk mencegah penyakit hepatitis B',
                'urutan' => 5,
                'aktif' => true,
            ],
            [
                'nama_vaksin' => 'Typhoid',
                'deskripsi' => 'Vaksin untuk mencegah penyakit tifus',
                'urutan' => 6,
                'aktif' => true,
            ],
            [
                'nama_vaksin' => 'Influenza',
                'deskripsi' => 'Vaksin untuk mencegah penyakit flu',
                'urutan' => 7,
                'aktif' => true,
            ],
            [
                'nama_vaksin' => 'Rabies',
                'deskripsi' => 'Vaksin untuk mencegah penyakit rabies',
                'urutan' => 8,
                'aktif' => true,
            ],
            [
                'nama_vaksin' => 'Japanese Encephalitis',
                'deskripsi' => 'Vaksin untuk mencegah penyakit radang otak Jepang',
                'urutan' => 9,
                'aktif' => true,
            ],
            [
                'nama_vaksin' => 'Tetanus',
                'deskripsi' => 'Vaksin untuk mencegah penyakit tetanus',
                'urutan' => 10,
                'aktif' => true,
            ],
            [
                'nama_vaksin' => 'MMR (Measles, Mumps, Rubella)',
                'deskripsi' => 'Vaksin untuk mencegah campak, gondongan, dan rubella',
                'urutan' => 11,
                'aktif' => true,
            ],
            [
                'nama_vaksin' => 'Varicella (Cacar Air)',
                'deskripsi' => 'Vaksin untuk mencegah penyakit cacar air',
                'urutan' => 12,
                'aktif' => true,
            ],
            [
                'nama_vaksin' => 'Lainnya',
                'deskripsi' => 'Jenis vaksin lainnya yang tidak termasuk dalam daftar',
                'urutan' => 99,
                'aktif' => true,
            ],
        ];

        foreach ($vaksins as $vaksin) {
            Vaksin::updateOrCreate(
                ['nama_vaksin' => $vaksin['nama_vaksin']],
                $vaksin
            );
        }

        $this->command->info('✅ Seeded ' . count($vaksins) . ' vaksin records');
    }
}
