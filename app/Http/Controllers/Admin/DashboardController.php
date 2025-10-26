<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VaccineRequest;
use App\Models\Screening;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung statistik untuk dashboard
        $totalUsers = User::count();
        $totalDokter = User::where('role', 'dokter')->count();
        $totalPermohonan = VaccineRequest::count();
        $pendingPermohonan = VaccineRequest::where('disetujui', false)->count();
        $screeningSelesai = Screening::count();
        
        // Rekap permohonan hari ini
        $permohonanHariIni = VaccineRequest::whereDate('created_at', Carbon::today())->count();
        
        // Rekap permohonan 7 hari terakhir (per hari)
        $rekapHarian = VaccineRequest::select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(6))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->get();
        
        // Rekap permohonan bulan ini
        $permohonanBulanIni = VaccineRequest::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        
        // Rekap per bulan (3 bulan terakhir)
        $rekapBulanan = VaccineRequest::select(
                DB::raw('YEAR(created_at) as tahun'),
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(2)->startOfMonth())
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();
        
        // Kirim data ke view
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalDokter',
            'totalPermohonan',
            'pendingPermohonan',
            'screeningSelesai',
            'permohonanHariIni',
            'rekapHarian',
            'permohonanBulanIni',
            'rekapBulanan'
        ));
    }
}
