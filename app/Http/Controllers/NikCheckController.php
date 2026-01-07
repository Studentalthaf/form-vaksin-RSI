<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\VaccineRequest;
use App\Models\Screening;
use Illuminate\Http\Request;

class NikCheckController extends Controller
{
    /**
     * Check if NIK has pending/unverified vaccine requests
     */
    public function checkNik(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:20'
        ]);

        $nik = $request->nik;

        // Cari pasien berdasarkan NIK
        $pasien = Pasien::where('nik', $nik)->first();

        if (!$pasien) {
            // NIK belum pernah terdaftar
            return response()->json([
                'status' => 'new',
                'message' => 'NIK belum terdaftar',
                'can_proceed' => true
            ]);
        }

        // Cek apakah ada permohonan vaksin yang belum terverifikasi dokter
        $pendingRequests = VaccineRequest::where('pasien_id', $pasien->id)
            ->whereHas('screening', function($query) {
                $query->where(function($q) {
                    // Belum dikonfirmasi dokter
                    $q->where('status_konfirmasi', '!=', 'sudah_dikonfirmasi')
                      ->orWhereNull('dokter_id')
                      ->orWhere('hasil_screening', 'pending');
                });
            })
            ->with(['screening' => function($query) {
                $query->select('id', 'vaccine_request_id', 'tanggal_screening', 'hasil_screening', 'status_konfirmasi', 'dokter_id');
            }])
            ->get();

        if ($pendingRequests->count() > 0) {
            // Ada permohonan yang masih dalam proses
            $latestRequest = $pendingRequests->first();
            $screening = $latestRequest->screening;

            return response()->json([
                'status' => 'pending',
                'message' => 'NIK ini memiliki permohonan yang masih dalam proses verifikasi',
                'can_proceed' => false,
                'data' => [
                    'nama' => $pasien->nama,
                    'nik' => $pasien->nik,
                    'total_pending' => $pendingRequests->count(),
                    'tanggal_daftar' => $latestRequest->created_at->format('d/m/Y H:i'),
                    'status_screening' => $screening ? $screening->hasil_screening : 'pending',
                    'status_konfirmasi' => $screening ? $screening->status_konfirmasi : 'belum_dikonfirmasi',
                ]
            ]);
        }

        // NIK sudah terdaftar tapi tidak ada pending request
        return response()->json([
            'status' => 'verified',
            'message' => 'NIK sudah terdaftar dan dapat mendaftar kembali',
            'can_proceed' => true,
            'data' => [
                'nama' => $pasien->nama,
                'nik' => $pasien->nik,
            ]
        ]);
    }
}
