<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    /**
     * Tampilkan daftar semua pasien
     */
    public function index(Request $request)
    {
        // Query pasien dengan relasi vaccine requests
        $query = Pasien::withCount('vaccineRequests');

        // Filter search nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama', 'like', '%' . $search . '%');
        }

        // Filter search SIM RS
        if ($request->filled('sim_rs')) {
            $simrs = $request->sim_rs;
            $query->where('sim_rs', 'like', '%' . $simrs . '%');
        }

        // Filter jenis kelamin
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        // Urutkan dari terbaru
        $pasiens = $query->orderBy('created_at', 'desc')->paginate(10);

        // Statistik
        $totalPasien = Pasien::count();
        $pasienBaru = Pasien::whereDate('created_at', today())->count();
        $pasienLaki = Pasien::where('jenis_kelamin', 'L')->count();
        $pasienPerempuan = Pasien::where('jenis_kelamin', 'P')->count();

        return view('admin.pasien.index', compact(
            'pasiens',
            'totalPasien',
            'pasienBaru',
            'pasienLaki',
            'pasienPerempuan'
        ));
    }

    /**
     * Tampilkan detail pasien
     */
    public function show(Pasien $pasien)
    {
        // Load relasi vaccine requests dengan screening
        $pasien->load(['vaccineRequests.screening.dokter', 'vaccineRequests.screening.penilaian']);

        return view('admin.pasien.show', compact('pasien'));
    }
}
