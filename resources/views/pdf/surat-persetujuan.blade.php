<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Surat Persetujuan Vaksinasi - {{ $screening->pasien->nama }}</title>
    <style>
        @page {
            margin: 1.5cm 2cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            line-height: 1.3;
            color: #000;
        }
        .page-break {
            page-break-before: always;
        }
        .kop-surat {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .kop-surat h1 {
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-surat h2 {
            margin: 2px 0 0 0;
            font-size: 11pt;
            font-weight: normal;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header h2 {
            margin: 3px 0;
            font-size: 11pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .intro-text {
            text-align: justify;
            margin: 6px 0;
            line-height: 1.4;
        }
        .form-section {
            margin: 8px 0;
        }
        .form-row {
            margin: 3px 0;
            line-height: 1.4;
        }
        .form-label {
            display: inline-block;
            width: 160px;
            vertical-align: top;
        }
        .form-value {
            display: inline-block;
            border-bottom: 1px dotted #000;
            min-width: 320px;
        }
        .consent-text {
            margin: 10px 0;
            text-align: justify;
            line-height: 1.5;
        }
        .signature-section {
            margin-top: 10px;
        }
        .signature-grid {
            display: table;
            width: 100%;
            margin-top: 8px;
        }
        .signature-box {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 0 8px;
            vertical-align: top;
        }
        .signature-box p {
            margin: 2px 0;
            font-size: 9pt;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 3px;
        }
        .rs-witness {
            margin-top: 10px;
        }
        .rs-witness-box {
            width: 180px;
        }
        .rs-witness-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 3px;
        }
        .footer-note {
            margin-top: 8px;
            font-size: 8pt;
            font-style: italic;
        }
        .bold {
            font-weight: bold;
        }
        .underline {
            text-decoration: underline;
        }
        .small {
            font-size: 8pt;
        }
        .italic {
            font-style: italic;
        }
        
        /* Halaman Penilaian */
        .assessment-page {
            margin-bottom: 20px;
        }
        .info-box {
            border: 1px solid #333;
            padding: 12px;
            margin: 12px 0;
            background-color: #fff;
        }
        .info-title {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 11pt;
            color: #000;
            border-bottom: 1px solid #000;
            padding-bottom: 4px;
            margin: -12px -12px 8px -12px;
            padding: 6px 12px;
        }
        .info-item {
            margin: 5px 0;
            padding-left: 10px;
            line-height: 1.5;
        }
        
        /* Halaman Screening */
        .screening-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 9pt;
        }
        .screening-table th,
        .screening-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }
        .screening-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        .screening-table .no-col {
            width: 30px;
            text-align: center;
        }
        .screening-table .checkbox-col {
            width: 40px;
            text-align: center;
        }
        .checkbox-mark {
            display: inline-block;
            width: 12px;
            height: 12px;
            position: relative;
        }
        .checkbox-mark.checked::before {
            content: '';
            position: absolute;
            left: 1px;
            top: -2px;
            width: 8px;
            height: 12px;
            border: solid #000;
            border-width: 0 3px 3px 0;
            transform: rotate(45deg);
        }
        .screening-table .keterangan-col {
            width: 120px;
        }
        .screening-header {
            margin: 8px 0;
        }
        .screening-header p {
            margin: 2px 0;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <!-- HALAMAN 1: HASIL PENILAIAN DOKTER -->
    <div class="assessment-page">
        <div class="kop-surat">
            <h1>HASIL PENILAIAN DOKTER</h1>
            <h2>VAKSINASI</h2>
        </div>

        <div class="form-section" style="margin-top: 15px;">
            <div style="font-weight: bold; margin-bottom: 8px; border-bottom: 2px solid #000; padding-bottom: 4px;">DATA PASIEN</div>
            <div class="form-row">
                <span class="form-label">Nama</span>
                <span>: <span class="form-value"><strong>{{ $screening->pasien->nama }}</strong></span></span>
            </div>
            @if($screening->pasien->nik)
            <div class="form-row">
                <span class="form-label">NIK</span>
                <span>: <span class="form-value"><strong>{{ $screening->pasien->nik }}</strong></span></span>
            </div>
            @endif
            <div class="form-row">
                <span class="form-label">Tempat, Tanggal Lahir</span>
                <span>: <span class="form-value">{{ $screening->pasien->tempat_lahir ?? '-' }}, {{ \Carbon\Carbon::parse($screening->pasien->tanggal_lahir)->locale('id')->isoFormat('DD MMMM YYYY') }} ({{ \Carbon\Carbon::parse($screening->pasien->tanggal_lahir)->age }} tahun)</span></span>
            </div>
            <div class="form-row">
                <span class="form-label">Jenis Kelamin</span>
                <span>: <span class="form-value">{{ $screening->pasien->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span></span>
            </div>
            <div class="form-row">
                <span class="form-label">Alamat</span>
                <span>: <span class="form-value">{{ $screening->pasien->alamat }}</span></span>
            </div>
            <div class="form-row">
                <span class="form-label">No. Telp</span>
                <span>: <span class="form-value">{{ $screening->pasien->no_telp }}</span></span>
            </div>
        </div>

        <div class="form-section" style="margin-top: 15px;">
            <div style="font-weight: bold; margin-bottom: 8px; border-bottom: 2px solid #000; padding-bottom: 4px;">JENIS VAKSINASI</div>
            @php
                $jenisVaksin = $screening->vaccineRequest->jenis_vaksin ?? '-';
                if (is_array($jenisVaksin)) {
                    $jenisVaksin = implode(', ', $jenisVaksin);
                }
            @endphp
            @if($screening->vaccineRequest && $screening->vaccineRequest->is_perjalanan)
            <div class="form-row">
                <span class="form-label">Jenis</span>
                <span>: <span class="form-value"><strong>{{ $jenisVaksin }}</strong></span></span>
            </div>
            <div class="form-row">
                <span class="form-label">Kategori</span>
                <span>: <span class="form-value"><strong>Vaksinasi Perjalanan Luar Negeri</strong></span></span>
            </div>
            @else
            <div class="form-row">
                <span class="form-label">Jenis</span>
                <span>: <span class="form-value"><strong>{{ $jenisVaksin }}</strong></span></span>
            </div>
            <div class="form-row">
                <span class="form-label">Kategori</span>
                <span>: <span class="form-value"><strong>Vaksinasi Umum</strong></span></span>
            </div>
            @endif
        </div>

        @if($screening->vaccineRequest && $screening->vaccineRequest->is_perjalanan)
        <div class="form-section" style="margin-top: 15px;">
            <div style="font-weight: bold; margin-bottom: 8px; border-bottom: 2px solid #000; padding-bottom: 4px;">DATA PERJALANAN</div>
            <div class="form-row">
                <span class="form-label">Negara Tujuan</span>
                <span>: <span class="form-value">{{ $screening->vaccineRequest->negara_tujuan ?? '-' }}</span></span>
            </div>
            <div class="form-row">
                <span class="form-label">Tanggal Berangkat</span>
                <span>: <span class="form-value">{{ $screening->vaccineRequest->tanggal_berangkat ? \Carbon\Carbon::parse($screening->vaccineRequest->tanggal_berangkat)->locale('id')->isoFormat('DD MMMM YYYY') : '-' }}</span></span>
            </div>
            @if($screening->pasien->nomor_paspor)
            <div class="form-row">
                <span class="form-label">No. Paspor</span>
                <span>: <span class="form-value"><strong>{{ $screening->pasien->nomor_paspor }}</strong></span></span>
            </div>
            @endif
            @if($screening->vaccineRequest->nama_travel)
            <div class="form-row">
                <span class="form-label">Travel</span>
                <span>: <span class="form-value">{{ $screening->vaccineRequest->nama_travel }}</span></span>
            </div>
            @endif
        </div>
        @endif

        @if($screening->nilaiScreening)
        <div class="form-section" style="margin-top: 15px;">
            <div style="font-weight: bold; margin-bottom: 8px; border-bottom: 2px solid #000; padding-bottom: 4px;">HASIL PEMERIKSAAN FISIK</div>
            <div class="form-row">
                <span class="form-label">Tekanan Darah</span>
                <span>: <span class="form-value"><strong>{{ $screening->nilaiScreening->td ?? '-' }}</strong> mmHg</span></span>
            </div>
            <div class="form-row">
                <span class="form-label">Nadi</span>
                <span>: <span class="form-value"><strong>{{ $screening->nilaiScreening->nadi ?? '-' }}</strong> x/menit</span></span>
            </div>
            <div class="form-row">
                <span class="form-label">Suhu</span>
                <span>: <span class="form-value"><strong>{{ $screening->nilaiScreening->suhu ?? '-' }}</strong> °C</span></span>
            </div>
            <div class="form-row">
                <span class="form-label">Berat Badan</span>
                <span>: <span class="form-value"><strong>{{ $screening->nilaiScreening->bb ?? '-' }}</strong> Kg</span></span>
            </div>
            <div class="form-row">
                <span class="form-label">Tinggi Badan</span>
                <span>: <span class="form-value"><strong>{{ $screening->nilaiScreening->tb ?? '-' }}</strong> cm</span></span>
            </div>
            @if($screening->tanggal_vaksinasi)
            <div class="form-row">
                <span class="form-label">Tanggal Vaksinasi</span>
                <span>: <span class="form-value"><strong>{{ \Carbon\Carbon::parse($screening->tanggal_vaksinasi)->locale('id')->isoFormat('DD MMMM YYYY') }}</strong></span></span>
            </div>
            @endif
        </div>

        <div class="form-section" style="margin-top: 15px;">
            <div style="font-weight: bold; margin-bottom: 8px; border-bottom: 2px solid #000; padding-bottom: 4px;">KESIMPULAN</div>
            @if($screening->catatan_dokter)
            <div style="padding: 5px 0; text-align: justify; line-height: 1.5;">{{ $screening->catatan_dokter }}</div>
            @else
            <div style="padding: 5px 0; min-height: 80px; border-bottom: 1px solid #ccc; margin-top: 10px;">
                <!-- Ruang kosong untuk menulis kesimpulan -->
            </div>
            @endif
        </div>

        <div style="margin-top: 30px;">
            <p style="margin: 2px 0; text-align: right; font-size: 10pt;">Dokter Pemeriksa,</p>
            @if($screening->tanda_tangan_dokter)
            <div style="margin-top: 10px; float: right; text-align: center;">
                <img src="{{ public_path('storage/' . $screening->tanda_tangan_dokter) }}" 
                     style="max-width: 180px; max-height: 60px; display: block; margin: 0 auto;" />
                <div style="border-top: 1px solid #000; width: 200px; padding-top: 5px; margin-top: 5px;">
                    <p style="margin: 0;"><strong>{{ $screening->dokter->nama ?? 'Dr. [Nama Dokter]' }}</strong></p>
                </div>
            </div>
            @else
            <div style="margin-top: 70px; float: right;">
                <div style="border-top: 1px solid #000; width: 200px; padding-top: 5px; text-align: center;">
                    <p style="margin: 0;"><strong>{{ $screening->dokter->nama ?? 'Dr. [Nama Dokter]' }}</strong></p>
                </div>
            </div>
            @endif
            <div style="clear: both;"></div>
        </div>
        @endif
    </div>

    <!-- HALAMAN 2: DAFTAR TILIK PENAPISAN KONTRAINDIKASI UNTUK VAKSINASI DEWASA -->
    <div class="page-break"></div>
    
    <div class="kop-surat">
        <h1>FORMULIR</h1>
        <h2>DAFTAR TILIK PENAPISAN KONTRAINDIKASI UNTUK VAKSINASI DEWASA</h2>
    </div>

    <div class="screening-header">
        <div style="display: table; width: 100%; margin-bottom: 8px;">
            <div style="display: table-row;">
                <div style="display: table-cell; width: 150px;"><strong>Nama Pasien</strong></div>
                <div style="display: table-cell;">: <strong>{{ $screening->pasien->nama }}</strong></div>
            </div>
            <div style="display: table-row;">
                <div style="display: table-cell; width: 150px;">Tanggal Lahir</div>
                <div style="display: table-cell;">: {{ \Carbon\Carbon::parse($screening->pasien->tanggal_lahir)->format('d / m / Y') }}</div>
            </div>
            <div style="display: table-row;">
                <div style="display: table-cell; width: 150px;">Tanggal Screening</div>
                <div style="display: table-cell;">: {{ \Carbon\Carbon::parse($screening->created_at)->format('d / m / Y') }}</div>
            </div>
        </div>
    </div>

    <table class="screening-table">
        <thead>
            <tr>
                <th class="no-col">No</th>
                <th>Pertanyaan</th>
                <th class="checkbox-col">Ya</th>
                <th class="checkbox-col">Tidak</th>
                <th class="keterangan-col">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Get all active questions
                $allQuestions = \App\Models\ScreeningQuestion::where('aktif', true)->orderBy('urutan')->get();
                // Map answers by question_id for easy lookup
                $answersMap = $screening->answers->keyBy('question_id');
            @endphp
            
            @forelse($allQuestions as $index => $question)
                @php
                    // Get answer for this question if exists
                    $answer = $answersMap->get($question->id);
                    
                    if ($answer && $answer->jawaban) {
                        // Normalize jawaban - case insensitive
                        $jawabanNormalized = strtolower(trim($answer->jawaban));
                        $isYa = in_array($jawabanNormalized, ['ya', 'y', 'yes', '1']);
                        $isTidak = in_array($jawabanNormalized, ['tidak', 'no', 'n', '0']);
                    } else {
                        $isYa = false;
                        $isTidak = false;
                    }
                    
                    $keterangan = $answer && $answer->keterangan ? trim($answer->keterangan) : '';
                @endphp
                <tr>
                    <td class="no-col">{{ $index + 1 }}</td>
                    <td>{{ $question->pertanyaan }}</td>
                    <td class="checkbox-col">
                        @if($isYa)
                        <span class="checkbox-mark checked"></span>
                        @endif
                    </td>
                    <td class="checkbox-col">
                        @if($isTidak)
                        <span class="checkbox-mark checked"></span>
                        @endif
                    </td>
                    <td>{{ $keterangan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px; font-style: italic;">
                        Tidak ada pertanyaan screening
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 15px; border: 1px solid #333; padding: 10px;">
        <p style="margin: 3px 0;"><strong>Hasil Screening:</strong></p>
        @if($screening->nilaiScreening)
        <div style="margin-top: 8px;">
            <table style="width: 100%; font-size: 9pt; border-collapse: collapse;">
                <tr>
                    <td style="padding: 3px 0; width: 35%;"><strong>Tekanan Darah</strong></td>
                    <td style="padding: 3px 0;">: {{ $screening->nilaiScreening->td ?? '-' }} mmHg</td>
                    <td style="padding: 3px 0; width: 35%;"><strong>Nadi</strong></td>
                    <td style="padding: 3px 0;">: {{ $screening->nilaiScreening->nadi ?? '-' }} x/menit</td>
                </tr>
                <tr>
                    <td style="padding: 3px 0;"><strong>Suhu</strong></td>
                    <td style="padding: 3px 0;">: {{ $screening->nilaiScreening->suhu ?? '-' }} °C</td>
                    <td style="padding: 3px 0;"><strong>Berat Badan</strong></td>
                    <td style="padding: 3px 0;">: {{ $screening->nilaiScreening->bb ?? '-' }} Kg</td>
                </tr>
                <tr>
                    <td style="padding: 3px 0;"><strong>Tinggi Badan</strong></td>
                    <td style="padding: 3px 0;">: {{ $screening->nilaiScreening->tb ?? '-' }} cm</td>
                    <td style="padding: 3px 0;"><strong>Hasil</strong></td>
                    <td style="padding: 3px 0;">: 
                        @if($screening->nilaiScreening->hasil_screening === 'aman')
                            <strong>AMAN</strong>
                        @elseif($screening->nilaiScreening->hasil_screening === 'perlu_perhatian')
                            <strong>PERLU PERHATIAN</strong>
                        @else
                            <strong>TIDAK LAYAK</strong>
                        @endif
                    </td>
                </tr>
            </table>
            @if($screening->nilaiScreening->admin)
            <p style="margin: 5px 0 0 0; font-size: 9pt;"><strong>Diperiksa oleh:</strong> {{ $screening->nilaiScreening->admin->nama }}</p>
            @endif
        </div>
        @endif
    </div>

    <div style="margin-top: 12px; padding: 8px; background-color: #f9f9f9; border: 1px dashed #666;">
        <p style="margin: 2px 0; font-size: 9pt;"><strong>Tanggal Pemeriksaan:</strong> {{ \Carbon\Carbon::parse($screening->created_at)->locale('id')->isoFormat('DD MMMM YYYY HH:mm') }} WIB</p>
        @if($screening->dokter)
        <p style="margin: 2px 0; font-size: 9pt;"><strong>Diverifikasi oleh Dokter:</strong> {{ $screening->dokter->nama }}</p>
        <p style="margin: 2px 0; font-size: 9pt;"><strong>Tanggal Verifikasi:</strong> {{ \Carbon\Carbon::parse($screening->updated_at)->locale('id')->isoFormat('DD MMMM YYYY HH:mm') }} WIB</p>
        @endif
    </div>

    <!-- HALAMAN 3: FORMULIR PERSETUJUAN -->
    <div class="page-break"></div>
    
    <div class="kop-surat">
        <h1>FORMULIR</h1>
        <h2>PERSETUJUAN/IZIN* TINDAKAN VAKSINASI</h2>
    </div>

    <div class="intro-text">
        Saya yang bertanda tangan di bawah ini,
    </div>

    <div class="form-section">
        <div class="form-row">
            <span class="form-label">Nama</span>
            <span>: <span class="form-value">{{ $screening->pasien->nama }}</span></span>
        </div>
        @if($screening->pasien->nik)
        <div class="form-row">
            <span class="form-label">NIK</span>
            <span>: <span class="form-value"><strong>{{ $screening->pasien->nik }}</strong></span></span>
        </div>
        @endif
        <div class="form-row">
            <span class="form-label">Alamat</span>
            <span>: <span class="form-value">{{ $screening->pasien->alamat }}</span></span>
        </div>
        <div class="form-row">
            <span class="form-label">No. Telp</span>
            <span>: <span class="form-value">{{ $screening->pasien->no_telp }}</span></span>
        </div>
    </div>

    <div class="intro-text">
        @php
            $jenisVaksinText = $screening->vaccineRequest->jenis_vaksin ?? '.............................';
            if (is_array($jenisVaksinText)) {
                $jenisVaksinText = implode(', ', $jenisVaksinText);
            }
        @endphp
        Dengan ini menyatakan dengan <strong>sesungguhnya</strong> telah memberikan <strong>PERSETUJUAN/ IZIN*</strong> untuk diberikan vaksinasi 
        @if($screening->vaccineRequest && $screening->vaccineRequest->is_perjalanan)
            <strong><u>{{ $jenisVaksinText }}</u></strong> untuk perjalanan ke <strong><u>{{ $screening->vaccineRequest->negara_tujuan ?? '.............................' }}</u></strong>
        @else
            <strong><u>{{ $jenisVaksinText }}</u></strong> (Vaksinasi Umum)
        @endif
        kepada diri saya sendiri:
    </div>

    <div class="form-section">
        <div class="form-row">
            <span class="form-label">Nama Lengkap</span>
            <span>: <span class="form-value">{{ $screening->pasien->nama }}</span></span>
        </div>
        @if($screening->pasien->nik)
        <div class="form-row">
            <span class="form-label">NIK</span>
            <span>: <span class="form-value"><strong>{{ $screening->pasien->nik }}</strong></span></span>
        </div>
        @endif
        <div class="form-row">
            <span class="form-label">Umur</span>
            <span>: <span class="form-value">{{ \Carbon\Carbon::parse($screening->pasien->tanggal_lahir)->age }} tahun</span></span>
        </div>
        <div class="form-row">
            <span class="form-label">Jenis Kelamin</span>
            <span>: <span class="form-value">{{ $screening->pasien->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span></span>
        </div>
        <div class="form-row">
            <span class="form-label">Pekerjaan</span>
            <span>: <span class="form-value">{{ $screening->pasien->pekerjaan ?? '-' }}</span></span>
        </div>
        @if($screening->tanggal_vaksinasi)
        <div class="form-row">
            <span class="form-label">Tanggal Vaksinasi</span>
            <span>: <span class="form-value"><strong>{{ \Carbon\Carbon::parse($screening->tanggal_vaksinasi)->locale('id')->isoFormat('dddd, D MMMM Y') }}</strong></span></span>
        </div>
        @endif
    </div>

    <div class="consent-text">
        <strong>Yang tujuan, sifat dan perlunya tindakan vaksinasi</strong> tersebut di atas, <strong>serta risiko yang dapat ditimbulkan (Kejadian Ikutan Pasca Imunisasi)</strong> telah cukup dijelaskan oleh dokter/petugas yang bertanggung jawab untuk hal tersebut, dan saya memahaminya.
    </div>

    <div class="intro-text">
        Demikian pernyataan persetujuan/izin* ini saya buat dengan penuh kesadaran dan tanpa paksaan.
    </div>

    <div style="text-align: right; margin: 8px 30px 8px 0;">
        <p style="margin: 3px 0;">..........................................................., {{ \Carbon\Carbon::parse($screening->tanggal_vaksinasi)->locale('id')->isoFormat('D MMMM Y') }}</p>
        <p style="margin: 3px 0; font-size: 8pt; font-style: italic;">(Tempat, Tanggal)</p>
    </div>

    <div class="signature-grid">
        <div class="signature-box">
            <p><strong>Dokter/Operator</strong></p>
            @if($screening->tanda_tangan_dokter)
            <div style="margin: 10px 0; text-align: center;">
                <img src="{{ public_path('storage/' . $screening->tanda_tangan_dokter) }}" 
                     style="max-width: 150px; max-height: 60px; display: inline-block;" />
            </div>
            <div style="margin-top: 5px; padding-top: 0;">
                <p style="margin: 0;"><strong>{{ $screening->dokter->nama ?? '' }}</strong></p>
            </div>
            @else
            <div style="margin: 70px 0 0 0;">
                <p>(&nbsp;..........................................................&nbsp;)</p>
            </div>
            @endif
        </div>
        <div class="signature-box">
            <p><strong>Pasien</strong></p>
            <div style="margin: 10px 0 -5px 0; text-align: center; min-height: 60px;">
                @if($screening->tanda_tangan_pasien)
                <img src="{{ public_path('storage/' . $screening->tanda_tangan_pasien) }}" 
                     style="max-width: 150px; max-height: 60px; display: inline-block; filter: grayscale(100%) brightness(0%);" />
                @endif
            </div>
            <div style="padding-top: 0;">
                <p style="margin: 0;"><strong>{{ $screening->pasien->nama ?? '' }}</strong></p>
            </div>
        </div>
        <div class="signature-box">
            <p><strong>Keluarga/Pendamping</strong></p>
            <div style="margin: 70px 0 0 0;">
                <p>(&nbsp;..........................................................&nbsp;)</p>
            </div>
        </div>
    </div>

    <div class="rs-witness">
        <p class="bold">Saksi dari Pihak RS</p>
        <p class="small italic">Tenaga Ahli</p>
        <div class="rs-witness-box">
            <div style="margin-top: 70px;">
                <p>(&nbsp;..........................................................&nbsp;)</p>
            </div>
        </div>
    </div>
</body>
</html>
