@extends('layouts.app')

@section('title', 'Tambah Kategori - Admin Panel')

@section('content')
<div class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </a>
                    <span class="ml-3 text-xl font-bold text-gray-800">Tambah Kategori</span>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-800">{{ Auth::user()->nama }}</p>
                        <p class="text-xs text-red-600 font-semibold">Administrator</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition duration-200">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Kategori Baru</h1>

            @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <ul class="list-disc list-inside text-red-600 text-sm">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.screening.categories.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_kategori" id="nama_kategori" value="{{ old('nama_kategori') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition" placeholder="Riwayat Penyakit" required>
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition" placeholder="Deskripsi kategori...">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="urutan" class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                        <input type="number" name="urutan" id="urutan" value="{{ old('urutan', 0) }}" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <div class="flex items-center h-full">
                            <input type="checkbox" name="aktif" id="aktif" value="1" {{ old('aktif', true) ? 'checked' : '' }} class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <label for="aktif" class="ml-2 text-sm text-gray-700">Aktif</label>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('admin.screening.categories.index') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition duration-200">Batal</a>
                    <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition duration-200">Simpan</button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection
