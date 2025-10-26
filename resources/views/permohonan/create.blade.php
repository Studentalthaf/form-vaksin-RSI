<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Permohonan Vaksinasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-green-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-blue-900 mb-2">Form Permohonan Vaksinasi</h1>
            <p class="text-gray-600">Silakan lengkapi data diri Anda untuk pendaftaran vaksinasi</p>
        </div>

        <!-- Form Card -->
        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-blue-600 to-green-600 text-white px-8 py-6">
                <h2 class="text-2xl font-bold">Formulir Pendaftaran</h2>
                <p class="text-blue-100 text-sm mt-1">* Wajib diisi</p>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
            <div class="mx-8 mt-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('permohonan.store') }}" class="p-8">
                @csrf

                <!-- Section: Data Pribadi -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-blue-500">Data Pribadi</h3>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Nama Lengkap -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap *</label>
                            <input type="text" name="nama" value="{{ old('nama') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                placeholder="Masukkan nama lengkap sesuai KTP/Paspor" required>
                        </div>

                        <!-- Nomor Telepon -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon *</label>
                            <input type="tel" name="no_telp" value="{{ old('no_telp') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                placeholder="Contoh: 08123456789" required>
                        </div>

                        <!-- Tempat Lahir -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                placeholder="Kota kelahiran">
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin</label>
                            <select name="jenis_kelamin" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <!-- Pekerjaan -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pekerjaan</label>
                            <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                placeholder="Contoh: Karyawan Swasta">
                        </div>

                        <!-- Alamat -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap</label>
                            <textarea name="alamat" rows="3" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Section: Jenis Vaksin (SELALU MUNCUL) -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-teal-500">Jenis Vaksin yang Diminta *</h3>
                    
                    <div class="grid md:grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Vaksin *</label>
                            <input type="text" name="jenis_vaksin" value="{{ old('jenis_vaksin') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" 
                                placeholder="Contoh: Meningitis, Yellow Fever, Hepatitis B, dll" required>
                            <p class="text-xs text-gray-500 mt-1">💡 Wajib diisi untuk semua jenis vaksinasi</p>
                        </div>
                    </div>
                </div>

                <!-- Section: Jenis Permohonan Vaksin -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-purple-500">Jenis Permohonan Vaksin</h3>
                    
                    <div class="bg-purple-50 border-2 border-purple-300 rounded-lg p-6">
                        <label class="flex items-start cursor-pointer group">
                            <input type="checkbox" name="is_perjalanan" id="is_perjalanan" value="1" 
                                {{ old('is_perjalanan') ? 'checked' : '' }}
                                class="mt-1 w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-2 focus:ring-purple-500">
                            <div class="ml-3">
                                <span class="text-base font-bold text-purple-900 group-hover:text-purple-700">
                                    ✈️ Vaksin untuk Perjalanan ke Luar Negeri
                                </span>
                                <p class="text-sm text-purple-700 mt-1">
                                    Centang jika Anda memerlukan vaksin untuk keperluan perjalanan internasional (umroh, haji, wisata, dll)
                                </p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Section: Data Perjalanan & Vaksinasi (Conditional) -->
                <div class="mb-8" id="section-perjalanan" style="display: none;">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-green-500">Data Perjalanan Luar Negeri</h3>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Nomor Paspor (Wajib untuk Perjalanan) -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor Paspor *
                                <span class="text-xs text-red-600 font-normal">(Wajib untuk perjalanan luar negeri)</span>
                            </label>
                            <input type="text" name="nomor_paspor" id="nomor_paspor" value="{{ old('nomor_paspor') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                placeholder="Contoh: A1234567">
                        </div>

                        <!-- Negara Tujuan -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Negara Tujuan</label>
                            <input type="text" name="negara_tujuan" value="{{ old('negara_tujuan') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                placeholder="Contoh: Arab Saudi">
                        </div>

                        <!-- Tanggal Berangkat -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Berangkat</label>
                            <input type="date" name="tanggal_berangkat" value="{{ old('tanggal_berangkat') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <!-- Nama Travel -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Travel/Agen (jika ada)</label>
                            <input type="text" name="nama_travel" value="{{ old('nama_travel') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                placeholder="Nama travel penyelenggara">
                        </div>

                        <!-- Alamat Travel -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Travel (jika ada)</label>
                            <input type="text" name="alamat_travel" value="{{ old('alamat_travel') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                placeholder="Alamat kantor travel">
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Setelah mengirim permohonan, kami akan menghubungi Anda melalui nomor telepon yang didaftarkan untuk konfirmasi jadwal vaksinasi.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ url('/') }}" class="px-8 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-green-600 hover:from-blue-700 hover:to-green-700 text-white font-semibold rounded-lg shadow-lg transition">
                        Kirim Permohonan
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer Info -->
        <div class="text-center mt-8 text-gray-600 text-sm">
            <p>Untuk informasi lebih lanjut, silakan hubungi kami di <strong>0821-xxxx-xxxx</strong></p>
        </div>
    </div>

    <script>
        // Toggle section perjalanan based on checkbox
        document.getElementById('is_perjalanan').addEventListener('change', function() {
            const sectionPerjalanan = document.getElementById('section-perjalanan');
            const inputs = sectionPerjalanan.querySelectorAll('input, textarea, select');
            const nomorPaspor = document.getElementById('nomor_paspor');
            
            if (this.checked) {
                sectionPerjalanan.style.display = 'block';
                // Set nomor paspor menjadi required
                if (nomorPaspor) {
                    nomorPaspor.required = true;
                }
                // Smooth scroll to section
                setTimeout(() => {
                    sectionPerjalanan.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            } else {
                sectionPerjalanan.style.display = 'none';
                // Remove required dari nomor paspor
                if (nomorPaspor) {
                    nomorPaspor.required = false;
                }
                // Clear all inputs when hidden
                inputs.forEach(input => {
                    if (input.type === 'checkbox' || input.type === 'radio') {
                        input.checked = false;
                    } else {
                        input.value = '';
                    }
                });
            }
        });

        // Check on page load if old value exists
        window.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('is_perjalanan');
            const nomorPaspor = document.getElementById('nomor_paspor');
            
            if (checkbox.checked) {
                document.getElementById('section-perjalanan').style.display = 'block';
                if (nomorPaspor) {
                    nomorPaspor.required = true;
                }
            }
        });
    </script>
</body>
</html>
