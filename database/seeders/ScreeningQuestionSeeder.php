<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScreeningQuestionCategory;
use App\Models\ScreeningQuestion;

class ScreeningQuestionSeeder extends Seeder
{
    public function run(): void
    {
        // Kategori 1: Riwayat Penyakit
        $kategori1 = ScreeningQuestionCategory::create([
            'nama_kategori' => 'Riwayat Penyakit',
            'deskripsi' => 'Pertanyaan terkait riwayat penyakit pasien',
            'urutan' => 1,
            'aktif' => true,
        ]);

        ScreeningQuestion::create([
            'category_id' => $kategori1->id,
            'pertanyaan' => 'Apakah Anda sedang mengalami demam atau suhu tubuh di atas 37,5°C?',
            'tipe_jawaban' => 'ya_tidak',
            'urutan' => 1,
            'wajib' => true,
            'aktif' => true,
        ]);

        ScreeningQuestion::create([
            'category_id' => $kategori1->id,
            'pertanyaan' => 'Apakah Anda memiliki riwayat penyakit jantung?',
            'tipe_jawaban' => 'ya_tidak',
            'urutan' => 2,
            'wajib' => true,
            'aktif' => true,
        ]);

        ScreeningQuestion::create([
            'category_id' => $kategori1->id,
            'pertanyaan' => 'Apakah Anda memiliki riwayat penyakit diabetes?',
            'tipe_jawaban' => 'ya_tidak',
            'urutan' => 3,
            'wajib' => true,
            'aktif' => true,
        ]);

        ScreeningQuestion::create([
            'category_id' => $kategori1->id,
            'pertanyaan' => 'Apakah Anda memiliki riwayat penyakit asma atau gangguan pernapasan?',
            'tipe_jawaban' => 'ya_tidak',
            'urutan' => 4,
            'wajib' => true,
            'aktif' => true,
        ]);

        // Kategori 2: Riwayat Alergi
        $kategori2 = ScreeningQuestionCategory::create([
            'nama_kategori' => 'Riwayat Alergi',
            'deskripsi' => 'Pertanyaan terkait alergi dan reaksi terhadap vaksin',
            'urutan' => 2,
            'aktif' => true,
        ]);

        ScreeningQuestion::create([
            'category_id' => $kategori2->id,
            'pertanyaan' => 'Apakah Anda memiliki alergi terhadap obat-obatan tertentu?',
            'tipe_jawaban' => 'ya_tidak',
            'urutan' => 1,
            'wajib' => true,
            'aktif' => true,
        ]);

        ScreeningQuestion::create([
            'category_id' => $kategori2->id,
            'pertanyaan' => 'Apakah Anda pernah mengalami reaksi alergi setelah vaksinasi sebelumnya?',
            'tipe_jawaban' => 'ya_tidak',
            'urutan' => 2,
            'wajib' => true,
            'aktif' => true,
        ]);

        // Kategori 3: Kondisi Saat Ini
        $kategori3 = ScreeningQuestionCategory::create([
            'nama_kategori' => 'Kondisi Saat Ini',
            'deskripsi' => 'Kondisi kesehatan pasien saat ini',
            'urutan' => 3,
            'aktif' => true,
        ]);

        ScreeningQuestion::create([
            'category_id' => $kategori3->id,
            'pertanyaan' => 'Apakah Anda sedang hamil atau menyusui?',
            'tipe_jawaban' => 'ya_tidak',
            'urutan' => 1,
            'wajib' => true,
            'aktif' => true,
        ]);

        ScreeningQuestion::create([
            'category_id' => $kategori3->id,
            'pertanyaan' => 'Apakah Anda sedang mengonsumsi obat-obatan tertentu secara rutin?',
            'tipe_jawaban' => 'ya_tidak',
            'urutan' => 2,
            'wajib' => true,
            'aktif' => true,
        ]);

        ScreeningQuestion::create([
            'category_id' => $kategori3->id,
            'pertanyaan' => 'Apakah Anda sedang mengalami batuk, pilek, atau sesak napas?',
            'tipe_jawaban' => 'ya_tidak',
            'urutan' => 3,
            'wajib' => true,
            'aktif' => true,
        ]);

        // Kategori 4: Vaksinasi Sebelumnya
        $kategori4 = ScreeningQuestionCategory::create([
            'nama_kategori' => 'Vaksinasi Sebelumnya',
            'deskripsi' => 'Riwayat vaksinasi pasien',
            'urutan' => 4,
            'aktif' => true,
        ]);

        ScreeningQuestion::create([
            'category_id' => $kategori4->id,
            'pertanyaan' => 'Apakah Anda sudah pernah mendapatkan vaksin Meningitis sebelumnya?',
            'tipe_jawaban' => 'ya_tidak',
            'urutan' => 1,
            'wajib' => true,
            'aktif' => true,
        ]);

        echo "✓ Screening questions seeder berhasil! " . ScreeningQuestion::count() . " pertanyaan dibuat.\n";
    }
}
