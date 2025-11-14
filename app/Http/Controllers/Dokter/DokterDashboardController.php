<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Screening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DokterDashboardController extends Controller
{
    /**
     * Dashboard Dokter
     */
    public function index(Request $request)
    {
        $dokter = Auth::user();
        
        // Statistik
        $totalPasien = Screening::where('dokter_id', $dokter->id)->count();
        $belumDivaksin = Screening::where('dokter_id', $dokter->id)
            ->where('status_vaksinasi', 'belum_divaksin')->count();
        $sudahDivaksin = Screening::where('dokter_id', $dokter->id)
            ->where('status_vaksinasi', 'sudah_divaksin')->count();
        $pasienHariIni = Screening::where('dokter_id', $dokter->id)
            ->whereDate('tanggal_vaksinasi', today())->count();
        
        // Ambil 5 pasien terbaru yang ditugaskan
        $pasienTerbaru = Screening::where('dokter_id', $dokter->id)
            ->with(['pasien', 'vaccineRequest'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('dokter.dashboard', compact(
            'totalPasien',
            'belumDivaksin',
            'sudahDivaksin',
            'pasienHariIni',
            'pasienTerbaru'
        ));
        
        // $dokter = Auth::user();
        
        // // Ambil semua screening untuk dokter ini - DATA TERBARU DI ATAS
        // $screenings = Screening::where('dokter_id', $dokter->id)
        //     ->with(['pasien', 'vaccineRequest', 'petugas'])
        //     ->orderBy('created_at', 'desc') // Data terbaru di atas
        //     ->orderBy('tanggal_vaksinasi', 'desc')
        //     ->get();
        
        // // Grouping screening berdasarkan tanggal vaksinasi
        // $jadwalPerTanggal = $screenings->groupBy(function($screening) {
        //     return Carbon::parse($screening->tanggal_vaksinasi)->format('Y-m-d');
        // });
        
        // // Statistik keseluruhan
        // $totalPasien = $screenings->count();
        // $belumDivaksin = $screenings->where('status_vaksinasi', 'belum_divaksin')->count();
        // $prosesDivaksin = $screenings->where('status_vaksinasi', 'proses_vaksinasi')->count();
        // $sudahDivaksin = $screenings->where('status_vaksinasi', 'sudah_divaksin')->count();
        
        // // Pasien hari ini
        // $pasienHariIni = $screenings->filter(function($screening) {
        //     return Carbon::parse($screening->tanggal_vaksinasi)->isToday();
        // })->count();
        
        // // Pasien minggu ini
        // $pasienMingguIni = $screenings->filter(function($screening) {
        //     return Carbon::parse($screening->tanggal_vaksinasi)->isCurrentWeek();
        // })->count();
        
        // return view('dokter.dashboard', compact(
        //     'screenings',
        //     'jadwalPerTanggal',
        //     'totalPasien',
        //     'belumDivaksin',
        //     'prosesDivaksin',
        //     'sudahDivaksin',
        //     'pasienHariIni',
        //     'pasienMingguIni'
        // ));
    }

    /**
     * Daftar Semua Pasien
     */
    public function daftarPasien(Request $request)
    {
        $dokter = Auth::user();
        
        $query = Screening::where('dokter_id', $dokter->id)
            ->with(['pasien', 'vaccineRequest']);
        
        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status_vaksinasi', $request->status);
        }
        
        // Search berdasarkan nama atau NIK
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('pasien', function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nik', 'like', '%' . $search . '%');
            });
        }
        
        $pasiens = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('dokter.pasien.index', compact('pasiens'));
    }
    
    /**
     * Detail Pasien dan Hasil Screening
     */
    public function detailPasien($id)
    {
        $dokter = Auth::user();
        
        $screening = Screening::where('dokter_id', $dokter->id)
            ->where('id', $id)
            ->with(['pasien', 'vaccineRequest', 'screeningAnswers.question', 'nilaiScreening.admin'])
            ->firstOrFail();
        
        return view('dokter.pasien.show', compact('screening'));
    }
    
    /**
     * Konfirmasi Pasien dengan Tanda Tangan Dokter dan Catatan
     */
    public function konfirmasiPasien(Request $request, $id)
    {
        $dokter = Auth::user();
        
        // Validasi
        $request->validate([
            'tanda_tangan' => 'required|string',
            'catatan_dokter' => 'required|string|min:10',
        ], [
            'tanda_tangan.required' => 'Tanda tangan dokter wajib diisi',
            'catatan_dokter.required' => 'Catatan dokter wajib diisi',
            'catatan_dokter.min' => 'Catatan dokter minimal 10 karakter',
        ]);
        
        // Cari screening
        $screening = Screening::where('dokter_id', $dokter->id)
            ->where('id', $id)
            ->firstOrFail();
        
        // Pastikan pasien sudah tanda tangan
        if (!$screening->tanda_tangan_pasien) {
            return redirect()
                ->back()
                ->with('error', 'Pasien belum menandatangani form persetujuan!');
        }
        
        // Simpan tanda tangan dokter
        $signatureData = $request->tanda_tangan;
        $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
        $signatureData = str_replace(' ', '+', $signatureData);
        $signatureDecoded = base64_decode($signatureData);
        
        $signatureFileName = 'signature_dokter_' . $screening->id . '_' . time() . '.png';
        $signaturePath = 'signatures/' . $signatureFileName;
        Storage::disk('public')->put($signaturePath, $signatureDecoded);
        
        // Update screening
        $screening->update([
            'catatan_dokter' => $request->catatan_dokter,
            'tanda_tangan_dokter' => $signaturePath,
            'tanggal_konfirmasi' => now(),
            'status_konfirmasi' => 'dikonfirmasi',
        ]);
        
        return redirect()
            ->route('dokter.pasien.index')
            ->with('success', 'Konfirmasi berhasil disimpan! Pasien telah diverifikasi dan data dikirim ke admin.');
    }
}
