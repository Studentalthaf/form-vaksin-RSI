@extends('layouts.admin')

@section('page-title', 'Edit Vaksin')
@section('page-subtitle', 'Edit data vaksin')

@section('content')
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="mb-6">
            <a href="{{ route('admin.vaksin.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-semibold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar Vaksin
            </a>
        </div>

        <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Vaksin</h1>

        <form action="{{ route('admin.vaksin.update', $vaksin) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Nama Vaksin -->
                <div>
                    <label for="nama_vaksin" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Vaksin <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="nama_vaksin" 
                           id="nama_vaksin" 
                           value="{{ old('nama_vaksin', $vaksin->nama_vaksin) }}"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('nama_vaksin') border-red-500 @enderror"
                           placeholder="Contoh: Yellow Fever, Meningitis, dll">
                    @error('nama_vaksin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" 
                              id="deskripsi" 
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                              placeholder="Deskripsi singkat tentang vaksin (opsional)">{{ old('deskripsi', $vaksin->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Urutan -->
                <div>
                    <label for="urutan" class="block text-sm font-semibold text-gray-700 mb-2">
                        Urutan Tampil
                    </label>
                    <input type="number" 
                           name="urutan" 
                           id="urutan" 
                           value="{{ old('urutan', $vaksin->urutan) }}"
                           min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('urutan') border-red-500 @enderror"
                           placeholder="0">
                    <p class="mt-1 text-sm text-gray-500">Urutan untuk menampilkan vaksin (angka lebih kecil akan muncul lebih dulu)</p>
                    @error('urutan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Aktif -->
                <div>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" 
                               name="aktif" 
                               value="1"
                               {{ old('aktif', $vaksin->aktif) ? 'checked' : '' }}
                               class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500">
                        <span class="ml-3 text-sm font-semibold text-gray-700">Aktif</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-500 ml-8">Vaksin aktif akan muncul di form permohonan pasien</p>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                    <a href="{{ route('admin.vaksin.index') }}" 
                       class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition duration-200">
                        Update Vaksin
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection









