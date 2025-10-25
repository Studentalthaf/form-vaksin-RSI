@extends('layouts.app')
@section('title', 'Detail Permohonan')
@section('content')
<div class="min-h-screen bg-gray-50">
    <nav class="bg-gradient-to-r from-red-600 to-pink-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <span class="text-xl font-bold text-white">Detail Permohonan Vaksinasi</span>
            <div class="flex items-center space-x-4">
                <span class="text-white">{{ Auth::user()->nama }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-white text-red-600 hover:bg-red-50 rounded-lg font-semibold">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('admin.permohonan.index') }}" class="inline-flex items-center text-red-600 hover:text-red-700 font-semibold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="md:col-span-2 space-y-6">
                <!-- Data Pasien -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-blue-600 text-white px-6 py-4">
                        <h2 class="text-xl font-bold">Data Pasien</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm text-gray-600">Nama Lengkap</div>
                            <div class="col-span-2 font-semibold">{{ $permohonan->pasien->nama }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 border-t pt-4">
                            <div class="text-sm text-gray-600">Nomor Paspor</div>
                            <div class="col-span-2">{{ $permohonan->pasien->nomor_paspor ?? '-' }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 border-t pt-4">
                            <div class="text-sm text-gray-600">Tempat, Tgl Lahir</div>
                            <div class="col-span-2">
                                {{ $permohonan->pasien->tempat_lahir ?? '-' }}{{ $permohonan->pasien->tanggal_lahir ? ', ' . $permohonan->pasien->tanggal_lahir->format('d/m/Y') : '' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 border-t pt-4">
                            <div class="text-sm text-gray-600">Jenis Kelamin</div>
                            <div class="col-span-2">
                                @if($permohonan->pasien->jenis_kelamin == 'L') Laki-laki
                                @elseif($permohonan->pasien->jenis_kelamin == 'P') Perempuan
                                @else - @endif
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 border-t pt-4">
                            <div class="text-sm text-gray-600">Pekerjaan</div>
                            <div class="col-span-2">{{ $permohonan->pasien->pekerjaan ?? '-' }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 border-t pt-4">
                            <div class="text-sm text-gray-600">Alamat</div>
                            <div class="col-span-2">{{ $permohonan->pasien->alamat ?? '-' }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 border-t pt-4">
                            <div class="text-sm text-gray-600">No. Telepon</div>
                            <div class="col-span-2 font-semibold text-blue-600">{{ $permohonan->pasien->no_telp }}</div>
                        </div>
                    </div>
                </div>

                <!-- Data Perjalanan & Vaksinasi -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-green-600 text-white px-6 py-4">
                        <h2 class="text-xl font-bold">Data Perjalanan & Vaksinasi</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm text-gray-600">Negara Tujuan</div>
                            <div class="col-span-2 font-semibold">{{ $permohonan->negara_tujuan ?? '-' }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 border-t pt-4">
                            <div class="text-sm text-gray-600">Tanggal Berangkat</div>
                            <div class="col-span-2">{{ $permohonan->tanggal_berangkat ? $permohonan->tanggal_berangkat->format('d/m/Y') : '-' }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 border-t pt-4">
                            <div class="text-sm text-gray-600">Jenis Vaksin</div>
                            <div class="col-span-2">{{ $permohonan->jenis_vaksin ?? '-' }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 border-t pt-4">
                            <div class="text-sm text-gray-600">Nama Travel</div>
                            <div class="col-span-2">{{ $permohonan->nama_travel ?? '-' }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 border-t pt-4">
                            <div class="text-sm text-gray-600">Alamat Travel</div>
                            <div class="col-span-2">{{ $permohonan->alamat_travel ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Actions -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="font-bold mb-4">Status Permohonan</h3>
                    @if($permohonan->disetujui)
                    <div class="bg-green-50 border-2 border-green-500 rounded-lg p-4 text-center">
                        <svg class="w-12 h-12 mx-auto text-green-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-green-700 font-bold">DISETUJUI</p>
                    </div>
                    @else
                    <div class="bg-yellow-50 border-2 border-yellow-500 rounded-lg p-4 text-center">
                        <svg class="w-12 h-12 mx-auto text-yellow-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-yellow-700 font-bold">PENDING</p>
                    </div>
                    @endif

                    <div class="mt-4 text-sm text-gray-600">
                        <p>Dibuat: {{ $permohonan->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <!-- Screening Status -->
                @php
                    $screening = \App\Models\Screening::where('pasien_id', $permohonan->pasien_id)
                        ->where('vaccine_request_id', $permohonan->id)
                        ->first();
                @endphp

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="font-bold mb-4">Status Screening</h3>
                    @if($screening)
                    <div class="bg-green-50 border-2 border-green-500 rounded-lg p-4 text-center mb-4">
                        <svg class="w-12 h-12 mx-auto text-green-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-green-700 font-bold">SUDAH DI-SCREENING</p>
                        <p class="text-sm text-green-600 mt-1">{{ $screening->tanggal_screening->format('d/m/Y H:i') }}</p>
                    </div>
                    <a href="{{ route('admin.screening.pasien.show', $permohonan) }}" 
                        class="block w-full px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-semibold text-center">
                        Lihat Hasil Screening
                    </a>
                    @else
                    <div class="bg-yellow-50 border-2 border-yellow-500 rounded-lg p-4 text-center mb-4">
                        <svg class="w-12 h-12 mx-auto text-yellow-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <p class="text-yellow-700 font-bold">BELUM DI-SCREENING</p>
                    </div>
                    <a href="{{ route('admin.screening.pasien.create', $permohonan) }}" 
                        class="block w-full px-4 py-3 bg-purple-500 hover:bg-purple-600 text-white rounded-lg font-semibold text-center">
                        Mulai Screening
                    </a>
                    @endif
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="font-bold mb-4">Aksi</h3>
                    <div class="space-y-3">
                        @if(!$permohonan->disetujui)
                        <form method="POST" action="{{ route('admin.permohonan.approve', $permohonan) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="w-full px-4 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg font-semibold">
                                Setujui Permohonan
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.permohonan.reject', $permohonan) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="w-full px-4 py-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-semibold">
                                Tolak Permohonan
                            </button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('admin.permohonan.destroy', $permohonan) }}" onsubmit="return confirm('Yakin hapus permohonan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full px-4 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg font-semibold">
                                Hapus Permohonan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="bg-blue-50 rounded-lg p-4">
                    <h4 class="font-semibold text-sm mb-2">Hubungi Pasien</h4>
                    <a href="tel:{{ $permohonan->pasien->no_telp }}" class="flex items-center text-blue-600 hover:text-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        {{ $permohonan->pasien->no_telp }}
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
