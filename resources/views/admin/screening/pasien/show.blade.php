@extends('layouts.app')
@section('title', 'Hasil Screening Pasien')
@section('content')
<div class="min-h-screen bg-gray-50">
    <nav class="bg-gradient-to-r from-red-600 to-pink-600 shadow-lg">
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
            </div>
        </div>

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
