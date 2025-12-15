@extends('layouts.admin')

@section('page-title', 'Pemeriksaan Pasien')
@section('page-subtitle', 'Lakukan pemeriksaan dan beri nilai screening')

@section('content')
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('admin.permohonan.show', $permohonan) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-semibold transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Detail
        </a>
    </div>

    @if(session('warning'))
        <div class="mb-4 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l6.518 11.59C19.022 16.92 18.266 18 17.004 18H2.996c-1.262 0-2.018-1.08-1.257-2.31l6.518-11.59zM11 14a1 1 0 10-2 0 1 1 0 002 0zm-1-2a1 1 0 01-1-1V7a1 1 0 112 0v4a1 1 0 01-1 1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-yellow-800">Peringatan</h3>
                    <p class="mt-1 text-sm text-yellow-700">
                        {{ session('warning') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

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
                <div class="bg-blue-600 text-white px-6 py-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold">📋 Jawaban Screening Pasien</h2>
                    <p class="text-sm mt-1">{{ $permohonan->pasien->nama }}</p>
                    </div>
                    <button type="button" onclick="toggleEditMode('jawaban')" id="btn-edit-jawaban" class="px-4 py-2 bg-white text-blue-600 rounded-lg font-semibold hover:bg-blue-50 transition">
                        ✏️ Edit
                    </button>
                </div>
                
                <!-- View Mode -->
                <div id="view-jawaban" class="p-6 max-h-[800px] overflow-y-auto">
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
                                            {{ $answer->jawaban === 'ya' || $answer->jawaban === 'Ya' ? 'bg-green-100 text-green-800' : ($answer->jawaban === 'Tidak Tahu' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
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
                
                <!-- Edit Mode -->
                <div id="edit-jawaban" class="hidden p-6 max-h-[800px] overflow-y-auto">
                    <form method="POST" action="{{ route('admin.screening.jawaban.update', $permohonan) }}">
                        @csrf
                        @method('PUT')
                        
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
                                        <p class="font-semibold text-gray-700 mb-3">{{ $answer->question->pertanyaan }}</p>
                                        
                                        @if($answer->question->tipe_jawaban == 'ya_tidak')
                                            <div class="flex gap-4 mb-3">
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="radio" name="jawaban_{{ $answer->question->id }}" value="Ya" 
                                                        {{ $answer->jawaban == 'Ya' ? 'checked' : '' }}
                                                        class="w-4 h-4 text-green-600 focus:ring-green-500"
                                                        onchange="toggleKeteranganEdit{{ $answer->question->id }}(this.value)">
                                                    <span class="ml-2 text-gray-700">Ya</span>
                                                </label>
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="radio" name="jawaban_{{ $answer->question->id }}" value="Tidak" 
                                                        {{ $answer->jawaban == 'Tidak' ? 'checked' : '' }}
                                                        class="w-4 h-4 text-green-600 focus:ring-green-500"
                                                        onchange="toggleKeteranganEdit{{ $answer->question->id }}(this.value)">
                                                    <span class="ml-2 text-gray-700">Tidak</span>
                                                </label>
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="radio" name="jawaban_{{ $answer->question->id }}" value="Tidak Tahu" 
                                                        {{ $answer->jawaban == 'Tidak Tahu' ? 'checked' : '' }}
                                                        class="w-4 h-4 text-green-600 focus:ring-green-500"
                                                        onchange="toggleKeteranganEdit{{ $answer->question->id }}(this.value)">
                                                    <span class="ml-2 text-gray-700">Tidak Tahu</span>
                                                </label>
                                            </div>
                                            
                                            <div id="keterangan_edit_{{ $answer->question->id }}" class="{{ $answer->jawaban == 'Ya' ? '' : 'hidden' }}">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                                                <textarea name="keterangan_{{ $answer->question->id }}" rows="2"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                                    placeholder="Jelaskan lebih detail...">{{ $answer->keterangan }}</textarea>
                                            </div>
                                            
                                            <script>
                                                function toggleKeteranganEdit{{ $answer->question->id }}(value) {
                                                    const container = document.getElementById('keterangan_edit_{{ $answer->question->id }}');
                                                    if (value === 'Ya') {
                                                        container.classList.remove('hidden');
                                                    } else {
                                                        container.classList.add('hidden');
                                                    }
                                                }
                                            </script>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        @elseif(isset($questions) && $questions->count() > 0)
                            {{-- Pasien belum mengisi, tapi admin boleh mengisi jawaban baru --}}
                            @php
                                $groupedQuestions = $questions->groupBy(function($q) {
                                    return optional($q->category)->nama ?? 'Lainnya';
                                });
                            @endphp

                            @foreach($groupedQuestions as $categoryName => $qs)
                                <div class="mb-6">
                                    <h3 class="text-lg font-bold text-gray-800 mb-3 pb-2 border-b-2 border-blue-500">
                                        {{ $categoryName }}
                                    </h3>
                                    <div class="space-y-4">
                                        @foreach($qs as $q)
                                            <div class="bg-gray-50 p-4 rounded-lg">
                                                <p class="font-semibold text-gray-700 mb-3">{{ $q->pertanyaan }}</p>

                                                @if($q->tipe_jawaban == 'ya_tidak')
                                                    <div class="flex gap-4 mb-3">
                                                        <label class="inline-flex items-center cursor-pointer">
                                                            <input type="radio" name="jawaban_{{ $q->id }}" value="Ya"
                                                                   class="w-4 h-4 text-green-600 focus:ring-green-500"
                                                                   onchange="toggleKeteranganEdit{{ $q->id }}(this.value)">
                                                            <span class="ml-2 text-gray-700">Ya</span>
                                                        </label>
                                                        <label class="inline-flex items-center cursor-pointer">
                                                            <input type="radio" name="jawaban_{{ $q->id }}" value="Tidak"
                                                                   class="w-4 h-4 text-green-600 focus:ring-green-500"
                                                                   onchange="toggleKeteranganEdit{{ $q->id }}(this.value)">
                                                            <span class="ml-2 text-gray-700">Tidak</span>
                                                        </label>
                                                        <label class="inline-flex items-center cursor-pointer">
                                                            <input type="radio" name="jawaban_{{ $q->id }}" value="Tidak Tahu"
                                                                   class="w-4 h-4 text-green-600 focus:ring-green-500"
                                                                   onchange="toggleKeteranganEdit{{ $q->id }}(this.value)">
                                                            <span class="ml-2 text-gray-700">Tidak Tahu</span>
                                                        </label>
                                                    </div>

                                                    <div id="keterangan_edit_{{ $q->id }}" class="hidden">
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                                                        <textarea name="keterangan_{{ $q->id }}" rows="2"
                                                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                                                  placeholder="Jelaskan lebih detail..."></textarea>
                                                    </div>

                                                    <script>
                                                        function toggleKeteranganEdit{{ $q->id }}(value) {
                                                            const container = document.getElementById('keterangan_edit_{{ $q->id }}');
                                                            if (value === 'Ya') {
                                                                container.classList.remove('hidden');
                                                            } else {
                                                                container.classList.add('hidden');
                                                            }
                                                        }
                                                    </script>
                                                @elseif($q->tipe_jawaban == 'pilihan_ganda')
                                                    @php
                                                        $pilihanBaru = json_decode($q->pilihan_jawaban, true) ?? [];
                                                    @endphp
                                                    <div class="space-y-2">
                                                        @foreach($pilihanBaru as $p)
                                                            <label class="flex items-center cursor-pointer">
                                                                <input type="radio" name="jawaban_{{ $q->id }}" value="{{ $p }}"
                                                                       class="w-4 h-4 text-green-600 focus:ring-green-500">
                                                                <span class="ml-2 text-gray-700">{{ $p }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                @elseif($q->tipe_jawaban == 'text')
                                                    <textarea name="jawaban_{{ $q->id }}" rows="3"
                                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                                              placeholder="Tuliskan jawaban ..."></textarea>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        
                        @if(($screening->answers->count() > 0) || (isset($questions) && $questions->count() > 0))
                        <div class="sticky bottom-0 bg-white border-t border-gray-200 pt-4 flex justify-end gap-2">
                            <button type="button" onclick="toggleEditMode('jawaban')" class="px-6 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg font-semibold">
                                Batal
                            </button>
                            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-lg">
                                💾 Simpan Perubahan
                            </button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Right Side: Forms -->
            <div class="space-y-6">
                <!-- Data Pasien -->
                <div class="bg-white rounded-lg shadow-lg">
                    <div class="bg-indigo-600 text-white px-6 py-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold">👤 Data Pasien</h3>
                            <p class="text-sm text-indigo-100 mt-1">Informasi data pasien</p>
                        </div>
                        <button type="button" onclick="toggleEditMode('data-pasien')" id="btn-edit-pasien" class="px-4 py-2 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-indigo-50 transition">
                            ✏️ Edit
                        </button>
                    </div>
                    
                    <!-- View Mode -->
                    <div id="view-data-pasien" class="p-6 border-t border-gray-200">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Nama Lengkap</label>
                                <p class="text-gray-900 font-semibold mt-1">{{ $permohonan->pasien->nama }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Email</label>
                                <p class="text-gray-900 font-semibold mt-1">{{ $permohonan->pasien->email ?: '-' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Nomor Telepon</label>
                                <p class="text-gray-900 font-semibold mt-1">{{ $permohonan->pasien->no_telp }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Tempat, Tanggal Lahir</label>
                                <p class="text-gray-900 font-semibold mt-1">
                                    {{ $permohonan->pasien->tempat_lahir ?: '-' }}{{ $permohonan->pasien->tanggal_lahir ? ', ' . $permohonan->pasien->tanggal_lahir->format('d F Y') : '' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Jenis Kelamin</label>
                                <p class="text-gray-900 font-semibold mt-1">{{ $permohonan->pasien->jenis_kelamin == 'L' ? 'Laki-laki' : ($permohonan->pasien->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Pekerjaan</label>
                                <p class="text-gray-900 font-semibold mt-1">{{ $permohonan->pasien->pekerjaan ?: '-' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Nomor RM</label>
                                <p class="text-gray-900 font-semibold mt-1">{{ $permohonan->pasien->nomor_rm ?: '-' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Nomor Paspor</label>
                                <p class="text-gray-900 font-semibold mt-1">{{ $permohonan->pasien->nomor_paspor ?: '-' }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="text-xs font-semibold text-gray-500 uppercase">Alamat</label>
                            <p class="text-gray-900 font-semibold mt-1">{{ $permohonan->pasien->alamat ?: '-' }}</p>
                        </div>
                    </div>
                    
                    <!-- Edit Mode -->
                    <div id="edit-data-pasien" class="hidden p-6 border-t border-gray-200">
                    <form method="POST" action="{{ route('admin.screening.pasien.update', $permohonan) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap *</label>
                                <input type="text" name="nama" value="{{ old('nama', $permohonan->pasien->nama) }}" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                                <input type="email" name="email" value="{{ old('email', $permohonan->pasien->email) }}" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon *</label>
                                <input type="text" name="no_telp" value="{{ old('no_telp', $permohonan->pasien->no_telp) }}" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $permohonan->pasien->tempat_lahir) }}" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $permohonan->pasien->tanggal_lahir ? $permohonan->pasien->tanggal_lahir->format('Y-m-d') : '') }}" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="L" {{ old('jenis_kelamin', $permohonan->pasien->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin', $permohonan->pasien->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Pekerjaan</label>
                                <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $permohonan->pasien->pekerjaan) }}" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor RM</label>
                                <input type="text" name="nomor_rm" value="{{ old('nomor_rm', $permohonan->pasien->nomor_rm) }}" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Paspor</label>
                                <input type="text" name="nomor_paspor" value="{{ old('nomor_paspor', $permohonan->pasien->nomor_paspor) }}" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                            <textarea name="alamat" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ old('alamat', $permohonan->pasien->alamat) }}</textarea>
                        </div>
                        
                        <div class="mt-4 flex justify-end gap-2">
                            <button type="button" onclick="toggleEditMode('data-pasien')" class="px-6 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg font-semibold">
                                Batal
                            </button>
                            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold shadow-lg">
                                💾 Simpan Perubahan
                            </button>
                        </div>
                    </form>
                    </div>
                </div>

                <!-- Informasi Vaksin -->
                <div class="bg-white rounded-lg shadow-lg">
                    <div class="bg-green-600 text-white px-6 py-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold">💉 Informasi Vaksin</h3>
                            <p class="text-sm text-green-100 mt-1">Jenis vaksin yang dimohonkan</p>
                        </div>
                        <button type="button" onclick="toggleEditMode('vaksin')" id="btn-edit-vaksin" class="px-4 py-2 bg-white text-green-600 rounded-lg font-semibold hover:bg-green-50 transition">
                            ✏️ Edit
                        </button>
                    </div>
                    
                    <!-- View Mode -->
                    <div id="view-vaksin" class="p-6 border-t border-gray-200">
                        <div class="mb-4">
                            <label class="text-xs font-semibold text-gray-500 uppercase mb-2 block">Jenis Vaksin</label>
                            <div class="flex flex-wrap gap-2">
                                @if(is_array($permohonan->jenis_vaksin) && count($permohonan->jenis_vaksin) > 0)
                                    @foreach($permohonan->jenis_vaksin as $vaksin)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800 border border-green-200">
                                            {{ $vaksin }}
                                        </span>
                                    @endforeach
                                @endif
                                
                                @if($permohonan->vaksin_lainnya)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800 border border-yellow-300">
                                        {{ $permohonan->vaksin_lainnya }}
                                    </span>
                                @endif
                                
                                @if(empty($permohonan->jenis_vaksin) && empty($permohonan->vaksin_lainnya))
                                    <span class="text-gray-500 italic">Belum ada vaksin</span>
                                @endif
                            </div>
                        </div>
                        
                        @if($permohonan->is_perjalanan == 1)
                        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-xs font-semibold text-blue-800 uppercase mb-2">Informasi Perjalanan Luar Negeri</p>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase">Negara Tujuan</label>
                                    <p class="text-gray-900 font-semibold mt-1">{{ $permohonan->negara_tujuan ?: '-' }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase">Tanggal Keberangkatan</label>
                                    <p class="text-gray-900 font-semibold mt-1">{{ $permohonan->tanggal_berangkat ? $permohonan->tanggal_berangkat->format('d F Y') : '-' }}</p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 mt-2 italic">* Informasi perjalanan tidak dapat diubah</p>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Edit Mode -->
                    <div id="edit-vaksin" class="hidden p-6 border-t border-gray-200">
                    <form method="POST" action="{{ route('admin.screening.vaksin.update', $permohonan) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Jenis Vaksin yang Dimohonkan *</label>
                            <div class="grid md:grid-cols-2 gap-3">
                                @if(isset($vaksins) && $vaksins->count() > 0)
                                    @foreach($vaksins as $vaksin)
                                    <label class="flex items-start p-3 border border-gray-300 rounded-lg hover:bg-green-50 cursor-pointer transition">
                                        <input type="checkbox" name="jenis_vaksin[]" value="{{ $vaksin->nama_vaksin }}" 
                                            {{ in_array($vaksin->nama_vaksin, old('jenis_vaksin', is_array($permohonan->jenis_vaksin) ? $permohonan->jenis_vaksin : [])) ? 'checked' : '' }}
                                            class="mt-1 w-4 h-4 text-green-600 rounded focus:ring-green-500"
                                            onchange="toggleVaksinLainnya()">
                                        <span class="ml-3 text-gray-700">
                                            {{ $vaksin->nama_vaksin }}
                                            @if($vaksin->deskripsi)
                                                <span class="text-xs text-gray-500 block">{{ $vaksin->deskripsi }}</span>
                                            @endif
                                        </span>
                                    </label>
                                    @endforeach
                                @endif
                                
                                <label class="flex items-start p-3 border border-gray-300 rounded-lg hover:bg-green-50 cursor-pointer transition">
                                    <input type="checkbox" name="jenis_vaksin[]" value="Lainnya" id="vaksinLainnyaCheckbox"
                                        {{ in_array('Lainnya', old('jenis_vaksin', is_array($permohonan->jenis_vaksin) ? $permohonan->jenis_vaksin : [])) ? 'checked' : '' }}
                                        class="mt-1 w-4 h-4 text-green-600 rounded focus:ring-green-500"
                                        onchange="toggleVaksinLainnya()">
                                    <span class="ml-3 text-gray-700">Lainnya</span>
                                </label>
                            </div>
                            
                            <div id="vaksinLainnyaContainer" class="mt-4 {{ in_array('Lainnya', old('jenis_vaksin', is_array($permohonan->jenis_vaksin) ? $permohonan->jenis_vaksin : [])) ? '' : 'hidden' }}">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Sebutkan Jenis Vaksin Lainnya</label>
                                <input type="text" name="vaksin_lainnya_text" id="vaksinLainnyaText" 
                                    value="{{ old('vaksin_lainnya_text', $permohonan->vaksin_lainnya) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" 
                                    placeholder="Contoh: Vaksin COVID-19, Vaksin Polio, dll">
                            </div>
                        </div>
                        
                        @if($permohonan->is_perjalanan == 1)
                        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-xs font-semibold text-blue-800 uppercase mb-2">Informasi Perjalanan Luar Negeri</p>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase">Negara Tujuan</label>
                                    <p class="text-gray-900 font-semibold mt-1">{{ $permohonan->negara_tujuan ?: '-' }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase">Tanggal Keberangkatan</label>
                                    <p class="text-gray-900 font-semibold mt-1">{{ $permohonan->tanggal_berangkat ? $permohonan->tanggal_berangkat->format('d F Y') : '-' }}</p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 mt-2 italic">* Informasi perjalanan tidak dapat diubah</p>
                        </div>
                        @endif
                        
                        <div class="mt-4 flex justify-end gap-2">
                            <button type="button" onclick="toggleEditMode('vaksin')" class="px-6 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg font-semibold">
                                Batal
                            </button>
                            <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold shadow-lg">
                                💾 Simpan Perubahan
                            </button>
                        </div>
                    </form>
                    </div>
                </div>

                <!-- Form Pemeriksaan Admin -->
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
        </div>

        <script>
            function toggleEditMode(section) {
                const viewSection = document.getElementById('view-' + section);
                const editSection = document.getElementById('edit-' + section);
                const btnEdit = document.getElementById('btn-edit-' + section);
                
                if (editSection.classList.contains('hidden')) {
                    // Show edit, hide view
                    viewSection.classList.add('hidden');
                    editSection.classList.remove('hidden');
                    if (btnEdit) btnEdit.classList.add('hidden');
                } else {
                    // Show view, hide edit
                    viewSection.classList.remove('hidden');
                    editSection.classList.add('hidden');
                    if (btnEdit) btnEdit.classList.remove('hidden');
                }
            }

            function toggleVaksinLainnya() {
                const checkbox = document.getElementById('vaksinLainnyaCheckbox');
                const container = document.getElementById('vaksinLainnyaContainer');
                
                if (checkbox && container) {
                    if (checkbox.checked) {
                        container.classList.remove('hidden');
                    } else {
                        container.classList.add('hidden');
                        const textInput = document.getElementById('vaksinLainnyaText');
                        if (textInput) textInput.value = '';
                    }
                }
            }
        </script>
@endsection

