@extends('layouts.app')
@section('title', 'Screening Pasien')
@section('content')
<div class="min-h-screen bg-gray-50">
    <nav class="bg-gradient-to-r from-red-600 to-pink-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <span class="text-xl font-bold text-white">Screening Pasien</span>
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
            <a href="{{ route('admin.permohonan.show', $permohonan) }}" class="inline-flex items-center text-red-600 hover:text-red-700 font-semibold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Detail Permohonan
            </a>
        </div>

        @if($existingScreening)
        <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-yellow-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div>
                    <p class="font-semibold text-yellow-800">Pasien sudah pernah di-screening sebelumnya</p>
                    <p class="text-sm text-yellow-700">Mengisi form ini akan menimpa data screening sebelumnya.</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
            <ul class="list-disc list-inside text-red-600 text-sm">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Patient Info Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Informasi Pasien</h2>
            <div class="grid md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">Nama:</span>
                    <span class="font-semibold ml-2">{{ $permohonan->pasien->nama }}</span>
                </div>
                <div>
                    <span class="text-gray-600">No. Telepon:</span>
                    <span class="font-semibold ml-2">{{ $permohonan->pasien->no_telp }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Tanggal Lahir:</span>
                    <span class="ml-2">{{ $permohonan->pasien->tanggal_lahir ? $permohonan->pasien->tanggal_lahir->format('d/m/Y') : '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Negara Tujuan:</span>
                    <span class="ml-2">{{ $permohonan->negara_tujuan ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Screening Form -->
        <form method="POST" action="{{ route('admin.screening.pasien.store', $permohonan) }}">
            @csrf

            @forelse($categories as $category)
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="border-b pb-3 mb-4">
                    <h3 class="text-lg font-bold text-gray-800">{{ $category->nama_kategori }}</h3>
                    @if($category->deskripsi)
                    <p class="text-sm text-gray-600 mt-1">{{ $category->deskripsi }}</p>
                    @endif
                </div>

                <div class="space-y-6">
                    @foreach($category->questions as $question)
                    <div class="border-l-4 border-blue-500 pl-4">
                        <label class="block font-semibold text-gray-800 mb-3">
                            {{ $question->pertanyaan }}
                            @if($question->wajib)
                            <span class="text-red-500">*</span>
                            @endif
                        </label>

                        @if($question->tipe_jawaban === 'ya_tidak')
                        <!-- Radio Ya/Tidak -->
                        <div class="flex items-center space-x-6 mb-3">
                            <label class="inline-flex items-center">
                                <input type="radio" name="jawaban[{{ $question->id }}]" value="ya" 
                                    class="w-4 h-4 text-blue-600" {{ old('jawaban.'.$question->id) === 'ya' ? 'checked' : '' }}
                                    {{ $question->wajib ? 'required' : '' }}>
                                <span class="ml-2">Ya</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="jawaban[{{ $question->id }}]" value="tidak" 
                                    class="w-4 h-4 text-blue-600" {{ old('jawaban.'.$question->id) === 'tidak' ? 'checked' : '' }}
                                    {{ $question->wajib ? 'required' : '' }}>
                                <span class="ml-2">Tidak</span>
                            </label>
                        </div>

                        @elseif($question->tipe_jawaban === 'pilihan_ganda')
                        <!-- Select Pilihan Ganda -->
                        <select name="jawaban[{{ $question->id }}]" 
                            class="w-full px-4 py-2 border rounded-lg mb-3 focus:ring-2 focus:ring-blue-500"
                            {{ $question->wajib ? 'required' : '' }}>
                            <option value="">-- Pilih Jawaban --</option>
                            @if(is_array($question->pilihan_jawaban))
                                @foreach($question->pilihan_jawaban as $pilihan)
                                <option value="{{ $pilihan }}" {{ old('jawaban.'.$question->id) === $pilihan ? 'selected' : '' }}>
                                    {{ $pilihan }}
                                </option>
                                @endforeach
                            @endif
                        </select>

                        @elseif($question->tipe_jawaban === 'text')
                        <!-- Textarea Text -->
                        <textarea name="jawaban[{{ $question->id }}]" rows="3" 
                            class="w-full px-4 py-2 border rounded-lg mb-3 focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan jawaban..." {{ $question->wajib ? 'required' : '' }}>{{ old('jawaban.'.$question->id) }}</textarea>
                        @endif

                        <!-- Keterangan Tambahan -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Keterangan Tambahan (opsional)</label>
                            <input type="text" name="keterangan[{{ $question->id }}]" 
                                value="{{ old('keterangan.'.$question->id) }}"
                                class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500"
                                placeholder="Tambahkan catatan jika diperlukan...">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded">
                <p class="text-yellow-700">Belum ada pertanyaan screening yang aktif. Silakan tambahkan pertanyaan terlebih dahulu di menu <a href="{{ route('admin.screening.questions.index') }}" class="font-semibold underline">Kelola Pertanyaan</a>.</p>
            </div>
            @endforelse

            @if($categories->isNotEmpty())
            <!-- Catatan Umum -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Catatan Umum</h3>
                <textarea name="catatan_umum" rows="4" 
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Tambahkan catatan umum hasil screening...">{{ old('catatan_umum') }}</textarea>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.permohonan.show', $permohonan) }}" 
                    class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold">
                    Batal
                </a>
                <button type="submit" 
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">
                    Simpan Screening
                </button>
            </div>
            @endif
        </form>
    </main>
</div>
@endsection
