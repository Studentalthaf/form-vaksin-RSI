<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use App\Models\VaccineRequest;
use App\Models\Screening;
use App\Models\ScreeningAnswer;
use App\Models\ScreeningQuestion;
use App\Models\ScreeningQuestionCategory;
use App\Models\User;
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
            ->with(['answers.question.category', 'petugas', 'dokter'])
            ->firstOrFail();

        // Ambil daftar dokter untuk assign
        $dokterList = User::where('role', 'dokter')
            ->orderBy('nama')
            ->get();

        return view('admin.screening.pasien.show', compact('permohonan', 'screening', 'dokterList'));
    }

    /**
     * Assign screening ke dokter
     */
    public function assignDokter(Request $request, VaccineRequest $permohonan)
    {
        $request->validate([
            'dokter_id' => 'required|exists:users,id',
            'tanggal_vaksinasi' => 'required|date|after_or_equal:today',
        ], [
            'dokter_id.required' => 'Pilih dokter terlebih dahulu',
            'dokter_id.exists' => 'Dokter tidak ditemukan',
            'tanggal_vaksinasi.required' => 'Tanggal vaksinasi harus diisi',
            'tanggal_vaksinasi.date' => 'Format tanggal tidak valid',
            'tanggal_vaksinasi.after_or_equal' => 'Tanggal vaksinasi tidak boleh sebelum hari ini',
        ]);

        // Cek apakah dokter yang dipilih benar-benar role dokter
        $dokter = User::where('id', $request->dokter_id)
            ->where('role', 'dokter')
            ->first();

        if (!$dokter) {
            return back()->withErrors(['dokter_id' => 'User yang dipilih bukan dokter']);
        }

        $screening = Screening::where('pasien_id', $permohonan->pasien_id)
            ->where('vaccine_request_id', $permohonan->id)
            ->firstOrFail();

        $screening->update([
            'dokter_id' => $request->dokter_id,
            'tanggal_vaksinasi' => $request->tanggal_vaksinasi,
            'status_vaksinasi' => 'proses_vaksinasi',
        ]);

        return redirect()->route('admin.screening.pasien.show', $permohonan)
            ->with('success', 'Pasien berhasil diserahkan ke Dr. ' . $dokter->nama . ' untuk vaksinasi tanggal ' . \Carbon\Carbon::parse($request->tanggal_vaksinasi)->format('d/m/Y'));
    }

    /**
     * Tampilkan daftar screening yang sudah selesai
     */
    public function selesai(Request $request)
    {
        // Query screening yang sudah diserahkan ke dokter
        $query = Screening::with([
            'pasien',
            'vaccineRequest',
            'dokter',
            'penilaian'
        ])
        ->whereNotNull('dokter_id') // Tampilkan semua yang sudah diserahkan ke dokter
        ->orderBy('updated_at', 'desc');

        // Filter search nama pasien
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pasien', function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%');
            });
        }

        // Filter search SIM RS
        if ($request->filled('sim_rs')) {
            $simrs = $request->sim_rs;
            $query->whereHas('pasien', function($q) use ($simrs) {
                $q->where('sim_rs', 'like', '%' . $simrs . '%');
            });
        }

        // Filter status
        if ($request->filled('status')) {
            if ($request->status == 'di_dokter') {
                // Yang belum ada penilaian
                $query->whereDoesntHave('penilaian');
            } elseif ($request->status == 'sudah_dinilai') {
                // Yang sudah ada penilaian
                $query->whereHas('penilaian');
            }
        }

        // Filter dokter
        if ($request->filled('dokter_id')) {
            $query->where('dokter_id', $request->dokter_id);
        }

        // Pagination
        $screenings = $query->paginate(10);

        // Daftar dokter untuk filter
        $dokterList = User::where('role', 'dokter')->orderBy('nama')->get();

        // Statistik
        $totalSelesai = Screening::whereNotNull('dokter_id')->count();
        $selesaiHariIni = Screening::whereNotNull('dokter_id')
            ->whereDate('updated_at', today())
            ->count();
        $diDokter = Screening::whereNotNull('dokter_id')
            ->whereDoesntHave('penilaian')
            ->count();
        $sudahDinilai = Screening::whereNotNull('dokter_id')
            ->whereHas('penilaian')
            ->count();

        return view('admin.screening.selesai', compact(
            'screenings',
            'dokterList',
            'totalSelesai',
            'selesaiHariIni',
            'diDokter',
            'sudahDinilai'
        ));
    }
}
