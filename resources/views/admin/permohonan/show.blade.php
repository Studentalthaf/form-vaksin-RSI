@extends('layouts.admin')

@section('page-title', 'Detail Permohonan')
@section('page-subtitle', 'Informasi lengkap permohonan vaksinasi')

@section('content')
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.permohonan.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-semibold transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
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
                        @if($permohonan->pasien->nama_keluarga)
                        <div class="grid grid-cols-3 gap-4 border-t pt-4">
                            <div class="text-sm text-gray-600">Nama Keluarga</div>
                            <div class="col-span-2 font-semibold">{{ $permohonan->pasien->nama_keluarga }}</div>
                        </div>
                        @endif
                        <div class="grid grid-cols-3 gap-4 border-t pt-4">
                            <div class="text-sm text-gray-600">NIK</div>
                            <div class="col-span-2 font-semibold">{{ $permohonan->pasien->nik ?? '-' }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 border-t pt-4">
                            <div class="text-sm text-gray-600">Status Pasien</div>
                            <div class="col-span-2">
                                @if($permohonan->pasien->status_pasien === 'baru')
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">Pasien Baru</span>
                                @else
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Pasien Lama</span>
                                @endif
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 border-t pt-4">
                            <div class="text-sm text-gray-600">Nomor Rekam Medis</div>
                            <div class="col-span-2">
                                @if($permohonan->pasien->nomor_rm)
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-lg text-blue-600">{{ $permohonan->pasien->nomor_rm }}</span>
                                    <button onclick="toggleEditRM()" class="text-sm text-blue-500 hover:text-blue-700 underline">
                                        Edit
                                    </button>
                                </div>
                                @else
                                <span class="text-red-600 font-medium">Belum ada - Silakan isi</span>
                                @endif
                                
                                <!-- Form Edit RM (hidden by default) -->
                                <form id="formEditRM" method="POST" action="{{ route('admin.pasien.update-rm', $permohonan->pasien) }}" 
                                      class="mt-3 {{ $permohonan->pasien->nomor_rm ? 'hidden' : '' }}" onsubmit="return confirm('Yakin ingin menyimpan Nomor RM ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex items-center space-x-2">
                                        <input type="text" 
                                               name="nomor_rm" 
                                               value="{{ $permohonan->pasien->nomor_rm }}"
                                               placeholder="Masukkan Nomor RM"
                                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               required>
                                        <button type="submit" 
                                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold text-sm">
                                            Simpan
                                        </button>
                                        @if($permohonan->pasien->nomor_rm)
                                        <button type="button" 
                                                onclick="toggleEditRM()" 
                                                class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg font-semibold text-sm">
                                            Batal
                                        </button>
                                        @endif
                                    </div>
                                </form>
                            </div>
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
                        <div class="grid grid-cols-3 gap-4 border-t pt-4">
                            <div class="text-sm text-gray-600">Email</div>
                            <div class="col-span-2">
                                @if($permohonan->pasien->email)
                                    <a href="mailto:{{ $permohonan->pasien->email }}" class="font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                                        {{ $permohonan->pasien->email }}
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </div>
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
                            <div class="col-span-2">
                                <div class="space-y-2">
                                    @if(is_array($permohonan->jenis_vaksin) && count($permohonan->jenis_vaksin) > 0)
                                        @foreach($permohonan->jenis_vaksin as $vaksin)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 mr-2 mb-2">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                </svg>
                                                {{ $vaksin }}
                                            </span>
                                        @endforeach
                                    @elseif($permohonan->jenis_vaksin)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                            {{ $permohonan->jenis_vaksin }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                    
                                    @if($permohonan->vaksin_lainnya)
                                        <div class="mt-3 p-3 bg-yellow-50 border-l-4 border-yellow-500 rounded-r-lg">
                                            <p class="text-xs font-semibold text-yellow-800 mb-1">Vaksin Lainnya:</p>
                                            <p class="text-sm text-yellow-900">{{ $permohonan->vaksin_lainnya }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
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

                <!-- Dokumen Pasien -->
                @php
                    $isPerjalananLuarNegeri = ($permohonan->is_perjalanan == 1 || $permohonan->negara_tujuan);
                    $showKTP = $permohonan->pasien->foto_ktp || $isPerjalananLuarNegeri; // Tampilkan KTP jika ada atau jika perjalanan luar negeri
                    $showPaspor = $isPerjalananLuarNegeri; // Selalu tampilkan kotak paspor jika perjalanan luar negeri
                @endphp
                @if($showKTP || $showPaspor)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-indigo-600 text-white px-6 py-4">
                        <h2 class="text-xl font-bold">Dokumen Identitas</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid {{ ($showKTP && $showPaspor) || $isPerjalananLuarNegeri ? 'grid-cols-2' : 'grid-cols-1' }} gap-4">
                            <!-- Foto KTP -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Foto KTP</label>
                                @if($permohonan->pasien->foto_ktp)
                                <div class="border-2 border-gray-200 rounded-lg overflow-hidden hover:border-indigo-500 transition cursor-pointer" 
                                     onclick="openImageModal('{{ asset('storage/' . $permohonan->pasien->foto_ktp) }}', 'Foto KTP - {{ $permohonan->pasien->nama }}')">
                                    <img src="{{ asset('storage/' . $permohonan->pasien->foto_ktp) }}" 
                                         alt="Foto KTP" 
                                         class="w-full h-48 object-cover hover:scale-105 transition">
                                    <div class="bg-gray-50 px-3 py-2 text-center">
                                        <span class="text-xs text-gray-600">Klik untuk memperbesar</span>
                                    </div>
                                </div>
                                @else
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-500">Tidak ada foto</p>
                                </div>
                                @endif
                            </div>

                            <!-- Foto Paspor (selalu tampilkan kotak jika perjalanan luar negeri) -->
                            @if($showPaspor)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Paspor</label>
                                @if($permohonan->pasien->foto_paspor)
                                <div class="border-2 border-gray-200 rounded-lg overflow-hidden hover:border-indigo-500 transition cursor-pointer" 
                                     onclick="openImageModal('{{ asset('storage/' . $permohonan->pasien->foto_paspor) }}', 'Foto Paspor - {{ $permohonan->pasien->nama }}')">
                                    <img src="{{ asset('storage/' . $permohonan->pasien->foto_paspor) }}" 
                                         alt="Foto Paspor" 
                                         class="w-full h-48 object-cover hover:scale-105 transition">
                                    <div class="bg-gray-50 px-3 py-2 text-center">
                                        <span class="text-xs text-gray-600">Klik untuk memperbesar</span>
                                    </div>
                                </div>
                                @else
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-500">Tidak ada foto</p>
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tanda Tangan Pasien & Keluarga -->
                @if($permohonan->screening && ($permohonan->screening->tanda_tangan_pasien || $permohonan->screening->tanda_tangan_keluarga))
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-purple-600 text-white px-6 py-4">
                        <h2 class="text-xl font-bold flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                            Tanda Tangan Persetujuan
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Tanda Tangan Pasien -->
                        @if($permohonan->screening->tanda_tangan_pasien)
                        <div>
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-3">
                                <h3 class="text-sm font-bold text-blue-900 mb-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Tanda Tangan Pasien
                                </h3>
                                <p class="text-xs text-blue-700">
                                    Pasien telah menandatangani form persetujuan saat pengisian screening
                                </p>
                                <p class="text-xs text-blue-600 mt-1">
                                    Ditandatangani pada: {{ $permohonan->screening->tanggal_screening ? $permohonan->screening->tanggal_screening->format('d/m/Y H:i') : '-' }}
                                </p>
                            </div>
                            
                            <div class="border-2 border-blue-200 rounded-lg bg-white p-4 hover:border-blue-400 transition cursor-pointer" 
                                 onclick="openImageModal('{{ asset('storage/' . $permohonan->screening->tanda_tangan_pasien) }}', 'Tanda Tangan Pasien - {{ $permohonan->pasien->nama }}')">
                                <img src="{{ asset('storage/' . $permohonan->screening->tanda_tangan_pasien) }}" 
                                     alt="Tanda Tangan Pasien" 
                                     class="w-full max-h-48 object-contain hover:scale-105 transition">
                                <div class="bg-blue-50 px-3 py-2 text-center mt-3 rounded">
                                    <span class="text-xs text-blue-700 font-medium">Klik untuk memperbesar</span>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Tanda Tangan Keluarga -->
                        @if($permohonan->screening->tanda_tangan_keluarga)
                        <div>
                            <div class="bg-purple-50 border-l-4 border-purple-500 p-4 mb-3">
                                <h3 class="text-sm font-bold text-purple-900 mb-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    Tanda Tangan Keluarga Pasien
                                </h3>
                                <p class="text-xs text-purple-700">
                                    Keluarga pasien telah menandatangani form persetujuan untuk vaksinasi
                                </p>
                                <p class="text-xs text-purple-600 mt-1">
                                    Ditandatangani pada: {{ $permohonan->screening->tanggal_screening ? $permohonan->screening->tanggal_screening->format('d/m/Y H:i') : '-' }}
                                </p>
                            </div>
                            
                            <div class="border-2 border-purple-200 rounded-lg bg-white p-4 hover:border-purple-400 transition cursor-pointer" 
                                 onclick="openImageModal('{{ asset('storage/' . $permohonan->screening->tanda_tangan_keluarga) }}', 'Tanda Tangan Keluarga - {{ $permohonan->pasien->nama }}')">
                                <img src="{{ asset('storage/' . $permohonan->screening->tanda_tangan_keluarga) }}" 
                                     alt="Tanda Tangan Keluarga" 
                                     class="w-full max-h-48 object-contain hover:scale-105 transition">
                                <div class="bg-purple-50 px-3 py-2 text-center mt-3 rounded">
                                    <span class="text-xs text-purple-700 font-medium">Klik untuk memperbesar</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

            </div>

            <!-- Sidebar Actions -->
            <div class="space-y-6">
                <!-- Status Pemeriksaan -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="font-bold mb-4">Status Pemeriksaan</h3>
                    
                    @if($permohonan->screening && $permohonan->screening->nilaiScreening)
                    <!-- SUDAH DICEK -->
                    <div class="bg-green-50 border-2 border-green-500 rounded-lg p-4 text-center mb-4">
                        <svg class="w-12 h-12 mx-auto text-green-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-green-700 font-bold">SUDAH DICEK</p>
                        <p class="text-sm text-green-600 mt-1">Diperiksa: {{ $permohonan->screening->nilaiScreening->updated_at->format('d/m/Y H:i') }}</p>
                    </div>

                    @if($permohonan->screening->nilaiScreening)
                    <!-- Detail Pemeriksaan -->
                    <div class="bg-blue-50 border-2 border-blue-500 rounded-lg p-4 mb-4">
                        <div class="flex items-center justify-center mb-2">
                            <svg class="w-8 h-8 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-left">
                                <p class="text-xs text-blue-600 font-medium">Diperiksa oleh:</p>
                                <p class="text-blue-900 font-bold">{{ $permohonan->screening->nilaiScreening->admin->nama }}</p>
                            </div>
                        </div>
                        <div class="text-center mt-2">
                            @if($permohonan->screening->nilaiScreening->hasil_screening === 'aman')
                            <span class="px-3 py-1 bg-green-500 text-white rounded-full text-xs font-bold">AMAN</span>
                            @elseif($permohonan->screening->nilaiScreening->hasil_screening === 'perlu_perhatian')
                            <span class="px-3 py-1 bg-yellow-500 text-white rounded-full text-xs font-bold">PERLU PERHATIAN</span>
                            @else
                            <span class="px-3 py-1 bg-red-500 text-white rounded-full text-xs font-bold">TIDAK LAYAK</span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('admin.screening.show', $permohonan) }}" 
                        class="block w-full px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-semibold text-center mb-4">
                        Lihat & Edit Hasil Pemeriksaan
                    </a>

                    <!-- Assign to Dokter Section (semua hasil screening bisa dikirim ke dokter) -->
                    @if(!$permohonan->screening->dokter_id)
                    <div class="bg-indigo-50 border-2 border-indigo-500 rounded-lg p-4 mb-4">
                        <h4 class="font-semibold text-indigo-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Serahkan ke Dokter
                        </h4>
                        <form id="assignDokterForm" method="POST" action="{{ route('admin.screening.assign-dokter', $permohonan) }}" onsubmit="return handleAssignDokterSubmit(event)">
                            @csrf
                            <div class="mb-3">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Dokter <span class="text-red-500">*</span></label>
                                <select name="dokter_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                                    <option value="">-- Pilih Dokter --</option>
                                    @foreach($dokterList ?? [] as $dokter)
                                    <option value="{{ $dokter->id }}">Dr. {{ $dokter->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Vaksinasi <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_vaksinasi" 
                                    min="{{ date('Y-m-d') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                            </div>
                            
                            <!-- Tanda Tangan Admin -->
                            <div class="mb-4 p-4 bg-white border-2 border-indigo-300 rounded-lg">
                                <div class="mb-3">
                                    <h5 class="text-sm font-bold text-indigo-900 flex items-center mb-1">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                        Tanda Tangan Admin
                                        <span class="text-red-500 ml-1">*</span>
                                    </h5>
                                    <p class="text-xs text-indigo-700">
                                        Tanda tangan admin sebagai verifikasi sebelum menyerahkan ke dokter
                                    </p>
                                </div>
                                <div class="border-2 border-gray-300 rounded-lg overflow-hidden bg-white">
                                    <canvas id="signaturePadAdmin" width="600" height="200" style="width: 100%; height: 200px; touch-action: none; cursor: crosshair; display: block;"></canvas>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <p class="text-xs text-gray-500">Silakan tanda tangan di kotak di atas</p>
                                    <button type="button" onclick="clearSignatureAdmin()" class="text-xs text-red-600 hover:text-red-700 font-semibold flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus Tanda Tangan
                                    </button>
                                </div>
                                <input type="hidden" name="tanda_tangan_admin" id="tandaTanganAdminInput" required>
                            </div>
                            
                            <button type="submit" class="w-full px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold shadow-lg">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Serahkan ke Dokter
                            </button>
                        </form>
                    </div>
                    @elseif($permohonan->screening->dokter_id)
                    <!-- Already assigned to dokter -->
                    <div class="bg-green-50 border-2 border-green-500 rounded-lg p-4 mb-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-8 h-8 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-green-600 font-medium">Diserahkan ke:</p>
                                <p class="text-green-900 font-bold">Dr. {{ $permohonan->screening->dokter->nama ?? '-' }}</p>
                            </div>
                        </div>
                        @if($permohonan->screening->tanggal_vaksinasi)
                        <p class="text-sm text-green-700">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Jadwal: {{ $permohonan->screening->tanggal_vaksinasi->format('d/m/Y') }}
                        </p>
                        @endif
                    </div>
                    
                    <!-- Tanda Tangan Admin -->
                    @if($permohonan->screening->tanda_tangan_admin)
                    <div class="bg-indigo-50 border-2 border-indigo-500 rounded-lg p-4">
                        <div class="mb-3">
                            <h5 class="text-sm font-bold text-indigo-900 flex items-center mb-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                                Tanda Tangan Admin
                            </h5>
                            <p class="text-xs text-indigo-700">
                                Tanda tangan admin sebagai verifikasi sebelum menyerahkan ke dokter
                            </p>
                        </div>
                        <div class="border-2 border-indigo-200 rounded-lg bg-white p-3 hover:border-indigo-400 transition cursor-pointer"
                             onclick="openImageModal('{{ asset('storage/' . $permohonan->screening->tanda_tangan_admin) }}', 'Tanda Tangan Admin - {{ $permohonan->pasien->nama }}')">
                            <img src="{{ asset('storage/' . $permohonan->screening->tanda_tangan_admin) }}"
                                 alt="Tanda Tangan Admin"
                                 class="w-full max-h-32 object-contain hover:scale-105 transition">
                            <div class="bg-indigo-50 px-2 py-1 text-center mt-2 rounded">
                                <span class="text-xs text-indigo-700 font-medium">Klik untuk memperbesar</span>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                    @else
                    <!-- Sudah isi screening tapi belum dicek -->
                    <div class="bg-red-50 border-2 border-red-500 rounded-lg p-4 text-center mb-4">
                        <svg class="w-12 h-12 mx-auto text-red-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <p class="text-red-700 font-bold">BELUM DICEK</p>
                        <p class="text-sm text-red-600 mt-1">Pasien sudah isi data, menunggu pemeriksaan</p>
                    </div>
                    <a href="{{ route('admin.screening.show', $permohonan) }}" 
                        class="block w-full px-4 py-3 bg-purple-500 hover:bg-purple-600 text-white rounded-lg font-semibold text-center">
                        Lakukan Pemeriksaan
                    </a>
                    @endif
                    
                    @else
                    <!-- Pasien belum isi form screening sama sekali - Admin tetap bisa beri nilai -->
                    <div class="bg-yellow-50 border-2 border-yellow-500 rounded-lg p-4 text-center mb-4">
                        <svg class="w-12 h-12 mx-auto text-yellow-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <p class="text-yellow-700 font-bold">BELUM DICEK</p>
                        <p class="text-sm text-yellow-600 mt-1">Pasien belum mengisi data screening</p>
                    </div>
                    <a href="{{ route('admin.screening.show', $permohonan) }}" 
                        class="block w-full px-4 py-3 bg-purple-500 hover:bg-purple-600 text-white rounded-lg font-semibold text-center">
                        Lakukan Pemeriksaan
                    </a>
                    @endif
                </div>

                <!-- Info Tambahan -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="font-bold mb-4">Informasi</h3>
                    <div class="space-y-3 text-sm text-gray-600">
                        <p>✓ Pasien mengisi screening sendiri</p>
                        <p>✓ Admin memberi nilai screening</p>
                        <p>✓ Status diperbarui otomatis</p>
                    </div>
                <!-- Contact Info -->
                <div class="bg-blue-50 rounded-lg p-4">
                    <h4 class="font-semibold text-sm mb-3">Hubungi Pasien</h4>
                    <div class="space-y-2">
                        <a href="tel:{{ $permohonan->pasien->no_telp }}" class="flex items-center text-blue-600 hover:text-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            {{ $permohonan->pasien->no_telp }}
                        </a>
                        @if($permohonan->pasien->email)
                        <a href="mailto:{{ $permohonan->pasien->email }}" class="flex items-center text-blue-600 hover:text-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            {{ $permohonan->pasien->email }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
        <div class="relative max-w-4xl max-h-full" onclick="event.stopPropagation()">
            <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img id="modalImage" src="" alt="" class="max-w-full max-h-[80vh] rounded-lg shadow-2xl">
            <p id="modalTitle" class="text-white text-center mt-4 font-semibold"></p>
        </div>
    </div>

    <script>
    function toggleEditRM() {
        const form = document.getElementById('formEditRM');
        form.classList.toggle('hidden');
    }

    function openImageModal(imageSrc, title) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });

    // ========== SIGNATURE PAD ADMIN ==========
    const canvasAdmin = document.getElementById('signaturePadAdmin');
    if (canvasAdmin) {
        const ctxAdmin = canvasAdmin.getContext('2d');
        let isDrawingAdmin = false;

        // Setup canvas context
        ctxAdmin.strokeStyle = '#000000';
        ctxAdmin.lineWidth = 3;
        ctxAdmin.lineCap = 'round';
        ctxAdmin.lineJoin = 'round';

        // Get mouse/touch position relative to canvas
        function getPositionAdmin(e) {
            const rect = canvasAdmin.getBoundingClientRect();
            const scaleX = canvasAdmin.width / rect.width;
            const scaleY = canvasAdmin.height / rect.height;
            
            if (e.touches && e.touches[0]) {
                return {
                    x: (e.touches[0].clientX - rect.left) * scaleX,
                    y: (e.touches[0].clientY - rect.top) * scaleY
                };
            }
            
            return {
                x: (e.clientX - rect.left) * scaleX,
                y: (e.clientY - rect.top) * scaleY
            };
        }

        // Start drawing
        function startDrawingAdmin(e) {
            isDrawingAdmin = true;
            const pos = getPositionAdmin(e);
            ctxAdmin.beginPath();
            ctxAdmin.moveTo(pos.x, pos.y);
        }

        // Draw
        function drawAdmin(e) {
            if (!isDrawingAdmin) return;
            e.preventDefault();
            
            const pos = getPositionAdmin(e);
            ctxAdmin.lineTo(pos.x, pos.y);
            ctxAdmin.stroke();
        }

        // Stop drawing
        function stopDrawingAdmin() {
            isDrawingAdmin = false;
            ctxAdmin.beginPath();
        }

        // Mouse events
        canvasAdmin.addEventListener('mousedown', startDrawingAdmin);
        canvasAdmin.addEventListener('mousemove', drawAdmin);
        canvasAdmin.addEventListener('mouseup', stopDrawingAdmin);
        canvasAdmin.addEventListener('mouseout', stopDrawingAdmin);

        // Touch events
        canvasAdmin.addEventListener('touchstart', (e) => {
            e.preventDefault();
            startDrawingAdmin(e);
        });
        canvasAdmin.addEventListener('touchmove', drawAdmin);
        canvasAdmin.addEventListener('touchend', (e) => {
            e.preventDefault();
            stopDrawingAdmin();
        });

        // Clear signature
        window.clearSignatureAdmin = function() {
            ctxAdmin.clearRect(0, 0, canvasAdmin.width, canvasAdmin.height);
            document.getElementById('tandaTanganAdminInput').value = '';
        };

        // Check if canvas is blank
        function isCanvasAdminBlank() {
            const blank = document.createElement('canvas');
            blank.width = canvasAdmin.width;
            blank.height = canvasAdmin.height;
            return canvasAdmin.toDataURL() === blank.toDataURL();
        }

        // Form submission handler
        window.handleAssignDokterSubmit = function(e) {
            e.preventDefault();

            // Validate signature admin
            if (isCanvasAdminBlank()) {
                alert('Silakan buat tanda tangan admin terlebih dahulu!');
                canvasAdmin.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            }

            // Save signature admin as base64
            const signatureDataAdmin = canvasAdmin.toDataURL('image/png');
            document.getElementById('tandaTanganAdminInput').value = signatureDataAdmin;

            // Show confirmation
            if (confirm('Apakah Anda yakin ingin menyerahkan permohonan ini ke dokter?\n\nPastikan:\n✓ Dokter sudah dipilih\n✓ Tanggal vaksinasi sudah diisi\n✓ Tanda tangan admin sudah dibuat\n\nData tidak dapat diubah setelah dikirim.')) {
                // Remove event listener to allow normal form submission
                const form = document.getElementById('assignDokterForm');
                form.removeEventListener('submit', handleAssignDokterSubmit);

                // Submit form normally (without preventDefault)
                form.submit();
            }

            return false;
        };
    }
    </script>
@endsection
