@extends('layouts.admin')

@section('page-title', 'Detail Permohonan')
@section('page-subtitle', 'Informasi lengkap permohonan vaksinasi')

@section('content')
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('admin.permohonan.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-semibold transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    {{-- Alert Error / Peringatan --}}
    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.75a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0v-4.5zM10 13.5a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-red-800">Peringatan</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

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
                        @if($permohonan->pasien->email)
                        <div class="grid grid-cols-3 gap-4 border-t pt-4">
                            <div class="text-sm text-gray-600">Email</div>
                            <div class="col-span-2 font-semibold text-blue-600">{{ $permohonan->pasien->email }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Dokumen Pasien -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-indigo-600 text-white px-6 py-4">
                        <h2 class="text-xl font-bold">Dokumen Identitas</h2>
                    </div>
                    <div class="p-6">
                        @php
                            $isPerjalananLuarNegeri = $permohonan->is_perjalanan == 1 || !empty($permohonan->negara_tujuan);
                            $hasKtp = !empty($permohonan->pasien->foto_ktp);
                            $hasPaspor = $isPerjalananLuarNegeri;
                            $gridCols = ($hasKtp && $hasPaspor) ? 'grid-cols-2' : 'grid-cols-1';
                        @endphp
                        @if($hasKtp || $hasPaspor)
                        <div class="grid {{ $gridCols }} gap-4">
                            <!-- Foto KTP -->
                            @if($permohonan->pasien->foto_ktp)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Foto KTP</label>
                                <div class="border-2 border-gray-200 rounded-lg overflow-hidden hover:border-indigo-500 transition cursor-pointer" 
                                     onclick="openImageModal('{{ asset('storage/' . $permohonan->pasien->foto_ktp) }}', 'Foto KTP - {{ $permohonan->pasien->nama }}')">
                                    <img src="{{ asset('storage/' . $permohonan->pasien->foto_ktp) }}" 
                                         alt="Foto KTP" 
                                         class="w-full h-48 object-cover hover:scale-105 transition">
                                    <div class="bg-gray-50 px-3 py-2 text-center">
                                        <span class="text-xs text-gray-600">Klik untuk memperbesar</span>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Foto KTP</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-500">Tidak ada foto</p>
                                </div>
                            </div>
                            @endif

                            <!-- Foto Paspor (hanya untuk perjalanan luar negeri) -->
                            @php
                                $isPerjalananLuarNegeri = $permohonan->is_perjalanan == 1 || !empty($permohonan->negara_tujuan);
                                $showPaspor = $isPerjalananLuarNegeri;
                            @endphp
                            @if($showPaspor)
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Passport Halaman Pertama</label>
                                    @if($permohonan->pasien->passport_halaman_pertama)
                                    <div class="border-2 border-gray-200 rounded-lg overflow-hidden hover:border-indigo-500 transition cursor-pointer" 
                                         onclick="openImageModal('{{ asset('storage/' . $permohonan->pasien->passport_halaman_pertama) }}', 'Passport Halaman Pertama - {{ $permohonan->pasien->nama }}')">
                                        <img src="{{ asset('storage/' . $permohonan->pasien->passport_halaman_pertama) }}" 
                                             alt="Passport Halaman Pertama" 
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
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Passport Halaman Kedua</label>
                                    @if($permohonan->pasien->passport_halaman_kedua)
                                    <div class="border-2 border-gray-200 rounded-lg overflow-hidden hover:border-indigo-500 transition cursor-pointer" 
                                         onclick="openImageModal('{{ asset('storage/' . $permohonan->pasien->passport_halaman_kedua) }}', 'Passport Halaman Kedua - {{ $permohonan->pasien->nama }}')">
                                        <img src="{{ asset('storage/' . $permohonan->pasien->passport_halaman_kedua) }}" 
                                             alt="Passport Halaman Kedua" 
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
                            </div>
                            @endif
                        </div>
                        @else
                        <div class="text-center py-8 text-gray-500">
                            <p>Tidak ada dokumen identitas yang diunggah</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Tanda Tangan Persetujuan Pasien -->
                @if($permohonan->screening)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-purple-600 text-white px-6 py-4">
                        <h2 class="text-xl font-bold flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                            Tanda Tangan Persetujuan
                        </h2>
                    </div>
                    <div class="p-6">
                        @php
                            $hasTtdPasien = !empty($permohonan->screening->tanda_tangan_pasien);
                            $hasTtdKeluarga = !empty($permohonan->screening->tanda_tangan_keluarga);
                            $gridCols = ($hasTtdPasien && $hasTtdKeluarga) ? 'md:grid-cols-2' : 'md:grid-cols-1';
                        @endphp

                        @if($hasTtdPasien || $hasTtdKeluarga)
                        <div class="grid grid-cols-1 {{ $gridCols }} gap-6">
                            <!-- Tanda Tangan Pasien -->
                            @if($hasTtdPasien)
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Tanda Tangan Pasien
                                </h3>
                                <div class="bg-purple-50 border-2 border-purple-300 rounded-lg p-4 mb-3">
                                    <p class="text-sm text-purple-700 mb-2">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Pasien telah menandatangani form persetujuan saat pengisian screening
                                    </p>
                                    <p class="text-xs text-purple-600">
                                        Ditandatangani pada: {{ $permohonan->screening->tanggal_screening ? $permohonan->screening->tanggal_screening->format('d/m/Y H:i') : '-' }}
                                    </p>
                                </div>
                                
                                <div class="border-2 border-purple-200 rounded-lg bg-white p-4 hover:border-purple-400 transition cursor-pointer" 
                                     onclick="openImageModal('{{ asset('storage/' . $permohonan->screening->tanda_tangan_pasien) }}', 'Tanda Tangan Pasien - {{ $permohonan->pasien->nama }}')">
                                    <img src="{{ asset('storage/' . $permohonan->screening->tanda_tangan_pasien) }}" 
                                         alt="Tanda Tangan Pasien" 
                                         class="w-full max-h-48 object-contain hover:scale-105 transition">
                                    <div class="bg-purple-50 px-3 py-2 text-center mt-3 rounded">
                                        <span class="text-xs text-purple-700 font-medium">Klik untuk memperbesar</span>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Tanda Tangan Keluarga -->
                            @if($hasTtdKeluarga)
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    Tanda Tangan Keluarga/Pendamping
                                </h3>
                                <div class="bg-blue-50 border-2 border-blue-300 rounded-lg p-4 mb-3">
                                    <p class="text-sm text-blue-700 mb-2">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Keluarga/Pendamping telah menandatangani form persetujuan saat pengisian screening
                                    </p>
                                    @if($permohonan->pasien->nama_keluarga)
                                    <p class="text-sm text-blue-800 font-semibold">
                                        Nama: {{ $permohonan->pasien->nama_keluarga }}
                                    </p>
                                    @endif
                                    <p class="text-xs text-blue-600 mt-1">
                                        Ditandatangani pada: {{ $permohonan->screening->tanggal_screening ? $permohonan->screening->tanggal_screening->format('d/m/Y H:i') : '-' }}
                                    </p>
                                </div>
                                
                                <div class="border-2 border-blue-200 rounded-lg bg-white p-4 hover:border-blue-400 transition cursor-pointer" 
                                     onclick="openImageModal('{{ asset('storage/' . $permohonan->screening->tanda_tangan_keluarga) }}', 'Tanda Tangan Keluarga - {{ $permohonan->pasien->nama_keluarga ?? $permohonan->pasien->nama }}')">
                                    <img src="{{ asset('storage/' . $permohonan->screening->tanda_tangan_keluarga) }}" 
                                         alt="Tanda Tangan Keluarga" 
                                         class="w-full max-h-48 object-contain hover:scale-105 transition">
                                    <div class="bg-blue-50 px-3 py-2 text-center mt-3 rounded">
                                        <span class="text-xs text-blue-700 font-medium">Klik untuk memperbesar</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            <p>Belum ada tanda tangan persetujuan</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

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
                                <div class="flex flex-wrap gap-2">
                                    @if(is_array($permohonan->jenis_vaksin) && count($permohonan->jenis_vaksin) > 0)
                                        @foreach($permohonan->jenis_vaksin as $vaksin)
                                            @if($vaksin !== 'Lainnya')
                                                <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded mr-1 mb-1">{{ $vaksin }}</span>
                                            @endif
                                        @endforeach
                                    @elseif($permohonan->jenis_vaksin)
                                        @if($permohonan->jenis_vaksin !== 'Lainnya')
                                            <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded mr-1 mb-1">{{ $permohonan->jenis_vaksin }}</span>
                                        @endif
                                    @endif
                                    
                                    @if($permohonan->vaksin_lainnya)
                                        <span class="inline-block px-2 py-1 bg-amber-100 text-amber-800 text-xs rounded mr-1 mb-1 border border-amber-300">
                                            {{ $permohonan->vaksin_lainnya }}
                                        </span>
                                    @elseif(is_array($permohonan->jenis_vaksin) && in_array('Lainnya', $permohonan->jenis_vaksin) && !$permohonan->vaksin_lainnya)
                                        <span class="inline-block px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded mr-1 mb-1">Lainnya (belum diisi)</span>
                                    @endif
                                    
                                    @if(empty($permohonan->jenis_vaksin) && empty($permohonan->vaksin_lainnya))
                                        <span class="text-gray-500 text-sm italic">-</span>
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
                        <form id="assignDokterForm" method="POST" action="{{ route('admin.screening.assign-dokter', $permohonan) }}" onsubmit="return handleFormSubmit(event)">
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
                    <div class="bg-green-50 border-2 border-green-500 rounded-lg p-4">
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
                        
                        <!-- Tanda Tangan Admin -->
                        @if($permohonan->screening->tanda_tangan_admin)
                        <div class="mt-4 pt-4 border-t border-green-300">
                            <p class="text-xs text-green-600 font-medium mb-2">Tanda Tangan Admin:</p>
                            <div class="border-2 border-green-200 rounded-lg p-2 bg-white">
                                <img src="{{ asset('storage/' . $permohonan->screening->tanda_tangan_admin) }}" 
                                     alt="Tanda Tangan Admin" 
                                     class="max-h-16 mx-auto">
                            </div>
                            <p class="text-xs text-green-700 mt-1 text-center">{{ $permohonan->screening->nilaiScreening->admin->nama ?? 'Admin' }}</p>
                        </div>
                        @endif
                    </div>
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
    function handleFormSubmit(e) {
        return confirm('Yakin ingin menyerahkan ke dokter?');
    }

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
    </script>
@endsection
