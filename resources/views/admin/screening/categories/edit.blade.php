@extends('layouts.admin')

@section('page-title', 'Edit Kategori Pertanyaan')
@section('page-subtitle', 'Ubah informasi kategori pertanyaan screening')

@section('content')
    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="text-red-800 font-semibold mb-1">Terdapat kesalahan pada input:</h3>
                    <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-white bg-opacity-20 rounded-lg p-2">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Edit Kategori: {{ $category->nama_kategori }}</h2>
                        <p class="text-indigo-100 text-sm mt-1">Ubah informasi kategori pertanyaan screening</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <form method="POST" action="{{ route('admin.screening.categories.update', $category) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Nama Kategori -->
            <div>
                <label for="nama_kategori" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Kategori <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="nama_kategori" 
                       id="nama_kategori" 
                       value="{{ old('nama_kategori', $category->nama_kategori) }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition duration-200" 
                       placeholder="Contoh: Riwayat Penyakit, Alergi, dll" 
                       required>
                <p class="text-xs text-gray-500 mt-1">Masukkan nama kategori yang jelas dan mudah dipahami</p>
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">
                    Deskripsi
                </label>
                <textarea name="deskripsi" 
                          id="deskripsi" 
                          rows="4" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition duration-200 resize-none" 
                          placeholder="Jelaskan lebih detail tentang kategori ini (opsional)">{{ old('deskripsi', $category->deskripsi) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Tambahkan deskripsi untuk memberikan konteks tentang kategori ini</p>
            </div>

            <!-- Urutan dan Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Urutan -->
                <div>
                    <label for="urutan" class="block text-sm font-semibold text-gray-700 mb-2">
                        Urutan Tampil
                    </label>
                    <input type="number" 
                           name="urutan" 
                           id="urutan" 
                           value="{{ old('urutan', $category->urutan) }}" 
                           min="0" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition duration-200" 
                           placeholder="0">
                    <p class="text-xs text-gray-500 mt-1">Urutan tampil kategori (semakin kecil, semakin awal)</p>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Status
                    </label>
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <input type="hidden" name="aktif" value="0">
                        <input type="checkbox" 
                               name="aktif" 
                               id="aktif" 
                               value="1" 
                               {{ old('aktif', $category->aktif) ? 'checked' : '' }} 
                               class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="aktif" class="text-sm font-medium text-gray-700 cursor-pointer">
                            Aktifkan kategori ini
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Kategori aktif akan muncul di form screening</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-end items-stretch sm:items-center gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.screening.categories.index') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update Kategori
                </button>
            </div>
        </form>
    </div>
@endsection
