@extends('layouts.dokter')

@section('page-title', 'Detail Pasien & Hasil Screening')
@section('page-subtitle', 'Informasi lengkap pasien dan hasil screening admin')

@section('content')
<div class="space-y-6">
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div>
        <a href="{{ route('dokter.pasien.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Pasien
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                    {{ strtoupper(substr($screening->pasien->nama ?? 'N', 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $screening->pasien->nama ?? '-' }}</h1>
                    <div class="flex items-center gap-3 mt-1">
                        <p class="text-sm text-gray-600">NIK: {{ $screening->pasien->nik ?? '-' }}</p>
                        <span class="text-gray-400">•</span>
                        <p class="text-sm font-semibold text-green-600">RM: {{ $screening->pasien->nomor_rm ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div>
                @if($screening->status_vaksinasi == 'belum_divaksin')
                    <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Belum Divaksin
                    </span>
                @elseif($screening->status_vaksinasi == 'sudah_divaksin')
                    <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Sudah Divaksin
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Kolom Kiri -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                    <h2 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Informasi Pribadi
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p class="text-sm text-gray-500 font-medium">Nama Lengkap</p>
                            <p class="text-base font-semibold text-gray-900">{{ $screening->pasien->nama ?? '-' }}</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p class="text-sm text-gray-500 font-medium">Nomor RM</p>
                            <p class="text-base font-semibold text-gray-900">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg bg-green-100 text-green-800 font-bold">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ $screening->pasien->nomor_rm ?? '-' }}
                                </span>
                            </p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p class="text-sm text-gray-500 font-medium">NIK</p>
                            <p class="text-base font-semibold text-gray-900">{{ $screening->pasien->nik ?? '-' }}</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p class="text-sm text-gray-500 font-medium">Tempat, Tanggal Lahir</p>
                            <p class="text-base font-semibold text-gray-900">
                                {{ $screening->pasien->tempat_lahir ?? '-' }}, 
                                {{ $screening->pasien->tanggal_lahir ? \Carbon\Carbon::parse($screening->pasien->tanggal_lahir)->format('d M Y') : '-' }}
                            </p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p class="text-sm text-gray-500 font-medium">Usia</p>
                            <p class="text-base font-semibold text-gray-900">
                                {{ $screening->pasien->tanggal_lahir ? \Carbon\Carbon::parse($screening->pasien->tanggal_lahir)->age . ' tahun' : '-' }}
                            </p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p class="text-sm text-gray-500 font-medium">Jenis Kelamin</p>
                            <p class="text-base font-semibold text-gray-900">{{ ucfirst($screening->pasien->jenis_kelamin ?? '-') }}</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p class="text-sm text-gray-500 font-medium">No. Telepon</p>
                            <p class="text-base font-semibold text-gray-900">{{ $screening->pasien->no_telp ?? '-' }}</p>
                        </div>
                        @if($screening->pasien->email)
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p class="text-sm text-gray-500 font-medium">Email</p>
                            <p class="text-base font-semibold text-blue-600">{{ $screening->pasien->email }}</p>
                        </div>
                        @endif
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p class="text-sm text-gray-500 font-medium">Pekerjaan</p>
                            <p class="text-base font-semibold text-gray-900">{{ $screening->pasien->pekerjaan ?? '-' }}</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-2 md:col-span-2">
                            <p class="text-sm text-gray-500 font-medium">Alamat</p>
                            <p class="text-base font-semibold text-gray-900">{{ $screening->pasien->alamat ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Permohonan -->
            @if($screening->vaccineRequest)
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-amber-600 to-orange-600 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Informasi Permohonan
                    </h2>
                    <button type="button" onclick="toggleEditMode('vaksin')" id="btn-edit-vaksin" class="px-4 py-2 bg-white text-amber-600 rounded-lg font-semibold hover:bg-amber-50 transition">
                        ✏️ Edit
                    </button>
                </div>
                
                <!-- View Mode -->
                <div id="view-vaksin" class="p-6 space-y-4">
                    <!-- Jenis Vaksin -->
                    <div>
                        <p class="text-sm text-gray-500 font-medium mb-2">Jenis Vaksin yang Dimohonkan</p>
                        <div class="flex flex-wrap gap-2">
                            @if(is_array($screening->vaccineRequest->jenis_vaksin) && count($screening->vaccineRequest->jenis_vaksin) > 0)
                                @foreach($screening->vaccineRequest->jenis_vaksin as $vaksin)
                                    @if($vaksin !== 'Lainnya')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $vaksin }}
                                        </span>
                                    @endif
                                @endforeach
                            @elseif($screening->vaccineRequest->jenis_vaksin)
                                @if($screening->vaccineRequest->jenis_vaksin !== 'Lainnya')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $screening->vaccineRequest->jenis_vaksin }}
                                    </span>
                                @endif
                            @endif
                            
                            @if($screening->vaccineRequest->vaksin_lainnya)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-300">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    {{ $screening->vaccineRequest->vaksin_lainnya }}
                                </span>
                            @endif
                            
                            @if(empty($screening->vaccineRequest->jenis_vaksin) && empty($screening->vaccineRequest->vaksin_lainnya))
                                <span class="text-gray-500 text-sm italic">-</span>
                            @endif
                        </div>
                    </div>

                    <!-- Data Keberangkatan -->
                    @if($screening->vaccineRequest->is_perjalanan == 1)
                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-xs font-semibold text-blue-800 uppercase mb-2">Informasi Perjalanan Luar Negeri</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($screening->vaccineRequest->negara_tujuan)
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Negara Tujuan</p>
                                <p class="text-base font-semibold text-gray-900">{{ $screening->vaccineRequest->negara_tujuan }}</p>
                            </div>
                            @endif
                            @if($screening->vaccineRequest->tanggal_berangkat)
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Tanggal Berangkat</p>
                                <p class="text-base font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($screening->vaccineRequest->tanggal_berangkat)->format('d M Y') }}
                                </p>
                            </div>
                            @endif
                            @if($screening->vaccineRequest->nama_travel)
                            <div class="md:col-span-2">
                                <p class="text-xs text-gray-500 font-medium">Nama Travel</p>
                                <p class="text-base font-semibold text-gray-900">{{ $screening->vaccineRequest->nama_travel }}</p>
                            </div>
                            @endif
                        </div>
                        <p class="text-xs text-gray-600 mt-2 italic">* Informasi perjalanan tidak dapat diubah</p>
                    </div>
                    @endif
                </div>
                
                <!-- Edit Mode -->
                <div id="edit-vaksin" class="hidden p-6 border-t border-gray-200">
                    <form method="POST" action="{{ route('dokter.pasien.vaksin.update', $screening->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Jenis Vaksin yang Dimohonkan *</label>
                            <div class="grid md:grid-cols-2 gap-3">
                                @if(isset($vaksins) && $vaksins->count() > 0)
                                    @foreach($vaksins as $vaksin)
                                    <label class="flex items-start p-3 border border-gray-300 rounded-lg hover:bg-amber-50 cursor-pointer transition">
                                        <input type="checkbox" name="jenis_vaksin[]" value="{{ $vaksin->nama_vaksin }}" 
                                            {{ in_array($vaksin->nama_vaksin, old('jenis_vaksin', is_array($screening->vaccineRequest->jenis_vaksin) ? $screening->vaccineRequest->jenis_vaksin : [])) ? 'checked' : '' }}
                                            class="mt-1 w-4 h-4 text-amber-600 rounded focus:ring-amber-500"
                                            onchange="toggleVaksinLainnya()">
                                        <span class="ml-3 text-gray-700">
                                            {{ $vaksin->nama_vaksin }}
                                            @if($vaksin->deskripsi)
                                                <span class="text-xs text-gray-500 block">{{ $vaksin->deskripsi }}</span>
                                            @endif
                                        </span>
                                    </label>
                                    @endforeach
                                @endif
                                
                                <label class="flex items-start p-3 border border-gray-300 rounded-lg hover:bg-amber-50 cursor-pointer transition">
                                    <input type="checkbox" name="jenis_vaksin[]" value="Lainnya" id="vaksinLainnyaCheckbox"
                                        {{ in_array('Lainnya', old('jenis_vaksin', is_array($screening->vaccineRequest->jenis_vaksin) ? $screening->vaccineRequest->jenis_vaksin : [])) ? 'checked' : '' }}
                                        class="mt-1 w-4 h-4 text-amber-600 rounded focus:ring-amber-500"
                                        onchange="toggleVaksinLainnya()">
                                    <span class="ml-3 text-gray-700">Lainnya</span>
                                </label>
                            </div>
                            
                            <div id="vaksinLainnyaContainer" class="mt-4 {{ in_array('Lainnya', old('jenis_vaksin', is_array($screening->vaccineRequest->jenis_vaksin) ? $screening->vaccineRequest->jenis_vaksin : [])) ? '' : 'hidden' }}">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Sebutkan Jenis Vaksin Lainnya</label>
                                <input type="text" name="vaksin_lainnya_text" id="vaksinLainnyaText" 
                                    value="{{ old('vaksin_lainnya_text', $screening->vaccineRequest->vaksin_lainnya) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500" 
                                    placeholder="Contoh: Vaksin COVID-19, Vaksin Polio, dll">
                            </div>
                        </div>
                        
                        @if($screening->vaccineRequest->is_perjalanan == 1)
                        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-xs font-semibold text-blue-800 uppercase mb-2">Informasi Perjalanan Luar Negeri</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($screening->vaccineRequest->negara_tujuan)
                                <div>
                                    <p class="text-xs text-gray-500 font-medium">Negara Tujuan</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $screening->vaccineRequest->negara_tujuan }}</p>
                                </div>
                                @endif
                                @if($screening->vaccineRequest->tanggal_berangkat)
                                <div>
                                    <p class="text-xs text-gray-500 font-medium">Tanggal Berangkat</p>
                                    <p class="text-base font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($screening->vaccineRequest->tanggal_berangkat)->format('d M Y') }}
                                    </p>
                                </div>
                                @endif
                            </div>
                            <p class="text-xs text-gray-600 mt-2 italic">* Informasi perjalanan tidak dapat diubah</p>
                        </div>
                        @endif
                        
                        <div class="mt-4 flex justify-end gap-2">
                            <button type="button" onclick="toggleEditMode('vaksin')" class="px-6 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg font-semibold">
                                Batal
                            </button>
                            <button type="submit" class="px-6 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-semibold shadow-lg">
                                💾 Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <!-- Dokumen Identitas -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4">
                    <h2 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Dokumen Identitas
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Foto KTP -->
                        @if($screening->pasien->foto_ktp)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Foto KTP</label>
                            <div class="border-2 border-gray-200 rounded-lg overflow-hidden hover:border-indigo-500 transition cursor-pointer" 
                                 onclick="openImageModal('{{ asset('storage/' . $screening->pasien->foto_ktp) }}', 'Foto KTP - {{ $screening->pasien->nama }}')">
                                <img src="{{ asset('storage/' . $screening->pasien->foto_ktp) }}" 
                                     alt="Foto KTP" 
                                     class="w-full h-48 object-cover hover:scale-105 transition">
                                <div class="bg-gray-50 px-3 py-2 text-center">
                                    <span class="text-xs text-gray-600">Klik untuk memperbesar</span>
                                </div>
                            </div>
                        </div>
                        @else
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Foto KTP</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm text-gray-500">Tidak ada foto</p>
                            </div>
                        </div>
                        @endif

                        <!-- Foto Paspor -->
                        @php
                            $showPaspor = $screening->vaccineRequest && ($screening->vaccineRequest->is_perjalanan == 1 || !empty($screening->vaccineRequest->negara_tujuan));
                        @endphp
                        @if($showPaspor)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Passport</label>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-2">Halaman Pertama</label>
                                    @if($screening->pasien->passport_halaman_pertama)
                                    <div class="border-2 border-gray-200 rounded-lg overflow-hidden hover:border-indigo-500 transition cursor-pointer" 
                                         onclick="openImageModal('{{ asset('storage/' . $screening->pasien->passport_halaman_pertama) }}', 'Passport Halaman Pertama - {{ $screening->pasien->nama }}')">
                                        <img src="{{ asset('storage/' . $screening->pasien->passport_halaman_pertama) }}" 
                                             alt="Passport Halaman Pertama" 
                                             class="w-full h-48 object-cover hover:scale-105 transition">
                                        <div class="bg-gray-50 px-3 py-2 text-center">
                                            <span class="text-xs text-gray-600">Klik untuk memperbesar</span>
                                        </div>
                                    </div>
                                    @else
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-sm text-gray-500">Tidak ada foto</p>
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-2">Halaman Kedua</label>
                                    @if($screening->pasien->passport_halaman_kedua)
                                    <div class="border-2 border-gray-200 rounded-lg overflow-hidden hover:border-indigo-500 transition cursor-pointer" 
                                         onclick="openImageModal('{{ asset('storage/' . $screening->pasien->passport_halaman_kedua) }}', 'Passport Halaman Kedua - {{ $screening->pasien->nama }}')">
                                        <img src="{{ asset('storage/' . $screening->pasien->passport_halaman_kedua) }}" 
                                             alt="Passport Halaman Kedua" 
                                             class="w-full h-48 object-cover hover:scale-105 transition">
                                        <div class="bg-gray-50 px-3 py-2 text-center">
                                            <span class="text-xs text-gray-600">Klik untuk memperbesar</span>
                                        </div>
                                    </div>
                                    @else
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-sm text-gray-500">Tidak ada foto</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tanda Tangan Pasien & Keluarga -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                    <h2 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        Tanda Tangan Persetujuan
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Tanda Tangan Pasien -->
                    @if($screening->tanda_tangan_pasien)
                    <div>
                        <div class="bg-purple-50 border-l-4 border-purple-500 p-4 mb-3">
                            <h3 class="text-sm font-bold text-purple-900 mb-1">Tanda Tangan Pasien</h3>
                            <p class="text-xs text-purple-700">
                                Ditandatangani pada: {{ $screening->tanggal_screening ? \Carbon\Carbon::parse($screening->tanggal_screening)->format('d M Y H:i') : '-' }}
                            </p>
                        </div>
                        <div class="border-2 border-purple-200 rounded-lg bg-white p-4 hover:border-purple-400 transition cursor-pointer" 
                             onclick="openImageModal('{{ asset('storage/' . $screening->tanda_tangan_pasien) }}', 'Tanda Tangan Pasien - {{ $screening->pasien->nama }}')">
                            <img src="{{ asset('storage/' . $screening->tanda_tangan_pasien) }}" 
                                 alt="Tanda Tangan Pasien" 
                                 class="w-full max-h-48 object-contain hover:scale-105 transition">
                            <div class="bg-purple-50 px-3 py-2 text-center mt-3 rounded">
                                <span class="text-xs text-purple-700 font-medium">Klik untuk memperbesar</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Tanda Tangan Keluarga (hanya tampil jika ada) -->
                    @if($screening->tanda_tangan_keluarga)
                    <div>
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-3">
                            <h3 class="text-sm font-bold text-blue-900 mb-1">Tanda Tangan Keluarga/Pendamping</h3>
                            <p class="text-xs text-blue-700">
                                Keluarga/Pendamping telah menandatangani form persetujuan
                            </p>
                            @if($screening->pasien->nama_keluarga)
                            <p class="text-sm text-blue-800 font-semibold mt-1">
                                Nama: {{ $screening->pasien->nama_keluarga }}
                            </p>
                            @endif
                        </div>
                        <div class="border-2 border-blue-200 rounded-lg bg-white p-4 hover:border-blue-400 transition cursor-pointer" 
                             onclick="openImageModal('{{ asset('storage/' . $screening->tanda_tangan_keluarga) }}', 'Tanda Tangan Keluarga - {{ $screening->pasien->nama_keluarga ?? $screening->pasien->nama }}')">
                            <img src="{{ asset('storage/' . $screening->tanda_tangan_keluarga) }}" 
                                 alt="Tanda Tangan Keluarga" 
                                 class="w-full max-h-48 object-contain hover:scale-105 transition">
                            <div class="bg-blue-50 px-3 py-2 text-center mt-3 rounded">
                                <span class="text-xs text-blue-700 font-medium">Klik untuk memperbesar</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kolom Kanan -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Hasil Screening Admin
                    </h2>
                    <button type="button" onclick="toggleEditMode('jawaban')" id="btn-edit-jawaban" class="px-4 py-2 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-indigo-50 transition">
                        ✏️ Edit
                    </button>
                </div>
                
                <!-- View Mode -->
                <div id="view-jawaban" class="p-6">
                    @if($screening->answers && $screening->answers->count() > 0)
                        @php
                            $groupedAnswers = $screening->answers->groupBy(function($answer) {
                                return $answer->question->category->nama ?? 'Lainnya';
                            });
                        @endphp
                        
                        @foreach($groupedAnswers as $categoryName => $answers)
                        <div class="mb-6">
                            <h3 class="text-md font-bold text-gray-800 mb-3 pb-2 border-b-2 border-indigo-500">
                                {{ $categoryName }}
                            </h3>
                            <div class="space-y-4">
                                @foreach($answers as $index => $answer)
                                <div class="border-l-4 {{ $answer->jawaban == 'Ya' || $answer->jawaban == 'ya' ? 'border-red-500 bg-red-50' : ($answer->jawaban == 'Tidak Tahu' ? 'border-yellow-500 bg-yellow-50' : 'border-green-500 bg-green-50') }} p-4 rounded-r-lg">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-800 mb-2">
                                                {{ $answer->question->pertanyaan ?? 'Pertanyaan tidak ditemukan' }}
                                            </p>
                                            <div class="flex items-center space-x-2">
                                                @if($answer->jawaban == 'Ya' || $answer->jawaban == 'ya')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                        Ya
                                                    </span>
                                                @elseif($answer->jawaban == 'Tidak Tahu')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                        Tidak Tahu
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Tidak
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @if($answer->keterangan)
                                    <div class="mt-3 pl-4 border-l-2 border-gray-300">
                                        <p class="text-sm text-gray-600"><strong>Keterangan:</strong> {{ $answer->keterangan }}</p>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-gray-500">Belum ada hasil screening</p>
                        </div>
                    @endif
                </div>
                
                <!-- Edit Mode -->
                <div id="edit-jawaban" class="hidden p-6 max-h-[600px] overflow-y-auto">
                    <form method="POST" action="{{ route('dokter.pasien.jawaban.update', $screening->id) }}">
                        @csrf
                        @method('PUT')
                        
                        @if($screening->answers && $screening->answers->count() > 0)
                            @php
                                $groupedAnswers = $screening->answers->groupBy(function($answer) {
                                    return $answer->question->category->nama ?? 'Lainnya';
                                });
                            @endphp
                            
                            @foreach($groupedAnswers as $categoryName => $answers)
                            <div class="mb-6">
                                <h3 class="text-md font-bold text-gray-800 mb-3 pb-2 border-b-2 border-indigo-500">
                                    {{ $categoryName }}
                                </h3>
                                <div class="space-y-4">
                                    @foreach($answers as $answer)
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="font-semibold text-gray-700 mb-3">{{ $answer->question->pertanyaan }}</p>
                                        
                                        @if($answer->question->tipe_jawaban == 'ya_tidak')
                                            <div class="flex gap-4 mb-3">
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="radio" name="jawaban_{{ $answer->question->id }}" value="Ya" 
                                                        {{ $answer->jawaban == 'Ya' ? 'checked' : '' }}
                                                        class="w-4 h-4 text-indigo-600 focus:ring-indigo-500"
                                                        onchange="toggleKeteranganEdit{{ $answer->question->id }}(this.value)">
                                                    <span class="ml-2 text-gray-700">Ya</span>
                                                </label>
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="radio" name="jawaban_{{ $answer->question->id }}" value="Tidak" 
                                                        {{ $answer->jawaban == 'Tidak' ? 'checked' : '' }}
                                                        class="w-4 h-4 text-indigo-600 focus:ring-indigo-500"
                                                        onchange="toggleKeteranganEdit{{ $answer->question->id }}(this.value)">
                                                    <span class="ml-2 text-gray-700">Tidak</span>
                                                </label>
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="radio" name="jawaban_{{ $answer->question->id }}" value="Tidak Tahu" 
                                                        {{ $answer->jawaban == 'Tidak Tahu' ? 'checked' : '' }}
                                                        class="w-4 h-4 text-indigo-600 focus:ring-indigo-500"
                                                        onchange="toggleKeteranganEdit{{ $answer->question->id }}(this.value)">
                                                    <span class="ml-2 text-gray-700">Tidak Tahu</span>
                                                </label>
                                            </div>
                                            
                                            <div id="keterangan_edit_{{ $answer->question->id }}" class="{{ $answer->jawaban == 'Ya' ? '' : 'hidden' }}">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                                                <textarea name="keterangan_{{ $answer->question->id }}" rows="2"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                                    placeholder="Jelaskan lebih detail...">{{ $answer->keterangan }}</textarea>
                                            </div>
                                            
                                            <script>
                                                function toggleKeteranganEdit{{ $answer->question->id }}(value) {
                                                    const container = document.getElementById('keterangan_edit_{{ $answer->question->id }}');
                                                    if (value === 'Ya') {
                                                        container.classList.remove('hidden');
                                                    } else {
                                                        container.classList.add('hidden');
                                                    }
                                                }
                                            </script>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        @endif
                        
                        @if($screening->answers && $screening->answers->count() > 0)
                        <div class="sticky bottom-0 bg-white border-t border-gray-200 pt-4 flex justify-end gap-2">
                            <button type="button" onclick="toggleEditMode('jawaban')" class="px-6 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg font-semibold">
                                Batal
                            </button>
                            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold shadow-lg">
                                💾 Simpan Perubahan
                            </button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Pemeriksaan Fisik Admin -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-red-600 to-orange-600 px-6 py-4">
                    <h2 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        Pemeriksaan Fisik Admin
                    </h2>
                </div>
                <div class="p-6">
                    @if($screening->nilaiScreening && ($screening->nilaiScreening->td || $screening->nilaiScreening->nadi || $screening->nilaiScreening->suhu || $screening->nilaiScreening->bb))
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Tekanan Darah -->
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                <div class="flex items-center mb-1">
                                    <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                    <p class="text-xs font-medium text-red-700">Tekanan Darah</p>
                                </div>
                                <p class="text-lg font-bold text-red-900">
                                    {{ $screening->nilaiScreening->td ?? '-' }}
                                    <span class="text-xs font-normal text-red-600">mmHg</span>
                                </p>
                            </div>

                            <!-- Nadi -->
                            <div class="bg-pink-50 border border-pink-200 rounded-lg p-3">
                                <div class="flex items-center mb-1">
                                    <svg class="w-5 h-5 text-pink-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    <p class="text-xs font-medium text-pink-700">Denyut Nadi</p>
                                </div>
                                <p class="text-lg font-bold text-pink-900">
                                    {{ $screening->nilaiScreening->nadi ?? '-' }}
                                    <span class="text-xs font-normal text-pink-600">bpm</span>
                                </p>
                            </div>

                            <!-- Suhu -->
                            <div class="bg-orange-50 border border-orange-200 rounded-lg p-3">
                                <div class="flex items-center mb-1">
                                    <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    <p class="text-xs font-medium text-orange-700">Suhu Tubuh</p>
                                </div>
                                <p class="text-lg font-bold text-orange-900">
                                    {{ $screening->nilaiScreening->suhu ?? '-' }}
                                    <span class="text-xs font-normal text-orange-600">°C</span>
                                </p>
                            </div>

                            <!-- Berat Badan -->
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                <div class="flex items-center mb-1">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                                    </svg>
                                    <p class="text-xs font-medium text-green-700">Berat Badan</p>
                                </div>
                                <p class="text-lg font-bold text-green-900">
                                    {{ $screening->nilaiScreening->bb ?? '-' }}
                                    <span class="text-xs font-normal text-green-600">kg</span>
                                </p>
                            </div>

                            <!-- Tinggi Badan -->
                            <div class="bg-teal-50 border border-teal-200 rounded-lg p-3">
                                <div class="flex items-center mb-1">
                                    <svg class="w-5 h-5 text-teal-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                    </svg>
                                    <p class="text-xs font-medium text-teal-700">Tinggi Badan</p>
                                </div>
                                <p class="text-lg font-bold text-teal-900">
                                    {{ $screening->nilaiScreening->tb ?? '-' }}
                                    <span class="text-xs font-normal text-teal-600">cm</span>
                                </p>
                            </div>

                            <!-- Alergi Obat -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <div class="flex items-center mb-1">
                                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <p class="text-xs font-medium text-yellow-700">Alergi Obat</p>
                                </div>
                                <p class="text-lg font-bold text-yellow-900">
                                    {{ $screening->nilaiScreening->alergi_obat ?? '-' }}
                                </p>
                            </div>

                            <!-- Alergi Vaksin -->
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-3">
                                <div class="flex items-center mb-1">
                                    <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <p class="text-xs font-medium text-purple-700">Alergi Vaksin</p>
                                </div>
                                <p class="text-lg font-bold text-purple-900">
                                    {{ $screening->nilaiScreening->alergi_vaksin ?? '-' }}
                                </p>
                            </div>

                            <!-- Vaksin COVID -->
                            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-3 col-span-2">
                                <div class="flex items-center mb-1">
                                    <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    <p class="text-xs font-medium text-indigo-700">Vaksin COVID-19</p>
                                </div>
                                <p class="text-base font-semibold text-indigo-900">
                                    Dosis: <span class="text-lg">{{ $screening->nilaiScreening->sudah_vaksin_covid ?? '-' }}</span>
                                    @if($screening->nilaiScreening && $screening->nilaiScreening->nama_vaksin_covid)
                                        <span class="ml-2 text-sm">| {{ $screening->nilaiScreening->nama_vaksin_covid }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if($screening->nilaiScreening && $screening->nilaiScreening->catatan)
                        <div class="mt-4 bg-gray-50 border-l-4 border-gray-400 p-4 rounded-r-lg">
                            <p class="text-xs font-semibold text-gray-700 mb-1">Catatan Pemeriksaan Admin:</p>
                            <p class="text-sm text-gray-600">{{ $screening->nilaiScreening->catatan }}</p>
                        </div>
                        @endif

                        @if($screening->nilaiScreening && $screening->nilaiScreening->admin)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-500">Diperiksa oleh: <span class="font-semibold">{{ $screening->nilaiScreening->admin->nama }}</span></p>
                            <p class="text-xs text-gray-500">Waktu: {{ $screening->nilaiScreening->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-6">
                            <svg class="mx-auto w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            <p class="text-sm text-gray-500">Belum ada data pemeriksaan fisik</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                    <h2 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Kesimpulan
                    </h2>
                </div>
                <div class="p-6">
                    @php
                        $jawabanYa = $screening->answers->whereIn('jawaban', ['Ya', 'ya'])->count();
                        $totalPertanyaan = $screening->answers->count();
                    @endphp
                    
                    @if($jawabanYa > 0)
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <div>
                                    <h3 class="font-bold text-yellow-900 mb-1">Perhatian Khusus</h3>
                                    <p class="text-sm text-yellow-800">
                                        Pasien memiliki <strong>{{ $jawabanYa }}</strong> dari <strong>{{ $totalPertanyaan }}</strong> kondisi yang memerlukan perhatian khusus.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <h3 class="font-bold text-green-900 mb-1">Kondisi Baik</h3>
                                    <p class="text-sm text-green-800">
                                        Tidak ada kondisi khusus yang perlu diperhatikan dari hasil screening.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Form Tanda Tangan & Catatan -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-orange-600 to-red-600 px-6 py-4">
                    <h2 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        Tanda Tangan & Catatan
                    </h2>
                </div>
                <div class="p-6">
                    <form id="formKonfirmasi" method="POST" action="{{ route('dokter.pasien.konfirmasi', $screening->id) }}">
                        @csrf

                        <!-- Tanda Tangan Pasien -->
                        <div class="mb-6 pb-6 border-b-2 border-gray-200">
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <h3 class="text-sm font-bold text-blue-900">Persetujuan Pasien</h3>
                                        <p class="text-xs text-blue-800 mt-1">Dokter harus menandatangani untuk sertifikasi kesehatan pasien</p>
                                    </div>
                                </div>
                            </div>

                        <!-- Signature Pad Dokter -->
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Tanda Tangan Dokter <span class="text-red-500">*</span>
                            </label>
                            <div class="border-2 border-gray-300 rounded-lg overflow-hidden bg-white">
                                <canvas id="signaturePad" width="600" height="200" style="width: 100%; height: 200px; touch-action: none; cursor: crosshair; display: block;"></canvas>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-xs text-gray-500">Silakan tanda tangan di kotak di atas</p>
                                <button type="button" onclick="clearSignature()" class="text-xs text-red-600 hover:text-red-700 font-semibold flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus Tanda Tangan
                                </button>
                            </div>
                            <input type="hidden" name="tanda_tangan" id="tandaTanganInput" required>
                        </div>

                        <!-- Catatan Dokter -->
                        <div class="mb-6">
                            <label for="catatan_dokter" class="block text-sm font-bold text-gray-700 mb-2">
                                Catatan Dokter (Opsional)
                            </label>
                            <textarea 
                                id="catatan_dokter" 
                                name="catatan_dokter" 
                                rows="5" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none"
                                placeholder="Tuliskan catatan, rekomendasi, atau instruksi terkait kondisi pasien (opsional)..."></textarea>
                            <p class="text-xs text-gray-500 mt-1">Catatan ini bersifat opsional</p>
                        </div>

                        <!-- Button Submit -->
                        <button 
                            type="submit" 
                            class="w-full px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold rounded-lg transition shadow-lg flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Konfirmasi & Simpan
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="font-bold text-gray-800 mb-4">Aksi Lainnya</h3>
                <div class="space-y-3">
                    <a href="{{ route('dokter.pasien.index') }}" class="block w-full px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition text-center">
                        Kembali ke Daftar Pasien
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative max-w-4xl max-h-full" onclick="event.stopPropagation()">
        <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img id="modalImage" src="" alt="" class="max-w-full max-h-[80vh] rounded-lg shadow-2xl">
        <p id="modalTitle" class="text-white text-center mt-4 font-semibold"></p>
    </div>
</div>

@push('scripts')
<script>
    // Image Modal Functions
    function openImageModal(imageSrc, title) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });


    // Form submission
    document.getElementById('formKonfirmasi').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate signature dokter
        if (isCanvasBlank()) {
            alert('Silakan buat tanda tangan dokter terlebih dahulu!');
            return false;
        }

        // Save signature dokter as base64
        const signatureData = canvas.toDataURL('image/png');
        document.getElementById('tandaTanganInput').value = signatureData;

        // Show confirmation
        if (confirm('Apakah Anda yakin ingin menyimpan konfirmasi ini?\n\nPastikan:\n✓ Dokter sudah menandatangani\n\nData tidak dapat diubah setelah disimpan.')) {
            this.submit();
        }
    });

    console.log('✅ Signature pad script loaded!');
    
    // ========== SIGNATURE PAD DOKTER ==========
    const canvas = document.getElementById('signaturePad');
    console.log('Canvas element:', canvas);
    
    if (!canvas) {
        console.error('Canvas element not found!');
    }
    
    const ctx = canvas.getContext('2d');
    console.log('Canvas context:', ctx);
    
    let isDrawing = false;

    // Setup canvas context
    ctx.strokeStyle = '#000000';
    ctx.lineWidth = 3;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';
    
    console.log('Canvas setup complete');

    // Get mouse/touch position relative to canvas
    function getPosition(e) {
        const rect = canvas.getBoundingClientRect();
        const scaleX = canvas.width / rect.width;
        const scaleY = canvas.height / rect.height;
        
        if (e.touches && e.touches[0]) {
            return {
                x: (e.touches[0].clientX - rect.left) * scaleX,
                y: (e.touches[0].clientY - rect.top) * scaleY
            };
        }
        
        return {
            x: (e.clientX - rect.left) * scaleX,
            y: (e.clientY - rect.top) * scaleY
        };
    }

    // Start drawing
    function startDrawing(e) {
        console.log('Start drawing at:', getPosition(e));
        isDrawing = true;
        const pos = getPosition(e);
        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
    }

    // Draw
    function draw(e) {
        if (!isDrawing) return;
        e.preventDefault();
        
        const pos = getPosition(e);
        console.log('Drawing to:', pos);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
    }

    // Stop drawing
    function stopDrawing() {
        console.log('Stop drawing');
        isDrawing = false;
        ctx.beginPath();
    }

    // Mouse events
    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);
    
    console.log('Mouse events attached');

    // Touch events
    canvas.addEventListener('touchstart', (e) => {
        e.preventDefault();
        startDrawing(e);
    });
    canvas.addEventListener('touchmove', draw);
    canvas.addEventListener('touchend', (e) => {
        e.preventDefault();
        stopDrawing();
    });

    // Clear signature
    function clearSignature() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        document.getElementById('tandaTanganInput').value = '';
    }

    // Check if canvas is blank
    function isCanvasBlank() {
        const blank = document.createElement('canvas');
        blank.width = canvas.width;
        blank.height = canvas.height;
        return canvas.toDataURL() === blank.toDataURL();
    }

    // Toggle Edit Mode Functions
    function toggleEditMode(section) {
        const viewSection = document.getElementById('view-' + section);
        const editSection = document.getElementById('edit-' + section);
        const btnEdit = document.getElementById('btn-edit-' + section);
        
        if (editSection.classList.contains('hidden')) {
            // Show edit, hide view
            viewSection.classList.add('hidden');
            editSection.classList.remove('hidden');
            if (btnEdit) btnEdit.classList.add('hidden');
        } else {
            // Show view, hide edit
            viewSection.classList.remove('hidden');
            editSection.classList.add('hidden');
            if (btnEdit) btnEdit.classList.remove('hidden');
        }
    }

    function toggleVaksinLainnya() {
        const checkbox = document.getElementById('vaksinLainnyaCheckbox');
        const container = document.getElementById('vaksinLainnyaContainer');
        
        if (checkbox && container) {
            if (checkbox.checked) {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
                const textInput = document.getElementById('vaksinLainnyaText');
                if (textInput) textInput.value = '';
            }
        }
    }
</script>
@endpush

@endsection
