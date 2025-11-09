<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use App\Models\VaccineRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PermohonanPasienController extends Controller
{
    /**
     * Tampilkan daftar permohonan pasien
     */
    public function index(Request $request)
    {
        // Query semua permohonan dengan screening
        $query = VaccineRequest::with(['pasien', 'screening.nilaiScreening']);

        // Filter berdasarkan search nama pemohon
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pasien', function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan NIK
        if ($request->filled('nik')) {
            $nik = $request->nik;
            $query->whereHas('pasien', function($q) use ($nik) {
                $q->where('nik', 'like', '%' . $nik . '%');
            });
        }

        // Filter berdasarkan status screening
        if ($request->filled('status')) {
            if ($request->status === 'belum_screening') {
                // Belum screening: belum ada screening ATAU screening belum dinilai
                $query->where(function($q) {
                    $q->doesntHave('screening')
                      ->orWhereHas('screening', function($subQ) {
                          $subQ->doesntHave('nilaiScreening');
                      });
                });
            } elseif ($request->status === 'sudah_screening') {
                // Sudah screening: screening ada DAN sudah dinilai
                $query->whereHas('screening.nilaiScreening');
            }
        }

        // Ambil data - DATA TERBARU DI ATAS
        $permohonan = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistik untuk card
        $totalPermohonan = VaccineRequest::count();
        $hariIni = VaccineRequest::whereDate('created_at', today())->count();
        
        // Belum screening: tidak punya screening ATAU screening belum dinilai
        $belumScreening = VaccineRequest::where(function($q) {
            $q->doesntHave('screening')
              ->orWhereHas('screening', function($subQ) {
                  $subQ->doesntHave('nilaiScreening');
              });
        })->count();
        
        // Sudah screening: punya screening DAN sudah dinilai
        $sudahScreening = VaccineRequest::whereHas('screening.nilaiScreening')->count();

        return view('admin.permohonan.index', compact(
            'permohonan',
            'totalPermohonan',
            'hariIni',
            'belumScreening',
            'sudahScreening'
        ));
    }

    /**
     * Tampilkan detail permohonan dengan screening (jika ada)
     */
    public function show(VaccineRequest $permohonan)
    {
        $permohonan->load([
            'pasien',
            'screening.answers.question.category',
            'screening.nilaiScreening.admin',
            'screening.dokter'
        ]);
        
        // Get list of dokter for assignment
        $dokterList = \App\Models\User::where('role', 'dokter')->orderBy('nama')->get();
        
        return view('admin.permohonan.show', compact('permohonan', 'dokterList'));
    }

    /**
     * Tampilkan daftar permohonan yang sudah terverifikasi oleh dokter
     */
    public function terverifikasi(Request $request)
    {
        // Query permohonan yang sudah dikonfirmasi dokter
        $query = VaccineRequest::with([
            'pasien', 
            'screening.nilaiScreening.admin',
            'screening.dokter'
        ])
        ->whereHas('screening', function($q) {
            $q->where('status_konfirmasi', 'dikonfirmasi')
              ->whereNotNull('tanda_tangan_dokter')
              ->whereNotNull('tanggal_konfirmasi');
        });

        // Filter berdasarkan search nama pemohon
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pasien', function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nik', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan dokter
        if ($request->filled('dokter_id')) {
            $query->whereHas('screening', function($q) use ($request) {
                $q->where('dokter_id', $request->dokter_id);
            });
        }

        // Filter berdasarkan tanggal konfirmasi
        if ($request->filled('tanggal_dari')) {
            $query->whereHas('screening', function($q) use ($request) {
                $q->whereDate('tanggal_konfirmasi', '>=', $request->tanggal_dari);
            });
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereHas('screening', function($q) use ($request) {
                $q->whereDate('tanggal_konfirmasi', '<=', $request->tanggal_sampai);
            });
        }

        // Ambil data - DATA TERBARU DI ATAS
        $permohonan = $query->orderBy('updated_at', 'desc')->paginate(15);

        // Statistik
        $totalTerverifikasi = VaccineRequest::whereHas('screening', function($q) {
            $q->where('status_konfirmasi', 'dikonfirmasi');
        })->count();

        $hariIni = VaccineRequest::whereHas('screening', function($q) {
            $q->where('status_konfirmasi', 'dikonfirmasi')
              ->whereDate('tanggal_konfirmasi', today());
        })->count();

        $mingguIni = VaccineRequest::whereHas('screening', function($q) {
            $q->where('status_konfirmasi', 'dikonfirmasi')
              ->whereBetween('tanggal_konfirmasi', [now()->startOfWeek(), now()->endOfWeek()]);
        })->count();

        $bulanIni = VaccineRequest::whereHas('screening', function($q) {
            $q->where('status_konfirmasi', 'dikonfirmasi')
              ->whereMonth('tanggal_konfirmasi', now()->month)
              ->whereYear('tanggal_konfirmasi', now()->year);
        })->count();

        // List dokter untuk filter
        $dokterList = \App\Models\User::where('role', 'dokter')->orderBy('nama')->get();

        return view('admin.permohonan.terverifikasi', compact(
            'permohonan',
            'totalTerverifikasi',
            'hariIni',
            'mingguIni',
            'bulanIni',
            'dokterList'
        ));
    }

    /**
     * Tampilkan detail permohonan yang sudah terverifikasi
     */
    public function showTerverifikasi(VaccineRequest $permohonan)
    {
        // Load all relationships needed
        $permohonan->load([
            'pasien',
            'screening.answers.question.category',
            'screening.nilaiScreening.admin',
            'screening.dokter'
        ]);

        // Pastikan ini benar-benar permohonan yang sudah terverifikasi
        if (!$permohonan->screening || $permohonan->screening->status_konfirmasi !== 'dikonfirmasi') {
            return redirect()->route('admin.permohonan.terverifikasi')
                ->with('error', 'Permohonan ini belum terverifikasi oleh dokter.');
        }

        return view('admin.permohonan.detail-terverifikasi', compact('permohonan'));
    }

    /**
     * Cetak PDF Surat Persetujuan Vaksinasi untuk Admin
     */
    public function cetakPdfTerverifikasi(VaccineRequest $permohonan)
    {
        // Load semua relasi yang diperlukan
        $permohonan->load([
            'pasien',
            'screening.answers.question.category',
            'screening.nilaiScreening.admin',
            'screening.dokter'
        ]);

        // Pastikan ini benar-benar permohonan yang sudah terverifikasi
        if (!$permohonan->screening || $permohonan->screening->status_konfirmasi !== 'dikonfirmasi') {
            return redirect()->route('admin.permohonan.terverifikasi')
                ->with('error', 'Permohonan ini belum terverifikasi oleh dokter.');
        }

        // Ambil screening untuk compatibility dengan template PDF yang sudah ada
        $screening = $permohonan->screening;

        // Generate PDF
        $pdf = Pdf::loadView('pdf.surat-persetujuan', compact('screening'));
        
        // Set paper size dan orientation  
        $pdf->setPaper('a4', 'portrait');
        
        // Download PDF dengan nama file + timestamp untuk avoid cache
        $timestamp = date('YmdHis');
        $filename = 'Surat_Vaksinasi_' . str_replace(' ', '_', $permohonan->pasien->nama) . '_' . $timestamp . '.pdf';
        
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

    /**
     * Hapus permohonan pasien beserta semua data terkait
     */
    public function destroy(VaccineRequest $permohonan)
    {
        try {
            $pasienNama = $permohonan->pasien->nama;
            
            // Hapus file foto KTP jika ada
            if ($permohonan->pasien->foto_ktp && Storage::disk('public')->exists($permohonan->pasien->foto_ktp)) {
                Storage::disk('public')->delete($permohonan->pasien->foto_ktp);
            }
            
            // Hapus file foto Paspor jika ada
            if ($permohonan->pasien->foto_paspor && Storage::disk('public')->exists($permohonan->pasien->foto_paspor)) {
                Storage::disk('public')->delete($permohonan->pasien->foto_paspor);
            }
            
            // Hapus tanda tangan pasien jika ada
            if ($permohonan->screening && $permohonan->screening->tanda_tangan_pasien) {
                if (Storage::disk('public')->exists($permohonan->screening->tanda_tangan_pasien)) {
                    Storage::disk('public')->delete($permohonan->screening->tanda_tangan_pasien);
                }
            }
            
            // Hapus tanda tangan dokter jika ada
            if ($permohonan->screening && $permohonan->screening->tanda_tangan_dokter) {
                if (Storage::disk('public')->exists($permohonan->screening->tanda_tangan_dokter)) {
                    Storage::disk('public')->delete($permohonan->screening->tanda_tangan_dokter);
                }
            }
            
            // Hapus permohonan (akan cascade delete ke screening, answers, dll)
            $permohonan->delete();
            
            return redirect()->route('admin.permohonan.index')
                ->with('success', "Permohonan pasien {$pasienNama} berhasil dihapus beserta semua data terkait!");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus permohonan: ' . $e->getMessage());
        }
    }
}
