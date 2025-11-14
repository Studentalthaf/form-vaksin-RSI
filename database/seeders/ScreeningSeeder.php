<?php

namespace Database\Seeders;

use App\Models\Screening;
use App\Models\ScreeningAnswer;
use App\Models\ScreeningQuestion;
use App\Models\User;
use App\Models\VaccineRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ScreeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Get all vaccine requests
        $vaccineRequests = VaccineRequest::with('pasien')->get();
        
        // Get all questions
        $questions = ScreeningQuestion::all();
        
        // Get admin user
        $admin = User::where('role', 'admin_rumah_sakit')->first();
        
        // Get dokter user
        $dokter = User::where('role', 'dokter')->first();

        if (!$admin) {
            $this->command->error('❌ Admin user tidak ditemukan! Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        foreach ($vaccineRequests as $vaccineRequest) {
            // 80% chance to have screening (some might not have been screened yet)
            if ($faker->boolean(80)) {
                $tanggalScreening = $faker->dateTimeBetween('-30 days', 'now');
                
                // Create screening
                $screening = Screening::create([
                    'pasien_id' => $vaccineRequest->pasien_id,
                    'vaccine_request_id' => $vaccineRequest->id,
                    'tanggal_screening' => $tanggalScreening,
                    'hasil_screening' => 'pending', // Will be updated after answers
                    'catatan' => 'Screening sedang diproses',
                ]);

                // Create answers for each question
                $hasMasalah = false;
                $catatanArray = [];

                foreach ($questions as $question) {
                    // Randomize answers - most "Tidak", some "Ya"
                    $isYa = $faker->boolean(30); // 30% kemungkinan jawab "Ya"
                    
                    $keterangan = null;
                    if ($isYa) {
                        $hasMasalah = true;
                        
                        // Generate keterangan based on question
                        $keterangan = $this->generateKeterangan($question->pertanyaan, $faker);
                        $catatanArray[] = $question->pertanyaan . ': ' . $keterangan;
                    }

                    // Ensure jawaban is always set - never null or empty
                    ScreeningAnswer::create([
                        'screening_id' => $screening->id,
                        'question_id' => $question->id,
                        'jawaban' => $isYa ? 'Ya' : 'Tidak', // Always 'Ya' or 'Tidak', never null
                        'keterangan' => $keterangan,
                    ]);
                }

                // Update screening with hasil and catatan
                $hasilScreening = $hasMasalah ? 'perlu_perhatian' : 'aman';
                $catatan = $hasMasalah 
                    ? 'Pasien memiliki kondisi yang memerlukan perhatian: ' . implode('; ', $catatanArray)
                    : 'Tidak ada kontraindikasi. Pasien dalam kondisi sehat dan aman untuk divaksinasi.';

                // 70% chance screening sudah direview oleh admin
                $isReviewed = $faker->boolean(70);
                $updateData = [
                    'hasil_screening' => $hasilScreening,
                    'catatan' => $catatan,
                ];

                if ($isReviewed) {
                    $updateData['admin_id'] = $admin->id;
                    
                    // Jika hasil aman, 60% chance sudah di-assign ke dokter
                    if ($hasilScreening === 'aman' && $dokter && $faker->boolean(60)) {
                        $updateData['dokter_id'] = $dokter->id;
                        $updateData['status_vaksinasi'] = 'dijadwalkan';
                        $updateData['tanggal_vaksinasi'] = $faker->dateTimeBetween('now', '+14 days');
                    } else {
                        $updateData['status_vaksinasi'] = $hasilScreening === 'aman' ? 'proses_vaksinasi' : 'belum_divaksin';
                    }

                    // 50% chance ada data pemeriksaan fisik
                    if ($faker->boolean(50)) {
                        $updateData['tekanan_darah_sistol'] = (string) $faker->numberBetween(100, 140);
                        $updateData['tekanan_darah_diastol'] = (string) $faker->numberBetween(60, 90);
                        $updateData['nadi'] = (string) $faker->numberBetween(60, 100);
                        $updateData['suhu'] = $faker->randomFloat(1, 36.0, 37.5);
                        $updateData['berat_badan'] = $faker->randomFloat(2, 45.0, 100.0);
                        $updateData['tinggi_badan'] = $faker->randomFloat(2, 150.0, 180.0);
                        $updateData['saturasi_oksigen'] = $faker->numberBetween(95, 100);
                        $updateData['catatan_pemeriksaan'] = 'Pemeriksaan fisik dalam batas normal. ' . $catatan;
                    }
                } else {
                    $updateData['status_vaksinasi'] = 'belum_divaksin';
                }

                $screening->update($updateData);
            }
        }

        $screeningCount = Screening::count();
        $answerCount = ScreeningAnswer::count();
        
        $this->command->info('✓ Screening seeder berhasil!');
        $this->command->info("  - {$screeningCount} screening dibuat");
        $this->command->info("  - {$answerCount} jawaban screening dibuat");
    }

    /**
     * Generate keterangan based on question
     */
    private function generateKeterangan(string $pertanyaan, $faker): string
    {
        // Asma
        if (stripos($pertanyaan, 'asma') !== false || stripos($pertanyaan, 'sesak') !== false) {
            return $faker->randomElement([
                'Asma ringan, terkontrol dengan obat',
                'Riwayat asma masa kecil, sekarang sudah jarang kambuh',
                'Asma musiman, biasanya kambuh saat cuaca dingin',
            ]);
        }

        // Alergi
        if (stripos($pertanyaan, 'alergi') !== false) {
            return $faker->randomElement([
                'Alergi makanan laut (udang, kepiting)',
                'Alergi obat antibiotik golongan penisilin',
                'Alergi debu dan bulu binatang',
                'Alergi telur',
            ]);
        }

        // Diabetes
        if (stripos($pertanyaan, 'diabetes') !== false || stripos($pertanyaan, 'gula') !== false) {
            return $faker->randomElement([
                'Diabetes tipe 2, terkontrol dengan metformin',
                'Pre-diabetes, sedang diet dan olahraga',
                'Diabetes tipe 1, menggunakan insulin',
            ]);
        }

        // Jantung
        if (stripos($pertanyaan, 'jantung') !== false) {
            return $faker->randomElement([
                'Riwayat hipertensi ringan, minum obat rutin',
                'Pernah mengalami aritmia ringan tahun lalu',
                'Riwayat keluarga penyakit jantung koroner',
            ]);
        }

        // Demam/Sakit
        if (stripos($pertanyaan, 'demam') !== false || stripos($pertanyaan, 'sakit') !== false) {
            return $faker->randomElement([
                'Batuk ringan sejak 2 hari yang lalu',
                'Demam ringan kemarin, hari ini sudah turun',
                'Flu ringan, hidung tersumbat',
            ]);
        }

        // Hamil/Menyusui
        if (stripos($pertanyaan, 'hamil') !== false || stripos($pertanyaan, 'menyusui') !== false) {
            return $faker->randomElement([
                'Hamil 8 minggu',
                'Sedang menyusui bayi usia 6 bulan',
                'Program hamil',
            ]);
        }

        // Vaksinasi sebelumnya
        if (stripos($pertanyaan, 'vaksin') !== false || stripos($pertanyaan, 'imunisasi') !== false) {
            return $faker->randomElement([
                'Vaksin COVID-19 terakhir 3 bulan lalu',
                'Vaksin Hepatitis B tahun lalu',
                'Vaksin Influenza 6 bulan lalu',
            ]);
        }

        // Obat-obatan
        if (stripos($pertanyaan, 'obat') !== false) {
            return $faker->randomElement([
                'Obat maag (omeprazole) setiap hari',
                'Vitamin dan suplemen',
                'Obat tekanan darah (amlodipine)',
            ]);
        }

        // Gangguan kekebalan
        if (stripos($pertanyaan, 'kekebalan') !== false || stripos($pertanyaan, 'imun') !== false) {
            return $faker->randomElement([
                'Riwayat lupus, terkontrol',
                'Sedang terapi imunosupresan',
            ]);
        }

        // Default
        return $faker->randomElement([
            'Ya, pernah mengalami kondisi tersebut',
            'Ada riwayat keluarga dengan kondisi ini',
            'Kondisi ringan, sudah membaik',
        ]);
    }
}
