@extends('layouts.admin')

@section('page-title', 'Tambah Pertanyaan Screening')
@section('page-subtitle', 'Tambah pertanyaan baru ke bank pertanyaan screening')

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
        <div class="bg-gradient-to-r from-red-600 to-pink-600 px-6 py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-white bg-opacity-20 rounded-lg p-2">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Tambah Pertanyaan Baru</h2>
                        <p class="text-red-100 text-sm mt-1">Isi formulir di bawah untuk menambahkan pertanyaan ke bank pertanyaan screening</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <form method="POST" action="{{ route('admin.screening.questions.store') }}" class="p-6 space-y-6">
            @csrf
            
            <!-- Kategori -->
            <div>
                <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    Kategori
                </label>
                <select name="category_id" 
                        id="category_id" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition duration-200 bg-white">
                    <option value="">-- Tanpa Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Pilih kategori untuk mengelompokkan pertanyaan (opsional)</p>
            </div>

            <!-- Pertanyaan -->
            <div>
                <label for="pertanyaan" class="block text-sm font-semibold text-gray-700 mb-2">
                    Pertanyaan <span class="text-red-500">*</span>
                </label>
                <textarea name="pertanyaan" 
                          id="pertanyaan" 
                          rows="4" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition duration-200 resize-none" 
                          placeholder="Masukkan pertanyaan screening yang akan ditanyakan kepada pasien" 
                          required>{{ old('pertanyaan') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Tulis pertanyaan dengan jelas dan mudah dipahami oleh pasien</p>
            </div>

            <!-- Tipe Jawaban -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-blue-900 mb-1">Tipe Jawaban</h3>
                        <p class="text-sm text-blue-800">
                            <strong>Ya / Tidak</strong> dengan Keterangan Tambahan (Opsional)
                        </p>
                        <p class="text-xs text-blue-700 mt-2">
                            Setiap pertanyaan akan memiliki pilihan Ya/Tidak dan field keterangan tambahan yang bersifat opsional untuk memberikan penjelasan lebih lanjut.
                        </p>
                    </div>
                </div>
                <input type="hidden" name="tipe_jawaban" value="ya_tidak">
            </div>

            <!-- Urutan, Wajib, dan Status -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Urutan -->
                <div>
                    <label for="urutan" class="block text-sm font-semibold text-gray-700 mb-2">
                        Urutan Tampil
                    </label>
                    <input type="number" 
                           name="urutan" 
                           id="urutan" 
                           value="{{ old('urutan', 0) }}" 
                           min="0" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition duration-200" 
                           placeholder="0">
                    <p class="text-xs text-gray-500 mt-1">Urutan tampil pertanyaan</p>
                </div>

                <!-- Wajib -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Wajib Diisi
                    </label>
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <input type="checkbox" 
                               name="wajib" 
                               id="wajib" 
                               value="1" 
                               {{ old('wajib', true) ? 'checked' : '' }} 
                               class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                        <label for="wajib" class="text-sm font-medium text-gray-700 cursor-pointer">
                            Wajib diisi
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Pertanyaan harus dijawab</p>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Status
                    </label>
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <input type="checkbox" 
                               name="aktif" 
                               id="aktif" 
                               value="1" 
                               {{ old('aktif', true) ? 'checked' : '' }} 
                               class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                        <label for="aktif" class="text-sm font-medium text-gray-700 cursor-pointer">
                            Aktif
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Pertanyaan aktif akan muncul</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-end items-stretch sm:items-center gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.screening.questions.index') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Pertanyaan
                </button>
            </div>
        </form>
    </div>
@endsection
