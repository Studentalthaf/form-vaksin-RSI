<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VaccineRequest;
use App\Models\Screening;
use App\Models\NilaiScreening;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            ->firstOrFail();

        return view('admin.screening.show', compact('permohonan', 'screening'));
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

            // Update screening status
            $screening->update([
                'admin_id' => Auth::id(),
                'hasil_screening' => $request->hasil_screening,
                'status_vaksinasi' => $request->hasil_screening === 'aman' ? 'proses_vaksinasi' : 'belum_divaksin',
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

            // Update screening status
            $screening->update([
                'hasil_screening' => $request->hasil_screening,
                'status_vaksinasi' => $request->hasil_screening === 'aman' ? 'proses_vaksinasi' : 'belum_divaksin',
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
            'tanggal_vaksinasi' => 'required|date|after_or_equal:today'
        ], [
            'dokter_id.required' => 'Dokter harus dipilih',
            'dokter_id.exists' => 'Dokter tidak valid',
            'tanggal_vaksinasi.required' => 'Tanggal vaksinasi harus diisi',
            'tanggal_vaksinasi.after_or_equal' => 'Tanggal vaksinasi tidak boleh kurang dari hari ini'
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

            // Validasi: pastikan hasil screening adalah aman
            if ($screening->nilaiScreening->hasil_screening !== 'aman') {
                return back()->withErrors(['error' => 'Hanya screening dengan hasil AMAN yang dapat diserahkan ke dokter.']);
            }

            // Update screening
            $screening->update([
                'dokter_id' => $request->dokter_id,
                'tanggal_vaksinasi' => $request->tanggal_vaksinasi,
                'status_vaksinasi' => 'dijadwalkan'
            ]);

            return redirect()->route('admin.permohonan.show', $permohonan)
                ->with('success', 'Berhasil diserahkan ke Dr. ' . $dokter->nama);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
