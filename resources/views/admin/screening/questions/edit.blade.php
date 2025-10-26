@extends('layouts.app')
@section('title', 'Edit Pertanyaan Screening')
@section('content')
<div class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow-sm border-b"><div class="max-w-7xl mx-auto px-4 py-4"><span class="text-xl font-bold">Edit Pertanyaan Screening</span></div></nav>
    <main class="max-w-3xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-2xl font-bold mb-6">Edit Pertanyaan</h1>
            @if($errors->any())<div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4"><ul class="list-disc list-inside text-red-600 text-sm">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <form method="POST" action="{{ route('admin.screening.questions.update', $question) }}" class="space-y-6">
                @csrf @method('PUT')
                <div><label class="block text-sm font-medium mb-2">Kategori</label>
                    <select name="category_id" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500">
                        <option value="">-- Tanpa Kategori --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $question->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-medium mb-2">Pertanyaan *</label>
                    <textarea name="pertanyaan" rows="3" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500" required>{{ old('pertanyaan', $question->pertanyaan) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Tipe Jawaban</label>
                    <div class="w-full px-4 py-3 bg-gray-100 border rounded-lg text-gray-700">
                        <strong>Ya / Tidak</strong> dengan Keterangan Tambahan (Opsional)
                    </div>
                    <input type="hidden" name="tipe_jawaban" value="ya_tidak">
                    <p class="text-xs text-gray-500 mt-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Setiap pertanyaan akan memiliki pilihan Ya/Tidak dan field keterangan tambahan yang bersifat opsional.
                    </p>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div><label class="block text-sm font-medium mb-2">Urutan</label><input type="number" name="urutan" value="{{ old('urutan', $question->urutan) }}" min="0" class="w-full px-4 py-3 border rounded-lg"></div>
                    <div><label class="block text-sm font-medium mb-2">Wajib</label><div class="flex items-center h-full"><input type="checkbox" name="wajib" value="1" {{ old('wajib', $question->wajib) ? 'checked' : '' }} class="w-5 h-5 rounded"><label class="ml-2">Ya</label></div></div>
                    <div><label class="block text-sm font-medium mb-2">Status</label><div class="flex items-center h-full"><input type="checkbox" name="aktif" value="1" {{ old('aktif', $question->aktif) ? 'checked' : '' }} class="w-5 h-5 rounded"><label class="ml-2">Aktif</label></div></div>
                </div>
                <div class="flex justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('admin.screening.questions.index') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 rounded-lg">Batal</a>
                    <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg">Update</button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection
