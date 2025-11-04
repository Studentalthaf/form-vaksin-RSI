<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Screening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DokterDashboardController extends Controller
{
    public function index(Request $request)
    {
        $dokter = Auth::user();
        
        // Ambil semua screening untuk dokter ini - DATA TERBARU DI ATAS
        $screenings = Screening::where('dokter_id', $dokter->id)
            ->with(['pasien', 'vaccineRequest', 'petugas'])
            ->orderBy('created_at', 'desc') // Data terbaru di atas
            ->orderBy('tanggal_vaksinasi', 'desc')
            ->get();
        
        // Grouping screening berdasarkan tanggal vaksinasi
        $jadwalPerTanggal = $screenings->groupBy(function($screening) {
            return Carbon::parse($screening->tanggal_vaksinasi)->format('Y-m-d');
        });
        
        // Statistik keseluruhan
        $totalPasien = $screenings->count();
        $belumDivaksin = $screenings->where('status_vaksinasi', 'belum_divaksin')->count();
        $prosesDivaksin = $screenings->where('status_vaksinasi', 'proses_vaksinasi')->count();
        $sudahDivaksin = $screenings->where('status_vaksinasi', 'sudah_divaksin')->count();
        
        // Pasien hari ini
        $pasienHariIni = $screenings->filter(function($screening) {
            return Carbon::parse($screening->tanggal_vaksinasi)->isToday();
        })->count();
        
        // Pasien minggu ini
        $pasienMingguIni = $screenings->filter(function($screening) {
            return Carbon::parse($screening->tanggal_vaksinasi)->isCurrentWeek();
        })->count();
        
        return view('dokter.dashboard', compact(
            'screenings',
            'jadwalPerTanggal',
            'totalPasien',
            'belumDivaksin',
            'prosesDivaksin',
            'sudahDivaksin',
            'pasienHariIni',
            'pasienMingguIni'
        ));
    }

    public function pasienHariIni()
    {
        $dokter = Auth::user();
        
        // Ambil pasien hari ini yang belum divaksin - DATA TERBARU DI ATAS
        $screenings = Screening::where('dokter_id', $dokter->id)
            ->whereDate('tanggal_vaksinasi', today())
            ->where('status_vaksinasi', 'belum_divaksin')
            ->with(['pasien', 'vaccineRequest', 'petugas'])
            ->orderBy('created_at', 'desc') // Data terbaru di atas
            ->paginate(10);
        
        return view('dokter.pasien-hari-ini', compact('screenings'));
    }

    public function pasienSelesai()
    {
        $dokter = Auth::user();
        
        // Ambil semua pasien yang sudah divaksin
        $screenings = Screening::where('dokter_id', $dokter->id)
            ->where('status_vaksinasi', 'sudah_divaksin')
            ->with(['pasien', 'vaccineRequest', 'petugas', 'penilaian'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
        
        return view('dokter.pasien-selesai', compact('screenings'));
    }
}
