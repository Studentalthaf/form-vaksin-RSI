<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\VaccineRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermohonanController extends Controller
{
    /**
     * Tampilkan form pendaftaran untuk calon pasien (publik)
     */
    public function create()
    {
        return view('permohonan.create');
    }

    /**
     * Simpan data pendaftaran calon pasien
     */
    public function store(Request $request)
    {
        // Log untuk debugging
        Log::info('Permohonan store attempt', ['data' => $request->all()]);

        $validated = $request->validate([
            // Data Pasien
            'nama' => 'required|string|max:100',
            'nomor_paspor' => 'nullable|string|max:50',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'pekerjaan' => 'nullable|string|max:100',
            'alamat' => 'nullable|string|max:255',
            'no_telp' => 'required|string|max:20',
            
            // Data Permohonan Vaksin
            'negara_tujuan' => 'nullable|string|max:100',
            'tanggal_berangkat' => 'nullable|date|after:today',
            'jenis_vaksin' => 'nullable|string|max:100',
            'nama_travel' => 'nullable|string|max:100',
            'alamat_travel' => 'nullable|string|max:255',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi',
            'no_telp.required' => 'Nomor telepon wajib diisi',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'tanggal_berangkat.date' => 'Format tanggal berangkat tidak valid',
            'tanggal_berangkat.after' => 'Tanggal berangkat harus setelah hari ini',
        ]);

        DB::beginTransaction();
        try {
            // Simpan data pasien
            $pasien = Pasien::create([
                'nama' => $validated['nama'],
                'nomor_paspor' => $validated['nomor_paspor'] ?? null,
                'tempat_lahir' => $validated['tempat_lahir'] ?? null,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                'pekerjaan' => $validated['pekerjaan'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'no_telp' => $validated['no_telp'],
            ]);

            // Simpan data permohonan vaksin
            VaccineRequest::create([
                'pasien_id' => $pasien->id,
                'negara_tujuan' => $validated['negara_tujuan'] ?? null,
                'tanggal_berangkat' => $validated['tanggal_berangkat'] ?? null,
                'jenis_vaksin' => $validated['jenis_vaksin'] ?? null,
                'nama_travel' => $validated['nama_travel'] ?? null,
                'alamat_travel' => $validated['alamat_travel'] ?? null,
                'disetujui' => false,
            ]);

            DB::commit();

            return redirect()->route('permohonan.success')
                ->with('success', 'Permohonan vaksinasi berhasil dikirim! Kami akan menghubungi Anda segera.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.']);
        }
    }

    /**
     * Halaman sukses setelah pendaftaran
     */
    public function success()
    {
        return view('permohonan.success');
    }
}
