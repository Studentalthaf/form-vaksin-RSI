@extends('layouts.app')

@section('title', 'Admin Panel - Form Vaksin')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Navbar -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="ml-3 text-xl font-bold text-gray-800">Admin Panel</span>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-800">{{ Auth::user()->nama }}</p>
                            <p class="text-xs text-red-600 font-semibold">Administrator</p>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition duration-200">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Dashboard Administrator</h1>
                <p class="text-gray-600 mt-2">Kelola sistem form vaksin rumah sakit</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <a href="{{ route('admin.users.index') }}" class="block">
                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 hover:bg-blue-50 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Total User</p>
                                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalUsers }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.permohonan.index') }}" class="block">
                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-50  hover:bg-green-50 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Total Permohonan</p>
                                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalPermohonan }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>


                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Screening Selesai</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $screeningSelesai }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Features -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Management Panel -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Manajemen Sistem
                    </h2>
                    <div class="space-y-3">
                        <a href="{{ route('admin.users.index') }}"
                            class="w-full flex items-center justify-between p-4 border-2 border-gray-200 rounded-lg hover:border-red-500 hover:bg-red-50 transition">
                            <span class="font-medium text-gray-700">Kelola User</span>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="{{ route('admin.screening.categories.index') }}"
                            class="w-full flex items-center justify-between p-4 border-2 border-gray-200 rounded-lg hover:border-red-500 hover:bg-red-50 transition">
                            <span class="font-medium text-gray-700">Kelola Kategori Screening</span>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="{{ route('admin.screening.questions.index') }}"
                            class="w-full flex items-center justify-between p-4 border-2 border-gray-200 rounded-lg hover:border-red-500 hover:bg-red-50 transition">
                            <span class="font-medium text-gray-700">Kelola Pertanyaan Screening</span>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="{{ route('admin.permohonan.index') }}"
                            class="w-full flex items-center justify-between p-4 border-2 border-gray-200 rounded-lg hover:border-red-500 hover:bg-red-50 transition">
                            <span class="font-medium text-gray-700">Kelola Permohonan</span>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <button
                            class="w-full flex items-center justify-between p-4 border-2 border-gray-200 rounded-lg hover:border-red-500 hover:bg-red-50 transition">
                            <span class="font-medium text-gray-700">Laporan Lengkap</span>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
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

            <!-- Info Box -->
            <div class="mt-6 bg-gradient-to-r from-red-500 to-pink-500 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-start">
                    <svg class="w-6 h-6 mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h3 class="font-bold text-lg mb-1">Akses Administrator</h3>
                        <p class="text-sm opacity-90">Anda memiliki akses penuh ke semua fitur sistem. Gunakan dengan
                            bijak!</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
