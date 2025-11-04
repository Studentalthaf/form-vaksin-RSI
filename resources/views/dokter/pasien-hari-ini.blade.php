@extends('layouts.app')

@section('title', 'Pasien Hari Ini - Dashboard Dokter')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-purple-600 to-pink-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('dokter.dashboard') }}" class="flex items-center text-white hover:text-purple-100 transition">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="text-sm font-medium">Kembali ke Dashboard</span>
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm font-medium text-white">Dr. {{ Auth::user()->nama }}</p>
                        <p class="text-xs text-purple-200 font-semibold">Dokter</p>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-white hover:bg-gray-100 text-purple-600 text-sm font-medium rounded-lg transition duration-200">
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
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Pasien Hari Ini</h1>
                    <p class="text-gray-600 mt-1">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</p>
                </div>
            </div>
            
            @if($screenings->count() > 0)
            <div class="bg-gradient-to-r from-purple-100 to-pink-100 border-l-4 border-purple-500 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-semibold text-purple-900">Ada <span class="text-2xl">{{ $screenings->count() }}</span> pasien yang perlu divaksin hari ini</p>
                        <p class="text-sm text-purple-700 mt-1">Semua pasien dengan status "Belum Divaksin"</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        @if($screenings->count() > 0)
        <!-- Tabel Pasien -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Daftar Pasien Hari Ini ({{ $screenings->count() }} Pasien)
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
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No. Telp</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Negara Tujuan</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Hasil Screening</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($screenings as $index => $screening)
                        <tr class="hover:bg-purple-50 transition bg-gradient-to-r from-purple-50/30 to-transparent">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ ($screenings->currentPage() - 1) * $screenings->perPage() + $index + 1 }}</td>
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
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('dokter.pasien.show', $screening->id) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition shadow-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Proses Vaksinasi
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <!-- Pagination -->
                @if($screenings->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $screenings->links() }}
                </div>
                @endif
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
                <h3 class="text-2xl font-bold text-gray-600 mb-2">Tidak Ada Pasien Hari Ini</h3>
                <p class="text-gray-500 mb-6">Semua pasien untuk hari ini sudah divaksin atau tidak ada jadwal</p>
                <a href="{{ route('dokter.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
        @endif

        <!-- Info Box -->
        <div class="mt-6 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-start">
                <svg class="w-6 h-6 mr-3 shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="font-bold text-lg mb-1">Prioritas Hari Ini</h3>
                    <ul class="text-sm opacity-90 space-y-1">
                        <li>• Pasien dengan status "PERLU PERHATIAN" memerlukan konsultasi khusus</li>
                        <li>• Pastikan memeriksa hasil screening sebelum vaksinasi</li>
                        <li>• Klik "Proses Vaksinasi" untuk mengisi form penilaian dan mencetak PDF</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
