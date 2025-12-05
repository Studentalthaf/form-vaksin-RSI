<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\VaccineRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class PermohonanController extends Controller
{
    /**
     * Tampilkan form pendaftaran untuk calon pasien (publik)
     */
    public function create()
    {
        // Ambil daftar vaksin aktif dari database
        $vaksins = \App\Models\Vaksin::where('aktif', true)
            ->orderBy('urutan')
            ->orderBy('nama_vaksin')
            ->get();
        
        return view('permohonan.create', compact('vaksins'));
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
        ]);

        // Validasi reCAPTCHA (hanya di production, skip di local)
        $isLocal = config('app.env') === 'local';
        if (!$isLocal) {
            $recaptchaResponse = $request->input('g-recaptcha-response');
            if (empty($recaptchaResponse)) {
                return back()->withErrors(['g-recaptcha-response' => 'Silakan centang "Saya bukan robot"'])->withInput();
            }

            // Verify reCAPTCHA with Google
            $recaptchaSecret = config('services.recaptcha.secret_key');
            $verifyResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $recaptchaSecret,
                'response' => $recaptchaResponse,
                'remoteip' => $request->ip()
            ]);

            $recaptchaResult = $verifyResponse->json();
            
            if (!$recaptchaResult['success']) {
                Log::warning('reCAPTCHA verification failed', ['result' => $recaptchaResult]);
                return back()->withErrors(['g-recaptcha-response' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.'])->withInput();
            }
        }

        // Validasi
        $rules = [
            // Data Pasien (WAJIB)
            'nik' => 'required|string|max:20',
            'nama' => 'required|string|max:100',
            'no_telp' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'pekerjaan' => 'nullable|string|max:100',
            'alamat' => 'nullable|string|max:255',
            'status_pasien' => 'required|in:baru,lama',
            'nomor_rm' => 'nullable|string|max:50',
            
            // Upload Files
            'foto_ktp' => 'required|image|mimes:jpeg,jpg,png,pdf|max:5120',
            
            // Jenis Vaksin (WAJIB) - bisa lebih dari satu
            'jenis_vaksin' => 'required|array|min:1',
            'jenis_vaksin.*' => 'required|string|max:100',
            'vaksin_lainnya_text' => 'nullable|string|max:200',
            
            // Jenis Permohonan
            'is_perjalanan' => 'nullable|boolean',
        ];

        // Validasi conditional: jika pilih "Lainnya", maka vaksin_lainnya_text wajib diisi
        if ($request->has('jenis_vaksin') && in_array('Lainnya', $request->jenis_vaksin)) {
            $rules['vaksin_lainnya_text'] = 'required|string|max:200';
        }

        // Jika perjalanan luar negeri - paspor dan foto paspor WAJIB
        if ($request->is_perjalanan == 1) {
            $rules['nomor_paspor'] = 'required|string|max:50';
            $rules['passport_halaman_pertama'] = 'required|image|mimes:jpeg,jpg,png,pdf|max:5120';
            $rules['passport_halaman_kedua'] = 'required|image|mimes:jpeg,jpg,png,pdf|max:5120';
            $rules['negara_tujuan'] = 'required|string|max:100';
            $rules['tanggal_berangkat'] = 'required|date|after:today';
            $rules['nama_travel'] = 'nullable|string|max:100';
            $rules['alamat_travel'] = 'nullable|string|max:255';
        }

        $validated = $request->validate($rules, [
            'nik.required' => 'NIK wajib diisi',
            'nama.required' => 'Nama lengkap wajib diisi',
            'no_telp.required' => 'Nomor telepon wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'status_pasien.required' => 'Status pasien wajib dipilih',
            'foto_ktp.required' => 'Foto KTP wajib diupload',
            'foto_ktp.image' => 'File KTP harus berupa gambar',
            'foto_ktp.max' => 'Ukuran file KTP maksimal 5MB',
            'passport_halaman_pertama.required' => 'Passport halaman pertama wajib diupload untuk perjalanan luar negeri',
            'passport_halaman_pertama.image' => 'File passport halaman pertama harus berupa gambar',
            'passport_halaman_pertama.max' => 'Ukuran file passport halaman pertama maksimal 5MB',
            'passport_halaman_kedua.required' => 'Passport halaman kedua wajib diupload untuk perjalanan luar negeri',
            'passport_halaman_kedua.image' => 'File passport halaman kedua harus berupa gambar',
            'passport_halaman_kedua.max' => 'Ukuran file passport halaman kedua maksimal 5MB',
            'jenis_vaksin.required' => 'Minimal pilih satu jenis vaksin',
            'jenis_vaksin.min' => 'Minimal pilih satu jenis vaksin',
            'vaksin_lainnya_text.required' => 'Sebutkan jenis vaksin lainnya yang dibutuhkan',
            'nomor_paspor.required' => 'Nomor paspor wajib diisi untuk perjalanan luar negeri',
            'negara_tujuan.required' => 'Negara tujuan wajib diisi',
            'tanggal_berangkat.required' => 'Tanggal keberangkatan wajib diisi',
            'tanggal_berangkat.after' => 'Tanggal berangkat harus setelah hari ini',
        ]);

        DB::beginTransaction();
        try {
            // Process jenis_vaksin: if patient selected "Lainnya", remove it from the list
            // and store the custom text separately in vaksin_lainnya so it will be saved
            // but NOT shown in public vaccine lists (only stored for admin reference).
            $jenisVaksin = $validated['jenis_vaksin'];
            $vaksinLainnyaText = null;
            if (in_array('Lainnya', $jenisVaksin)) {
                // Remove "Lainnya" from array so only predefined vaccines remain
                $jenisVaksin = array_filter($jenisVaksin, function($item) {
                    return $item !== 'Lainnya';
                });
                $jenisVaksin = array_values($jenisVaksin);

                // Capture the custom text (if provided)
                if (!empty($request->vaksin_lainnya_text)) {
                    $vaksinLainnyaText = trim($request->vaksin_lainnya_text);
                }
            }
            
            // Upload foto KTP
            $fotoKtpPath = null;
            if ($request->hasFile('foto_ktp')) {
                $fotoKtpPath = $request->file('foto_ktp')->store('foto-ktp', 'public');
            }

            // Upload foto Paspor halaman pertama dan kedua (opsional)
            $passportHalamanPertamaPath = null;
            $passportHalamanKeduaPath = null;
            if ($request->hasFile('passport_halaman_pertama')) {
                $passportHalamanPertamaPath = $request->file('passport_halaman_pertama')->store('foto-paspor', 'public');
            }
            if ($request->hasFile('passport_halaman_kedua')) {
                $passportHalamanKeduaPath = $request->file('passport_halaman_kedua')->store('foto-paspor', 'public');
            }

            // Cek apakah pasien dengan NIK ini sudah ada
            $pasien = Pasien::where('nik', $request->nik)->first();
            
            if ($pasien) {
                // Update data pasien jika ada perubahan
                $updateData = [
                    'nama' => $validated['nama'],
                    'email' => $validated['email'] ?? $pasien->email,
                    'nomor_rm' => $validated['nomor_rm'] ?? $pasien->nomor_rm,
                    'nomor_paspor' => $request->is_perjalanan == 1 ? $request->nomor_paspor : $pasien->nomor_paspor,
                    'tempat_lahir' => $validated['tempat_lahir'] ?? $pasien->tempat_lahir,
                    'tanggal_lahir' => $validated['tanggal_lahir'] ?? $pasien->tanggal_lahir,
                    'jenis_kelamin' => $validated['jenis_kelamin'] ?? $pasien->jenis_kelamin,
                    'pekerjaan' => $validated['pekerjaan'] ?? $pasien->pekerjaan,
                    'alamat' => $validated['alamat'] ?? $pasien->alamat,
                    'no_telp' => $validated['no_telp'],
                    'status_pasien' => $validated['status_pasien'],
                ];
                
                // Update foto jika ada upload baru
                if ($fotoKtpPath) {
                    $updateData['foto_ktp'] = $fotoKtpPath;
                }
                if ($passportHalamanPertamaPath) {
                    $updateData['passport_halaman_pertama'] = $passportHalamanPertamaPath;
                }
                if ($passportHalamanKeduaPath) {
                    $updateData['passport_halaman_kedua'] = $passportHalamanKeduaPath;
                }
                
                $pasien->update($updateData);
            } else {
                // Buat pasien baru
                $pasien = Pasien::create([
                    'nik' => $validated['nik'],
                    'nama' => $validated['nama'],
                    'email' => $validated['email'] ?? null,
                    'nomor_rm' => $validated['nomor_rm'] ?? null,
                    'nomor_paspor' => $request->is_perjalanan == 1 ? $request->nomor_paspor : null,
                    'tempat_lahir' => $validated['tempat_lahir'] ?? null,
                    'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                    'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                    'pekerjaan' => $validated['pekerjaan'] ?? null,
                    'alamat' => $validated['alamat'] ?? null,
                    'no_telp' => $validated['no_telp'],
                    'foto_ktp' => $fotoKtpPath,
                    'passport_halaman_pertama' => $passportHalamanPertamaPath,
                    'passport_halaman_kedua' => $passportHalamanKeduaPath,
                    'status_pasien' => $validated['status_pasien'],
                ]);
            }

            // Simpan permohonan vaksin (simpan vaksin_lainnya terpisah jika ada)
            $vaccineRequest = VaccineRequest::create([
                'pasien_id' => $pasien->id,
                'is_perjalanan' => $request->is_perjalanan == 1,
                'jenis_vaksin' => $jenisVaksin, // Use processed vaccine array
                'vaksin_lainnya' => $vaksinLainnyaText,
                'negara_tujuan' => $validated['negara_tujuan'] ?? null,
                'tanggal_berangkat' => $validated['tanggal_berangkat'] ?? null,
                'nama_travel' => $validated['nama_travel'] ?? null,
                'alamat_travel' => $validated['alamat_travel'] ?? null,
                'disetujui' => false,
            ]);

            DB::commit();

            // Redirect ke halaman screening questions
            return redirect()->route('permohonan.screening', ['vaccine_request_id' => $vaccineRequest->id])
                ->with('success', 'Data berhasil disimpan! Silakan lanjutkan dengan menjawab pertanyaan screening.');

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
     * Tampilkan form screening questions
     */
    public function showScreening($vaccine_request_id)
    {
        $vaccineRequest = VaccineRequest::with('pasien')->findOrFail($vaccine_request_id);
        
        // Get active screening questions, ordered by urutan
        $questions = \App\Models\ScreeningQuestion::where('aktif', true)
            ->orderBy('urutan')
            ->get();
        
        return view('permohonan.screening', compact('vaccineRequest', 'questions'));
    }

    /**
     * Simpan jawaban screening dari pasien
     */
    public function storeScreening(Request $request, $vaccine_request_id)
    {
        $vaccineRequest = VaccineRequest::with('pasien')->findOrFail($vaccine_request_id);
        
        // Validasi: pastikan semua pertanyaan wajib dijawab dan tanda tangan
        $questions = \App\Models\ScreeningQuestion::where('aktif', true)
            ->where('wajib', true)
            ->get();
        
        // Validasi tanda tangan
        $rules = [
            'tanda_tangan' => 'required|string',
            'tanda_tangan_keluarga' => 'nullable|string',
            'nama_keluarga' => 'nullable|string|max:100',
        ];
        
        $messages = [
            'tanda_tangan.required' => 'Tanda tangan persetujuan wajib diisi',
        ];
        
        // Jika checkbox perlu_tanda_tangan_keluarga dicentang, maka nama_keluarga dan tanda_tangan_keluarga wajib
        if ($request->has('perlu_tanda_tangan_keluarga') && $request->perlu_tanda_tangan_keluarga == '1') {
            $rules['nama_keluarga'] = 'required|string|max:100';
            $rules['tanda_tangan_keluarga'] = 'required|string';
            $messages['nama_keluarga.required'] = 'Nama keluarga/pendamping wajib diisi jika memilih tanda tangan keluarga';
            $messages['tanda_tangan_keluarga.required'] = 'Tanda tangan keluarga wajib dibuat jika memilih opsi ini';
        }
        
        $request->validate($rules, $messages);
        
        foreach ($questions as $question) {
            if (!$request->has('jawaban_' . $question->id)) {
                return back()->withInput()
                    ->withErrors(['error' => 'Semua pertanyaan wajib dijawab!']);
            }
        }
        
        DB::beginTransaction();
        try {
            // Process signature
            $signatureData = $request->tanda_tangan;
            $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
            $signatureData = str_replace(' ', '+', $signatureData);
            $signatureDecoded = base64_decode($signatureData);
            
            // Save signature file
            $signatureFileName = 'signature_pasien_' . $vaccine_request_id . '_' . time() . '.png';
            $signaturePath = 'signatures/' . $signatureFileName;
            Storage::disk('public')->put($signaturePath, $signatureDecoded);
            
            // Process family signature if provided
            $signatureKeluargaPath = null;
            if ($request->has('tanda_tangan_keluarga') && $request->tanda_tangan_keluarga) {
                $signatureKeluargaData = $request->tanda_tangan_keluarga;
                $signatureKeluargaData = str_replace('data:image/png;base64,', '', $signatureKeluargaData);
                $signatureKeluargaData = str_replace(' ', '+', $signatureKeluargaData);
                $signatureKeluargaDecoded = base64_decode($signatureKeluargaData);
                
                $signatureKeluargaFileName = 'signature_keluarga_' . $vaccine_request_id . '_' . time() . '.png';
                $signatureKeluargaPath = 'signatures/' . $signatureKeluargaFileName;
                Storage::disk('public')->put($signatureKeluargaPath, $signatureKeluargaDecoded);
            }
            
            // Update pasien dengan nama_keluarga jika ada
            if ($request->has('nama_keluarga') && $request->nama_keluarga) {
                $vaccineRequest->pasien->update([
                    'nama_keluarga' => $request->nama_keluarga
                ]);
            }
            
            // Buat record screening
            $screening = \App\Models\Screening::create([
                'pasien_id' => $vaccineRequest->pasien_id,
                'vaccine_request_id' => $vaccine_request_id,
                'tanggal_screening' => now(),
                'hasil_screening' => 'pending', // Akan di-review oleh admin/dokter
                'catatan' => 'Dijawab oleh pasien',
                'tanda_tangan_pasien' => $signaturePath, // Simpan path signature
                'tanda_tangan_keluarga' => $signatureKeluargaPath, // Simpan path signature keluarga jika ada
            ]);
            
            // Simpan semua jawaban
            $allQuestions = \App\Models\ScreeningQuestion::where('aktif', true)->get();
            
            foreach ($allQuestions as $question) {
                $jawaban = $request->input('jawaban_' . $question->id);
                $keterangan = $request->input('keterangan_' . $question->id);
                
                if ($jawaban) {
                    \App\Models\ScreeningAnswer::create([
                        'screening_id' => $screening->id,
                        'question_id' => $question->id,
                        'jawaban' => $jawaban,
                        'keterangan' => $keterangan,
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('permohonan.success')
                ->with('success', 'Permohonan vaksinasi berhasil dikirim! Kami akan menghubungi Anda segera.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing screening: ' . $e->getMessage());
            return back()->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
