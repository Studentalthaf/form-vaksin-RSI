<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use App\Models\VaccineRequest;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    /**
     * Tampilkan daftar semua pasien
     */
    public function index(Request $request)
    {
        // Query pasien dengan counting vaccine requests berdasarkan NIK yang sama
        $query = Pasien::query()->withCount([
            'vaccineRequests' => function ($query) {
                // Hitung semua permohonan dengan NIK yang sama
                $query->whereHas('pasien', function ($q) {
                    $q->whereColumn('pasiens.nik', 'pasiens.nik');
                });
            }
        ]);

        // Filter search nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama', 'like', '%' . $search . '%');
        }

        // Filter jenis kelamin
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        // Urutkan dari terbaru
        $pasiens = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Hitung total permohonan untuk setiap pasien berdasarkan NIK
        foreach ($pasiens as $pasien) {
            if ($pasien->nik) {
                $pasien->total_permohonan_by_nik = VaccineRequest::whereHas('pasien', function($q) use ($pasien) {
                    $q->where('nik', $pasien->nik);
                })->count();
            } else {
                $pasien->total_permohonan_by_nik = $pasien->vaccine_requests_count;
            }
        }

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
        $pasien->load([
            'vaccineRequests.screening.dokter', 
            'vaccineRequests.screening.penilaian'
        ]);

        return view('admin.pasien.show', compact('pasien'));
    }

    /**
     * Update Nomor Rekam Medis pasien
     */
    public function updateNomorRM(Request $request, Pasien $pasien)
    {
        $request->validate([
            'nomor_rm' => 'required|string|max:50|unique:pasiens,nomor_rm,' . $pasien->id
        ], [
            'nomor_rm.required' => 'Nomor RM harus diisi',
            'nomor_rm.unique' => 'Nomor RM sudah digunakan oleh pasien lain',
            'nomor_rm.max' => 'Nomor RM maksimal 50 karakter'
        ]);

        $pasien->update([
            'nomor_rm' => $request->nomor_rm
        ]);

        return redirect()->back()->with('success', 'Nomor Rekam Medis berhasil disimpan');
    }
}
