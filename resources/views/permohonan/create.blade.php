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
                            <input type="file" name="foto_ktp" id="foto_ktp" accept="image/*,.pdf"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" 
                                required
                                onchange="validateFileSize(this, 5, 'foto_ktp')">
                            <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, atau PDF. Maksimal 5MB</p>
                            <p class="text-sm text-red-600 mt-1 hidden" id="error_foto_ktp"></p>
                            <p class="text-sm text-green-600 mt-1 hidden" id="info_foto_ktp"></p>
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

                    <!-- Checkbox Perjalanan -->
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_perjalanan" id="isPerjalanan" value="1"
                                class="w-5 h-5 text-purple-600 rounded focus:ring-purple-500" 
                                onchange="togglePerjalananFields()">
                            <span class="ml-2 text-gray-700 font-medium">Vaksin untuk Perjalanan Luar Negeri</span>
                        </label>
                        <p class="text-sm text-gray-600 ml-7 mt-1">Centang jika vaksin diperlukan untuk keperluan perjalanan internasional</p>
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
                            <input type="file" name="passport_halaman_pertama" id="passport_halaman_pertama" accept="image/*,.pdf"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100"
                                onchange="validateFileSize(this, 5, 'passport_halaman_pertama')">
                            <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, atau PDF. Maksimal 5MB</p>
                            <p class="text-sm text-red-600 mt-1 hidden" id="error_passport_halaman_pertama"></p>
                            <p class="text-sm text-green-600 mt-1 hidden" id="info_passport_halaman_pertama"></p>
                        </div>

                        <!-- Upload Foto Paspor Halaman Kedua -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Passport Halaman Kedua *</label>
                            <input type="file" name="passport_halaman_kedua" id="passport_halaman_kedua" accept="image/*,.pdf"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100"
                                onchange="validateFileSize(this, 5, 'passport_halaman_kedua')">
                            <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, atau PDF. Maksimal 5MB</p>
                            <p class="text-sm text-red-600 mt-1 hidden" id="error_passport_halaman_kedua"></p>
                            <p class="text-sm text-green-600 mt-1 hidden" id="info_passport_halaman_kedua"></p>
                        </div>
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

        // Validate individual file size
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
                        infoElement.textContent = `✅ File sesuai: ${formatFileSize(file.size)}`;
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

        // Toggle perjalanan fields
        function togglePerjalananFields() {
            const checkbox = document.getElementById('isPerjalanan');
            const container = document.getElementById('perjalananContainer');
            const requiredFields = ['negara_tujuan', 'tanggal_berangkat'];
            
            if (checkbox.checked) {
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

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleNomorRM();
            toggleVaksinLainnyaInput(); // Initialize vaksin lainnya state
            
            // Validate vaccine selection and file sizes on form submit
            const form = document.getElementById('formPermohonan');
            form.addEventListener('submit', function(e) {
                // Validate vaccine selection
                const checkedVaccines = document.querySelectorAll('input[name="jenis_vaksin[]"]:checked');
                const errorMsg = document.getElementById('vaksinError');
                const vaksinLainnya = document.getElementById('vaksinLainnya');
                const vaksinLainnyaText = document.getElementById('vaksinLainnyaText');
                
                // Check if "Lainnya" is selected but text is empty
                if (vaksinLainnya.checked && vaksinLainnyaText.value.trim() === '') {
                    e.preventDefault();
                    vaksinLainnyaText.focus();
                    vaksinLainnyaText.classList.add('border-red-500');
                    alert('⚠️ Silakan sebutkan jenis vaksin lainnya yang Anda butuhkan.');
                    return false;
                } else {
                    vaksinLainnyaText.classList.remove('border-red-500');
                }
                
                if (checkedVaccines.length === 0) {
                    e.preventDefault();
                    errorMsg.style.display = 'block';
                    errorMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    alert('⚠️ Minimal pilih satu jenis vaksin yang dibutuhkan.');
                    return false;
                } else {
                    errorMsg.style.display = 'none';
                }

                // Validate file sizes before submit
                const files = [
                    { input: document.getElementById('foto_ktp'), name: 'Foto KTP', maxSize: 5 },
                    { input: document.getElementById('passport_halaman_pertama'), name: 'Passport Halaman Pertama', maxSize: 5, required: document.getElementById('isPerjalanan')?.checked },
                    { input: document.getElementById('passport_halaman_kedua'), name: 'Passport Halaman Kedua', maxSize: 5, required: document.getElementById('isPerjalanan')?.checked }
                ];

                let totalSize = 0;
                let hasError = false;
                let errorMessages = [];

                files.forEach(({ input, name, maxSize, required }) => {
                    if (input && input.files[0]) {
                        const file = input.files[0];
                        const fileSizeMB = file.size / (1024 * 1024);
                        totalSize += file.size;

                        if (file.size > maxSize * 1024 * 1024) {
                            hasError = true;
                            errorMessages.push(`❌ ${name}: ${formatFileSize(file.size)} (Maksimal: ${maxSize}MB)`);
                        }
                    } else if (required) {
                        hasError = true;
                        errorMessages.push(`❌ ${name}: File wajib diupload untuk perjalanan luar negeri`);
                    }
                });

                // Check total size
                if (totalSize > MAX_TOTAL_SIZE_BYTES) {
                    hasError = true;
                    errorMessages.push(`❌ Total ukuran semua file: ${formatFileSize(totalSize)} (Maksimal: ${MAX_TOTAL_SIZE_MB}MB)`);
                }

                if (hasError) {
                    e.preventDefault();
                    const errorMsg = '⚠️ Terdapat Kesalahan pada File Upload\n\n' + errorMessages.join('\n') + '\n\nSilakan perbaiki file yang bermasalah sebelum mengirim formulir.';
                    alert(errorMsg);
                    
                    // Scroll to first error
                    const firstErrorInput = files.find(f => f.input && f.input.files[0] && f.input.files[0].size > (f.maxSize * 1024 * 1024));
                    if (firstErrorInput) {
                        firstErrorInput.input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstErrorInput.input.focus();
                    }
                    
                    return false;
                }

                // Show loading/confirmation
                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Mengirim...';
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
