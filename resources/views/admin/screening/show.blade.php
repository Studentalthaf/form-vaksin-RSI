@extends('layouts.admin')

@section('page-title', 'Pemeriksaan Pasien')
@section('page-subtitle', 'Lakukan pemeriksaan dan beri nilai screening')

@section('content')
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.permohonan.show', $permohonan) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-semibold transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Detail
        </a>
    </div>

        @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="grid lg:grid-cols-2 gap-6">
            <!-- Left Side: Jawaban Screening Pasien -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-blue-600 text-white px-6 py-4">
                    <h2 class="text-xl font-bold">Jawaban Screening Pasien</h2>
                    <p class="text-sm mt-1">{{ $permohonan->pasien->nama }}</p>
                </div>
                <div class="p-6 max-h-[800px] overflow-y-auto">
                    @if($screening->answers->count() > 0)
                        @php
                            $groupedAnswers = $screening->answers->groupBy(function($answer) {
                                return $answer->question->category->nama ?? 'Lainnya';
                            });
                        @endphp

                        @foreach($groupedAnswers as $categoryName => $answers)
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-3 pb-2 border-b-2 border-blue-500">
                                {{ $categoryName }}
                            </h3>
                            <div class="space-y-4">
                                @foreach($answers as $answer)
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="font-semibold text-gray-700 mb-2">{{ $answer->question->pertanyaan }}</p>
                                    <p class="text-gray-600">
                                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                            {{ $answer->jawaban === 'ya' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($answer->jawaban) }}
                                        </span>
                                    </p>
                                    @if($answer->keterangan)
                                    <p class="text-sm text-gray-500 mt-2 italic">Keterangan: {{ $answer->keterangan }}</p>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-center py-8">Tidak ada jawaban screening</p>
                    @endif
                </div>
            </div>

            <!-- Right Side: Form Pemeriksaan Admin -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-purple-600 text-white px-6 py-4">
                    <h2 class="text-xl font-bold">
                        @if($screening->nilaiScreening)
                            Edit Hasil Pemeriksaan
                        @else
                            Form Pemeriksaan Pasien
                        @endif
                    </h2>
                    <p class="text-sm mt-1">Isi data pemeriksaan dengan lengkap</p>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ $screening->nilaiScreening 
                        ? route('admin.screening.nilai.update', $permohonan) 
                        : route('admin.screening.nilai.store', $permohonan) }}">
                        @csrf
                        @if($screening->nilaiScreening)
                            @method('PUT')
                        @endif

                        <!-- Info Vaksin & Negara -->
                        <div class="mb-6 bg-blue-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-blue-900 mb-3">Informasi Permohonan</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-gray-600 text-sm font-medium block mb-2">Jenis Vaksin yang Dimohonkan:</span>
                                    <div class="flex flex-wrap gap-2">
                                        @if(is_array($permohonan->jenis_vaksin) && count($permohonan->jenis_vaksin) > 0)
                                            @foreach($permohonan->jenis_vaksin as $vaksin)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                    </svg>
                                                    {{ $vaksin }}
                                                </span>
                                            @endforeach
                                        @elseif($permohonan->jenis_vaksin)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                </svg>
                                                {{ $permohonan->jenis_vaksin }}
                                            </span>
                                        @endif
                                        
                                        @if($permohonan->vaksin_lainnya)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-300">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                </svg>
                                                {{ $permohonan->vaksin_lainnya }}
                                            </span>
                                        @endif
                                        
                                        @if(empty($permohonan->jenis_vaksin) && empty($permohonan->vaksin_lainnya))
                                            <span class="text-gray-500 text-sm italic">Belum ada jenis vaksin</span>
                                        @endif
                                    </div>
                                </div>
                                @if($permohonan->negara_tujuan)
                                <div>
                                    <span class="text-gray-600 text-sm font-medium">Negara Tujuan:</span>
                                    <p class="font-semibold text-blue-900">{{ $permohonan->negara_tujuan }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Alergi -->
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alergi Obat <span class="text-red-500">*</span></label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="alergi_obat" value="ada" 
                                        {{ old('alergi_obat', $screening->nilaiScreening->alergi_obat ?? '') === 'ada' ? 'checked' : '' }}
                                        class="mr-2" required>
                                    <span>Ada</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="alergi_obat" value="tidak" 
                                        {{ old('alergi_obat', $screening->nilaiScreening->alergi_obat ?? '') === 'tidak' ? 'checked' : '' }}
                                        class="mr-2" required>
                                    <span>Tidak Ada</span>
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alergi Vaksin <span class="text-red-500">*</span></label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="alergi_vaksin" value="ada" 
                                        {{ old('alergi_vaksin', $screening->nilaiScreening->alergi_vaksin ?? '') === 'ada' ? 'checked' : '' }}
                                        class="mr-2" required>
                                    <span>Ada</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="alergi_vaksin" value="tidak" 
                                        {{ old('alergi_vaksin', $screening->nilaiScreening->alergi_vaksin ?? '') === 'tidak' ? 'checked' : '' }}
                                        class="mr-2" required>
                                    <span>Tidak Ada</span>
                                </label>
                            </div>
                        </div>

                        <!-- Vaksinasi COVID -->
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Sudah Vaksin COVID?</label>
                            <select name="sudah_vaksin_covid" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                                <option value="">-- Pilih --</option>
                                <option value="1" {{ old('sudah_vaksin_covid', $screening->nilaiScreening->sudah_vaksin_covid ?? '') === '1' ? 'selected' : '' }}>Dosis 1</option>
                                <option value="2" {{ old('sudah_vaksin_covid', $screening->nilaiScreening->sudah_vaksin_covid ?? '') === '2' ? 'selected' : '' }}>Dosis 2</option>
                                <option value="booster" {{ old('sudah_vaksin_covid', $screening->nilaiScreening->sudah_vaksin_covid ?? '') === 'booster' ? 'selected' : '' }}>Booster</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Vaksin COVID</label>
                            <input type="text" name="nama_vaksin_covid" 
                                value="{{ old('nama_vaksin_covid', $screening->nilaiScreening->nama_vaksin_covid ?? '') }}"
                                placeholder="Contoh: Sinovac, AstraZeneca, Pfizer"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Dimana Vaksinasi?</label>
                            <input type="text" name="dimana" 
                                value="{{ old('dimana', $screening->nilaiScreening->dimana ?? '') }}"
                                placeholder="Contoh: RS. XYZ, Puskesmas ABC"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kapan Vaksinasi?</label>
                            <input type="text" name="kapan" 
                                value="{{ old('kapan', $screening->nilaiScreening->kapan ?? '') }}"
                                placeholder="Contoh: Januari 2024"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>

                        <!-- Tanggal Keberangkatan (hanya untuk perjalanan luar negeri) -->
                        @if($permohonan->is_perjalanan == 1 || $permohonan->negara_tujuan)
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Keberangkatan</label>
                            <input type="date" name="tanggal_berangkat_umroh" 
                                value="{{ $permohonan->tanggal_berangkat ? $permohonan->tanggal_berangkat->format('Y-m-d') : '' }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed" 
                                readonly>
                            <p class="text-xs text-gray-500 mt-1">*Diambil dari form permohonan pasien (tidak dapat diubah)</p>
                        </div>
                        @endif

                        <!-- Tanda Vital -->
                        <div class="mb-6 bg-green-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-green-900 mb-3">Tanda Vital</h3>
                            
                            <div class="grid grid-cols-2 gap-4 mb-3">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tekanan Darah (TD)</label>
                                    <input type="text" name="td" 
                                        value="{{ old('td', $screening->nilaiScreening->td ?? '') }}"
                                        placeholder="Contoh: 120/80"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nadi (x/menit)</label>
                                    <input type="text" name="nadi" 
                                        value="{{ old('nadi', $screening->nilaiScreening->nadi ?? '') }}"
                                        placeholder="Contoh: 80"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Suhu (°C)</label>
                                    <input type="text" name="suhu" 
                                        value="{{ old('suhu', $screening->nilaiScreening->suhu ?? '') }}"
                                        placeholder="36.5"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">TB (cm)</label>
                                    <input type="text" name="tb" 
                                        value="{{ old('tb', $screening->nilaiScreening->tb ?? '') }}"
                                        placeholder="170"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">BB (kg)</label>
                                    <input type="text" name="bb" 
                                        value="{{ old('bb', $screening->nilaiScreening->bb ?? '') }}"
                                        placeholder="60"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                </div>
                            </div>
                        </div>

                        <!-- Hasil Screening -->
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Hasil Screening <span class="text-red-500">*</span></label>
                            <div class="space-y-2">
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-green-50 cursor-pointer">
                                    <input type="radio" name="hasil_screening" value="aman" 
                                        {{ old('hasil_screening', $screening->nilaiScreening->hasil_screening ?? '') === 'aman' ? 'checked' : '' }}
                                        class="mr-3" required>
                                    <div>
                                        <span class="font-semibold text-green-700">Aman</span>
                                        <p class="text-xs text-gray-600">Pasien aman untuk divaksinasi</p>
                                    </div>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-yellow-50 cursor-pointer">
                                    <input type="radio" name="hasil_screening" value="perlu_perhatian" 
                                        {{ old('hasil_screening', $screening->nilaiScreening->hasil_screening ?? '') === 'perlu_perhatian' ? 'checked' : '' }}
                                        class="mr-3" required>
                                    <div>
                                        <span class="font-semibold text-yellow-700">Perlu Perhatian</span>
                                        <p class="text-xs text-gray-600">Perlu evaluasi lebih lanjut</p>
                                    </div>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-red-50 cursor-pointer">
                                    <input type="radio" name="hasil_screening" value="tidak_layak" 
                                        {{ old('hasil_screening', $screening->nilaiScreening->hasil_screening ?? '') === 'tidak_layak' ? 'checked' : '' }}
                                        class="mr-3" required>
                                    <div>
                                        <span class="font-semibold text-red-700">Tidak Layak</span>
                                        <p class="text-xs text-gray-600">Tidak direkomendasikan untuk vaksinasi</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan</label>
                            <textarea name="catatan" rows="4" 
                                placeholder="Catatan tambahan mengenai kondisi pasien..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">{{ old('catatan', $screening->nilaiScreening->catatan ?? '') }}</textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex space-x-3">
                            <button type="submit" class="flex-1 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-bold shadow-lg">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ $screening->nilaiScreening ? 'Update Hasil Pemeriksaan' : 'Simpan Hasil Pemeriksaan' }}
                            </button>
                            <a href="{{ route('admin.permohonan.show', $permohonan) }}" 
                               class="px-6 py-3 bg-gray-400 hover:bg-gray-500 text-white rounded-lg font-bold text-center">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
