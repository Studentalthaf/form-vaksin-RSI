@extends('layouts.app')
@section('title', 'Tambah Pertanyaan Screening')
@section('content')
<div class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow-sm border-b"><div class="max-w-7xl mx-auto px-4 py-4"><span class="text-xl font-bold">Tambah Pertanyaan Screening</span></div></nav>
    <main class="max-w-3xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-2xl font-bold mb-6">Tambah Pertanyaan Baru</h1>
            @if($errors->any())<div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4"><ul class="list-disc list-inside text-red-600 text-sm">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <form method="POST" action="{{ route('admin.screening.questions.store') }}" class="space-y-6">
                @csrf
                <div><label class="block text-sm font-medium mb-2">Kategori</label>
                    <select name="category_id" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500">
                        <option value="">-- Tanpa Kategori --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-medium mb-2">Pertanyaan *</label>
                    <textarea name="pertanyaan" rows="3" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500" required>{{ old('pertanyaan') }}</textarea>
                </div>
                <div><label class="block text-sm font-medium mb-2">Tipe Jawaban *</label>
                    <select name="tipe_jawaban" id="tipe_jawaban" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500" required>
                        <option value="ya_tidak" {{ old('tipe_jawaban') == 'ya_tidak' ? 'selected' : '' }}>Ya/Tidak</option>
                        <option value="pilihan_ganda" {{ old('tipe_jawaban') == 'pilihan_ganda' ? 'selected' : '' }}>Pilihan Ganda</option>
                        <option value="text" {{ old('tipe_jawaban') == 'text' ? 'selected' : '' }}>Text</option>
                    </select>
                </div>
                <div id="pilihan_container" class="hidden"><label class="block text-sm font-medium mb-2">Pilihan Jawaban (satu per baris)</label>
                    <textarea name="pilihan_jawaban[]" rows="4" class="w-full px-4 py-3 border rounded-lg" placeholder="Pilihan 1&#10;Pilihan 2&#10;Pilihan 3"></textarea>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div><label class="block text-sm font-medium mb-2">Urutan</label><input type="number" name="urutan" value="{{ old('urutan', 0) }}" min="0" class="w-full px-4 py-3 border rounded-lg"></div>
                    <div><label class="block text-sm font-medium mb-2">Wajib</label><div class="flex items-center h-full"><input type="checkbox" name="wajib" value="1" {{ old('wajib', true) ? 'checked' : '' }} class="w-5 h-5 rounded"><label class="ml-2">Ya</label></div></div>
                    <div><label class="block text-sm font-medium mb-2">Status</label><div class="flex items-center h-full"><input type="checkbox" name="aktif" value="1" {{ old('aktif', true) ? 'checked' : '' }} class="w-5 h-5 rounded"><label class="ml-2">Aktif</label></div></div>
                </div>
                <div class="flex justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('admin.screening.questions.index') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 rounded-lg">Batal</a>
                    <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </main>
</div>
<script>
document.getElementById('tipe_jawaban').addEventListener('change', function() {
    const pilihanContainer = document.getElementById('pilihan_container');
    if (this.value === 'pilihan_ganda') {
        pilihanContainer.classList.remove('hidden');
    } else {
        pilihanContainer.classList.add('hidden');
    }
});
// Check on page load
if (document.getElementById('tipe_jawaban').value === 'pilihan_ganda') {
    document.getElementById('pilihan_container').classList.remove('hidden');
}
</script>
@endsection
