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
    public function index(Request $request)
    {
        // Tab yang aktif (default: pending)
        $activeTab = $request->get('tab', 'pending');
        
        // Query untuk Pending (belum disetujui)
        $queryPending = VaccineRequest::with('pasien')->where('disetujui', false);
        
        // Query untuk Disetujui
        $queryDisetujui = VaccineRequest::with('pasien')->where('disetujui', true);

        // Filter berdasarkan search nama pemohon
        if ($request->filled('search')) {
            $search = $request->search;
            $queryPending->whereHas('pasien', function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%');
            });
            $queryDisetujui->whereHas('pasien', function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $queryPending->whereDate('created_at', '>=', $request->tanggal_dari);
            $queryDisetujui->whereDate('created_at', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $queryPending->whereDate('created_at', '<=', $request->tanggal_sampai);
            $queryDisetujui->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        // Ambil data berdasarkan tab aktif - DATA TERBARU DI ATAS
        $permohonanPending = $queryPending->orderBy('created_at', 'desc')->paginate(15, ['*'], 'pending_page');
        $permohonanDisetujui = $queryDisetujui->orderBy('created_at', 'desc')->paginate(15, ['*'], 'disetujui_page');

        // Statistik untuk card
        $hariIni = VaccineRequest::whereDate('created_at', today())->count();
        $hariIniDisetujui = VaccineRequest::whereDate('created_at', today())->where('disetujui', true)->count();
        $hariIniPending = VaccineRequest::whereDate('created_at', today())->where('disetujui', false)->count();
        $totalPermohonan = VaccineRequest::count();
        $totalPending = VaccineRequest::where('disetujui', false)->count();
        $totalDisetujui = VaccineRequest::where('disetujui', true)->count();

        return view('admin.permohonan.index', compact(
            'permohonanPending',
            'permohonanDisetujui',
            'activeTab',
            'hariIni', 
            'hariIniDisetujui', 
            'hariIniPending',
            'totalPermohonan',
            'totalPending',
            'totalDisetujui'
        ));
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
