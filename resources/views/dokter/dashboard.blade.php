@extends('layouts.app')

@section('title', 'Dashboard Dokter - Form Vaksin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient from-blue-600 to-indigo-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <span class="ml-3 text-xl font-bold text-white">Dashboard Dokter</span>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm font-medium text-white">Dr. {{ Auth::user()->nama }}</p>
                        <p class="text-xs text-blue-200 font-semibold">Dokter</p>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-white hover:bg-gray-100 text-blue-600 text-sm font-medium rounded-lg transition duration-200">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Selamat Datang, Dr. {{ Auth::user()->nama }}</h1>
            <p class="text-gray-600 mt-2">Jadwal Vaksinasi Pasien yang Ditugaskan Kepada Anda</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 sm:gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs sm:text-sm font-medium">Total Pasien</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalPasien }}</p>
                        <p class="text-xs text-blue-600 font-medium mt-1">Semua Pasien</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <a href="{{ route('dokter.pasien-hari-ini') }}" class="block bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500 hover:shadow-xl hover:scale-105 transition-all cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs sm:text-sm font-medium">Hari Ini</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $pasienHariIni }}</p>
                        <p class="text-xs text-purple-600 font-medium mt-1">{{ \Carbon\Carbon::now()->format('d M Y') }}</p>
                        @if($pasienHariIni > 0)
                        <p class="text-xs text-purple-700 font-bold mt-2">👉 Klik untuk lihat detail</p>
                        @endif
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </a>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-500 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs sm:text-sm font-medium">Minggu Ini</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $pasienMingguIni }}</p>
                        <p class="text-xs text-indigo-600 font-medium mt-1">7 Hari</p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs sm:text-sm font-medium">Belum Divaksin</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $belumDivaksin }}</p>
                        <p class="text-xs text-yellow-600 font-medium mt-1">Menunggu</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs sm:text-sm font-medium">Proses</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $prosesDivaksin }}</p>
                        <p class="text-xs text-orange-600 font-medium mt-1">Sedang Proses</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <a href="{{ route('dokter.pasien-selesai') }}" class="block bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:shadow-xl hover:scale-105 transition-all cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs sm:text-sm font-medium">Selesai</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $sudahDivaksin }}</p>
                        <p class="text-xs text-green-600 font-medium mt-1">Sudah Divaksin</p>
                        @if($sudahDivaksin > 0)
                        <p class="text-xs text-green-700 font-bold mt-2">👉 Klik untuk lihat detail</p>
                        @endif
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </a>
        </div>

        <!-- Jadwal Vaksinasi per Tanggal -->
        @if($jadwalPerTanggal->count() > 0)
            @foreach($jadwalPerTanggal as $tanggal => $pasienPerTanggal)
            @php
                $carbonDate = \Carbon\Carbon::parse($tanggal);
                $isToday = $carbonDate->isToday();
                $isPast = $carbonDate->isPast() && !$isToday;
                $isFuture = $carbonDate->isFuture();
            @endphp
            
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6 
                @if($isToday) ring-4 ring-blue-500 @endif">
                <!-- Header dengan Tanggal -->
                <div class="px-6 py-4 
                    @if($isToday) bg-gradient-to-r from-blue-600 to-indigo-600
                    @elseif($isPast) bg-gradient-to-r from-gray-500 to-gray-600
                    @else bg-gradient-to-r from-green-600 to-teal-600
                    @endif">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 
                                    @if($isToday) text-blue-600
                                    @elseif($isPast) text-gray-600
                                    @else text-green-600
                                    @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">
                                    {{ $carbonDate->isoFormat('dddd, D MMMM YYYY') }}
                                </h2>
                                <p class="text-sm text-white opacity-90">
                                    @if($isToday)
                                        <span class="font-bold">⭐ HARI INI</span>
                                    @elseif($isPast)
                                        <span>Sudah Lewat</span>
                                    @else
                                        <span>{{ $carbonDate->diffForHumans() }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg px-4 py-2">
                                <p class="text-2xl font-bold text-white">{{ $pasienPerTanggal->count() }}</p>
                                <p class="text-xs text-white opacity-90">Pasien</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Pasien -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b-2 border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Pasien</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No. Paspor</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No. Telp</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Negara Tujuan</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Hasil Screening</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($pasienPerTanggal as $index => $screening)
                            <tr class="hover:bg-gray-50 transition 
                                @if($isToday && $screening->status_vaksinasi === 'belum_divaksin') bg-blue-50 @endif">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">{{ $screening->pasien->nama }}</div>
                                    <div class="text-xs text-gray-500">{{ $screening->pasien->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}, {{ \Carbon\Carbon::parse($screening->pasien->tanggal_lahir)->age }} tahun</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ $screening->pasien->nomor_paspor ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $screening->pasien->no_telp ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if($screening->vaccineRequest)
                                        <span class="font-semibold text-indigo-600">{{ $screening->vaccineRequest->negara_tujuan }}</span>
                                        @if($screening->vaccineRequest->jenis_vaksin)
                                        <div class="text-xs text-gray-500 mt-1">{{ $screening->vaccineRequest->jenis_vaksin }}</div>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($screening->hasil_screening === 'aman')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold inline-flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        AMAN
                                    </span>
                                    @elseif($screening->hasil_screening === 'perlu_perhatian')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold inline-flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        PERLU PERHATIAN
                                    </span>
                                    @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">PENDING</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($screening->status_vaksinasi === 'sudah_divaksin')
                                    <span class="px-3 py-1 bg-green-600 text-white rounded-full text-xs font-bold inline-flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        SELESAI
                                    </span>
                                    @elseif($screening->status_vaksinasi === 'proses_vaksinasi')
                                    <span class="px-3 py-1 bg-orange-500 text-white rounded-full text-xs font-bold inline-flex items-center">
                                        <svg class="w-3 h-3 mr-1 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        PROSES
                                    </span>
                                    @else
                                    <span class="px-3 py-1 bg-yellow-500 text-white rounded-full text-xs font-bold inline-flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        MENUNGGU
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('dokter.pasien.show', $screening->id) }}" 
                                       class="inline-flex items-center px-4 py-2 
                                       @if($screening->status_vaksinasi === 'belum_divaksin')
                                           bg-blue-600 hover:bg-blue-700
                                       @elseif($screening->status_vaksinasi === 'proses_vaksinasi')
                                           bg-orange-600 hover:bg-orange-700
                                       @else
                                           bg-gray-600 hover:bg-gray-700
                                       @endif
                                       text-white rounded-lg text-sm font-medium transition shadow-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        @if($screening->status_vaksinasi === 'sudah_divaksin')
                                            Lihat Detail
                                        @else
                                            Proses Vaksinasi
                                        @endif
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        @else
        <!-- Tidak Ada Jadwal -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-12 text-center">
                <svg class="w-24 h-24 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-2xl font-bold text-gray-600 mb-2">Belum Ada Jadwal Vaksinasi</h3>
                <p class="text-gray-500">Belum ada pasien yang ditugaskan kepada Anda oleh admin</p>
            </div>
        </div>
        @endif

        <!-- Info Box -->
        <div class="mt-6 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-start">
                <svg class="w-6 h-6 mr-3 shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="font-bold text-lg mb-1">Informasi Penting</h3>
                    <ul class="text-sm opacity-90 space-y-1">
                        <li>• Jadwal dengan highlight <span class="font-bold">BIRU</span> adalah jadwal hari ini</li>
                        <li>• Pastikan memeriksa hasil screening sebelum vaksinasi</li>
                        <li>• Pasien dengan status "PERLU PERHATIAN" memerlukan konsultasi khusus</li>
                        <li>• Klik "Proses Vaksinasi" untuk mengisi form penilaian dan mencetak PDF</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
