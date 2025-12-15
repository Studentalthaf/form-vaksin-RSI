<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VaccineRequest;
use App\Models\Screening;
use App\Models\NilaiScreening;
use App\Models\User;
use App\Models\Pasien;
use App\Models\Vaksin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ScreeningPasienController extends Controller
{
    /**
     * Tampilkan detail screening pasien untuk dinilai
     */
    public function show(VaccineRequest $permohonan)
    {
        $permohonan->load('pasien');
        
        // Ambil screening beserta jawaban-jawabannya
        $screening = Screening::where('pasien_id', $permohonan->pasien_id)
            ->where('vaccine_request_id', $permohonan->id)
            ->with([
                'answers.question.category',
                'nilaiScreening.admin'
            ])
            ->first();

        // Jika pasien belum mengisi screening, JANGAN 404.
        // Buat record screening kosong agar admin tetap bisa menilai screening.
        if (!$screening) {
            $screening = Screening::create([
                'pasien_id' => $permohonan->pasien_id,
                'vaccine_request_id' => $permohonan->id,
                'tanggal_screening' => now(),
                'hasil_screening' => 'pending',
                'status_vaksinasi' => 'belum_divaksin',
                'catatan' => 'Screening dibuat oleh admin karena pasien belum mengisi pertanyaan screening.',
            ]);

            // Reload relasi yang diperlukan
            $screening->load(['answers.question.category', 'nilaiScreening.admin']);

            session()->flash(
                'warning',
                'Pasien belum mengisi formulir screening. Anda tetap dapat melakukan pemeriksaan dan memberi nilai screening.'
            );
        }

        // Ambil daftar vaksin aktif untuk dropdown
        $vaksins = Vaksin::where('aktif', true)
            ->orderBy('urutan')
            ->orderBy('nama_vaksin')
            ->get();

        // Ambil semua pertanyaan screening aktif (untuk kasus belum ada jawaban sama sekali)
        $questions = \App\Models\ScreeningQuestion::where('aktif', true)
            ->orderBy('urutan')
            ->get();

        return view('admin.screening.show', compact('permohonan', 'screening', 'vaksins', 'questions'));
    }

    /**
     * Simpan nilai screening dari admin
     */
    public function storeNilai(Request $request, VaccineRequest $permohonan)
    {
        $request->validate([
            'alergi_obat' => 'required|in:ada,tidak',
            'alergi_vaksin' => 'required|in:ada,tidak',
            'sudah_vaksin_covid' => 'nullable|in:1,2,booster',
            'nama_vaksin_covid' => 'nullable|string|max:255',
            'dimana' => 'nullable|string',
            'kapan' => 'nullable|string',
            // tanggal_berangkat_umroh tidak perlu validasi, diambil dari permohonan
            'td' => 'nullable|string|max:50',
            'nadi' => 'nullable|string|max:50',
            'suhu' => 'nullable|string|max:50',
            'tb' => 'nullable|string|max:50',
            'bb' => 'nullable|string|max:50',
            'hasil_screening' => 'required|in:aman,perlu_perhatian,tidak_layak',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $screening = Screening::where('pasien_id', $permohonan->pasien_id)
                ->where('vaccine_request_id', $permohonan->id)
                ->firstOrFail();

            // Simpan nilai screening
            NilaiScreening::create([
                'screening_id' => $screening->id,
                'admin_id' => Auth::id(),
                'jenis_vaksin' => is_array($permohonan->jenis_vaksin) 
                    ? implode(', ', $permohonan->jenis_vaksin) 
                    : $permohonan->jenis_vaksin,
                'negara_tujuan' => $permohonan->negara_tujuan,
                'alergi_obat' => $request->alergi_obat,
                'alergi_vaksin' => $request->alergi_vaksin,
                'sudah_vaksin_covid' => $request->sudah_vaksin_covid,
                'nama_vaksin_covid' => $request->nama_vaksin_covid,
                'dimana' => $request->dimana,
                'kapan' => $request->kapan,
                // Ambil tanggal keberangkatan dari permohonan, bukan dari input
                'tanggal_berangkat_umroh' => $permohonan->tanggal_berangkat,
                'td' => $request->td,
                'nadi' => $request->nadi,
                'suhu' => $request->suhu,
                'tb' => $request->tb,
                'bb' => $request->bb,
                'hasil_screening' => $request->hasil_screening,
                'catatan' => $request->catatan,
            ]);

            // Parse tekanan darah dari format "120/80" menjadi sistol dan diastol
            $tekananDarahSistol = null;
            $tekananDarahDiastol = null;
            if ($request->td) {
                $tdParts = explode('/', $request->td);
                if (count($tdParts) == 2) {
                    $tekananDarahSistol = trim($tdParts[0]);
                    $tekananDarahDiastol = trim($tdParts[1]);
                }
            }

            // Update screening dengan data pemeriksaan fisik
            $screening->update([
                'admin_id' => Auth::id(),
                'hasil_screening' => $request->hasil_screening,
                'status_vaksinasi' => $request->hasil_screening === 'aman' ? 'proses_vaksinasi' : 'belum_divaksin',
                'tekanan_darah_sistol' => $tekananDarahSistol,
                'tekanan_darah_diastol' => $tekananDarahDiastol,
                'nadi' => $request->nadi,
                'suhu' => $request->suhu ? (float) $request->suhu : null,
                'berat_badan' => $request->bb ? (float) $request->bb : null,
                'tinggi_badan' => $request->tb ? (float) $request->tb : null,
                'catatan_pemeriksaan' => $request->catatan,
            ]);

            DB::commit();

            return redirect()->route('admin.permohonan.show', $permohonan)
                ->with('success', 'Nilai screening berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Form edit nilai screening
     */
    public function editNilai(VaccineRequest $permohonan)
    {
        $permohonan->load('pasien');
        
        $screening = Screening::where('pasien_id', $permohonan->pasien_id)
            ->where('vaccine_request_id', $permohonan->id)
            ->with(['answers.question.category', 'nilaiScreening'])
            ->firstOrFail();

        if (!$screening->nilaiScreening) {
            return redirect()->route('admin.screening.show', $permohonan)
                ->withErrors(['error' => 'Nilai screening belum ada']);
        }

        return view('admin.screening.edit', compact('permohonan', 'screening'));
    }

    /**
     * Update nilai screening
     */
    public function updateNilai(Request $request, VaccineRequest $permohonan)
    {
        $request->validate([
            'alergi_obat' => 'required|in:ada,tidak',
            'alergi_vaksin' => 'required|in:ada,tidak',
            'sudah_vaksin_covid' => 'nullable|in:1,2,booster',
            'nama_vaksin_covid' => 'nullable|string|max:255',
            'dimana' => 'nullable|string',
            'kapan' => 'nullable|string',
            // tanggal_berangkat_umroh tidak perlu validasi, diambil dari permohonan
            'td' => 'nullable|string|max:50',
            'nadi' => 'nullable|string|max:50',
            'suhu' => 'nullable|string|max:50',
            'tb' => 'nullable|string|max:50',
            'bb' => 'nullable|string|max:50',
            'hasil_screening' => 'required|in:aman,perlu_perhatian,tidak_layak',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $screening = Screening::where('pasien_id', $permohonan->pasien_id)
                ->where('vaccine_request_id', $permohonan->id)
                ->with('nilaiScreening')
                ->firstOrFail();

            if (!$screening->nilaiScreening) {
                return back()->withErrors(['error' => 'Nilai screening tidak ditemukan']);
            }

            // Update nilai screening
            $screening->nilaiScreening->update([
                'admin_id' => Auth::id(),
                'alergi_obat' => $request->alergi_obat,
                'alergi_vaksin' => $request->alergi_vaksin,
                'sudah_vaksin_covid' => $request->sudah_vaksin_covid,
                'nama_vaksin_covid' => $request->nama_vaksin_covid,
                'dimana' => $request->dimana,
                'kapan' => $request->kapan,
                // Tetap gunakan tanggal dari permohonan
                'tanggal_berangkat_umroh' => $permohonan->tanggal_berangkat,
                'td' => $request->td,
                'nadi' => $request->nadi,
                'suhu' => $request->suhu,
                'tb' => $request->tb,
                'bb' => $request->bb,
                'hasil_screening' => $request->hasil_screening,
                'catatan' => $request->catatan,
            ]);

            // Parse tekanan darah dari format "120/80" menjadi sistol dan diastol
            $tekananDarahSistol = null;
            $tekananDarahDiastol = null;
            if ($request->td) {
                $tdParts = explode('/', $request->td);
                if (count($tdParts) == 2) {
                    $tekananDarahSistol = trim($tdParts[0]);
                    $tekananDarahDiastol = trim($tdParts[1]);
                }
            }

            // Update screening dengan data pemeriksaan fisik
            $screening->update([
                'admin_id' => Auth::id(),
                'hasil_screening' => $request->hasil_screening,
                'status_vaksinasi' => $request->hasil_screening === 'aman' ? 'proses_vaksinasi' : 'belum_divaksin',
                'tekanan_darah_sistol' => $tekananDarahSistol,
                'tekanan_darah_diastol' => $tekananDarahDiastol,
                'nadi' => $request->nadi,
                'suhu' => $request->suhu ? (float) $request->suhu : null,
                'berat_badan' => $request->bb ? (float) $request->bb : null,
                'tinggi_badan' => $request->tb ? (float) $request->tb : null,
                'catatan_pemeriksaan' => $request->catatan,
            ]);

            DB::commit();

            return redirect()->route('admin.permohonan.show', $permohonan)
                ->with('success', 'Nilai screening berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Assign screening ke dokter untuk proses vaksinasi
     */
    public function assignDokter(Request $request, VaccineRequest $permohonan)
    {
        $request->validate([
            'dokter_id' => 'required|exists:users,id',
            'tanggal_vaksinasi' => 'required|date|after_or_equal:today',
        ], [
            'dokter_id.required' => 'Dokter harus dipilih',
            'dokter_id.exists' => 'Dokter tidak valid',
            'tanggal_vaksinasi.required' => 'Tanggal vaksinasi harus diisi',
            'tanggal_vaksinasi.after_or_equal' => 'Tanggal vaksinasi tidak boleh kurang dari hari ini',
        ]);

        // Validasi: pastikan dokter yang dipilih memang role dokter
        $dokter = User::where('id', $request->dokter_id)
            ->where('role', 'dokter')
            ->firstOrFail();

        try {
            $screening = Screening::where('pasien_id', $permohonan->pasien_id)
                ->where('vaccine_request_id', $permohonan->id)
                ->with('nilaiScreening')
                ->firstOrFail();

            // Validasi: pastikan sudah ada nilai screening
            if (!$screening->nilaiScreening) {
                return back()->withErrors(['error' => 'Belum ada penilaian screening. Silakan beri nilai terlebih dahulu.']);
            }

            // Semua hasil screening (aman, perlu_perhatian, tidak_layak) bisa dikirim ke dokter untuk evaluasi lebih lanjut

            // Copy tanda tangan admin dari file yang sudah ada di ttd_admin
            $ttdAdminSource = 'ttd_admin/ttd_kikik.jpg';
            $ttdAdminPath = 'signatures/ttd_admin_' . $permohonan->id . '_' . time() . '.jpg';
            
            // Copy file dari ttd_admin ke signatures
            if (Storage::disk('public')->exists($ttdAdminSource)) {
                $sourceContent = Storage::disk('public')->get($ttdAdminSource);
                Storage::disk('public')->put($ttdAdminPath, $sourceContent);
            } else {
                return back()->withErrors(['error' => 'File tanda tangan admin tidak ditemukan. Pastikan file ttd_kikik.jpg ada di storage/ttd_admin/']);
            }

            // Update screening
            $screening->update([
                'dokter_id' => $request->dokter_id,
                'tanggal_vaksinasi' => $request->tanggal_vaksinasi,
                'status_vaksinasi' => 'dijadwalkan',
                'tanda_tangan_admin' => $ttdAdminPath, // Simpan path signature admin dari file
            ]);

            return redirect()->route('admin.permohonan.show', $permohonan)
                ->with('success', 'Berhasil diserahkan ke Dr. ' . $dokter->nama);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Update data pasien
     */
    public function updatePasien(Request $request, VaccineRequest $permohonan)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'no_telp' => 'required|string|max:20',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'pekerjaan' => 'nullable|string|max:100',
            'alamat' => 'nullable|string|max:255',
            'nomor_rm' => 'nullable|string|max:50',
            'nomor_paspor' => 'nullable|string|max:50',
        ]);

        try {
            $permohonan->pasien->update([
                'nama' => $request->nama,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'pekerjaan' => $request->pekerjaan,
                'alamat' => $request->alamat,
                'nomor_rm' => $request->nomor_rm,
                'nomor_paspor' => $request->nomor_paspor,
            ]);

            return back()->with('success', 'Data pasien berhasil diupdate!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Update informasi vaksin
     */
    public function updateVaksin(Request $request, VaccineRequest $permohonan)
    {
        $request->validate([
            'jenis_vaksin' => 'required|array|min:1',
            'jenis_vaksin.*' => 'required|string|max:100',
            'vaksin_lainnya_text' => 'nullable|string|max:200',
            // Negara tujuan dan tanggal berangkat tidak bisa diubah, hanya untuk perjalanan luar negeri
        ]);

        try {
            $jenisVaksin = $request->jenis_vaksin;
            $vaksinLainnyaText = null;

            // Jika ada "Lainnya", pisahkan
            if (in_array('Lainnya', $jenisVaksin)) {
                $jenisVaksin = array_filter($jenisVaksin, function($item) {
                    return $item !== 'Lainnya';
                });
                $jenisVaksin = array_values($jenisVaksin);
                
                if (!empty($request->vaksin_lainnya_text)) {
                    $vaksinLainnyaText = trim($request->vaksin_lainnya_text);
                }
            }

            // Hanya update jenis vaksin, tidak update negara_tujuan dan tanggal_berangkat
            $permohonan->update([
                'jenis_vaksin' => $jenisVaksin,
                'vaksin_lainnya' => $vaksinLainnyaText,
                // negara_tujuan dan tanggal_berangkat tetap dari data sebelumnya (tidak diubah)
            ]);

            // Update juga di nilai screening jika ada
            $screening = Screening::where('pasien_id', $permohonan->pasien_id)
                ->where('vaccine_request_id', $permohonan->id)
                ->with('nilaiScreening')
                ->first();

            if ($screening && $screening->nilaiScreening) {
                $screening->nilaiScreening->update([
                    'jenis_vaksin' => is_array($jenisVaksin) 
                        ? implode(', ', $jenisVaksin) 
                        : ($jenisVaksin ?: ''),
                    // negara_tujuan tetap dari permohonan (tidak diubah)
                    'negara_tujuan' => $permohonan->negara_tujuan,
                ]);
            }

            return back()->with('success', 'Informasi vaksin berhasil diupdate!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Update jawaban screening pasien
     */
    public function updateJawaban(Request $request, VaccineRequest $permohonan)
    {
        DB::beginTransaction();
        try {
            $screening = Screening::where('pasien_id', $permohonan->pasien_id)
                ->where('vaccine_request_id', $permohonan->id)
                ->with('answers')
                ->firstOrFail();

            // Get all questions
            $questions = \App\Models\ScreeningQuestion::where('aktif', true)->get();
            
            foreach ($questions as $question) {
                $jawaban = $request->input('jawaban_' . $question->id);
                $keterangan = $request->input('keterangan_' . $question->id);
                
                if ($jawaban) {
                    // Find existing answer
                    $answer = $screening->answers()->where('question_id', $question->id)->first();
                    
                    if ($answer) {
                        // Update existing answer
                        $answer->update([
                            'jawaban' => $jawaban,
                            'keterangan' => $keterangan,
                        ]);
                    } else {
                        // Create new answer if not exist
                        \App\Models\ScreeningAnswer::create([
                            'screening_id' => $screening->id,
                            'question_id' => $question->id,
                            'jawaban' => $jawaban,
                            'keterangan' => $keterangan,
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            return back()->with('success', 'Jawaban screening berhasil diupdate!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
