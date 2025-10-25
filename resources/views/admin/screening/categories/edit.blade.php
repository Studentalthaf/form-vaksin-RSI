@extends('layouts.app')
@section('title', 'Edit Kategori - Admin Panel')
@section('content')
<div class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <span class="text-xl font-bold text-gray-800">Edit Kategori</span>
                <div class="flex items-center space-x-4">
                    <p class="text-sm font-medium text-gray-800">{{ Auth::user()->nama }}</p>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg">Logout</button></form>
                </div>
            </div>
        </div>
    </nav>
    <main class="max-w-3xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Kategori: {{ $category->nama_kategori }}</h1>
            @if($errors->any())<div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded"><ul class="list-disc list-inside text-red-600 text-sm">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <form method="POST" action="{{ route('admin.screening.categories.update', $category) }}" class="space-y-6">
                @csrf @method('PUT')
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori *</label><input type="text" name="nama_kategori" value="{{ old('nama_kategori', $category->nama_kategori) }}" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500" required></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label><textarea name="deskripsi" rows="3" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500">{{ old('deskripsi', $category->deskripsi) }}</textarea></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label><input type="number" name="urutan" value="{{ old('urutan', $category->urutan) }}" min="0" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-2">Status</label><div class="flex items-center h-full"><input type="checkbox" name="aktif" value="1" {{ old('aktif', $category->aktif) ? 'checked' : '' }} class="w-5 h-5 text-red-600 rounded"><label class="ml-2 text-sm">Aktif</label></div></div>
                </div>
                <div class="flex justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('admin.screening.categories.index') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg">Batal</a>
                    <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg">Update</button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection
