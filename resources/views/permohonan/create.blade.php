<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Permohonan Vaksinasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            <form method="POST" action="{{ route('permohonan.store') }}" class="p-8" id="formPermohonan" enctype="multipart/form-data">
                @csrf

                <!-- Section: Data Pribadi -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-blue-500">Data Pasien</h3>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Status Pasien -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status Pasien *</label>
                            <div class="flex gap-6">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="status_pasien" value="baru" 
                                        {{ old('status_pasien', 'baru') == 'baru' ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 focus:ring-blue-500" 
                                        onchange="toggleNomorRM()" required>
                                    <span class="ml-2 text-gray-700">Pasien Baru</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="status_pasien" value="lama" 
                                        {{ old('status_pasien') == 'lama' ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 focus:ring-blue-500"
                                        onchange="toggleNomorRM()">
                                    <span class="ml-2 text-gray-700">Pasien Lama</span>
                                </label>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Pilih "Pasien Baru" jika ini kunjungan pertama Anda</p>
                        </div>

                        <!-- Nomor RM (Hidden by default, shown for Pasien Lama) -->
                        <div class="md:col-span-2 hidden" id="nomorRMContainer">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Rekam Medis (RM) RSI</label>
                            <input type="text" name="nomor_rm" id="nomor_rm" value="{{ old('nomor_rm') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                placeholder="Masukkan nomor RM atau ketik (-) jika lupa">
                            <p class="text-sm text-gray-500 mt-1">Jika pernah vaksin atau berobat di RSI, masukkan nomor RM. Boleh diisi (-) jika lupa</p>
                        </div>

                        <!-- Perjalanan Luar Negeri -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Apakah Vaksin untuk Perjalanan Luar Negeri? *</label>
                            <div class="flex gap-6">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="is_perjalanan" value="1" 
                                        {{ old('is_perjalanan') == '1' ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 focus:ring-blue-500" 
                                        onchange="togglePerjalananRadio()" required>
                                    <span class="ml-2 text-gray-700">YA</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="is_perjalanan" value="0" 
                                        {{ old('is_perjalanan', '0') == '0' ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 focus:ring-blue-500"
                                        onchange="togglePerjalananRadio()">
                                    <span class="ml-2 text-gray-700">TIDAK</span>
                                </label>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Pilih "YA" jika vaksin diperlukan untuk keperluan perjalanan internasional</p>
                        </div>

                        <!-- NIK -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">NIK (Nomor Induk Kependudukan) *</label>
                            <input type="text" name="nik" id="nik" value="{{ old('nik') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                placeholder="Masukkan 16 digit NIK sesuai KTP" 
                                maxlength="16"
                                required>
                            <p class="text-sm text-gray-500 mt-1">Masukkan NIK sesuai KTP Anda</p>
                        </div>

                        <!-- Nama Lengkap -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap *</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                placeholder="Masukkan nama lengkap sesuai KTP/Paspor" required>
                        </div>

                        <!-- Nomor Telepon -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon *</label>
                            <input type="tel" name="no_telp" id="no_telp" value="{{ old('no_telp') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                placeholder="Contoh: 08123456789" required>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                placeholder="Contoh: nama@email.com" required>
                            <p class="text-sm text-gray-500 mt-1">Isi email anda dengan benar</p>
                        </div>

                        <!-- Tempat Lahir -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                placeholder="Kota kelahiran">
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <!-- Pekerjaan -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pekerjaan</label>
                            <input type="text" name="pekerjaan" id="pekerjaan" value="{{ old('pekerjaan') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                placeholder="Contoh: Pegawai Swasta">
                        </div>

                        <!-- Alamat Lengkap -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap</label>
                            <textarea name="alamat" id="alamat" rows="3" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                        </div>

                        <!-- Upload Foto KTP -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Foto KTP *</label>
                            <input type="file" name="foto_ktp" id="foto_ktp" accept="image/jpeg,image/jpg,image/png,image/heic,image/heif,application/pdf,.jpg,.jpeg,.png,.heic,.heif,.pdf" capture="environment"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" 
                                required
                                onchange="validateFileSize(this, 5, 'foto_ktp')">
                            <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, PDF, atau HEIC/HEIF (iPhone). Maksimal 5MB. Bisa ambil foto langsung dari kamera.</p>
                            <p class="text-sm text-red-600 mt-1 hidden" id="error_foto_ktp"></p>
                            <p class="text-sm text-green-600 mt-1 hidden" id="info_foto_ktp"></p>
                            
                            <!-- Contoh KTP -->
                            <div class="mt-4 p-4 bg-blue-50 border-2 border-blue-200 rounded-lg">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold text-blue-900 mb-2">Contoh Format KTP yang Benar:</h4>
                                        <div class="bg-white p-2 rounded-lg border border-blue-300 shadow-sm">
                                            <img src="{{ asset('storage/asset/KTP.png') }}" 
                                                 alt="Contoh KTP" 
                                                 class="w-full max-w-md mx-auto rounded-md shadow-md border border-gray-300"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <div style="display:none;" class="text-center text-gray-500 text-sm py-4">
                                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                                </svg>
                                                <p>Contoh gambar KTP</p>
                                            </div>
                                        </div>
                                        <p class="text-xs text-blue-700 mt-2">
                                            ✓ Pastikan foto KTP jelas dan tidak buram<br>
                                            ✓ Semua informasi di KTP dapat terbaca dengan baik<br>
                                            ✓ Tidak ada pantulan cahaya yang menutupi data
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Data Perjalanan (Hidden by default) -->
                <div id="perjalananContainer" class="mb-8 hidden">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-yellow-500">Data Perjalanan</h3>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Nomor Paspor -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Paspor *</label>
                            <input type="text" name="nomor_paspor" id="nomor_paspor" value="{{ old('nomor_paspor') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                                placeholder="Contoh: A1234567">
                        </div>

                        <!-- Negara Tujuan -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Negara Tujuan *</label>
                            <input type="text" name="negara_tujuan" id="negara_tujuan" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                                placeholder="Contoh: Arab Saudi">
                        </div>

                        <!-- Tanggal Berangkat -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Keberangkatan *</label>
                            <input type="date" name="tanggal_berangkat" id="tanggal_berangkat" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        </div>

                        <!-- Nama Travel -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Biro Perjalanan</label>
                            <input type="text" name="nama_travel" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                                placeholder="Contoh: PT. Travel Sejahtera">
                        </div>

                        <!-- Alamat Travel -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Biro Perjalanan</label>
                            <input type="text" name="alamat_travel" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                                placeholder="Alamat lengkap travel">
                        </div>

                        <!-- Upload Foto Paspor Halaman Pertama -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Passport Halaman Pertama *</label>
                            <input type="file" name="passport_halaman_pertama" id="passport_halaman_pertama" accept="image/jpeg,image/jpg,image/png,image/heic,image/heif,application/pdf,.jpg,.jpeg,.png,.heic,.heif,.pdf" capture="environment"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100"
                                onchange="validateFileSize(this, 5, 'passport_halaman_pertama')">
                            <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, PDF, atau HEIC/HEIF (iPhone). Maksimal 5MB. Bisa ambil foto langsung dari kamera.</p>
                            <p class="text-sm text-red-600 mt-1 hidden" id="error_passport_halaman_pertama"></p>
                            <p class="text-sm text-green-600 mt-1 hidden" id="info_passport_halaman_pertama"></p>
                            
                            <!-- Contoh Passport Halaman 1 -->
                            <div class="mt-4 p-4 bg-yellow-50 border-2 border-yellow-200 rounded-lg">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold text-yellow-900 mb-2">Contoh Passport Halaman Pertama:</h4>
                                        <div class="bg-white p-2 rounded-lg border border-yellow-300 shadow-sm">
                                            <img src="{{ asset('storage/asset/pasport.png') }}" 
                                                 alt="Contoh Passport Halaman 1" 
                                                 class="w-full max-w-md mx-auto rounded-md shadow-md border border-gray-300"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <div style="display:none;" class="text-center text-gray-500 text-sm py-4">
                                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <p>Contoh gambar Passport Halaman 1</p>
                                            </div>
                                        </div>
                                        <p class="text-xs text-yellow-700 mt-2">
                                            ✓ Halaman dengan foto dan data diri<br>
                                            ✓ Pastikan nomor passport terlihat jelas<br>
                                            ✓ Foto tidak buram dan semua teks terbaca
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Foto Paspor Halaman Kedua -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Passport Halaman Kedua *</label>
                            <input type="file" name="passport_halaman_kedua" id="passport_halaman_kedua" accept="image/jpeg,image/jpg,image/png,image/heic,image/heif,application/pdf,.jpg,.jpeg,.png,.heic,.heif,.pdf" capture="environment"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100"
                                onchange="validateFileSize(this, 5, 'passport_halaman_kedua')">
                            <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, PDF, atau HEIC/HEIF (iPhone). Maksimal 5MB. Bisa ambil foto langsung dari kamera.</p>
                            <p class="text-sm text-red-600 mt-1 hidden" id="error_passport_halaman_kedua"></p>
                            <p class="text-sm text-green-600 mt-1 hidden" id="info_passport_halaman_kedua"></p>
                            
                            <!-- Contoh Passport Halaman 2 -->
                            <div class="mt-4 p-4 bg-yellow-50 border-2 border-yellow-200 rounded-lg">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold text-yellow-900 mb-2">Contoh Passport Halaman Kedua:</h4>
                                        <div class="bg-white p-2 rounded-lg border border-yellow-300 shadow-sm">
                                            <img src="{{ asset('storage/asset/pasport2.png') }}" 
                                                 alt="Contoh Passport Halaman 2" 
                                                 class="w-full max-w-md mx-auto rounded-md shadow-md border border-gray-300"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <div style="display:none;" class="text-center text-gray-500 text-sm py-4">
                                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <p>Contoh gambar Passport Halaman 2</p>
                                            </div>
                                        </div>
                                        <p class="text-xs text-yellow-700 mt-2">
                                            ✓ Halaman dengan informasi tambahan<br>
                                            ✓ Pastikan semua teks dapat terbaca<br>
                                            ✓ Tidak ada bagian yang terpotong
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Jenis Vaksin -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-purple-500">Jenis Vaksinasi</h3>
                    
                    <!-- Jenis Vaksin -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-4">Jenis Vaksin yang Dibutuhkan *</label>
                        <p class="text-sm text-gray-600 mb-3">Pilih satu atau lebih vaksin yang Anda butuhkan:</p>
                        
                        <div class="grid md:grid-cols-2 gap-3">
                            @if(isset($vaksins) && $vaksins->count() > 0)
                                @foreach($vaksins as $vaksin)
                                <label class="flex items-start p-3 border border-gray-300 rounded-lg hover:bg-purple-50 cursor-pointer transition">
                                    <input type="checkbox" name="jenis_vaksin[]" value="{{ $vaksin->nama_vaksin }}" 
                                        {{ in_array($vaksin->nama_vaksin, old('jenis_vaksin', [])) ? 'checked' : '' }}
                                        class="mt-1 w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                                    <span class="ml-3 text-gray-700">
                                        {{ $vaksin->nama_vaksin }}
                                        @if($vaksin->deskripsi)
                                            <span class="text-xs text-gray-500 block">{{ $vaksin->deskripsi }}</span>
                                        @endif
                                    </span>
                                </label>
                                @endforeach
                            @else
                                {{-- Fallback jika belum ada data vaksin di database --}}
                                <label class="flex items-start p-3 border border-gray-300 rounded-lg hover:bg-purple-50 cursor-pointer transition">
                                    <input type="checkbox" name="jenis_vaksin[]" value="Yellow Fever" 
                                        class="mt-1 w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                                    <span class="ml-3 text-gray-700">Yellow Fever (Demam Kuning)</span>
                                </label>
                                <label class="flex items-start p-3 border border-gray-300 rounded-lg hover:bg-purple-50 cursor-pointer transition">
                                    <input type="checkbox" name="jenis_vaksin[]" value="Meningitis" 
                                        class="mt-1 w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                                    <span class="ml-3 text-gray-700">Meningitis (Meningokokus)</span>
                                </label>
                                <label class="flex items-start p-3 border border-gray-300 rounded-lg hover:bg-purple-50 cursor-pointer transition">
                                    <input type="checkbox" name="jenis_vaksin[]" value="Hepatitis A" 
                                        class="mt-1 w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                                    <span class="ml-3 text-gray-700">Hepatitis A</span>
                                </label>
                                <label class="flex items-start p-3 border border-gray-300 rounded-lg hover:bg-purple-50 cursor-pointer transition">
                                    <input type="checkbox" name="jenis_vaksin[]" value="Hepatitis B" 
                                        class="mt-1 w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                                    <span class="ml-3 text-gray-700">Hepatitis B</span>
                                </label>
                                <label class="flex items-start p-3 border border-gray-300 rounded-lg hover:bg-purple-50 cursor-pointer transition">
                                    <input type="checkbox" name="jenis_vaksin[]" value="Typhoid" 
                                        class="mt-1 w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                                    <span class="ml-3 text-gray-700">Typhoid (Tifus)</span>
                                </label>
                                <label class="flex items-start p-3 border border-gray-300 rounded-lg hover:bg-purple-50 cursor-pointer transition">
                                    <input type="checkbox" name="jenis_vaksin[]" value="Rabies" 
                                        class="mt-1 w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                                    <span class="ml-3 text-gray-700">Rabies</span>
                                </label>
                                <label class="flex items-start p-3 border border-gray-300 rounded-lg hover:bg-purple-50 cursor-pointer transition">
                                    <input type="checkbox" name="jenis_vaksin[]" value="Japanese Encephalitis" 
                                        class="mt-1 w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                                    <span class="ml-3 text-gray-700">Japanese Encephalitis</span>
                                </label>
                                <label class="flex items-start p-3 border border-gray-300 rounded-lg hover:bg-purple-50 cursor-pointer transition">
                                    <input type="checkbox" name="jenis_vaksin[]" value="Influenza" 
                                        class="mt-1 w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                                    <span class="ml-3 text-gray-700">Influenza</span>
                                </label>
                                <label class="flex items-start p-3 border border-gray-300 rounded-lg hover:bg-purple-50 cursor-pointer transition">
                                    <input type="checkbox" name="jenis_vaksin[]" value="MMR" 
                                        class="mt-1 w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                                    <span class="ml-3 text-gray-700">MMR (Campak, Gondongan, Rubella)</span>
                                </label>
                            @endif
                            
                            {{-- Opsi Lainnya selalu ada --}}
                            <label class="flex items-start p-3 border border-gray-300 rounded-lg hover:bg-purple-50 cursor-pointer transition">
                                <input type="checkbox" name="jenis_vaksin[]" value="Lainnya" id="vaksinLainnya"
                                    {{ in_array('Lainnya', old('jenis_vaksin', [])) ? 'checked' : '' }}
                                    class="mt-1 w-4 h-4 text-purple-600 rounded focus:ring-purple-500"
                                    onchange="toggleVaksinLainnyaInput()">
                                <span class="ml-3 text-gray-700">Lainnya</span>
                            </label>
                        </div>
                        
                        <!-- Input field untuk vaksin lainnya (hidden by default) -->
                        <div id="vaksinLainnyaContainer" class="mt-4 hidden">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Sebutkan Jenis Vaksin Lainnya *
                            </label>
                            <input type="text" name="vaksin_lainnya_text" id="vaksinLainnyaText" 
                                value="{{ old('vaksin_lainnya_text') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                placeholder="Contoh: Vaksin COVID-19, Vaksin Polio, dll">
                            <p class="text-sm text-gray-500 mt-1">Tuliskan nama vaksin yang Anda butuhkan</p>
                        </div>
                        
                        <p class="text-sm text-red-600 mt-2" id="vaksinError" style="display: none;">
                            Minimal pilih satu jenis vaksin
                        </p>
                    </div>

                </div>


                <!-- reCAPTCHA Verification (hanya di production) -->
                @php
                    $isLocal = config('app.env') === 'local';
                @endphp
                @if(!$isLocal)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">Verifikasi Keamanan</h3>
                    <div class="flex flex-col items-center">
                        <div class="p-4 bg-gray-50 rounded-lg border-2 border-gray-300">
                            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                        </div>
                        @error('g-recaptcha-response')
                        <div class="mt-3 text-sm text-red-600 text-center font-semibold bg-red-50 px-4 py-2 rounded-lg">
                            ⚠️ {{ $message }}
                        </div>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500 text-center">Centang kotak "Saya bukan robot" untuk melanjutkan</p>
                    </div>
                </div>
                @endif

                <!-- Total File Size Info -->
                <div id="totalSizeInfo" class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg hidden">
                    <p class="text-sm text-blue-800">
                        <strong>Total ukuran file:</strong> <span id="totalSizeText">0 MB</span>
                        <span class="text-xs text-blue-600 ml-2">(Maksimal 20MB untuk semua file)</span>
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end gap-4 pt-6 border-t">
                    <button type="submit" id="submitBtn"
                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-green-600 text-white font-bold rounded-lg hover:from-blue-700 hover:to-green-700 transition duration-200 shadow-lg">
                        Kirim Permohonan
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Footer -->
        <div class="max-w-4xl mx-auto mt-6 text-center text-gray-600 text-sm">
            <p>Data Anda akan dijaga kerahasiaannya dan hanya digunakan untuk keperluan vaksinasi</p>
        </div>
    </div>

    <!-- NIK Warning Modal -->
    <div id="nikWarningModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-lg mx-4">
            <!-- Warning Icon -->
            <div class="flex justify-center mb-4">
                <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Warning Title -->
            <h3 class="text-2xl font-bold text-gray-800 mb-3 text-center">Permohonan Sedang Diproses</h3>
            
            <!-- Warning Message -->
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-4 rounded">
                <p class="text-gray-700 mb-2">
                    <strong>NIK:</strong> <span id="warningNik" class="font-mono"></span>
                </p>
                <p class="text-gray-700 mb-2">
                    <strong>Nama:</strong> <span id="warningNama"></span>
                </p>
                <p class="text-gray-700 mb-2">
                    <strong>Tanggal Daftar:</strong> <span id="warningTanggal"></span>
                </p>
                <p class="text-gray-700 mb-2">
                    <strong>Status:</strong> <span id="warningStatus" class="px-2 py-1 bg-yellow-200 text-yellow-800 rounded text-sm"></span>
                </p>
            </div>
            
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                <p class="text-red-800 text-sm leading-relaxed">
                    <strong>⚠️ Perhatian!</strong><br>
                    Anda memiliki <strong><span id="warningPendingCount">1</span> permohonan</strong> yang masih dalam proses verifikasi oleh dokter. 
                    Mohon tunggu hingga permohonan sebelumnya selesai diproses sebelum mendaftar kembali.
                </p>
            </div>
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-blue-800 text-sm">
                    <strong>ℹ️ Informasi:</strong><br>
                    Untuk kejelasan status permohonan Anda, silakan menghubungi admin Rumah Sakit.
                </p>
            </div>
            
            <!-- Close Button -->
            <div class="flex justify-center">
                <button type="button" onclick="closeNikWarningModal()" 
                    class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition duration-200 shadow-lg">
                    Saya Mengerti
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md mx-4 text-center">
            <!-- Spinner Animation -->
            <div class="flex justify-center mb-6">
                <div class="relative">
                    <div class="w-20 h-20 border-8 border-blue-200 rounded-full"></div>
                    <div class="w-20 h-20 border-8 border-blue-600 rounded-full animate-spin border-t-transparent absolute top-0 left-0"></div>
                </div>
            </div>
            
            <!-- Loading Text -->
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Mengirim Permohonan...</h3>
            <p class="text-gray-600 mb-4">Mohon tunggu, data Anda sedang diproses</p>
            
            <!-- Progress Steps -->
            <div class="space-y-2 text-left">
                <div class="flex items-center text-sm text-gray-700">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Memvalidasi data formulir</span>
                </div>
                <div class="flex items-center text-sm text-gray-700">
                    <div class="w-5 h-5 border-2 border-blue-600 border-t-transparent rounded-full animate-spin mr-2"></div>
                    <span>Mengunggah dokumen</span>
                </div>
                <div class="flex items-center text-sm text-gray-400">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
                    </svg>
                    <span>Menyimpan ke database</span>
                </div>
            </div>
            
            <!-- Warning -->
            <div class="mt-6 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-xs text-yellow-800">
                    <strong>⚠️ Jangan tutup halaman ini</strong><br>
                    Proses upload sedang berlangsung
                </p>
            </div>
        </div>
    </div>

    <script>
        // File size validation
        const MAX_FILE_SIZE_MB = 5; // Per file maksimal 5MB
        const MAX_TOTAL_SIZE_MB = 20; // Total maksimal 20MB
        const MAX_FILE_SIZE_BYTES = MAX_FILE_SIZE_MB * 1024 * 1024;
        const MAX_TOTAL_SIZE_BYTES = MAX_TOTAL_SIZE_MB * 1024 * 1024;

        // Format bytes to readable size (Bahasa Indonesia)
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Byte';
            const k = 1024;
            const sizes = ['Byte', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        // Validate individual file size and type
        function validateFileSize(input, maxSizeMB, fieldId) {
            const file = input.files[0];
            const errorElement = document.getElementById('error_' + fieldId);
            const infoElement = document.getElementById('info_' + fieldId);
            
            // Hide previous messages
            if (errorElement) {
                errorElement.classList.add('hidden');
                errorElement.textContent = '';
            }
            if (infoElement) {
                infoElement.classList.add('hidden');
                infoElement.textContent = '';
            }

            if (file) {
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/heic', 'image/heif', 'application/pdf'];
                const allowedExtensions = ['.jpg', '.jpeg', '.png', '.heic', '.heif', '.pdf'];
                const fileName = file.name.toLowerCase();
                const fileType = file.type.toLowerCase();
                
                // Check by MIME type or extension (for HEIC/HEIF which might not have MIME type)
                const isValidType = allowedTypes.includes(fileType) || 
                                   allowedExtensions.some(ext => fileName.endsWith(ext));
                
                if (!isValidType) {
                    input.value = '';
                    if (errorElement) {
                        errorElement.textContent = `❌ Format file tidak didukung! Format yang diizinkan: JPG, PNG, PDF, HEIC, atau HEIF`;
                        errorElement.classList.remove('hidden');
                    }
                    alert(`⚠️ Format File Tidak Didukung!\n\nFile "${file.name}" tidak dalam format yang diizinkan.\n\nFormat yang diizinkan:\n- JPG/JPEG\n- PNG\n- PDF\n- HEIC/HEIF (iPhone)\n\nSilakan pilih file dengan format yang benar.`);
                    updateTotalSize();
                    return false;
                }
                
                const fileSizeMB = file.size / (1024 * 1024);
                
                if (file.size > maxSizeMB * 1024 * 1024) {
                    // File too large
                    input.value = ''; // Clear the input
                    if (errorElement) {
                        errorElement.textContent = `❌ File terlalu besar! Ukuran: ${formatFileSize(file.size)}, Maksimal: ${maxSizeMB}MB`;
                        errorElement.classList.remove('hidden');
                    }
                    alert(`⚠️ File "${file.name}" Terlalu Besar!\n\nUkuran file: ${formatFileSize(file.size)}\nMaksimal yang diizinkan: ${maxSizeMB}MB\n\nSilakan pilih file yang lebih kecil atau kompres file terlebih dahulu.`);
                    updateTotalSize();
                    return false;
                } else {
                    // File OK
                    if (infoElement) {
                        const fileTypeText = fileName.endsWith('.heic') || fileName.endsWith('.heif') ? 'HEIC/HEIF' : 
                                            fileName.endsWith('.pdf') ? 'PDF' : 'Gambar';
                        infoElement.textContent = `✅ File ${fileTypeText} sesuai: ${formatFileSize(file.size)}`;
                        infoElement.classList.remove('hidden');
                    }
                    updateTotalSize();
                    return true;
                }
            }
            
            updateTotalSize();
            return true;
        }

        // Calculate and display total file size
        function updateTotalSize() {
            const files = [
                document.getElementById('foto_ktp'),
                document.getElementById('passport_halaman_pertama'),
                document.getElementById('passport_halaman_kedua')
            ];

            let totalSize = 0;
            let fileCount = 0;

            files.forEach(input => {
                if (input && input.files[0]) {
                    totalSize += input.files[0].size;
                    fileCount++;
                }
            });

            const totalSizeInfo = document.getElementById('totalSizeInfo');
            const totalSizeText = document.getElementById('totalSizeText');
            const submitBtn = document.getElementById('submitBtn');

            if (totalSize > 0) {
                const totalSizeMB = totalSize / (1024 * 1024);
                
                if (totalSizeInfo) {
                    totalSizeInfo.classList.remove('hidden');
                }
                
                if (totalSizeText) {
                    totalSizeText.textContent = formatFileSize(totalSize);
                    
                    // Change color based on size
                    if (totalSizeMB > MAX_TOTAL_SIZE_MB) {
                        totalSizeText.className = 'text-red-600 font-bold';
                        totalSizeInfo.className = 'mb-4 p-3 bg-red-50 border border-red-300 rounded-lg';
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        }
                    } else if (totalSizeMB > MAX_TOTAL_SIZE_MB * 0.8) {
                        totalSizeText.className = 'text-yellow-600 font-bold';
                        totalSizeInfo.className = 'mb-4 p-3 bg-yellow-50 border border-yellow-300 rounded-lg';
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    } else {
                        totalSizeText.className = 'text-blue-600 font-bold';
                        totalSizeInfo.className = 'mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg';
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    }
                }
            } else {
                if (totalSizeInfo) {
                    totalSizeInfo.classList.add('hidden');
                }
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }
        }

        // Toggle Nomor RM based on status pasien
        function toggleNomorRM() {
            const statusPasien = document.querySelector('input[name="status_pasien"]:checked');
            const nomorRMContainer = document.getElementById('nomorRMContainer');
            
            if (statusPasien && statusPasien.value === 'lama') {
                nomorRMContainer.classList.remove('hidden');
            } else {
                nomorRMContainer.classList.add('hidden');
                // Clear value if hidden
                document.getElementById('nomor_rm').value = '';
            }
        }

        // Toggle perjalanan fields (menggunakan radio button)
        function togglePerjalananRadio() {
            const radioYa = document.querySelector('input[name="is_perjalanan"][value="1"]');
            const container = document.getElementById('perjalananContainer');
            const requiredFields = ['negara_tujuan', 'tanggal_berangkat'];
            
            if (radioYa && radioYa.checked) {
                container.classList.remove('hidden');
                // Set required untuk field perjalanan, paspor, dan foto paspor
                requiredFields.forEach(id => {
                    const field = document.getElementById(id);
                    if (field) field.required = true;
                });
                document.getElementById('nomor_paspor').required = true;
                document.getElementById('passport_halaman_pertama').required = true;
                document.getElementById('passport_halaman_kedua').required = true;
            } else {
                container.classList.add('hidden');
                // Remove required dari field perjalanan, paspor, dan foto paspor
                requiredFields.forEach(id => {
                    const field = document.getElementById(id);
                    if (field) field.required = false;
                });
                document.getElementById('nomor_paspor').required = false;
                document.getElementById('passport_halaman_pertama').required = false;
                document.getElementById('passport_halaman_kedua').required = false;
            }
        }

        // Fungsi lama untuk backward compatibility (jika masih ada yang memanggil)
        function togglePerjalananFields() {
            togglePerjalananRadio();
        }

        // Toggle input field untuk vaksin lainnya
        function toggleVaksinLainnyaInput() {
            const checkbox = document.getElementById('vaksinLainnya');
            const container = document.getElementById('vaksinLainnyaContainer');
            const inputField = document.getElementById('vaksinLainnyaText');
            
            if (checkbox.checked) {
                container.classList.remove('hidden');
                inputField.required = true;
            } else {
                container.classList.add('hidden');
                inputField.required = false;
                inputField.value = ''; // Clear value when unchecked
            }
        }

        // NIK Warning Modal Functions
        let nikCheckPassed = false;
        let currentNikValue = '';

        function showNikWarningModal(data) {
            const modal = document.getElementById('nikWarningModal');
            document.getElementById('warningNik').textContent = data.nik || '';
            document.getElementById('warningNama').textContent = data.nama || '';
            document.getElementById('warningTanggal').textContent = data.tanggal_daftar || '';
            document.getElementById('warningPendingCount').textContent = data.total_pending || '1';
            
            // Set status text
            let statusText = 'Menunggu Verifikasi';
            if (data.status_screening === 'pending') {
                statusText = 'Menunggu Verifikasi Dokter';
            } else if (data.status_konfirmasi === 'belum_dikonfirmasi') {
                statusText = 'Belum Dikonfirmasi';
            }
            document.getElementById('warningStatus').textContent = statusText;
            
            modal.classList.remove('hidden');
        }

        function closeNikWarningModal() {
            const modal = document.getElementById('nikWarningModal');
            modal.classList.add('hidden');
            
            // Reset NIK field to allow user to change it
            const nikInput = document.getElementById('nik');
            if (nikInput) {
                nikInput.focus();
                nikInput.select();
            }
        }

        // Check NIK via AJAX
        function checkNikDuplicate(nik) {
            // Skip if NIK is empty or too short
            if (!nik || nik.length < 10) {
                nikCheckPassed = false;
                return;
            }

            // Skip if we already checked this NIK
            if (nik === currentNikValue && nikCheckPassed) {
                return;
            }

            currentNikValue = nik;

            // Show loading indicator on NIK field
            const nikInput = document.getElementById('nik');
            const originalBorder = nikInput.className;
            nikInput.classList.add('border-blue-500', 'animate-pulse');

            fetch('{{ route("api.check-nik") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ nik: nik })
            })
            .then(response => response.json())
            .then(data => {
                // Remove loading indicator
                nikInput.classList.remove('border-blue-500', 'animate-pulse');
                
                if (data.status === 'pending') {
                    // NIK has pending requests - show warning
                    nikCheckPassed = false;
                    nikInput.classList.add('border-yellow-500');
                    showNikWarningModal(data.data);
                } else {
                    // NIK is OK to proceed
                    nikCheckPassed = true;
                    nikInput.classList.remove('border-yellow-500', 'border-red-500');
                    nikInput.classList.add('border-green-500');
                    
                    // Show success indicator briefly
                    setTimeout(() => {
                        nikInput.classList.remove('border-green-500');
                    }, 2000);
                }
            })
            .catch(error => {
                console.error('Error checking NIK:', error);
                // Remove loading indicator
                nikInput.classList.remove('border-blue-500', 'animate-pulse');
                // Allow to proceed on error (fail-safe)
                nikCheckPassed = true;
            });
        }

        // Debounce function to avoid too many API calls
        let nikCheckTimeout;
        function debounceNikCheck(nik) {
            clearTimeout(nikCheckTimeout);
            nikCheckTimeout = setTimeout(() => {
                checkNikDuplicate(nik);
            }, 800); // Wait 800ms after user stops typing
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleNomorRM();
            toggleVaksinLainnyaInput(); // Initialize vaksin lainnya state
            togglePerjalananRadio(); // Initialize perjalanan luar negeri state
            
            // Add event listener to NIK input for real-time checking
            const nikInput = document.getElementById('nik');
            if (nikInput) {
                nikInput.addEventListener('input', function(e) {
                    const nik = e.target.value.trim();
                    if (nik.length >= 10) {
                        debounceNikCheck(nik);
                    } else {
                        nikCheckPassed = false;
                    }
                });

                // Also check on blur (when user leaves the field)
                nikInput.addEventListener('blur', function(e) {
                    const nik = e.target.value.trim();
                    if (nik.length >= 10) {
                        checkNikDuplicate(nik);
                    }
                });
            }
            
            // Validate vaccine selection and file sizes on form submit
            const form = document.getElementById('formPermohonan');
            form.addEventListener('submit', function(e) {
                let hasError = false;
                let errorMessages = [];
                
                // Validasi NIK - Check for pending requests
                const nik = document.getElementById('nik');
                if (!nik.value.trim()) {
                    hasError = true;
                    errorMessages.push('❌ NIK wajib diisi');
                    nik.classList.add('border-red-500');
                } else if (nik.value.length > 20) {
                    hasError = true;
                    errorMessages.push('❌ NIK tidak boleh lebih dari 20 karakter');
                    nik.classList.add('border-red-500');
                } else if (!nikCheckPassed && nik.value.length >= 10) {
                    // Block submission if NIK has pending requests
                    e.preventDefault();
                    hasError = true;
                    errorMessages.push('❌ NIK ini memiliki permohonan yang masih dalam proses verifikasi');
                    nik.classList.add('border-yellow-500');
                    
                    // Re-check NIK to show modal
                    checkNikDuplicate(nik.value.trim());
                    
                    alert('⚠️ Permohonan Tidak Dapat Diproses\n\nNIK yang Anda masukkan memiliki permohonan yang masih dalam proses verifikasi oleh dokter.\n\nMohon tunggu hingga permohonan sebelumnya selesai diproses.');
                    return false;
                } else {
                    nik.classList.remove('border-red-500');
                }
                
                // Validasi Nama
                const nama = document.getElementById('nama');
                if (!nama.value.trim()) {
                    hasError = true;
                    errorMessages.push('❌ Nama lengkap wajib diisi');
                    nama.classList.add('border-red-500');
                } else if (nama.value.length > 100) {
                    hasError = true;
                    errorMessages.push('❌ Nama tidak boleh lebih dari 100 karakter');
                    nama.classList.add('border-red-500');
                } else {
                    nama.classList.remove('border-red-500');
                }
                
                // Validasi Nomor Telepon
                const noTelp = document.getElementById('no_telp');
                if (!noTelp.value.trim()) {
                    hasError = true;
                    errorMessages.push('❌ Nomor telepon wajib diisi');
                    noTelp.classList.add('border-red-500');
                } else if (noTelp.value.length > 20) {
                    hasError = true;
                    errorMessages.push('❌ Nomor telepon tidak boleh lebih dari 20 karakter');
                    noTelp.classList.add('border-red-500');
                } else {
                    noTelp.classList.remove('border-red-500');
                }
                
                // Validasi Email
                const email = document.getElementById('email');
                if (!email.value.trim()) {
                    hasError = true;
                    errorMessages.push('❌ Email wajib diisi');
                    email.classList.add('border-red-500');
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                    hasError = true;
                    errorMessages.push('❌ Format email tidak valid');
                    email.classList.add('border-red-500');
                } else if (email.value.length > 100) {
                    hasError = true;
                    errorMessages.push('❌ Email tidak boleh lebih dari 100 karakter');
                    email.classList.add('border-red-500');
                } else {
                    email.classList.remove('border-red-500');
                }
                
                // Validasi Foto KTP
                const fotoKtp = document.getElementById('foto_ktp');
                if (!fotoKtp.files[0]) {
                    hasError = true;
                    errorMessages.push('❌ Foto KTP wajib diupload');
                    fotoKtp.classList.add('border-red-500');
                } else {
                    const file = fotoKtp.files[0];
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf', 'image/heic', 'image/heif'];
                    const fileExtension = file.name.split('.').pop().toLowerCase();
                    const allowedExtensions = ['jpeg', 'jpg', 'png', 'pdf', 'heic', 'heif'];
                    
                    if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
                        hasError = true;
                        errorMessages.push('❌ File KTP harus berformat: JPEG, JPG, PNG, PDF, atau HEIC');
                        fotoKtp.classList.add('border-red-500');
                    } else {
                        fotoKtp.classList.remove('border-red-500');
                    }
                }
                
                // Validasi Status Pasien
                const statusPasien = document.querySelector('input[name="status_pasien"]:checked');
                if (!statusPasien) {
                    hasError = true;
                    errorMessages.push('❌ Status pasien wajib dipilih');
                }
                
                // Validasi Nomor RM jika Pasien Lama
                if (statusPasien && statusPasien.value === 'lama') {
                    const nomorRM = document.getElementById('nomor_rm');
                    if (nomorRM && nomorRM.value.trim() && nomorRM.value.length > 50) {
                        hasError = true;
                        errorMessages.push('❌ Nomor RM tidak boleh lebih dari 50 karakter');
                        nomorRM.classList.add('border-red-500');
                    } else if (nomorRM) {
                        nomorRM.classList.remove('border-red-500');
                    }
                }
                
                // Validasi Vaksin
                const checkedVaccines = document.querySelectorAll('input[name="jenis_vaksin[]"]:checked');
                const errorMsg = document.getElementById('vaksinError');
                const vaksinLainnya = document.getElementById('vaksinLainnya');
                const vaksinLainnyaText = document.getElementById('vaksinLainnyaText');
                
                // Check if "Lainnya" is selected but text is empty
                if (vaksinLainnya.checked && vaksinLainnyaText.value.trim() === '') {
                    hasError = true;
                    errorMessages.push('❌ Silakan sebutkan jenis vaksin lainnya yang Anda butuhkan');
                    vaksinLainnyaText.classList.add('border-red-500');
                } else {
                    vaksinLainnyaText.classList.remove('border-red-500');
                }
                
                if (checkedVaccines.length === 0) {
                    hasError = true;
                    errorMessages.push('❌ Minimal pilih satu jenis vaksin yang dibutuhkan');
                    if (errorMsg) errorMsg.style.display = 'block';
                } else {
                    if (errorMsg) errorMsg.style.display = 'none';
                }
                
                // Validasi Perjalanan Luar Negeri (menggunakan radio button)
                const isPerjalananYa = document.querySelector('input[name="is_perjalanan"][value="1"]');
                if (isPerjalananYa && isPerjalananYa.checked) {
                    const nomorPaspor = document.getElementById('nomor_paspor');
                    const negaraTujuan = document.getElementById('negara_tujuan');
                    const tanggalBerangkat = document.getElementById('tanggal_berangkat');
                    const passportPertama = document.getElementById('passport_halaman_pertama');
                    const passportKedua = document.getElementById('passport_halaman_kedua');
                    
                    if (!nomorPaspor.value.trim()) {
                        hasError = true;
                        errorMessages.push('❌ Nomor paspor wajib diisi untuk perjalanan luar negeri');
                        nomorPaspor.classList.add('border-red-500');
                    } else if (nomorPaspor.value.length > 50) {
                        hasError = true;
                        errorMessages.push('❌ Nomor paspor tidak boleh lebih dari 50 karakter');
                        nomorPaspor.classList.add('border-red-500');
                    } else {
                        nomorPaspor.classList.remove('border-red-500');
                    }
                    
                    if (!negaraTujuan.value.trim()) {
                        hasError = true;
                        errorMessages.push('❌ Negara tujuan wajib diisi');
                        negaraTujuan.classList.add('border-red-500');
                    } else if (negaraTujuan.value.length > 100) {
                        hasError = true;
                        errorMessages.push('❌ Negara tujuan tidak boleh lebih dari 100 karakter');
                        negaraTujuan.classList.add('border-red-500');
                    } else {
                        negaraTujuan.classList.remove('border-red-500');
                    }
                    
                    if (!tanggalBerangkat.value) {
                        hasError = true;
                        errorMessages.push('❌ Tanggal keberangkatan wajib diisi');
                        tanggalBerangkat.classList.add('border-red-500');
                    } else {
                        const tanggal = new Date(tanggalBerangkat.value);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);
                        if (tanggal <= today) {
                            hasError = true;
                            errorMessages.push('❌ Tanggal berangkat harus setelah hari ini');
                            tanggalBerangkat.classList.add('border-red-500');
                        } else {
                            tanggalBerangkat.classList.remove('border-red-500');
                        }
                    }
                    
                    // Validasi Passport files
                    if (passportPertama && passportPertama.files[0]) {
                        const file = passportPertama.files[0];
                        const allowedExtensions = ['jpeg', 'jpg', 'png', 'pdf', 'heic', 'heif'];
                        const fileExtension = file.name.split('.').pop().toLowerCase();
                        if (!allowedExtensions.includes(fileExtension)) {
                            hasError = true;
                            errorMessages.push('❌ File passport halaman pertama harus berformat: JPEG, JPG, PNG, PDF, atau HEIC');
                            passportPertama.classList.add('border-red-500');
                        } else {
                            passportPertama.classList.remove('border-red-500');
                        }
                    } else if (passportPertama) {
                        hasError = true;
                        errorMessages.push('❌ Passport halaman pertama wajib diupload untuk perjalanan luar negeri');
                        passportPertama.classList.add('border-red-500');
                    }
                    
                    if (passportKedua && passportKedua.files[0]) {
                        const file = passportKedua.files[0];
                        const allowedExtensions = ['jpeg', 'jpg', 'png', 'pdf', 'heic', 'heif'];
                        const fileExtension = file.name.split('.').pop().toLowerCase();
                        if (!allowedExtensions.includes(fileExtension)) {
                            hasError = true;
                            errorMessages.push('❌ File passport halaman kedua harus berformat: JPEG, JPG, PNG, PDF, atau HEIC');
                            passportKedua.classList.add('border-red-500');
                        } else {
                            passportKedua.classList.remove('border-red-500');
                        }
                    } else if (passportKedua) {
                        hasError = true;
                        errorMessages.push('❌ Passport halaman kedua wajib diupload untuk perjalanan luar negeri');
                        passportKedua.classList.add('border-red-500');
                    }
                }
                
                // Validate file sizes before submit
                const files = [
                    { input: document.getElementById('foto_ktp'), name: 'Foto KTP', maxSize: 5 },
                    { input: document.getElementById('passport_halaman_pertama'), name: 'Passport Halaman Pertama', maxSize: 5, required: document.getElementById('isPerjalanan')?.checked },
                    { input: document.getElementById('passport_halaman_kedua'), name: 'Passport Halaman Kedua', maxSize: 5, required: document.getElementById('isPerjalanan')?.checked }
                ];

                let totalSize = 0;

                files.forEach(({ input, name, maxSize, required }) => {
                    if (input && input.files[0]) {
                        const file = input.files[0];
                        totalSize += file.size;

                        if (file.size > maxSize * 1024 * 1024) {
                            hasError = true;
                            errorMessages.push(`❌ ${name}: ${formatFileSize(file.size)} (Maksimal: ${maxSize}MB)`);
                            input.classList.add('border-red-500');
                        } else {
                            input.classList.remove('border-red-500');
                        }
                    } else if (required) {
                        hasError = true;
                        errorMessages.push(`❌ ${name}: File wajib diupload untuk perjalanan luar negeri`);
                        if (input) input.classList.add('border-red-500');
                    }
                });

                // Check total size
                if (totalSize > MAX_TOTAL_SIZE_BYTES) {
                    hasError = true;
                    errorMessages.push(`❌ Total ukuran semua file: ${formatFileSize(totalSize)} (Maksimal: ${MAX_TOTAL_SIZE_MB}MB)`);
                }

                // Jika ada error, tampilkan alert dan stop submit
                if (hasError) {
                    e.preventDefault();
                    const errorMsg = '⚠️ Terdapat Kesalahan pada Formulir\n\n' + errorMessages.join('\n') + '\n\nSilakan perbaiki data yang bermasalah sebelum mengirim formulir.';
                    alert(errorMsg);
                    
                    // Scroll to first error field
                    const firstErrorField = document.querySelector('.border-red-500');
                    if (firstErrorField) {
                        firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstErrorField.focus();
                    }
                    
                    return false;
                }

                // Show loading/confirmation
                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengirim...';
                }
                
                // Show loading overlay
                const loadingOverlay = document.getElementById('loadingOverlay');
                if (loadingOverlay) {
                    loadingOverlay.classList.remove('hidden');
                }
            });
            
            // Hide error when user selects a vaccine
            const vaccineCheckboxes = document.querySelectorAll('input[name="jenis_vaksin[]"]');
            vaccineCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checkedVaccines = document.querySelectorAll('input[name="jenis_vaksin[]"]:checked');
                    const errorMsg = document.getElementById('vaksinError');
                    if (checkedVaccines.length > 0) {
                        errorMsg.style.display = 'none';
                    }
                });
            });
        });
    </script>

    <!-- Google reCAPTCHA Script - Load at end for better performance (hanya di production) -->
    @if(!$isLocal)
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    
    <!-- Fallback if reCAPTCHA doesn't load -->
    <script>
        // Check if reCAPTCHA loaded after 5 seconds
        setTimeout(function() {
            const recaptchaDiv = document.querySelector('.g-recaptcha');
            if (recaptchaDiv && !recaptchaDiv.innerHTML) {
                console.error('reCAPTCHA gagal dimuat. Periksa koneksi internet.');
                // Show error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'mt-2 text-sm text-red-600 text-center';
                errorDiv.innerHTML = '⚠️ reCAPTCHA gagal dimuat. Periksa koneksi internet atau disable AdBlock.';
                recaptchaDiv.parentNode.appendChild(errorDiv);
            }
        }, 5000);
    </script>
</body>
</html>
