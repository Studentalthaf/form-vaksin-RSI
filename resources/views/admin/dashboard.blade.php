@extends('layouts.admin')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang di panel admin')

@section('content')
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl shadow-xl p-8 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ Auth::user()->nama }}! 👋</h1>
                <p class="text-indigo-100">Kelola sistem vaksinasi dengan mudah dan efisien</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-32 h-32 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                </svg>
            </div>
        </div>
    </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('admin.users.index') }}" class="block group">
                    <div class="bg-white rounded-xl shadow-md hover:shadow-2xl p-6 border-l-4 border-blue-500 transform hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Total User</p>
                                <p class="text-4xl font-extrabold text-gray-900 mt-2">{{ $totalUsers }}</p>
                                <p class="text-xs text-blue-600 mt-2 font-medium group-hover:underline">Lihat Detail →</p>
                            </div>
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.permohonan.index') }}" class="block group">
                    <div class="bg-white rounded-xl shadow-md hover:shadow-2xl p-6 border-l-4 border-green-500 transform hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Total Permohonan</p>
                                <p class="text-4xl font-extrabold text-gray-900 mt-2">{{ $totalPermohonan }}</p>
                                <p class="text-xs text-green-600 mt-2 font-medium group-hover:underline">Lihat Detail →</p>
                            </div>
                            <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="{{route('admin.permohonan.index')}}" class="block group">
                <div class="bg-white rounded-xl shadow-md hover:shadow-2xl p-6 border-l-4 border-purple-500 transform hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Screening Selesai</p>
                            <p class="text-4xl font-extrabold text-gray-900 mt-2">{{ $screeningSelesai }}</p>
                            <p class="text-xs text-purple-600 mt-2 font-medium">Sudah Di-review Admin</p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                </a>
            </div>

            <!-- Quick Actions & Rekap -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Quick Actions
                    </h2>
                    <div class="space-y-2">
                        <a href="{{ route('admin.permohonan.index') }}"
                            class="w-full flex items-center space-x-3 p-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition group">
                            <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <span class="text-sm font-semibold text-gray-800">Lihat Permohonan</span>
                                <p class="text-xs text-gray-500">Kelola permohonan pasien</p>
                            </div>
                        </a>
                        
                        <a href="{{ route('admin.users.index') }}"
                            class="w-full flex items-center space-x-3 p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition group">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <span class="text-sm font-semibold text-gray-800">Kelola User</span>
                                <p class="text-xs text-gray-500">Manajemen user sistem</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.pasien.index') }}"
                            class="w-full flex items-center space-x-3 p-3 bg-green-50 hover:bg-green-100 rounded-lg transition group">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <span class="text-sm font-semibold text-gray-800">Data Pasien</span>
                                <p class="text-xs text-gray-500">Lihat daftar pasien</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Rekap Permohonan -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Rekap Permohonan
                    </h2>

                    <!-- Tabs -->
                    <div class="mb-4 border-b border-gray-200">
                        <nav class="flex space-x-4">
                            <button onclick="switchTab('harian')" id="tab-harian"
                                class="tab-button py-2 px-4 border-b-2 border-red-500 text-red-600 font-medium text-sm">
                                Rekap Harian
                            </button>
                            <button onclick="switchTab('bulanan')" id="tab-bulanan"
                                class="tab-button py-2 px-4 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                                Rekap Bulanan
                            </button>
                        </nav>
                    </div>

                    <!-- Rekap Harian -->
                    <div id="content-harian" class="tab-content">
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4 rounded">
                            <p class="text-sm text-blue-700 font-medium">Permohonan Hari Ini</p>
                            <p class="text-2xl font-bold text-blue-900 mt-1">{{ $permohonanHariIni }} Permohonan</p>
                        </div>

                        <div class="space-y-3">
                            <p class="text-sm font-semibold text-gray-700 mb-2">7 Hari Terakhir:</p>
                            @forelse($rekapHarian as $rekap)
                                <div
                                    class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">
                                                {{ \Carbon\Carbon::parse($rekap->tanggal)->format('d M Y') }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($rekap->tanggal)->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-800">{{ $rekap->jumlah }}</p>
                                        <p class="text-xs text-gray-500">permohonan</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-gray-500 text-sm">Belum ada permohonan dalam 7 hari terakhir</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Rekap Bulanan -->
                    <div id="content-bulanan" class="tab-content hidden">
                        <div class="bg-purple-50 border-l-4 border-purple-500 p-4 mb-4 rounded">
                            <p class="text-sm text-purple-700 font-medium">Permohonan Bulan Ini</p>
                            <p class="text-2xl font-bold text-purple-900 mt-1">{{ $permohonanBulanIni }} Permohonan</p>
                        </div>

                        <div class="space-y-3">
                            <p class="text-sm font-semibold text-gray-700 mb-2">3 Bulan Terakhir:</p>
                            @forelse($rekapBulanan as $rekap)
                                @php
                                    $namaBulan = [
                                        1 => 'Januari',
                                        2 => 'Februari',
                                        3 => 'Maret',
                                        4 => 'April',
                                        5 => 'Mei',
                                        6 => 'Juni',
                                        7 => 'Juli',
                                        8 => 'Agustus',
                                        9 => 'September',
                                        10 => 'Oktober',
                                        11 => 'November',
                                        12 => 'Desember',
                                    ];
                                @endphp
                                <div
                                    class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">
                                                {{ $namaBulan[$rekap->bulan] }} {{ $rekap->tahun }}
                                            </p>
                                            <p class="text-xs text-gray-500">Periode bulanan</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-800">{{ $rekap->jumlah }}</p>
                                        <p class="text-xs text-gray-500">permohonan</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-gray-500 text-sm">Belum ada permohonan dalam 3 bulan terakhir</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function switchTab(tabName) {
                    // Hide all tab contents
                    document.querySelectorAll('.tab-content').forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Remove active state from all tabs
                    document.querySelectorAll('.tab-button').forEach(button => {
                        button.classList.remove('border-red-500', 'text-red-600');
                        button.classList.add('border-transparent', 'text-gray-500');
                    });

                    // Show selected tab content
                    document.getElementById('content-' + tabName).classList.remove('hidden');

                    // Add active state to selected tab
                    const activeTab = document.getElementById('tab-' + tabName);
                    activeTab.classList.add('border-red-500', 'text-red-600');
                    activeTab.classList.remove('border-transparent', 'text-gray-500');
                }
            </script>
@endsection
