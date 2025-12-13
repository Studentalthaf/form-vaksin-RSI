@extends('layouts.dokter')

@section('page-title', 'Dashboard Dokter')
@section('page-subtitle', 'Selamat datang di panel dokter')

@section('content')
<div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl shadow-xl p-8 mb-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">Selamat Datang, Dr. {{ Auth::user()->nama }}! </h1>
            <p class="text-green-100">Panel monitoring dan manajemen pasien vaksinasi</p>
        </div>
        <div class="hidden md:block">
            <svg class="w-32 h-32 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-md hover:shadow-2xl p-6 border-l-4 border-blue-500 transform hover:-translate-y-1 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Total Pasien</p>
                <p class="text-4xl font-extrabold text-gray-900 mt-2">{{ $totalPasien }}</p>
                <p class="text-xs text-blue-600 mt-2 font-medium">Pasien Anda</p>
            </div>
            <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md hover:shadow-2xl p-6 border-l-4 border-purple-500 transform hover:-translate-y-1 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Hari Ini</p>
                <p class="text-4xl font-extrabold text-gray-900 mt-2">{{ $pasienHariIni }}</p>
                <p class="text-xs text-purple-600 mt-2 font-medium">Jadwal Vaksinasi</p>
            </div>
            <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md hover:shadow-2xl p-6 border-l-4 border-yellow-500 transform hover:-translate-y-1 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Belum Divaksin</p>
                <p class="text-4xl font-extrabold text-gray-900 mt-2">{{ $belumDivaksin }}</p>
                <p class="text-xs text-yellow-600 mt-2 font-medium">Pasien Baru / Belum Dikonfirmasi</p>
            </div>
            <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md hover:shadow-2xl p-6 border-l-4 border-green-500 transform hover:-translate-y-1 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Sudah Divaksin</p>
                <p class="text-4xl font-extrabold text-gray-900 mt-2">{{ $sudahDivaksin }}</p>
                <p class="text-xs text-green-600 mt-2 font-medium">Sudah Ditandatangani & Dikonfirmasi</p>
            </div>
            <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>


<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Informasi Akun
        </h2>
        <div class="space-y-4">
            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Nama Dokter</p>
                    <p class="font-semibold text-gray-900">{{ Auth::user()->nama }}</p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-semibold text-gray-900">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Bantuan & Kontak
        </h2>
        <div class="space-y-4">
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-4 rounded-lg border border-indigo-200">
                <h3 class="font-semibold text-indigo-900 mb-2">Butuh Bantuan?</h3>
                <p class="text-sm text-indigo-700 mb-3">Hubungi administrator untuk informasi lebih lanjut tentang sistem.</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600">
                    <strong>Jam Operasional:</strong><br>
                    Senin - Jumat: 08:00 - 16:00 WIB<br>
                    Sabtu: 08:00 - 12:00 WIB
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Pasien Baru -->
@if($pasienTerbaru->count() > 0)
<div class="mt-6">
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 flex items-center justify-between">
            <h2 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Pasien Baru Ditugaskan (Belum Dikonfirmasi)
            </h2>
            <a href="{{ route('dokter.pasien.index') }}" class="bg-white text-green-600 hover:bg-green-50 px-4 py-2 rounded-lg text-sm font-semibold transition">
                Lihat Semua
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">NIK</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Pasien</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tanggal Vaksinasi</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pasienTerbaru as $index => $screening)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $screening->pasien->nik ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $screening->pasien->nama ?? '-' }}</div>
                            <div class="text-sm text-gray-500">{{ $screening->pasien->jenis_kelamin ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($screening->tanggal_vaksinasi)
                                {{ \Carbon\Carbon::parse($screening->tanggal_vaksinasi)->format('d M Y') }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($screening->status_konfirmasi == 'dikonfirmasi' && $screening->tanda_tangan_dokter)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Sudah Dikonfirmasi
                                </span>
                            @elseif($screening->tanda_tangan_dokter && $screening->status_konfirmasi != 'dikonfirmasi')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Menunggu Konfirmasi
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Belum Ditandatangani
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <a href="{{ route('dokter.pasien.show', $screening->id) }}" 
                               class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<!-- Pesan jika tidak ada pasien baru -->
<div class="mt-6">
    <div class="bg-white rounded-xl shadow-md p-8 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Tidak Ada Pasien Baru</h3>
        <p class="text-gray-500 text-sm">Semua pasien yang ditugaskan kepada Anda sudah dikonfirmasi.</p>
        <a href="{{ route('dokter.pasien.index') }}" class="mt-4 inline-block px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition">
            Lihat Semua Pasien
        </a>
    </div>
</div>
@endif
@endsection
