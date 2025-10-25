@extends('layouts.app')

@section('title', 'Kelola Pertanyaan Screening - Admin Panel')

@section('content')
<div class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </a>
                    <span class="ml-3 text-xl font-bold text-gray-800">Pertanyaan Screening</span>
                </div>
                <div class="flex items-center space-x-4">
                    <p class="text-sm font-medium text-gray-800">{{ Auth::user()->nama }}</p>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg">Logout</button></form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))<div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg"><p class="text-green-700">{{ session('success') }}</p></div>@endif

        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Pertanyaan Screening</h1>
                <p class="text-gray-600 mt-2">Kelola pertanyaan untuk screening pasien vaksin</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.screening.categories.index') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">Kelola Kategori</a>
                <a href="{{ route('admin.screening.questions.create') }}" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Tambah Pertanyaan
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pertanyaan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe Jawaban</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Urutan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($questions as $index => $question)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4"><div class="text-sm font-medium text-gray-900">{{ Str::limit($question->pertanyaan, 80) }}</div></td>
                        <td class="px-6 py-4 text-sm">
                            @if($question->category)
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">{{ $question->category->nama_kategori }}</span>
                            @else
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Tanpa Kategori</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($question->tipe_jawaban === 'ya_tidak')
                            <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Ya/Tidak</span>
                            @elseif($question->tipe_jawaban === 'pilihan_ganda')
                            <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">Pilihan Ganda</span>
                            @else
                            <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Text</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $question->urutan }}</td>
                        <td class="px-6 py-4">
                            @if($question->aktif)
                            <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">Aktif</span>
                            @else
                            <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <a href="{{ route('admin.screening.questions.edit', $question) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.screening.questions.destroy', $question) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus pertanyaan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-8 text-center text-gray-500">Belum ada pertanyaan screening</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</div>
@endsection
