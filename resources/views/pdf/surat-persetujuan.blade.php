<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
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
            border: 2px solid #2563eb;
            padding: 10px;
            margin: 10px 0;
            background-color: #f0f9ff;
            border-radius: 4px;
        }
        .info-title {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 11pt;
            color: #1e40af;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 4px;
            background-color: #dbeafe;
            padding: 5px 10px;
            margin: -10px -10px 8px -10px;
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

        <div class="info-box">
            <div class="info-title">DATA PASIEN</div>
            <div class="info-item">Nama: <strong>{{ $screening->pasien->nama }}</strong></div>
            <div class="info-item">Tempat, Tanggal Lahir: {{ $screening->pasien->tempat_lahir ?? '-' }}, {{ \Carbon\Carbon::parse($screening->pasien->tanggal_lahir)->format('d-m-Y') }} ({{ \Carbon\Carbon::parse($screening->pasien->tanggal_lahir)->age }} tahun)</div>
            <div class="info-item">Jenis Kelamin: {{ $screening->pasien->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
            <div class="info-item">Alamat: {{ $screening->pasien->alamat }}</div>
            <div class="info-item">No. Telp: {{ $screening->pasien->no_telp }}</div>
            @if($screening->pasien->nomor_paspor)
            <div class="info-item">No. Paspor: {{ $screening->pasien->nomor_paspor }}</div>
            @endif
        </div>

        <div class="info-box" style="border-color: {{ $screening->vaccineRequest && $screening->vaccineRequest->is_perjalanan ? '#f59e0b' : '#10b981' }}; background-color: {{ $screening->vaccineRequest && $screening->vaccineRequest->is_perjalanan ? '#fffbeb' : '#f0fdf4' }};">
            <div class="info-title" style="color: {{ $screening->vaccineRequest && $screening->vaccineRequest->is_perjalanan ? '#d97706' : '#059669' }}; border-bottom-color: {{ $screening->vaccineRequest && $screening->vaccineRequest->is_perjalanan ? '#fbbf24' : '#34d399' }}; background-color: {{ $screening->vaccineRequest && $screening->vaccineRequest->is_perjalanan ? '#fef3c7' : '#d1fae5' }};">
                JENIS VAKSINASI
                @if($screening->vaccineRequest && $screening->vaccineRequest->is_perjalanan)
                <span style="float: right; font-size: 9pt; font-weight: normal;">✈️ Perjalanan</span>
                @else
                <span style="float: right; font-size: 9pt; font-weight: normal;">💉 Umum</span>
                @endif
            </div>
            @if($screening->vaccineRequest && $screening->vaccineRequest->is_perjalanan)
            <div class="info-item">Jenis: <strong>{{ $screening->vaccineRequest->jenis_vaksin ?? '-' }}</strong></div>
            <div class="info-item">Kategori: <strong>Vaksinasi Perjalanan Luar Negeri</strong></div>
            <div class="info-item">Negara Tujuan: {{ $screening->vaccineRequest->negara_tujuan ?? '-' }}</div>
            <div class="info-item">Tanggal Berangkat: {{ $screening->vaccineRequest->tanggal_berangkat ? \Carbon\Carbon::parse($screening->vaccineRequest->tanggal_berangkat)->format('d-m-Y') : '-' }}</div>
            @if($screening->vaccineRequest->nama_travel)
            <div class="info-item">Travel: {{ $screening->vaccineRequest->nama_travel }}</div>
            @endif
            @else
            <div class="info-item">Jenis: <strong>{{ $screening->penilaian->jenis_vaksin ?? $screening->vaccineRequest->jenis_vaksin ?? '-' }}</strong></div>
            <div class="info-item">Kategori: <strong>Vaksinasi Umum</strong></div>
            @endif
        </div>

        @if($screening->penilaian)
        <div class="info-box" style="border-color: #06b6d4; background-color: #ecfeff;">
            <div class="info-title" style="color: #0891b2; border-bottom-color: #22d3ee; background-color: #cffafe;">HASIL PEMERIKSAAN FISIK</div>
            <div class="info-item">📊 Tekanan Darah: <strong>{{ $screening->penilaian->td ?? '-' }}</strong> mmHg</div>
            <div class="info-item">💓 Nadi: <strong>{{ $screening->penilaian->nadi ?? '-' }}</strong> x/menit</div>
            <div class="info-item">🌡️ Suhu: <strong>{{ $screening->penilaian->suhu ?? '-' }}</strong> °C</div>
            <div class="info-item">📅 Tanggal Vaksinasi: <strong>{{ \Carbon\Carbon::parse($screening->tanggal_vaksinasi)->format('d-m-Y') }}</strong></div>
        </div>

        @if($screening->penilaian->catatan)
        <div class="info-box" style="border-color: #7c3aed; background-color: #faf5ff;">
            <div class="info-title" style="color: #6b21a8; border-bottom-color: #a78bfa; background-color: #ede9fe;">CATATAN DOKTER</div>
            <div style="padding: 5px 10px; text-align: justify;">{{ $screening->penilaian->catatan }}</div>
        </div>
        @endif

        <div class="info-box" style="border-color: {{ $screening->hasil_screening === 'aman' ? '#16a34a' : '#dc2626' }}; background-color: {{ $screening->hasil_screening === 'aman' ? '#f0fdf4' : '#fef2f2' }};">
            <div class="info-title" style="color: {{ $screening->hasil_screening === 'aman' ? '#15803d' : '#b91c1c' }}; border-bottom-color: {{ $screening->hasil_screening === 'aman' ? '#4ade80' : '#f87171' }}; background-color: {{ $screening->hasil_screening === 'aman' ? '#dcfce7' : '#fee2e2' }};">KESIMPULAN</div>
            <div style="padding: 5px 10px; font-size: 11pt;">
                Pasien <strong style="font-size: 12pt; color: {{ $screening->hasil_screening === 'aman' ? '#15803d' : '#b91c1c' }};">{{ strtoupper($screening->hasil_screening) }}</strong> untuk dilakukan vaksinasi.
            </div>
        </div>

        <div style="margin-top: 15px; padding: 10px; background-color: #f1f5f9; border: 1px solid #64748b; border-radius: 4px;">
            <p style="margin: 2px 0; text-align: right; font-size: 10pt;">Dokter Pemeriksa,</p>
            <div style="margin-top: 50px; border-top: 2px solid #334155; width: 200px; display: inline-block; padding-top: 5px; float: right;">
                <p style="margin: 0; text-align: center;"><strong>{{ $screening->dokter->nama ?? 'Dr. [Nama Dokter]' }}</strong></p>
            </div>
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
                <th class="checkbox-col">Tahu</th>
                <th class="keterangan-col">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @if($screening->answers && $screening->answers->count() > 0)
                @foreach($screening->answers as $index => $answer)
                <tr>
                    <td class="no-col">{{ $index + 1 }}</td>
                    <td>{{ $answer->question->pertanyaan ?? '-' }}</td>
                    <td class="checkbox-col">
                        @if(strtolower($answer->jawaban) === 'ya')
                        ✓
                        @endif
                    </td>
                    <td class="checkbox-col">
                        @if(strtolower($answer->jawaban) === 'tidak')
                        ✓
                        @endif
                    </td>
                    <td class="checkbox-col"></td>
                    <td>{{ $answer->keterangan ?? '-' }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; font-style: italic;">
                        Tidak ada data screening
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <div style="margin-top: 15px; border: 1px solid #333; padding: 10px;">
        <p style="margin: 3px 0;"><strong>Hasil Screening:</strong> {{ strtoupper($screening->hasil_screening) }}</p>
        @if($screening->catatan_screening)
        <p style="margin: 3px 0;"><strong>Catatan Screening:</strong> {{ $screening->catatan_screening }}</p>
        @endif
    </div>

    <div style="margin-top: 12px; padding: 8px; background-color: #f9f9f9; border: 1px dashed #666;">
        <p style="margin: 2px 0; font-size: 9pt;"><strong>Diperiksa oleh Admin:</strong> {{ Auth::user()->nama ?? 'Admin Rumah Sakit' }}</p>
        <p style="margin: 2px 0; font-size: 9pt;"><strong>Tanggal Pemeriksaan:</strong> {{ \Carbon\Carbon::parse($screening->created_at)->format('d-m-Y H:i') }} WIB</p>
        @if($screening->dokter)
        <p style="margin: 2px 0; font-size: 9pt;"><strong>Diverifikasi oleh Dokter:</strong> {{ $screening->dokter->nama }}</p>
        <p style="margin: 2px 0; font-size: 9pt;"><strong>Tanggal Verifikasi:</strong> {{ \Carbon\Carbon::parse($screening->updated_at)->format('d-m-Y H:i') }} WIB</p>
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
        Dengan ini menyatakan dengan <strong>sesungguhnya</strong> telah memberikan <strong>PERSETUJUAN/ IZIN*</strong> untuk diberikan vaksinasi 
        @if($screening->vaccineRequest && $screening->vaccineRequest->is_perjalanan)
            <strong><u>{{ $screening->vaccineRequest->jenis_vaksin ?? '.............................' }}</u></strong> untuk perjalanan ke <strong><u>{{ $screening->vaccineRequest->negara_tujuan ?? '.............................' }}</u></strong>
        @else
            <strong><u>{{ $screening->penilaian->jenis_vaksin ?? $screening->vaccineRequest->jenis_vaksin ?? '.............................' }}</u></strong> (Vaksinasi Umum)
        @endif
        kepada diri saya sendiri:
    </div>

    <div class="form-section">
        <div class="form-row">
            <span class="form-label">Nama Lengkap</span>
            <span>: <span class="form-value">{{ $screening->pasien->nama }}</span></span>
        </div>
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
        <div class="form-row">
            <span class="form-label">Tanggal Vaksinasi</span>
            <span>: <span class="form-value"><strong>{{ \Carbon\Carbon::parse($screening->tanggal_vaksinasi)->isoFormat('dddd, D MMMM Y') }}</strong></span></span>
        </div>
    </div>

    <div class="consent-text">
        <strong>Yang tujuan, sifat dan perlunya tindakan vaksinasi</strong> tersebut di atas, <strong>serta risiko yang dapat ditimbulkan (Kejadian Ikutan Pasca Imunisasi)</strong> telah cukup dijelaskan oleh dokter/petugas yang bertanggung jawab untuk hal tersebut, dan saya memahaminya.
    </div>

    <div class="intro-text">
        Demikian pernyataan persetujuan/izin* ini saya buat dengan penuh kesadaran dan tanpa paksaan.
    </div>

    <div style="text-align: right; margin: 8px 30px 8px 0;">
        <p style="margin: 3px 0;">..........................................................., {{ \Carbon\Carbon::parse($screening->tanggal_vaksinasi)->isoFormat('D MMMM Y') }}</p>
        <p style="margin: 3px 0; font-size: 8pt; font-style: italic;">(Tempat, Tanggal)</p>
    </div>

    <div class="signature-grid">
        <div class="signature-box">
            <p><strong>Dokter/Operator</strong></p>
            <div class="signature-line">
                <p>(&nbsp;..........................................................&nbsp;)</p>
                <p class="small">Nama jelas</p>
            </div>
        </div>
        <div class="signature-box">
            <p><strong>Pasien</strong></p>
            <div class="signature-line">
                <p>(&nbsp;..........................................................&nbsp;)</p>
                <p class="small">Nama jelas</p>
            </div>
        </div>
        <div class="signature-box">
            <p><strong>Keluarga/Pendamping</strong></p>
            <div class="signature-line">
                <p>(&nbsp;..........................................................&nbsp;)</p>
                <p class="small">Nama jelas</p>
            </div>
        </div>
    </div>

    <div class="rs-witness">
        <p class="bold">Saksi dari Pihak RS</p>
        <p class="small italic">Tenaga Ahli</p>
        <div class="rs-witness-box">
            <div class="rs-witness-line">
                <p>(&nbsp;..........................................................&nbsp;)</p>
                <p class="small">Nama Jelas</p>
            </div>
        </div>
    </div>
</body>
</html>
