<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Screening;
use App\Models\PenilaianDokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class DokterPasienController extends Controller
{
    /**
     * Tampilkan detail pasien dengan form penilaian
     */
    public function show(Screening $screening)
    {
        // Pastikan screening ini milik dokter yang login
        if ($screening->dokter_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pasien ini');
        }

        $screening->load(['pasien', 'vaccineRequest', 'petugas', 'answers.question.category', 'penilaian']);

        return view('dokter.pasien.show', compact('screening'));
    }

    /**
     * Simpan atau update penilaian dokter
     */
    public function storePenilaian(Request $request, Screening $screening)
    {
        // Pastikan screening ini milik dokter yang login
        if ($screening->dokter_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pasien ini');
        }

        $request->validate([
            'alergi_obat' => 'nullable|string|max:255',
            'alergi_vasin' => 'nullable|string|max:255',
            'sudah_vaksin_covid' => 'nullable|in:1,2,booster',
            'jenis_vaksin' => 'nullable|string|max:255',
            'negara_tujuan' => 'nullable|string|max:255',
            'nama_vaksin_covid' => 'nullable|string|max:255',
            'dimana' => 'nullable|string|max:500',
            'kapan' => 'nullable|string|max:500',
            'tanggal_berangkat_umroh' => 'nullable|date',
            'td' => 'nullable|string|max:50',
            'nadi' => 'nullable|string|max:50',
            'suhu' => 'nullable|string|max:50',
            'tb' => 'nullable|string|max:50',
            'bb' => 'nullable|string|max:50',
            'catatan' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Simpan atau update penilaian
            $penilaian = PenilaianDokter::updateOrCreate(
                ['screening_id' => $screening->id],
                $request->only([
                    'alergi_obat',
                    'alergi_vasin',
                    'sudah_vaksin_covid',
                    'jenis_vaksin',
                    'negara_tujuan',
                    'nama_vaksin_covid',
                    'dimana',
                    'kapan',
                    'tanggal_berangkat_umroh',
                    'td',
                    'nadi',
                    'suhu',
                    'tb',
                    'bb',
                    'catatan',
                ])
            );

            // Update status vaksinasi jika belum ada
            if ($screening->status_vaksinasi === 'proses_vaksinasi') {
                $screening->update([
                    'status_vaksinasi' => 'sudah_divaksin'
                ]);
            }

            DB::commit();

            return redirect()->route('dokter.pasien.show', $screening)
                ->with('success', 'Penilaian berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Cetak PDF Surat Persetujuan Vaksinasi
     */
    public function cetakPdf(Screening $screening)
    {
        // Pastikan screening ini milik dokter yang login
        if ($screening->dokter_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pasien ini');
        }

        // Load semua relasi yang diperlukan
        $screening->load([
            'pasien',
            'vaccineRequest',
            'petugas',
            'dokter',
            'answers.question.category',
            'penilaian'
        ]);

        // Generate PDF
        $pdf = Pdf::loadView('pdf.surat-persetujuan', compact('screening'));
        
        // Set paper size dan orientation  
        $pdf->setPaper('a4', 'portrait');
        
        // Download PDF dengan nama file + timestamp untuk avoid cache
        $timestamp = date('YmdHis');
        $filename = 'Surat_Vaksinasi_' . str_replace(' ', '_', $screening->pasien->nama) . '_' . $timestamp . '.pdf';
        
        // Return with no-cache headers
        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, $filename, [
            'Content-Type' => 'application/pdf',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }
}
