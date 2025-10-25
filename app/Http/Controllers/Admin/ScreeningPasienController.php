<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use App\Models\VaccineRequest;
use App\Models\Screening;
use App\Models\ScreeningAnswer;
use App\Models\ScreeningQuestion;
use App\Models\ScreeningQuestionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ScreeningPasienController extends Controller
{
    /**
     * Tampilkan form screening untuk pasien tertentu
     */
    public function create(VaccineRequest $permohonan)
    {
        $permohonan->load('pasien');
        
        // Ambil semua kategori dengan pertanyaan yang aktif
        $categories = ScreeningQuestionCategory::where('aktif', true)
            ->with(['questions' => function($query) {
                $query->where('aktif', true)->orderBy('urutan');
            }])
            ->orderBy('urutan')
            ->get();

        // Cek apakah sudah pernah di-screening
        $existingScreening = Screening::where('pasien_id', $permohonan->pasien_id)
            ->where('vaccine_request_id', $permohonan->id)
            ->first();

        return view('admin.screening.pasien.create', compact('permohonan', 'categories', 'existingScreening'));
    }

    /**
     * Simpan hasil screening pasien
     */
    public function store(Request $request, VaccineRequest $permohonan)
    {
        $request->validate([
            'jawaban' => 'required|array',
            'jawaban.*' => 'required|string',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string|max:500',
        ], [
            'jawaban.required' => 'Mohon jawab semua pertanyaan wajib',
            'jawaban.*.required' => 'Jawaban tidak boleh kosong',
        ]);

        DB::beginTransaction();
        try {
            // Buat atau update screening record
            $screening = Screening::updateOrCreate(
                [
                    'pasien_id' => $permohonan->pasien_id,
                    'vaccine_request_id' => $permohonan->id,
                ],
                [
                    'tanggal_screening' => now(),
                    'petugas_id' => Auth::id(),
                    'hasil_screening' => 'pending', // akan diupdate berdasarkan jawaban
                    'catatan' => $request->catatan_umum,
                ]
            );

            // Hapus jawaban lama jika ada
            ScreeningAnswer::where('screening_id', $screening->id)->delete();

            // Simpan semua jawaban
            $hasRisiko = false;
            foreach ($request->jawaban as $questionId => $jawaban) {
                $question = ScreeningQuestion::find($questionId);
                
                // Cek apakah ada risiko (misal jawaban "ya" pada pertanyaan tertentu)
                if ($jawaban === 'ya' && $question->pertanyaan) {
                    $hasRisiko = true;
                }

                ScreeningAnswer::create([
                    'screening_id' => $screening->id,
                    'question_id' => $questionId,
                    'jawaban' => $jawaban,
                    'keterangan' => $request->keterangan[$questionId] ?? null,
                ]);
            }

            // Update hasil screening berdasarkan jawaban
            $screening->update([
                'hasil_screening' => $hasRisiko ? 'perlu_perhatian' : 'aman'
            ]);

            DB::commit();

            return redirect()->route('admin.permohonan.show', $permohonan)
                ->with('success', 'Screening berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Tampilkan hasil screening pasien
     */
    public function show(VaccineRequest $permohonan)
    {
        $permohonan->load('pasien');
        
        $screening = Screening::where('pasien_id', $permohonan->pasien_id)
            ->where('vaccine_request_id', $permohonan->id)
            ->with(['answers.question.category', 'petugas'])
            ->firstOrFail();

        return view('admin.screening.pasien.show', compact('permohonan', 'screening'));
    }
}
