@extends('layouts.app')
@section('title', 'Daftar Permohonan Vaksinasi')
@section('content')
<div class="min-h-screen bg-gray-50">
    <nav class="bg-gradient-to-r from-red-600 to-pink-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <span class="text-xl font-bold text-white">Daftar Permohonan Vaksinasi</span>
            <div class="flex items-center space-x-4">
                <span class="text-white">{{ Auth::user()->nama }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-white text-red-600 hover:bg-red-50 rounded-lg font-semibold">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-red-600 hover:text-red-700 font-semibold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
            <p class="text-green-700">{{ session('success') }}</p>
        </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Total Permohonan</p>
                        <p class="text-2xl font-bold">{{ $permohonan->total() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Disetujui</p>
                        <p class="text-2xl font-bold">{{ $permohonan->where('disetujui', true)->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Pending</p>
                        <p class="text-2xl font-bold">{{ $permohonan->where('disetujui', false)->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Hari Ini</p>
                        <p class="text-2xl font-bold">{{ $permohonan->where('created_at', '>=', today())->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-red-600 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold">No</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Tanggal</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Nama Pasien</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">No. Telepon</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Negara Tujuan</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Tgl Berangkat</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($permohonan as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $permohonan->firstItem() + $index }}</td>
                            <td class="px-6 py-4 text-sm">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $item->pasien->nama }}</td>
                            <td class="px-6 py-4 text-sm">{{ $item->pasien->no_telp }}</td>
                            <td class="px-6 py-4 text-sm">{{ $item->negara_tujuan ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $item->tanggal_berangkat ? $item->tanggal_berangkat->format('d/m/Y') : '-' }}</td>
                            <td class="px-6 py-4">
                                @if($item->disetujui)
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Disetujui</span>
                                @else
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.permohonan.show', $item) }}" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-xs">Detail</a>
                                    @if(!$item->disetujui)
                                    <form method="POST" action="{{ route('admin.permohonan.approve', $item) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded text-xs">Setujui</button>
                                    </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.permohonan.destroy', $item) }}" class="inline" onsubmit="return confirm('Yakin hapus permohonan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-xs">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="font-semibold">Belum ada permohonan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($permohonan->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $permohonan->links() }}
            </div>
            @endif
        </div>
    </main>
</div>
@endsection
