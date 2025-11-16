@extends('layouts.admin')

@section('page-title', 'Detail Permohonan Terverifikasi')
@section('page-subtitle', 'Informasi lengkap permohonan yang sudah diverifikasi dokter')

@push('styles')
<style>
    .tab-button {
        color: #6b7280;
        border-bottom-color: transparent;
    }
    .tab-button:hover {
        color: #10b981;
        border-bottom-color: #d1fae5;
    }
    .tab-button.active {
        color: #10b981;
        border-bottom-color: #10b981;
    }
    .tab-content {
        display: none;
    }
    .tab-content.active {
        display: block;
        animation: fadeIn 0.3s;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.permohonan.terverifikasi') }}" class="inline-flex items-center text-green-600 hover:text-green-700 font-semibold transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
        
        <div class="flex items-center space-x-3">
            <!-- Tombol Cetak PDF -->
            <a href="{{ route('admin.permohonan.terverifikasi.cetak-pdf', $permohonan) }}" 
               target="_blank"
               class="inline-flex items-center px-5 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Cetak PDF
            </a>
            
            <!-- Status Badge -->
            <div class="flex items-center space-x-3 bg-green-50 border-2 border-green-500 rounded-lg px-5 py-2 shadow">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="font-bold text-green-900">TERVERIFIKASI</p>
                    <p class="text-xs text-green-600">{{ $permohonan->screening->tanggal_konfirmasi ? \Carbon\Carbon::parse($permohonan->screening->tanggal_konfirmasi)->format('d/m/Y H:i') : '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="border-b border-gray-200 bg-gray-50">
            <nav class="flex space-x-1 px-4" aria-label="Tabs">
                <button onclick="switchTab('tab1')" id="btn-tab1" class="tab-button active py-3 px-4 text-sm font-semibold border-b-2 transition">
                    📋 Data Pasien
                </button>
                <button onclick="switchTab('tab2')" id="btn-tab2" class="tab-button py-3 px-4 text-sm font-semibold border-b-2 transition">
                    ✍️ Screening
                </button>
                <button onclick="switchTab('tab3')" id="btn-tab3" class="tab-button py-3 px-4 text-sm font-semibold border-b-2 transition">
                    🩺 Pemeriksaan
                </button>
                <button onclick="switchTab('tab4')" id="btn-tab4" class="tab-button py-3 px-4 text-sm font-semibold border-b-2 transition">
                    ✅ Verifikasi
                </button>
            </nav>
        </div>

        <div class="p-6">
            <!-- TAB 1: DATA PASIEN -->
            <div id="tab1" class="tab-content active">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Data Pribadi -->
                    <div class="space-y-4">
                        <h3 class="font-bold text-lg text-gray-800 border-b pb-2">Data Pribadi</h3>
                        
                        <div class="grid grid-cols-3 gap-2 text-sm">
                            <span class="text-gray-600 font-medium">Nama</span>
                            <span class="col-span-2 font-semibold">{{ $permohonan->pasien->nama }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                            <span class="text-gray-600 font-medium">NIK</span>
                            <span class="col-span-2">{{ $permohonan->pasien->nik ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                            <span class="text-gray-600 font-medium">No. RM</span>
                            <span class="col-span-2">
                                @if($permohonan->pasien->nomor_rm)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded">{{ $permohonan->pasien->nomor_rm }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                            <span class="text-gray-600 font-medium">Status</span>
                            <span class="col-span-2">
                                @if($permohonan->pasien->status_pasien === 'baru')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">Pasien Baru</span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Pasien Lama</span>
                                @endif
                            </span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                            <span class="text-gray-600 font-medium">Paspor</span>
                            <span class="col-span-2">{{ $permohonan->pasien->nomor_paspor ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                            <span class="text-gray-600 font-medium">TTL</span>
                            <span class="col-span-2">{{ $permohonan->pasien->tempat_lahir ?? '-' }}{{ $permohonan->pasien->tanggal_lahir ? ', ' . \Carbon\Carbon::parse($permohonan->pasien->tanggal_lahir)->format('d/m/Y') : '' }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                            <span class="text-gray-600 font-medium">Jenis Kelamin</span>
                            <span class="col-span-2">
                                @if($permohonan->pasien->jenis_kelamin == 'L') Laki-laki
                                @elseif($permohonan->pasien->jenis_kelamin == 'P') Perempuan
                                @else - @endif
                            </span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                            <span class="text-gray-600 font-medium">Pekerjaan</span>
                            <span class="col-span-2">{{ $permohonan->pasien->pekerjaan ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                            <span class="text-gray-600 font-medium">Alamat</span>
                            <span class="col-span-2">{{ $permohonan->pasien->alamat ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                            <span class="text-gray-600 font-medium">Telepon</span>
                            <span class="col-span-2 font-semibold text-blue-600">{{ $permohonan->pasien->no_telp }}</span>
                        </div>
                        @if($permohonan->pasien->email)
                        <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                            <span class="text-gray-600 font-medium">Email</span>
                            <span class="col-span-2 font-semibold text-blue-600">{{ $permohonan->pasien->email }}</span>
                        </div>
                        @endif

                        <!-- Data Perjalanan -->
                        <h3 class="font-bold text-lg text-gray-800 border-b pb-2 mt-6">Data Perjalanan</h3>
                        
                        <div class="grid grid-cols-3 gap-2 text-sm">
                            <span class="text-gray-600 font-medium">Negara Tujuan</span>
                            <span class="col-span-2 font-semibold">{{ $permohonan->negara_tujuan ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                            <span class="text-gray-600 font-medium">Tgl Berangkat</span>
                            <span class="col-span-2">{{ $permohonan->tanggal_berangkat ? \Carbon\Carbon::parse($permohonan->tanggal_berangkat)->format('d/m/Y') : '-' }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                            <span class="text-gray-600 font-medium">Jenis Vaksin</span>
                            <span class="col-span-2">
                                <div class="flex flex-wrap gap-2">
                                    @if(is_array($permohonan->jenis_vaksin) && count($permohonan->jenis_vaksin) > 0)
                                        @foreach($permohonan->jenis_vaksin as $vaksin)
                                            @if($vaksin !== 'Lainnya')
                                                <span class="inline-block px-2 py-1 bg-amber-100 text-amber-800 text-xs rounded-full mr-1 mb-1">{{ $vaksin }}</span>
                                            @endif
                                        @endforeach
                                    @elseif($permohonan->jenis_vaksin)
                                        @if($permohonan->jenis_vaksin !== 'Lainnya')
                                            <span class="inline-block px-2 py-1 bg-amber-100 text-amber-800 text-xs rounded-full mr-1 mb-1">{{ $permohonan->jenis_vaksin }}</span>
                                        @endif
                                    @endif
                                    
                                    @if($permohonan->vaksin_lainnya)
                                        <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full mr-1 mb-1 border border-yellow-300">
                                            {{ $permohonan->vaksin_lainnya }}
                                        </span>
                                    @elseif(is_array($permohonan->jenis_vaksin) && in_array('Lainnya', $permohonan->jenis_vaksin) && !$permohonan->vaksin_lainnya)
                                        <span class="inline-block px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full mr-1 mb-1">Lainnya (belum diisi)</span>
                                    @endif
                                    
                                    @if(empty($permohonan->jenis_vaksin) && empty($permohonan->vaksin_lainnya))
                                        <span class="text-gray-500 text-sm italic">-</span>
                                    @endif
                                </div>
                            </span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                            <span class="text-gray-600 font-medium">Travel</span>
                            <span class="col-span-2">{{ $permohonan->nama_travel ?? '-' }}</span>
                        </div>
                    </div>

                    <!-- Dokumen & Tanda Tangan Pasien -->
                    <div class="space-y-4">
                        <h3 class="font-bold text-lg text-gray-800 border-b pb-2">Dokumen Identitas</h3>
                        
                        @if($permohonan->pasien->foto_ktp)
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Foto KTP</label>
                            <div class="border rounded-lg overflow-hidden hover:border-blue-500 transition cursor-pointer" 
                                 onclick="openImageModal('{{ asset('storage/' . $permohonan->pasien->foto_ktp) }}', 'KTP')">
                                <img src="{{ asset('storage/' . $permohonan->pasien->foto_ktp) }}" 
                                     alt="KTP" 
                                     class="w-full h-32 object-cover">
                                <div class="bg-gray-50 px-2 py-1 text-center">
                                    <span class="text-xs text-gray-600">Klik untuk perbesar</span>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($permohonan->pasien->foto_paspor)
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Foto Paspor</label>
                            <div class="border rounded-lg overflow-hidden hover:border-blue-500 transition cursor-pointer" 
                                 onclick="openImageModal('{{ asset('storage/' . $permohonan->pasien->foto_paspor) }}', 'Paspor')">
                                <img src="{{ asset('storage/' . $permohonan->pasien->foto_paspor) }}" 
                                     alt="Paspor" 
                                     class="w-full h-32 object-cover">
                                <div class="bg-gray-50 px-2 py-1 text-center">
                                    <span class="text-xs text-gray-600">Klik untuk perbesar</span>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($permohonan->screening && $permohonan->screening->tanda_tangan_pasien)
                        <div class="mt-6">
                            <h3 class="font-bold text-lg text-gray-800 border-b pb-2 mb-2">Tanda Tangan Pasien</h3>
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-3 mb-2">
                                <p class="text-xs text-purple-700">
                                    Ditandatangani: {{ $permohonan->screening->tanggal_screening ? \Carbon\Carbon::parse($permohonan->screening->tanggal_screening)->format('d/m/Y H:i') : '-' }}
                                </p>
                            </div>
                            <div class="border-2 border-purple-200 rounded-lg p-3 cursor-pointer hover:border-purple-400 transition"
                                 onclick="openImageModal('{{ asset('storage/' . $permohonan->screening->tanda_tangan_pasien) }}', 'TTD Pasien')">
                                <img src="{{ asset('storage/' . $permohonan->screening->tanda_tangan_pasien) }}" 
                                     alt="TTD Pasien" 
                                     class="w-full h-32 object-contain">
                            </div>
                        </div>
                        @endif

                        @if($permohonan->screening && $permohonan->screening->tanda_tangan_keluarga)
                        <div class="mt-6">
                            <h3 class="font-bold text-lg text-gray-800 border-b pb-2 mb-2">Tanda Tangan Keluarga/Pendamping</h3>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-2">
                                <p class="text-xs text-blue-700">
                                    Keluarga/Pendamping telah menandatangani form persetujuan
                                </p>
                                @if($permohonan->pasien->nama_keluarga)
                                <p class="text-xs text-blue-800 font-semibold mt-1">
                                    Nama: {{ $permohonan->pasien->nama_keluarga }}
                                </p>
                                @endif
                            </div>
                            <div class="border-2 border-blue-200 rounded-lg p-3 cursor-pointer hover:border-blue-400 transition"
                                 onclick="openImageModal('{{ asset('storage/' . $permohonan->screening->tanda_tangan_keluarga) }}', 'TTD Keluarga')">
                                <img src="{{ asset('storage/' . $permohonan->screening->tanda_tangan_keluarga) }}" 
                                     alt="TTD Keluarga" 
                                     class="w-full h-32 object-contain">
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- TAB 2: HASIL SCREENING -->
            <div id="tab2" class="tab-content">
                @if($permohonan->screening && $permohonan->screening->answers && $permohonan->screening->answers->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    @foreach($permohonan->screening->answers as $index => $answer)
                    <div class="border-l-4 border-indigo-400 bg-gray-50 p-3 rounded-r">
                        <div class="flex justify-between items-start mb-2">
                            <p class="font-semibold text-sm text-gray-800">{{ $index + 1 }}. {{ $answer->question->pertanyaan }}</p>
                            @if($answer->question->wajib)
                            <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs rounded-full">Wajib</span>
                            @endif
                        </div>
                        
                        <div class="mt-1">
                            @if($answer->jawaban == 'ya')
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">✓ Ya</span>
                            @elseif($answer->jawaban == 'tidak')
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">✗ Tidak</span>
                            @else
                                <span class="font-semibold text-sm text-gray-800">{{ $answer->jawaban }}</span>
                            @endif
                        </div>

                        @if($answer->keterangan)
                        <div class="mt-2 bg-yellow-50 border border-yellow-200 rounded p-2">
                            <p class="text-xs text-yellow-700 font-semibold">Keterangan:</p>
                            <p class="text-xs text-gray-700">{{ $answer->keterangan }}</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12 text-gray-500">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p>Belum ada data screening</p>
                </div>
                @endif
            </div>

            <!-- TAB 3: PEMERIKSAAN FISIK -->
            <div id="tab3" class="tab-content">
                @if($permohonan->screening && $permohonan->screening->nilaiScreening)
                <div class="space-y-4">
                    <!-- Info Admin & Hasil -->
                    <div class="bg-teal-50 border-2 border-teal-300 rounded-lg p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-teal-700 font-medium">Diperiksa oleh:</p>
                            <p class="text-lg font-bold text-teal-900">{{ $permohonan->screening->nilaiScreening->admin->nama ?? '-' }}</p>
                        </div>
                        <div class="text-right">
                            @if($permohonan->screening->nilaiScreening->hasil_screening === 'aman')
                            <span class="px-4 py-2 bg-green-500 text-white rounded-full text-sm font-bold">✓ AMAN</span>
                            @elseif($permohonan->screening->nilaiScreening->hasil_screening === 'perlu_perhatian')
                            <span class="px-4 py-2 bg-yellow-500 text-white rounded-full text-sm font-bold">⚠ PERHATIAN</span>
                            @else
                            <span class="px-4 py-2 bg-red-500 text-white rounded-full text-sm font-bold">✗ TIDAK LAYAK</span>
                            @endif
                        </div>
                    </div>

                    <!-- Data Vital dalam Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="bg-red-50 border border-red-200 p-3 rounded-lg text-center">
                            <p class="text-xs text-red-600 font-semibold">Tekanan Darah</p>
                            <p class="text-2xl font-bold text-red-900 mt-1">{{ $permohonan->screening->nilaiScreening->td ?? '-' }}</p>
                            <p class="text-xs text-red-500">mmHg</p>
                        </div>
                        <div class="bg-pink-50 border border-pink-200 p-3 rounded-lg text-center">
                            <p class="text-xs text-pink-600 font-semibold">Nadi</p>
                            <p class="text-2xl font-bold text-pink-900 mt-1">{{ $permohonan->screening->nilaiScreening->nadi ?? '-' }}</p>
                            <p class="text-xs text-pink-500">x/menit</p>
                        </div>
                        <div class="bg-orange-50 border border-orange-200 p-3 rounded-lg text-center">
                            <p class="text-xs text-orange-600 font-semibold">Suhu</p>
                            <p class="text-2xl font-bold text-orange-900 mt-1">{{ $permohonan->screening->nilaiScreening->suhu ?? '-' }}</p>
                            <p class="text-xs text-orange-500">°C</p>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 p-3 rounded-lg text-center">
                            <p class="text-xs text-blue-600 font-semibold">Berat Badan</p>
                            <p class="text-2xl font-bold text-blue-900 mt-1">{{ $permohonan->screening->nilaiScreening->bb ?? '-' }}</p>
                            <p class="text-xs text-blue-500">Kg</p>
                        </div>
                        <div class="bg-purple-50 border border-purple-200 p-3 rounded-lg text-center">
                            <p class="text-xs text-purple-600 font-semibold">Tinggi</p>
                            <p class="text-2xl font-bold text-purple-900 mt-1">{{ $permohonan->screening->nilaiScreening->tb ?? '-' }}</p>
                            <p class="text-xs text-purple-500">cm</p>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 p-3 rounded-lg">
                            <p class="text-xs text-yellow-600 font-semibold mb-1">Alergi Obat</p>
                            <p class="text-sm font-bold text-yellow-900">{{ $permohonan->screening->nilaiScreening->alergi_obat ?? 'Tidak ada' }}</p>
                        </div>
                        <div class="bg-red-50 border border-red-200 p-3 rounded-lg">
                            <p class="text-xs text-red-600 font-semibold mb-1">Alergi Vaksin</p>
                            <p class="text-sm font-bold text-red-900">{{ $permohonan->screening->nilaiScreening->alergi_vaksin ?? 'Tidak ada' }}</p>
                        </div>
                        <div class="bg-green-50 border border-green-200 p-3 rounded-lg">
                            <p class="text-xs text-green-600 font-semibold mb-1">Vaksin COVID</p>
                            <p class="text-sm font-bold text-green-900">{{ $permohonan->screening->nilaiScreening->vaksin_covid ?? 'Belum' }}</p>
                        </div>
                    </div>

                    @if($permohonan->screening->nilaiScreening->catatan)
                    <div class="bg-gray-50 border-l-4 border-teal-500 p-3 rounded-r">
                        <p class="text-xs font-semibold text-gray-700 mb-1">Catatan Admin:</p>
                        <p class="text-sm text-gray-800">{{ $permohonan->screening->nilaiScreening->catatan }}</p>
                    </div>
                    @endif
                </div>
                @else
                <div class="text-center py-12 text-gray-500">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <p>Belum ada pemeriksaan fisik</p>
                </div>
                @endif
            </div>

            <!-- TAB 4: VERIFIKASI DOKTER -->
            <div id="tab4" class="tab-content">
                @if($permohonan->screening && $permohonan->screening->status_konfirmasi === 'dikonfirmasi')
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Info Verifikasi -->
                    <div class="space-y-4">
                        <div class="bg-green-50 border-2 border-green-300 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <p class="text-sm text-green-700 font-medium">Dokter:</p>
                                    <p class="text-xl font-bold text-green-900">Dr. {{ $permohonan->screening->dokter->nama ?? '-' }}</p>
                                </div>
                                <span class="px-3 py-1 bg-green-500 text-white rounded-full font-bold text-xs">✓ VERIFIED</span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <p class="text-green-600 font-medium text-xs">Tanggal Konfirmasi</p>
                                    <p class="font-semibold text-green-900">{{ $permohonan->screening->tanggal_konfirmasi ? \Carbon\Carbon::parse($permohonan->screening->tanggal_konfirmasi)->format('d/m/Y H:i') : '-' }}</p>
                                </div>
                                @if($permohonan->screening->tanggal_vaksinasi)
                                <div>
                                    <p class="text-green-600 font-medium text-xs">Jadwal Vaksinasi</p>
                                    <p class="font-semibold text-green-900">{{ \Carbon\Carbon::parse($permohonan->screening->tanggal_vaksinasi)->format('d F Y') }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        @if($permohonan->screening->catatan_dokter)
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r">
                            <p class="text-sm font-semibold text-blue-700 mb-2 flex items-center">
                                📝 Catatan Dokter
                            </p>
                            <p class="text-sm text-gray-800">{{ $permohonan->screening->catatan_dokter }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Tanda Tangan Dokter -->
                    @if($permohonan->screening->tanda_tangan_dokter)
                    <div>
                        <h3 class="font-bold text-lg text-gray-800 border-b pb-2 mb-3">Tanda Tangan Dokter</h3>
                        <div class="border-2 border-green-200 rounded-lg bg-white p-4 cursor-pointer hover:border-green-400 transition"
                             onclick="openImageModal('{{ asset('storage/' . $permohonan->screening->tanda_tangan_dokter) }}', 'TTD Dokter')">
                            <img src="{{ asset('storage/' . $permohonan->screening->tanda_tangan_dokter) }}" 
                                 alt="TTD Dokter" 
                                 class="w-full h-40 object-contain">
                            <div class="bg-green-50 px-3 py-2 text-center mt-3 rounded">
                                <span class="text-xs text-green-700 font-medium">Klik untuk memperbesar</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @else
                <div class="text-center py-12 text-gray-500">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>Belum diverifikasi</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 items-center justify-center p-4 hidden" onclick="closeImageModal()">
        <div class="relative max-w-4xl max-h-full" onclick="event.stopPropagation()">
            <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img id="modalImage" src="" alt="" class="max-w-full max-h-[80vh] rounded-lg shadow-2xl">
            <p id="modalTitle" class="text-white text-center mt-4 font-semibold text-lg"></p>
        </div>
    </div>

    <script>
    function switchTab(tabId) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Show selected tab
        document.getElementById(tabId).classList.add('active');
        document.getElementById('btn-' + tabId).classList.add('active');
    }

    function openImageModal(imageSrc, title) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('modalTitle').textContent = title;
        const modal = document.getElementById('imageModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });
    </script>
@endsection
