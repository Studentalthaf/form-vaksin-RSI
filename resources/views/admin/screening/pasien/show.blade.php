@extends('layouts.app')
@section('title', 'Hasil Screening Pasien')
@section('content')
<div class="min-h-screen bg-gray-50">
    <nav class="bg-gradient from-red-600 to-pink-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <span class="text-xl font-bold text-white">Hasil Screening Pasien</span>
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

        <!-- Patient & Screening Info -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Informasi Screening</h2>
            <div class="grid md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">Nama Pasien:</span>
                    <span class="font-semibold ml-2">{{ $permohonan->pasien->nama }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Tanggal Screening:</span>
                    <span class="ml-2">{{ $screening->tanggal_screening->format('d/m/Y H:i') }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Petugas:</span>
                    <span class="ml-2">{{ $screening->petugas->nama }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Hasil:</span>
                    @if($screening->hasil_screening === 'aman')
                    <span class="ml-2 px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">AMAN</span>
                    @elseif($screening->hasil_screening === 'perlu_perhatian')
                    <span class="ml-2 px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">PERLU PERHATIAN</span>
                    @else
                    <span class="ml-2 px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">PENDING</span>
                    @endif
                </div>
                @if($screening->dokter_id)
                <div class="md:col-span-2 bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <span class="text-gray-600">Dokter yang Menangani:</span>
                    <span class="ml-2 font-bold text-blue-900">Dr. {{ $screening->dokter->nama }}</span>
                    <span class="ml-2 px-2 py-1 bg-blue-600 text-white rounded-full text-xs font-semibold">
                        {{ strtoupper(str_replace('_', ' ', $screening->status_vaksinasi)) }}
                    </span>
                </div>
                @endif
            </div>
        </div>

        <!-- Assign Dokter Section -->
        @if(!$screening->dokter_id)
        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg shadow-lg p-6 mb-6 border-2 border-purple-300">
            <div class="flex items-start mb-4">
                <div class="p-3 bg-purple-600 rounded-full mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-purple-900 mb-2">Serahkan ke Dokter</h3>
                    <p class="text-purple-700 text-sm mb-4">Pilih dokter yang akan melakukan vaksinasi untuk pasien ini</p>
                    
                    @if(session('success'))
                    <div class="mb-4 bg-green-100 border-l-4 border-green-500 p-3 rounded">
                        <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="mb-4 bg-red-100 border-l-4 border-red-500 p-3 rounded">
                        <p class="text-red-700 text-sm font-medium">{{ $errors->first() }}</p>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('admin.screening.pasien.assign-dokter', $permohonan) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Dokter</label>
                            <select name="dokter_id" required class="w-full px-4 py-3 border-2 border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-gray-800 font-medium">
                                <option value="">-- Pilih Dokter --</option>
                                @foreach($dokterList as $dokter)
                                <option value="{{ $dokter->id }}">Dr. {{ $dokter->nama }} - {{ $dokter->email }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Vaksinasi</label>
                            <input type="date" name="tanggal_vaksinasi" required 
                                min="{{ date('Y-m-d') }}"
                                value="{{ old('tanggal_vaksinasi', date('Y-m-d')) }}"
                                class="w-full px-4 py-3 border-2 border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-gray-800 font-medium">
                            <p class="text-xs text-gray-500 mt-1">Pilih tanggal dilakukannya vaksinasi</p>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-purple-600">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Pasien akan diserahkan ke dokter untuk proses vaksinasi
                            </p>
                            <button type="submit" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition transform hover:scale-105">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                Serahkan ke Dokter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- Screening Answers by Category -->
        @php
            $groupedAnswers = $screening->answers->groupBy(fn($answer) => $answer->question->category_id);
        @endphp

        @foreach($groupedAnswers as $categoryId => $answers)
            @php
                $category = $answers->first()->question->category;
            @endphp
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="border-b pb-3 mb-4">
                    <h3 class="text-lg font-bold text-gray-800">{{ $category->nama_kategori ?? 'Tanpa Kategori' }}</h3>
                    @if($category && $category->deskripsi)
                    <p class="text-sm text-gray-600 mt-1">{{ $category->deskripsi }}</p>
                    @endif
                </div>

                <div class="space-y-4">
                    @foreach($answers as $answer)
                    <div class="border-l-4 {{ $answer->jawaban === 'ya' ? 'border-red-500' : 'border-green-500' }} pl-4 py-2">
                        <p class="font-semibold text-gray-800 mb-2">{{ $answer->question->pertanyaan }}</p>
                        <div class="flex items-center space-x-4 text-sm">
                            <div>
                                <span class="text-gray-600">Jawaban:</span>
                                @if($answer->jawaban === 'ya')
                                <span class="ml-2 px-2 py-1 bg-red-100 text-red-700 rounded font-semibold">{{ strtoupper($answer->jawaban) }}</span>
                                @elseif($answer->jawaban === 'tidak')
                                <span class="ml-2 px-2 py-1 bg-green-100 text-green-700 rounded font-semibold">{{ strtoupper($answer->jawaban) }}</span>
                                @else
                                <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-700 rounded">{{ $answer->jawaban }}</span>
                                @endif
                            </div>
                        </div>
                        @if($answer->keterangan)
                        <div class="mt-2 text-sm">
                            <span class="text-gray-600">Keterangan:</span>
                            <span class="ml-2 text-gray-700 italic">"{{ $answer->keterangan }}"</span>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <!-- Catatan Umum -->
        @if($screening->catatan)
        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded mb-6">
            <h4 class="font-semibold text-blue-900 mb-2">Catatan Umum</h4>
            <p class="text-blue-800">{{ $screening->catatan }}</p>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.screening.pasien.create', $permohonan) }}" 
                class="px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-semibold">
                Edit Screening
            </a>
            <button onclick="window.print()" 
                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">
                Cetak Hasil
            </button>
        </div>
    </main>
</div>

<style>
@media print {
    nav, .flex.justify-end { display: none !important; }
}
</style>
@endsection
