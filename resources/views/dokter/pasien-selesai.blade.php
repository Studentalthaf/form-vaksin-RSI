@extends('layouts.app')

@section('title', 'Pasien Selesai - Dashboard Dokter')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-green-600 to-teal-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('dokter.dashboard') }}" class="flex items-center text-white hover:text-green-100 transition">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="text-sm font-medium">Kembali ke Dashboard</span>
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm font-medium text-white">Dr. {{ Auth::user()->nama }}</p>
                        <p class="text-xs text-green-200 font-semibold">Dokter</p>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-white hover:bg-gray-100 text-green-600 text-sm font-medium rounded-lg transition duration-200">
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
            <div class="flex items-center mb-4">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-teal-500 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Pasien Selesai Divaksin</h1>
                    <p class="text-gray-600 mt-1">Riwayat pasien yang sudah menyelesaikan vaksinasi</p>
                </div>
            </div>
            
            @if($screenings->count() > 0)
            <div class="bg-gradient-to-r from-green-100 to-teal-100 border-l-4 border-green-500 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-semibold text-green-900">Total <span class="text-2xl">{{ $screenings->count() }}</span> pasien yang sudah selesai divaksin</p>
                        <p class="text-sm text-green-700 mt-1">Semua pasien dengan status "Sudah Divaksin"</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        @if($screenings->count() > 0)
        <!-- Tabel Pasien -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Daftar Pasien Selesai ({{ $screenings->count() }} Pasien)
                </h2>
            </div>

            <!-- Desktop Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Pasien</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No. Paspor</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Negara Tujuan</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tanggal Vaksinasi</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Hasil Screening</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($screenings as $index => $screening)
                        <tr class="hover:bg-green-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $screening->pasien->nama }}</div>
                                        <div class="text-xs text-gray-500">{{ $screening->pasien->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}, {{ \Carbon\Carbon::parse($screening->pasien->tanggal_lahir)->age }} tahun</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ $screening->pasien->nomor_paspor ?? '-' }}</td>
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
                            <td class="px-6 py-4 text-sm">
                                <div class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($screening->tanggal_vaksinasi)->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($screening->updated_at)->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($screening->hasil_screening === 'aman')
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold inline-flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    AMAN
                                </span>
                                @else
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold inline-flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    PERLU PERHATIAN
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('dokter.pasien.show', $screening->id) }}" 
                                       class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-medium transition shadow-sm"
                                       title="Lihat Detail">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Detail
                                    </a>
                                    <a href="{{ route('dokter.pasien.cetak-pdf', $screening->id) }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-medium transition shadow-sm"
                                       title="Cetak PDF">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                        </svg>
                                        PDF
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <!-- Tidak Ada Pasien -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-600 mb-2">Belum Ada Pasien Selesai</h3>
                <p class="text-gray-500 mb-6">Belum ada pasien yang menyelesaikan vaksinasi</p>
                <a href="{{ route('dokter.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
        @endif

        <!-- Info Box -->
        <div class="mt-6 bg-gradient-to-r from-green-500 to-teal-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-start">
                <svg class="w-6 h-6 mr-3 shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="font-bold text-lg mb-1">Informasi</h3>
                    <ul class="text-sm opacity-90 space-y-1">
                        <li>• Klik "Detail" untuk melihat informasi lengkap pasien dan hasil penilaian</li>
                        <li>• Klik "PDF" untuk mencetak surat persetujuan vaksinasi</li>
                        <li>• Data diurutkan dari yang terbaru selesai divaksin</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
