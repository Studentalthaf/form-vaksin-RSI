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
            <form method="POST" action="{{ route('permohonan.store') }}" class="p-8" id="formPermohonan">
                @csrf

                <!-- Section: SIM RS Check -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-blue-500">Data Pasien</h3>
                    
                    <!-- Pertanyaan SIM RS -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mb-6">
                        <label class="block text-sm font-semibold text-gray-800 mb-3">
                            Apakah Anda sudah memiliki Nomor SIM RS (Sistem Informasi Manajemen Rumah Sakit)? *
                        </label>
                        <div class="flex gap-4">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="has_sim_rs" value="1" class="w-4 h-4 text-blue-600" onclick="toggleSimRSInput(true)">
                                <span class="ml-2 text-gray-700 font-medium">Ya, saya punya</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="has_sim_rs" value="0" class="w-4 h-4 text-blue-600" onclick="toggleSimRSInput(false)" checked>
                                <span class="ml-2 text-gray-700 font-medium">Belum punya</span>
                            </label>
                        </div>
                    </div>

                    <!-- Input SIM RS (Hidden by default) -->
                    <div id="simRSContainer" class="hidden mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor SIM RS *</label>
                        <div class="flex gap-2">
                            <input type="text" id="simRSInput" name="sim_rs" 
                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                placeholder="Contoh: SIM12345678">
                            <button type="button" onclick="checkSimRS()" 
                                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                                Cek Data
                            </button>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Masukkan nomor SIM RS untuk mengisi data otomatis</p>
                        <div id="simRSStatus" class="mt-2"></div>
                    </div>
                </div>

                <!-- Section: Data Pribadi (Manual Input) -->
                <div id="manualDataContainer" class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-green-500">Data Pribadi</h3>
                    
                    <div class="grid md:grid-cols-2 gap-6">
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
                    </div>
                </div>

                <!-- Section: Jenis Vaksin -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-purple-500">Jenis Vaksinasi</h3>
                    
                    <!-- Jenis Vaksin -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Vaksin yang Dibutuhkan *</label>
                        <select name="jenis_vaksin" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">-- Pilih Jenis Vaksin --</option>
                            <option value="Yellow Fever">Yellow Fever (Demam Kuning)</option>
                            <option value="Meningitis">Meningitis (Meningokokus)</option>
                            <option value="Hepatitis A">Hepatitis A</option>
                            <option value="Hepatitis B">Hepatitis B</option>
                            <option value="Typhoid">Typhoid (Tifus)</option>
                            <option value="Rabies">Rabies</option>
                            <option value="Japanese Encephalitis">Japanese Encephalitis</option>
                            <option value="Influenza">Influenza</option>
                            <option value="MMR">MMR (Campak, Gondongan, Rubella)</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
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
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end gap-4 pt-6 border-t">
                    <button type="submit" 
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
        // Toggle SIM RS input visibility
        function toggleSimRSInput(show) {
            const container = document.getElementById('simRSContainer');
            const manualContainer = document.getElementById('manualDataContainer');
            
            if (show) {
                container.classList.remove('hidden');
                // Hide manual data karena akan auto-fill via AJAX
                manualContainer.classList.add('hidden');
                clearForm();
            } else {
                container.classList.add('hidden');
                // Show manual data untuk input manual
                manualContainer.classList.remove('hidden');
                clearForm();
                // Enable semua field untuk pasien baru
                disablePersonalFields(false);
                // Set required ke true untuk pasien baru
                toggleRequiredFields(true);
            }
        }

        // Check SIM RS via AJAX
        function checkSimRS() {
            const simRS = document.getElementById('simRSInput').value.trim();
            const statusDiv = document.getElementById('simRSStatus');
            const manualContainer = document.getElementById('manualDataContainer');
            
            if (!simRS) {
                statusDiv.innerHTML = '<p class="text-sm text-red-600">⚠️ Masukkan nomor SIM RS terlebih dahulu</p>';
                return;
            }

            statusDiv.innerHTML = '<p class="text-sm text-blue-600">🔍 Mencari data...</p>';

            fetch(`/api/check-sim-rs/${simRS}`)
                .then(response => response.json())
                .then(data => {
                    if (data.found) {
                        // Show container untuk display data
                        manualContainer.classList.remove('hidden');
                        
                        // Fill form with existing data
                        document.getElementById('nama').value = data.data.nama || '';
                        document.getElementById('no_telp').value = data.data.no_telp || '';
                        document.getElementById('tempat_lahir').value = data.data.tempat_lahir || '';
                        document.getElementById('tanggal_lahir').value = data.data.tanggal_lahir || '';
                        document.getElementById('jenis_kelamin').value = data.data.jenis_kelamin || '';
                        document.getElementById('pekerjaan').value = data.data.pekerjaan || '';
                        document.getElementById('alamat').value = data.data.alamat || '';
                        document.getElementById('nomor_paspor').value = data.data.nomor_paspor || '';

                        // Disable all personal data fields (tidak bisa edit)
                        disablePersonalFields(true);
                        
                        // Remove required karena data sudah ada
                        toggleRequiredFields(false);
                        
                        statusDiv.innerHTML = '<p class="text-sm text-green-600 font-medium">✅ Data pasien ditemukan! Anda hanya perlu mengisi jenis vaksin yang dibutuhkan.</p>';
                    } else {
                        statusDiv.innerHTML = '<p class="text-sm text-red-600">❌ Nomor SIM RS tidak ditemukan dalam database. Silakan pilih "Belum punya" dan isi data manual.</p>';
                        manualContainer.classList.add('hidden');
                    }
                })
                .catch(error => {
                    statusDiv.innerHTML = '<p class="text-sm text-red-600">⚠️ Terjadi kesalahan koneksi. Silakan coba lagi.</p>';
                    console.error('Error:', error);
                });
        }

        // Disable/enable personal data fields
        function disablePersonalFields(disable) {
            const fields = ['nama', 'no_telp', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'pekerjaan', 'alamat'];
            fields.forEach(id => {
                const field = document.getElementById(id);
                if (field) {
                    field.disabled = disable;
                    if (disable) {
                        field.classList.add('bg-gray-100', 'cursor-not-allowed');
                    } else {
                        field.classList.remove('bg-gray-100', 'cursor-not-allowed');
                    }
                }
            });
        }

        // Toggle required attribute untuk personal data fields
        function toggleRequiredFields(required) {
            const requiredFields = ['nama', 'no_telp'];
            requiredFields.forEach(id => {
                const field = document.getElementById(id);
                if (field) {
                    field.required = required;
                }
            });
        }

        // Clear form
        function clearForm() {
            const simRSInput = document.getElementById('simRSInput');
            if (simRSInput) simRSInput.value = '';
            
            document.getElementById('simRSStatus').innerHTML = '';
            
            // Clear all personal data fields
            const fields = ['nama', 'no_telp', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'pekerjaan', 'alamat', 'nomor_paspor'];
            fields.forEach(id => {
                const field = document.getElementById(id);
                if (field) {
                    field.value = '';
                }
            });
            
            disablePersonalFields(false);
        }

        // Toggle perjalanan fields
        function togglePerjalananFields() {
            const checkbox = document.getElementById('isPerjalanan');
            const container = document.getElementById('perjalananContainer');
            const requiredFields = ['negara_tujuan', 'tanggal_berangkat'];
            
            if (checkbox.checked) {
                container.classList.remove('hidden');
                // Set required untuk field perjalanan dan paspor
                requiredFields.forEach(id => {
                    const field = document.getElementById(id);
                    if (field) field.required = true;
                });
                document.getElementById('nomor_paspor').required = true;
            } else {
                container.classList.add('hidden');
                // Remove required dari field perjalanan dan paspor
                requiredFields.forEach(id => {
                    const field = document.getElementById(id);
                    if (field) field.required = false;
                });
                document.getElementById('nomor_paspor').required = false;
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Set default: belum punya SIM RS
            toggleSimRSInput(false);
        });
    </script>
</body>
</html>
