<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use App\Models\VaccineRequest;
use Illuminate\Http\Request;

class PermohonanPasienController extends Controller
{
    /**
     * Tampilkan daftar permohonan pasien
     */
    public function index()
    {
        $permohonan = VaccineRequest::with('pasien')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.permohonan.index', compact('permohonan'));
    }

    /**
     * Tampilkan detail permohonan
     */
    public function show(VaccineRequest $permohonan)
    {
        $permohonan->load('pasien');
        return view('admin.permohonan.show', compact('permohonan'));
    }

    /**
     * Setujui permohonan
     */
    public function approve(VaccineRequest $permohonan)
    {
        $permohonan->update(['disetujui' => true]);

        return redirect()->route('admin.permohonan.index')
            ->with('success', 'Permohonan berhasil disetujui');
    }

    /**
     * Tolak permohonan
     */
    public function reject(VaccineRequest $permohonan)
    {
        $permohonan->update(['disetujui' => false]);

        return redirect()->route('admin.permohonan.index')
            ->with('success', 'Permohonan berhasil ditolak');
    }

    /**
     * Hapus permohonan
     */
    public function destroy(VaccineRequest $permohonan)
    {
        $permohonan->delete();

        return redirect()->route('admin.permohonan.index')
            ->with('success', 'Permohonan berhasil dihapus');
    }
}
