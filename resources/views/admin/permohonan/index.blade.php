@extends('layouts.app')
@section('title', 'Daftar Permohonan Vaksinasi')
@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-red-600 to-pink-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                <span class="text-xl font-bold text-white">Daftar Permohonan Vaksinasi</span>
                <div class="flex items-center space-x-3">
                    <span class="text-white text-sm sm:text-base">{{ Auth::user()->nama }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-white text-red-600 hover:bg-red-50 rounded-lg font-semibold text-sm transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-red-600 hover:text-red-700 font-semibold transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 border-l-4 border-blue-500 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs sm:text-sm font-medium">Total Semua</p>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-1">{{ $totalPermohonan }}</p>
                        <p class="text-xs text-gray-400 mt-1">Keseluruhan</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 border-l-4 border-purple-500 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs sm:text-sm font-medium">Hari Ini</p>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-1">{{ $hariIni }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::now()->format('d M Y') }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 border-l-4 border-green-500 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs sm:text-sm font-medium">Disetujui</p>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-1">{{ $totalDisetujui }}</p>
                        <p class="text-xs text-gray-400 mt-1">Approved</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 border-l-4 border-yellow-500 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs sm:text-sm font-medium">Pending</p>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-1">{{ $totalPending }}</p>
                        <p class="text-xs text-gray-400 mt-1">Menunggu</p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6">
            <form method="GET" action="{{ route('admin.permohonan.index') }}" id="filterForm" class="space-y-4">
                <input type="hidden" name="tab" value="{{ $activeTab }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari Nama Pemohon</label>
                        <div class="relative">
                            <input type="text" 
                                   id="searchInput"
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Ketik nama pemohon..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Tanggal Dari -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                        <input type="date" 
                               name="tanggal_dari" 
                               value="{{ request('tanggal_dari') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>

                    <!-- Tanggal Sampai -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                        <input type="date" 
                               name="tanggal_sampai" 
                               value="{{ request('tanggal_sampai') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.permohonan.index') }}?tab={{ $activeTab }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold transition flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- TABS -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Tab Headers -->
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <a href="{{ route('admin.permohonan.index') }}?tab=pending{{ request('search') ? '&search='.request('search') : '' }}{{ request('tanggal_dari') ? '&tanggal_dari='.request('tanggal_dari') : '' }}{{ request('tanggal_sampai') ? '&tanggal_sampai='.request('tanggal_sampai') : '' }}"
                       class="tab-link w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm {{ $activeTab === 'pending' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} transition">
                        <div class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Pending ({{ $totalPending }})</span>
                        </div>
                    </a>
                    <a href="{{ route('admin.permohonan.index') }}?tab=disetujui{{ request('search') ? '&search='.request('search') : '' }}{{ request('tanggal_dari') ? '&tanggal_dari='.request('tanggal_dari') : '' }}{{ request('tanggal_sampai') ? '&tanggal_sampai='.request('tanggal_sampai') : '' }}"
                       class="tab-link w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm {{ $activeTab === 'disetujui' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} transition">
                        <div class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Disetujui ({{ $totalDisetujui }})</span>
                        </div>
                    </a>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="overflow-x-auto">
                @if($activeTab === 'pending')
                    @include('admin.permohonan.partials.pending-table', ['permohonan' => $permohonanPending])
                @else
                    @include('admin.permohonan.partials.disetujui-table', ['permohonan' => $permohonanDisetujui])
                @endif
            </div>
        </div>
    </main>
</div>

<script>
// Auto-submit form saat mengetik di search (dengan debounce 500ms)
let searchTimeout;
const searchInput = document.getElementById('searchInput');
const searchForm = searchInput.closest('form');

searchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function() {
        searchForm.submit();
    }, 500);
});
</script>
@endsection
