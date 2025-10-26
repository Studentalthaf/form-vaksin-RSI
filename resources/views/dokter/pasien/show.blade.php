@extends('layouts.app')
@section('title', 'Detail Pasien - ' . $screening->pasien->nama)
@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-blue-600 to-indigo-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <span class="text-xl font-bold text-white">Detail Pasien & Penilaian</span>
            <div class="flex items-center space-x-4">
                <span class="text-white text-sm">Dr. {{ Auth::user()->nama }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-white text-blue-600 hover:bg-gray-100 rounded-lg font-semibold text-sm transition">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('dokter.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 p-4 rounded">
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 bg-red-100 border-l-4 border-red-500 p-4 rounded">
            <p class="text-red-700 font-medium">{{ $errors->first() }}</p>
        </div>
        @endif

        <!-- Header Pasien -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg p-6 mb-6 text-white">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold">{{ $screening->pasien->nama }}</h1>
                    <div class="mt-2 space-y-1">
                        <p class="text-blue-100">NIK: <span class="font-semibold">{{ $screening->pasien->nik }}</span></p>
                        <p class="text-blue-100">No. Telp: <span class="font-semibold">{{ $screening->pasien->no_telp }}</span></p>
                        <p class="text-blue-100">Tanggal Vaksinasi: <span class="font-bold text-yellow-300">{{ \Carbon\Carbon::parse($screening->tanggal_vaksinasi)->format('d F Y') }}</span></p>
                        
                        @if($screening->vaccineRequest)
                        <div class="mt-3 pt-3 border-t border-blue-400">
                            <div class="flex items-center mb-2">
                                @if($screening->vaccineRequest->is_perjalanan)
                                <span class="px-3 py-1 bg-yellow-400 text-yellow-900 rounded-full text-xs font-bold mr-2">
                                    ✈️ VAKSIN PERJALANAN
                                </span>
                                @else
                                <span class="px-3 py-1 bg-green-400 text-green-900 rounded-full text-xs font-bold mr-2">
                                    💉 VAKSIN BIASA
                                </span>
                                @endif
                                <p class="text-blue-100 font-semibold">Data Permohonan Vaksin</p>
                            </div>
                            
                            @if($screening->vaccineRequest->is_perjalanan)
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div>
                                    <span class="text-blue-200">Negara Tujuan:</span>
                                    <span class="font-bold text-white ml-1">{{ $screening->vaccineRequest->negara_tujuan ?? '-' }}</span>
                                </div>
                                @if($screening->vaccineRequest->jenis_vaksin)
                                <div>
                                    <span class="text-blue-200">Jenis Vaksin:</span>
                                    <span class="font-bold text-yellow-300 ml-1">{{ $screening->vaccineRequest->jenis_vaksin }}</span>
                                </div>
                                @endif
                                @if($screening->vaccineRequest->tanggal_berangkat)
                                <div>
                                    <span class="text-blue-200">Tanggal Berangkat:</span>
                                    <span class="font-bold text-white ml-1">{{ \Carbon\Carbon::parse($screening->vaccineRequest->tanggal_berangkat)->format('d M Y') }}</span>
                                </div>
                                @endif
                            </div>
                            @else
                            <div class="text-sm">
                                @if($screening->vaccineRequest->jenis_vaksin)
                                <div>
                                    <span class="text-blue-200">Jenis Vaksin:</span>
                                    <span class="font-bold text-yellow-300 ml-1">{{ $screening->vaccineRequest->jenis_vaksin }}</span>
                                </div>
                                @else
                                <p class="text-blue-200 italic">Vaksinasi umum (non-perjalanan)</p>
                                @endif
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    @if($screening->status_vaksinasi === 'sudah_divaksin')
                    <span class="px-4 py-2 bg-green-500 text-white rounded-full text-sm font-bold">SUDAH DIVAKSIN</span>
                    @else
                    <span class="px-4 py-2 bg-orange-500 text-white rounded-full text-sm font-bold">PROSES VAKSINASI</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Left Column: Screening Results -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Hasil Screening -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between border-b pb-4 mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Hasil Screening</h2>
                        @if($screening->hasil_screening === 'aman')
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">AMAN</span>
                        @else
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold">PERLU PERHATIAN</span>
                        @endif
                    </div>

                    <div class="text-sm space-y-2 mb-4">
                        <p><span class="text-gray-600">Tanggal Screening:</span> <span class="font-semibold">{{ $screening->tanggal_screening->format('d/m/Y H:i') }}</span></p>
                        <p><span class="text-gray-600">Petugas:</span> <span class="font-semibold">{{ $screening->petugas->nama }}</span></p>
                    </div>

                    <!-- Screening Answers by Category -->
                    @php
                        $groupedAnswers = $screening->answers->groupBy(fn($answer) => $answer->question->category_id);
                    @endphp

                    @foreach($groupedAnswers as $categoryId => $answers)
                        @php
                            $category = $answers->first()->question->category;
                        @endphp
                        <div class="mb-6">
                            <h3 class="font-bold text-gray-800 mb-3 border-b pb-2">{{ $category->nama_kategori ?? 'Tanpa Kategori' }}</h3>
                            <div class="space-y-3">
                                @foreach($answers as $answer)
                                <div class="border-l-4 {{ strtolower($answer->jawaban) === 'ya' ? 'border-red-500 bg-red-50' : 'border-green-500 bg-green-50' }} p-3 rounded">
                                    <p class="text-sm font-semibold text-gray-800 mb-1">{{ $answer->question->pertanyaan }}</p>
                                    <div class="flex items-center space-x-2">
                                        @if(strtolower($answer->jawaban) === 'ya')
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">YA</span>
                                        @elseif(strtolower($answer->jawaban) === 'tidak')
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">TIDAK</span>
                                        @else
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">{{ $answer->jawaban }}</span>
                                        @endif
                                    </div>
                                    @if($answer->keterangan)
                                    <p class="text-xs text-gray-600 mt-1 italic">"{{ $answer->keterangan }}"</p>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    @if($screening->catatan)
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mt-4">
                        <p class="text-sm font-semibold text-blue-900 mb-1">Catatan Umum:</p>
                        <p class="text-sm text-blue-800">{{ $screening->catatan }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Form Penilaian Dokter -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden sticky top-6">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-4">
                        <h2 class="text-lg font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Form Penilaian Dokter
                        </h2>
                        <p class="text-blue-100 text-xs mt-1">Lengkapi data penilaian medis pasien</p>
                    </div>

                    <form method="POST" action="{{ route('dokter.pasien.store-penilaian', $screening) }}" class="p-6 space-y-5">
                        @csrf

                        <!-- SECTION: Data Alergi -->
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <h3 class="font-bold text-red-900 mb-3 flex items-center text-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Data Alergi Pasien
                            </h3>
                            
                            <!-- Alergi Obat -->
                            <div class="mb-3">
                                <label class="block text-sm font-semibold text-gray-800 mb-2">
                                    Alergi Obat: <span class="text-red-600">*</span>
                                </label>
                                <div class="flex space-x-4">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="alergi_obat" value="Ada" 
                                            {{ old('alergi_obat', $screening->penilaian->alergi_obat ?? '') === 'Ada' ? 'checked' : '' }}
                                            class="form-radio text-red-600 h-4 w-4">
                                        <span class="ml-2 text-sm font-medium">Ada</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="alergi_obat" value="Tidak" 
                                            {{ old('alergi_obat', $screening->penilaian->alergi_obat ?? '') === 'Tidak' ? 'checked' : '' }}
                                            class="form-radio text-green-600 h-4 w-4">
                                        <span class="ml-2 text-sm font-medium">Tidak</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Alergi Vasin -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2">
                                    Alergi Vaksin: <span class="text-red-600">*</span>
                                </label>
                                <div class="flex space-x-4">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="alergi_vasin" value="Ada" 
                                            {{ old('alergi_vasin', $screening->penilaian->alergi_vasin ?? '') === 'Ada' ? 'checked' : '' }}
                                            class="form-radio text-red-600 h-4 w-4">
                                        <span class="ml-2 text-sm font-medium">Ada</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="alergi_vasin" value="Tidak" 
                                            {{ old('alergi_vasin', $screening->penilaian->alergi_vasin ?? '') === 'Tidak' ? 'checked' : '' }}
                                            class="form-radio text-green-600 h-4 w-4">
                                        <span class="ml-2 text-sm font-medium">Tidak</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION: Riwayat Vaksinasi COVID-19 -->
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <h3 class="font-bold text-purple-900 mb-3 flex items-center text-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                Riwayat Vaksinasi COVID-19
                            </h3>
                            
                            <!-- Sudah Vaksin COVID-19 -->
                            <div class="mb-3">
                                <label class="block text-sm font-semibold text-gray-800 mb-2">
                                    Sudah vaksin covid 19 ke:
                                </label>
                                <div class="flex flex-col space-y-2">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="sudah_vaksin_covid" value="1" 
                                            {{ old('sudah_vaksin_covid', $screening->penilaian->sudah_vaksin_covid ?? '') === '1' ? 'checked' : '' }}
                                            class="form-radio text-purple-600 h-4 w-4">
                                        <span class="ml-2 text-sm font-medium">1 (Dosis Pertama)</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="sudah_vaksin_covid" value="2" 
                                            {{ old('sudah_vaksin_covid', $screening->penilaian->sudah_vaksin_covid ?? '') === '2' ? 'checked' : '' }}
                                            class="form-radio text-purple-600 h-4 w-4">
                                        <span class="ml-2 text-sm font-medium">2 (Dosis Lengkap)</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="sudah_vaksin_covid" value="booster" 
                                            {{ old('sudah_vaksin_covid', $screening->penilaian->sudah_vaksin_covid ?? '') === 'booster' ? 'checked' : '' }}
                                            class="form-radio text-purple-600 h-4 w-4">
                                        <span class="ml-2 text-sm font-medium">Booster (Penguat)</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Nama Vaksin COVID-19 -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2">
                                    Nama vaksin covid 19:
                                </label>
                                <input type="text" name="nama_vaksin_covid" 
                                    value="{{ old('nama_vaksin_covid', $screening->penilaian->nama_vaksin_covid ?? '') }}"
                                    class="w-full px-3 py-2 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm"
                                    placeholder="Contoh: Sinovac, Moderna, Pfizer">
                            </div>

                            <!-- Dimana & Kapan (Grid 2 kolom) -->
                            <div class="grid grid-cols-2 gap-3 mt-3">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">
                                        Dimana:
                                    </label>
                                    <input type="text" name="dimana" 
                                        value="{{ old('dimana', $screening->penilaian->dimana ?? '') }}"
                                        class="w-full px-3 py-2 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm"
                                        placeholder="Tempat">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">
                                        Kapan:
                                    </label>
                                    <input type="text" name="kapan" 
                                        value="{{ old('kapan', $screening->penilaian->kapan ?? '') }}"
                                        class="w-full px-3 py-2 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm"
                                        placeholder="Waktu">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION: Data Permohonan Vaksin -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h3 class="font-bold text-blue-900 mb-3 flex items-center text-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Data Permohonan Vaksin
                                <span class="ml-auto text-xs font-normal text-blue-600">(Otomatis dari sistem)</span>
                            </h3>
                            
                            <!-- Jenis Vaksin (Read-only) -->
                            <div class="mb-3">
                                <label class="block text-sm font-semibold text-gray-800 mb-2">
                                    Jenis Vaksin yang Dimohonkan:
                                </label>
                                <div class="relative">
                                    <input type="text" name="jenis_vaksin" 
                                        value="{{ old('jenis_vaksin', $screening->penilaian->jenis_vaksin ?? $screening->vaccineRequest->jenis_vaksin ?? '') }}"
                                        readonly
                                        class="w-full px-3 py-2 pr-10 border-2 border-blue-300 bg-blue-50 rounded-lg text-sm font-semibold text-blue-900 cursor-not-allowed"
                                        placeholder="Tidak ada data jenis vaksin">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Negara Tujuan (Conditional) -->
                            @if($screening->vaccineRequest && $screening->vaccineRequest->is_perjalanan)
                            <div class="mb-3">
                                <label class="block text-sm font-semibold text-gray-800 mb-2">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Negara Tujuan (Vaksin Perjalanan):
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="negara_tujuan" 
                                        value="{{ old('negara_tujuan', $screening->penilaian->negara_tujuan ?? $screening->vaccineRequest->negara_tujuan ?? '') }}"
                                        readonly
                                        class="w-full px-3 py-2 pr-10 border-2 border-blue-300 bg-blue-50 rounded-lg text-sm font-semibold text-blue-900 cursor-not-allowed"
                                        placeholder="Negara tujuan perjalanan">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            @else
                            <!-- Jika BUKAN Perjalanan - Field Disabled -->
                            <div class="mb-3 bg-gray-100 border border-gray-200 rounded-lg p-3">
                                <label class="block text-sm font-semibold text-gray-500 mb-2">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Negara Tujuan:
                                    </span>
                                </label>
                                <input type="hidden" name="negara_tujuan" value="">
                                <div class="relative">
                                    <input type="text" 
                                        value="Tidak ada (Vaksin Umum)"
                                        disabled
                                        class="w-full px-3 py-2 pr-10 border border-gray-300 bg-gray-100 rounded-lg text-sm text-gray-500 cursor-not-allowed italic">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    ℹ️ Field ini hanya diisi untuk vaksinasi perjalanan luar negeri
                                </p>
                            </div>
                            @endif

                            <!-- Tanggal Berangkat (Conditional) -->
                            @if($screening->vaccineRequest && $screening->vaccineRequest->is_perjalanan)
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Tanggal Berangkat (Dari Permohonan):
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="date" name="tanggal_berangkat_umroh" 
                                        value="{{ old('tanggal_berangkat_umroh', $screening->penilaian->tanggal_berangkat_umroh ?? $screening->vaccineRequest->tanggal_berangkat ?? '') }}"
                                        readonly
                                        class="w-full px-3 py-2 pr-10 border-2 border-blue-300 bg-blue-50 rounded-lg text-sm font-semibold text-blue-900 cursor-not-allowed">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-xs text-blue-600 mt-1">
                                    🔒 Data ini otomatis diambil dari permohonan pasien dan tidak dapat diubah
                                </p>
                            </div>
                            @else
                            <div class="bg-gray-100 border border-gray-200 rounded-lg p-3">
                                <label class="block text-sm font-semibold text-gray-500 mb-2">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Tanggal Berangkat:
                                    </span>
                                </label>
                                <input type="hidden" name="tanggal_berangkat_umroh" value="">
                                <input type="text" 
                                    value="Tidak diperlukan (Vaksin Umum)"
                                    disabled
                                    class="w-full px-3 py-2 border border-gray-300 bg-gray-100 rounded-lg text-sm text-gray-500 cursor-not-allowed italic">
                                <p class="text-xs text-gray-500 mt-1">
                                    ℹ️ Field ini hanya diisi untuk vaksinasi perjalanan luar negeri
                                </p>
                            </div>
                            @endif
                        </div>

                        <!-- SECTION: Tanda Vital -->
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <h3 class="font-bold text-green-900 mb-3 flex items-center text-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Tanda Vital & Pemeriksaan Fisik
                            </h3>
                            
                            <!-- TD & Nadi -->
                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-800 mb-1">
                                        <span class="flex items-center">
                                            <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                            Tekanan Darah (TD):
                                        </span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="td" 
                                            value="{{ old('td', $screening->penilaian->td ?? '') }}"
                                            class="w-full px-3 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm"
                                            placeholder="120/80">
                                        <span class="absolute right-3 top-2 text-xs text-gray-500">mmHg</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-800 mb-1">
                                        <span class="flex items-center">
                                            <span class="w-2 h-2 bg-pink-500 rounded-full mr-2"></span>
                                            Nadi:
                                        </span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="nadi" 
                                            value="{{ old('nadi', $screening->penilaian->nadi ?? '') }}"
                                            class="w-full px-3 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm"
                                            placeholder="80">
                                        <span class="absolute right-3 top-2 text-xs text-gray-500">x/mnt</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Suhu & TB -->
                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-800 mb-1">
                                        <span class="flex items-center">
                                            <span class="w-2 h-2 bg-orange-500 rounded-full mr-2"></span>
                                            Suhu Tubuh:
                                        </span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="suhu" 
                                            value="{{ old('suhu', $screening->penilaian->suhu ?? '') }}"
                                            class="w-full px-3 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm"
                                            placeholder="36.5">
                                        <span class="absolute right-3 top-2 text-xs text-gray-500">°C</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-800 mb-1">
                                        <span class="flex items-center">
                                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                            Tinggi Badan (TB):
                                        </span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="tb" 
                                            value="{{ old('tb', $screening->penilaian->tb ?? '') }}"
                                            class="w-full px-3 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm"
                                            placeholder="170">
                                        <span class="absolute right-3 top-2 text-xs text-gray-500">cm</span>
                                    </div>
                                </div>
                            </div>

                            <!-- BB -->
                            <div>
                                <label class="block text-sm font-medium text-gray-800 mb-1">
                                    <span class="flex items-center">
                                        <span class="w-2 h-2 bg-purple-500 rounded-full mr-2"></span>
                                        Berat Badan (BB):
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="bb" 
                                        value="{{ old('bb', $screening->penilaian->bb ?? '') }}"
                                        class="w-full px-3 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm"
                                        placeholder="65">
                                    <span class="absolute right-3 top-2 text-xs text-gray-500">kg</span>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION: Catatan -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h3 class="font-bold text-gray-900 mb-3 flex items-center text-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Catatan Tambahan Dokter
                            </h3>
                            <textarea name="catatan" rows="4" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 text-sm"
                                placeholder="Tulis catatan tambahan, rekomendasi, atau observasi khusus untuk pasien ini...">{{ old('catatan', $screening->penilaian->catatan ?? '') }}</textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg font-bold transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Penilaian Dokter
                            </button>
                        </div>
                    </form>

                    <!-- PDF Button (jika sudah ada penilaian) -->
                    @if($screening->penilaian)
                    <div class="px-6 pb-6">
                        <a href="{{ route('dokter.pasien.cetak-pdf', $screening) }}" 
                           target="_blank"
                           class="block w-full px-6 py-3 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white rounded-lg font-bold text-center transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            📄 Cetak PDF Lengkap
                        </a>
                        <p class="text-xs text-center text-gray-500 mt-2">
                            📋 Termasuk: Penilaian, Screening & Surat Persetujuan
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
