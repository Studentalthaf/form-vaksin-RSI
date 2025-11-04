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

        // Convert checkbox to boolean
        $request->merge([
            'is_perjalanan' => $request->has('is_perjalanan') ? 1 : 0,
            'has_sim_rs' => (int) $request->has_sim_rs
        ]);

        // Validasi berbeda untuk pasien baru vs existing
        $rules = [
            // Pilihan SIM RS
            'has_sim_rs' => 'required|boolean',
            
            // Jenis Vaksin (WAJIB untuk semua)
            'jenis_vaksin' => 'required|string|max:100',
            
            // Jenis Permohonan
            'is_perjalanan' => 'nullable|boolean',
        ];

        // Jika PUNYA SIM RS (pasien existing)
        if ($request->has_sim_rs == 1) {
            $rules['sim_rs'] = 'required|string|max:20|exists:pasiens,sim_rs';
        } else {
            // Jika BELUM PUNYA (pasien baru) - wajib isi semua data
            $rules['nama'] = 'required|string|max:100';
            $rules['no_telp'] = 'required|string|max:20';
            $rules['tempat_lahir'] = 'nullable|string|max:100';
            $rules['tanggal_lahir'] = 'nullable|date';
            $rules['jenis_kelamin'] = 'nullable|in:L,P';
            $rules['pekerjaan'] = 'nullable|string|max:100';
            $rules['alamat'] = 'nullable|string|max:255';
        }

        // Jika perjalanan luar negeri - paspor WAJIB
        if ($request->is_perjalanan == 1) {
            $rules['nomor_paspor'] = 'required|string|max:50';
            $rules['negara_tujuan'] = 'required|string|max:100';
            $rules['tanggal_berangkat'] = 'required|date|after:today';
            $rules['nama_travel'] = 'nullable|string|max:100';
            $rules['alamat_travel'] = 'nullable|string|max:255';
        }

        $validated = $request->validate($rules, [
            'has_sim_rs.required' => 'Silakan pilih apakah Anda memiliki SIM RS',
            'sim_rs.required' => 'Nomor SIM RS wajib diisi',
            'sim_rs.exists' => 'Nomor SIM RS tidak ditemukan dalam database',
            'nama.required' => 'Nama lengkap wajib diisi',
            'no_telp.required' => 'Nomor telepon wajib diisi',
            'jenis_vaksin.required' => 'Jenis vaksin wajib dipilih',
            'nomor_paspor.required' => 'Nomor paspor wajib diisi untuk perjalanan luar negeri',
            'negara_tujuan.required' => 'Negara tujuan wajib diisi',
            'tanggal_berangkat.required' => 'Tanggal keberangkatan wajib diisi',
            'tanggal_berangkat.after' => 'Tanggal berangkat harus setelah hari ini',
        ]);

        DB::beginTransaction();
        try {
            // SCENARIO 1: Pasien SUDAH TERDAFTAR (punya SIM RS)
            if ($request->has_sim_rs == 1) {
                $pasien = Pasien::where('sim_rs', $request->sim_rs)->firstOrFail();
                
                // Update nomor paspor jika perjalanan (optional update)
                if ($request->is_perjalanan == 1 && $request->nomor_paspor) {
                    $pasien->update(['nomor_paspor' => $request->nomor_paspor]);
                }
            } 
            // SCENARIO 2: Pasien BARU (belum punya SIM RS)
            else {
                // Generate SIM RS otomatis: SIM + Timestamp
                $sim_rs = 'SIM' . now()->format('ymdHis');
                
                $pasien = Pasien::create([
                    'sim_rs' => $sim_rs,
                    'nama' => $validated['nama'],
                    'nomor_paspor' => $request->is_perjalanan == 1 ? $request->nomor_paspor : null,
                    'tempat_lahir' => $validated['tempat_lahir'] ?? null,
                    'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                    'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                    'pekerjaan' => $validated['pekerjaan'] ?? null,
                    'alamat' => $validated['alamat'] ?? null,
                    'no_telp' => $validated['no_telp'],
                ]);
            }

            // Simpan permohonan vaksin (untuk pasien baru maupun existing)
            VaccineRequest::create([
                'pasien_id' => $pasien->id,
                'is_perjalanan' => $request->is_perjalanan == 1,
                'jenis_vaksin' => $validated['jenis_vaksin'],
                'negara_tujuan' => $validated['negara_tujuan'] ?? null,
                'tanggal_berangkat' => $validated['tanggal_berangkat'] ?? null,
                'nama_travel' => $validated['nama_travel'] ?? null,
                'alamat_travel' => $validated['alamat_travel'] ?? null,
                'disetujui' => false,
            ]);

            DB::commit();

            // Session flash dengan SIM RS untuk pasien baru
            if ($request->has_sim_rs == 0) {
                session()->flash('sim_rs', $pasien->sim_rs);
                session()->flash('message', 'Selamat! Nomor SIM RS Anda adalah: ' . $pasien->sim_rs . '. Simpan nomor ini untuk permohonan selanjutnya.');
            }

            return redirect()->route('permohonan.success')
                ->with('success', 'Permohonan vaksinasi berhasil dikirim! Kami akan menghubungi Anda segera.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing permohonan: ' . $e->getMessage());
            return back()->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Halaman sukses setelah pendaftaran
     */
    public function success()
    {
        return view('permohonan.success');
    }

    /**
     * API: Cek data pasien berdasarkan nomor SIM RS
     */
    public function checkSimRS($sim_rs)
    {
        $pasien = Pasien::where('sim_rs', $sim_rs)->first();
        
        if ($pasien) {
            return response()->json([
                'found' => true,
                'data' => [
                    'nama' => $pasien->nama,
                    'nomor_paspor' => $pasien->nomor_paspor,
                    'tempat_lahir' => $pasien->tempat_lahir,
                    'tanggal_lahir' => $pasien->tanggal_lahir?->format('Y-m-d'),
                    'jenis_kelamin' => $pasien->jenis_kelamin,
                    'pekerjaan' => $pasien->pekerjaan,
                    'alamat' => $pasien->alamat,
                    'no_telp' => $pasien->no_telp,
                ]
            ]);
        }
        
        return response()->json(['found' => false]);
    }
}
