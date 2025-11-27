<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Screening Kesehatan - Form Vaksinasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gradient-to-br from-green-50 via-white to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-green-900 mb-2">Screening Kesehatan</h1>
            <p class="text-gray-600">Mohon jawab semua pertanyaan berikut dengan jujur untuk keselamatan Anda</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="max-w-4xl mx-auto mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Form Card -->
        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-green-600 to-blue-600 text-white px-8 py-6">
                <h2 class="text-2xl font-bold">Pertanyaan Screening Kesehatan</h2>
                <p class="text-green-100 text-sm mt-1">Step 2 dari 2</p>
                <div class="mt-3 text-sm">
                    <p><strong>Nama:</strong> {{ $vaccineRequest->pasien->nama }}</p>
                    <p><strong>NIK:</strong> {{ $vaccineRequest->pasien->nik }}</p>
                    <p><strong>Vaksin:</strong> {{ is_array($vaccineRequest->jenis_vaksin) ? implode(', ', $vaccineRequest->jenis_vaksin) : $vaccineRequest->jenis_vaksin }}</p>
                </div>
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
            <form method="POST" action="{{ route('permohonan.screening.store', $vaccineRequest->id) }}" class="p-8" id="screeningForm">
                @csrf

                @if($questions->count() > 0)
                    <div class="space-y-6">
                        @foreach($questions as $index => $question)
                        <div class="p-6 border border-gray-200 rounded-lg {{ $question->wajib ? 'bg-yellow-50' : 'bg-white' }}">
                            <div class="flex items-start">
                                <span class="flex-shrink-0 w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                    {{ $index + 1 }}
                                </span>
                                <div class="ml-4 flex-1">
                                    <label class="block text-gray-800 font-semibold mb-3">
                                        {{ $question->pertanyaan }}
                                        @if($question->wajib)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>

                                    @if($question->tipe_jawaban == 'ya_tidak')
                                        <div class="flex gap-4">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="radio" name="jawaban_{{ $question->id }}" value="Ya" 
                                                    class="w-4 h-4 text-green-600 focus:ring-green-500"
                                                    {{ $question->wajib ? 'required' : '' }}
                                                    onchange="toggleKeterangan{{ $question->id }}(this.value)">
                                                <span class="ml-2 text-gray-700">Ya</span>
                                            </label>
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="radio" name="jawaban_{{ $question->id }}" value="Tidak" 
                                                    class="w-4 h-4 text-green-600 focus:ring-green-500"
                                                    onchange="toggleKeterangan{{ $question->id }}(this.value)">
                                                <span class="ml-2 text-gray-700">Tidak</span>
                                            </label>
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="radio" name="jawaban_{{ $question->id }}" value="Tidak Tahu" 
                                                    class="w-4 h-4 text-green-600 focus:ring-green-500"
                                                    onchange="toggleKeterangan{{ $question->id }}(this.value)">
                                                <span class="ml-2 text-gray-700">Tidak Tahu</span>
                                            </label>
                                        </div>

                                        <!-- Keterangan (muncul jika jawaban Ya) -->
                                        <div id="keterangan_container_{{ $question->id }}" class="mt-3 hidden">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Keterangan Tambahan
                                            </label>
                                            <textarea name="keterangan_{{ $question->id }}" rows="2"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                                placeholder="Jelaskan lebih detail..."></textarea>
                                        </div>

                                        <script>
                                            function toggleKeterangan{{ $question->id }}(value) {
                                                const container = document.getElementById('keterangan_container_{{ $question->id }}');
                                                if (value === 'Ya') {
                                                    container.classList.remove('hidden');
                                                } else {
                                                    container.classList.add('hidden');
                                                }
                                            }
                                        </script>

                                    @elseif($question->tipe_jawaban == 'pilihan_ganda')
                                        @php
                                            $pilihan = json_decode($question->pilihan_jawaban, true) ?? [];
                                        @endphp
                                        <div class="space-y-2">
                                            @foreach($pilihan as $p)
                                            <label class="flex items-center cursor-pointer">
                                                <input type="radio" name="jawaban_{{ $question->id }}" value="{{ $p }}" 
                                                    class="w-4 h-4 text-green-600 focus:ring-green-500"
                                                    {{ $question->wajib ? 'required' : '' }}>
                                                <span class="ml-2 text-gray-700">{{ $p }}</span>
                                            </label>
                                            @endforeach
                                        </div>

                                    @elseif($question->tipe_jawaban == 'text')
                                        <textarea name="jawaban_{{ $question->id }}" rows="3"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            placeholder="Tuliskan jawaban Anda..."
                                            {{ $question->wajib ? 'required' : '' }}></textarea>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Info Box -->
                    <div class="mt-8 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    <strong>Penting:</strong> Jawablah semua pertanyaan dengan jujur. Informasi ini diperlukan untuk menilai kelayakan vaksinasi Anda.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Tanda Tangan Pasien -->
                    <div class="mt-8 p-6 border-2 border-green-500 rounded-lg bg-green-50">
                        <div class="mb-4">
                            <h3 class="text-lg font-bold text-green-900 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                                Tanda Tangan Persetujuan
                                <span class="text-red-500 ml-1">*</span>
                            </h3>
                            <p class="text-sm text-green-700 mt-1">
                                Dengan menandatangani, saya menyatakan bahwa semua informasi yang saya berikan adalah benar dan saya bersedia untuk divaksinasi.
                            </p>
                        </div>

                        <div class="bg-white border-2 border-green-300 rounded-lg overflow-hidden">
                            <canvas id="signaturePad" width="700" height="200" style="width: 100%; height: 200px; touch-action: none; cursor: crosshair; display: block;"></canvas>
                        </div>

                        <div class="flex items-center justify-between mt-3">
                            <p class="text-xs text-gray-600">Silakan tanda tangan di kotak putih di atas</p>
                            <button type="button" onclick="clearSignature()" class="text-sm text-red-600 hover:text-red-700 font-semibold flex items-center px-3 py-1 border border-red-300 rounded hover:bg-red-50 transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus Tanda Tangan
                            </button>
                        </div>

                        <input type="hidden" name="tanda_tangan" id="tandaTanganInput" required>
                    </div>

                    <!-- Tanda Tangan Keluarga (Optional) -->
                    <div class="mt-6 p-6 border-2 border-blue-300 rounded-lg bg-blue-50">
                        <div class="mb-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" id="adaTandaTanganKeluarga" name="ada_tanda_tangan_keluarga" value="1" 
                                    onchange="toggleTandaTanganKeluarga()"
                                    class="w-5 h-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm font-semibold text-blue-900">
                                    Ada tanda tangan keluarga/pendamping
                                </span>
                            </label>
                            <p class="text-xs text-blue-700 mt-1 ml-7">
                                Centang jika ada keluarga/pendamping yang ikut menandatangani
                            </p>
                        </div>

                        <div id="tandaTanganKeluargaContainer" class="hidden">
                            <div class="mb-4">
                                <h3 class="text-lg font-bold text-blue-900 flex items-center mb-2">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                    Tanda Tangan Keluarga/Pendamping
                                </h3>
                                <p class="text-sm text-blue-700">
                                    Tanda tangan keluarga/pendamping pasien (opsional)
                                </p>
                            </div>

                            <div class="bg-white border-2 border-blue-300 rounded-lg overflow-hidden">
                                <canvas id="signaturePadKeluarga" width="700" height="200" style="width: 100%; height: 200px; touch-action: none; cursor: crosshair; display: block;"></canvas>
                            </div>

                            <div class="flex items-center justify-between mt-3">
                                <p class="text-xs text-gray-600">Silakan tanda tangan di kotak putih di atas</p>
                                <button type="button" onclick="clearSignatureKeluarga()" class="text-sm text-red-600 hover:text-red-700 font-semibold flex items-center px-3 py-1 border border-red-300 rounded hover:bg-red-50 transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus Tanda Tangan
                                </button>
                            </div>

                            <input type="hidden" name="tanda_tangan_keluarga" id="tandaTanganKeluargaInput">

                            <!-- Nama Keluarga (di bawah tanda tangan) -->
                            <div class="mt-4">
                                <label class="block text-sm font-semibold text-blue-900 mb-2">
                                    Nama Keluarga/Pendamping
                                </label>
                                <input type="text" name="nama_keluarga" id="namaKeluargaInput" 
                                    placeholder="Masukkan nama keluarga/pendamping"
                                    maxlength="100"
                                    class="w-full px-4 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <p class="text-xs text-blue-600 mt-1">Nama orang yang menandatangani di atas</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end gap-4 pt-6 border-t mt-8">
                        <button type="submit" 
                            class="px-8 py-3 bg-gradient-to-r from-green-600 to-blue-600 text-white font-bold rounded-lg hover:from-green-700 hover:to-blue-700 transition duration-200 shadow-lg">
                            Kirim Jawaban
                        </button>
                    </div>

                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">Belum Ada Pertanyaan Screening</h3>
                        <p class="mt-1 text-sm text-gray-500">Admin belum menambahkan pertanyaan screening.</p>
                        <div class="mt-6">
                            <a href="{{ route('permohonan.success') }}" 
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                Lanjutkan
                            </a>
                        </div>
                    </div>
                @endif
            </form>
        </div>

        <!-- Info Footer -->
        <div class="max-w-4xl mx-auto mt-6 text-center text-gray-600 text-sm">
            <p>Data Anda akan dijaga kerahasiaannya dan hanya digunakan untuk keperluan medis</p>
        </div>
    </div>

    <script>
        // ========== SIGNATURE PAD ==========
        const canvas = document.getElementById('signaturePad');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            let isDrawing = false;

            // Setup canvas
            ctx.strokeStyle = '#000000'; // Black
            ctx.lineWidth = 3;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';

            function getPosition(e) {
                const rect = canvas.getBoundingClientRect();
                const scaleX = canvas.width / rect.width;
                const scaleY = canvas.height / rect.height;
                
                if (e.touches && e.touches[0]) {
                    return {
                        x: (e.touches[0].clientX - rect.left) * scaleX,
                        y: (e.touches[0].clientY - rect.top) * scaleY
                    };
                }
                
                return {
                    x: (e.clientX - rect.left) * scaleX,
                    y: (e.clientY - rect.top) * scaleY
                };
            }

            function startDrawing(e) {
                isDrawing = true;
                const pos = getPosition(e);
                ctx.beginPath();
                ctx.moveTo(pos.x, pos.y);
            }

            function draw(e) {
                if (!isDrawing) return;
                e.preventDefault();
                const pos = getPosition(e);
                ctx.lineTo(pos.x, pos.y);
                ctx.stroke();
            }

            function stopDrawing() {
                isDrawing = false;
                ctx.beginPath();
            }

            // Mouse events
            canvas.addEventListener('mousedown', startDrawing);
            canvas.addEventListener('mousemove', draw);
            canvas.addEventListener('mouseup', stopDrawing);
            canvas.addEventListener('mouseout', stopDrawing);

            // Touch events
            canvas.addEventListener('touchstart', (e) => {
                e.preventDefault();
                startDrawing(e);
            });
            canvas.addEventListener('touchmove', draw);
            canvas.addEventListener('touchend', (e) => {
                e.preventDefault();
                stopDrawing();
            });

            // Clear signature
            window.clearSignature = function() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                document.getElementById('tandaTanganInput').value = '';
            }

            // Check if canvas is blank
            function isCanvasBlank() {
                const blank = document.createElement('canvas');
                blank.width = canvas.width;
                blank.height = canvas.height;
                return canvas.toDataURL() === blank.toDataURL();
            }

            // Form submission validation
            const form = document.getElementById('screeningForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Validate signature
                if (isCanvasBlank()) {
                    alert('❌ Tanda tangan belum dibuat!\n\nSilakan tanda tangan terlebih dahulu di kotak yang tersedia sebagai tanda persetujuan Anda.');
                    canvas.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }

                // Save signature as base64
                const signatureData = canvas.toDataURL('image/png');
                document.getElementById('tandaTanganInput').value = signatureData;

                // Handle family signature if checked
                const adaTandaTanganKeluarga = document.getElementById('adaTandaTanganKeluarga').checked;
                if (adaTandaTanganKeluarga) {
                    const canvasKeluarga = document.getElementById('signaturePadKeluarga');
                    if (canvasKeluarga) {
                        const ctxKeluarga = canvasKeluarga.getContext('2d');
                        const blank = document.createElement('canvas');
                        blank.width = canvasKeluarga.width;
                        blank.height = canvasKeluarga.height;
                        
                        if (canvasKeluarga.toDataURL() === blank.toDataURL()) {
                            alert('❌ Tanda tangan keluarga belum dibuat!\n\nSilakan tanda tangan keluarga di kotak yang tersedia atau hapus centang jika tidak ada.');
                            canvasKeluarga.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            return false;
                        }
                        
                        const signatureKeluargaData = canvasKeluarga.toDataURL('image/png');
                        document.getElementById('tandaTanganKeluargaInput').value = signatureKeluargaData;
                    }
                }

                // Confirm before submit
                if (confirm('Apakah Anda yakin data yang Anda isi sudah benar?\n\n✓ Semua pertanyaan sudah dijawab\n✓ Tanda tangan sudah dibuat\n\nData tidak dapat diubah setelah dikirim.')) {
                    // Clear localStorage
                    localStorage.clear();
                    this.submit();
                }
            });
        }

        // ========== SIGNATURE PAD KELUARGA ==========
        let canvasKeluarga = null;
        let ctxKeluarga = null;
        let isDrawingKeluarga = false;

        function initCanvasKeluarga() {
            canvasKeluarga = document.getElementById('signaturePadKeluarga');
            if (!canvasKeluarga) return;
            
            ctxKeluarga = canvasKeluarga.getContext('2d');
            ctxKeluarga.strokeStyle = '#000000';
            ctxKeluarga.lineWidth = 3;
            ctxKeluarga.lineCap = 'round';
            ctxKeluarga.lineJoin = 'round';

            function getPositionKeluarga(e) {
                const rect = canvasKeluarga.getBoundingClientRect();
                const scaleX = canvasKeluarga.width / rect.width;
                const scaleY = canvasKeluarga.height / rect.height;
                
                if (e.touches && e.touches[0]) {
                    return {
                        x: (e.touches[0].clientX - rect.left) * scaleX,
                        y: (e.touches[0].clientY - rect.top) * scaleY
                    };
                }
                
                return {
                    x: (e.clientX - rect.left) * scaleX,
                    y: (e.clientY - rect.top) * scaleY
                };
            }

            function startDrawingKeluarga(e) {
                isDrawingKeluarga = true;
                const pos = getPositionKeluarga(e);
                ctxKeluarga.beginPath();
                ctxKeluarga.moveTo(pos.x, pos.y);
            }

            function drawKeluarga(e) {
                if (!isDrawingKeluarga) return;
                e.preventDefault();
                const pos = getPositionKeluarga(e);
                ctxKeluarga.lineTo(pos.x, pos.y);
                ctxKeluarga.stroke();
            }

            function stopDrawingKeluarga() {
                isDrawingKeluarga = false;
                ctxKeluarga.beginPath();
            }

            // Mouse events
            canvasKeluarga.addEventListener('mousedown', startDrawingKeluarga);
            canvasKeluarga.addEventListener('mousemove', drawKeluarga);
            canvasKeluarga.addEventListener('mouseup', stopDrawingKeluarga);
            canvasKeluarga.addEventListener('mouseout', stopDrawingKeluarga);

            // Touch events
            canvasKeluarga.addEventListener('touchstart', (e) => {
                e.preventDefault();
                startDrawingKeluarga(e);
            });
            canvasKeluarga.addEventListener('touchmove', drawKeluarga);
            canvasKeluarga.addEventListener('touchend', (e) => {
                e.preventDefault();
                stopDrawingKeluarga();
            });
        }

        function toggleTandaTanganKeluarga() {
            const checkbox = document.getElementById('adaTandaTanganKeluarga');
            const container = document.getElementById('tandaTanganKeluargaContainer');
            
            if (checkbox.checked) {
                container.classList.remove('hidden');
                if (!canvasKeluarga) {
                    initCanvasKeluarga();
                }
            } else {
                container.classList.add('hidden');
                clearSignatureKeluarga();
                document.getElementById('namaKeluargaInput').value = '';
            }
        }

        function clearSignatureKeluarga() {
            if (ctxKeluarga && canvasKeluarga) {
                ctxKeluarga.clearRect(0, 0, canvasKeluarga.width, canvasKeluarga.height);
                document.getElementById('tandaTanganKeluargaInput').value = '';
            }
        }

        window.clearSignatureKeluarga = clearSignatureKeluarga;

        // Auto-refresh CSRF token setiap 10 menit untuk mencegah 419 Page Expired
        setInterval(function() {
            fetch('/permohonan', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(function(response) {
                return response.text();
            }).then(function(html) {
                // Extract new CSRF token from response
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newToken = doc.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                if (newToken) {
                    // Update CSRF token in meta tag
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', newToken);
                    // Update CSRF token in form
                    document.querySelector('input[name="_token"]').value = newToken;
                    console.log('CSRF token refreshed');
                }
            }).catch(function(error) {
                console.error('Failed to refresh CSRF token:', error);
            });
        }, 10 * 60 * 1000); // 10 menit
        
        // Simpan progress ke localStorage setiap ada perubahan
        const form = document.getElementById('screeningForm');
        if (form) {
            const inputs = form.querySelectorAll('input[type="radio"], textarea');
            inputs.forEach(input => {
                // Load saved value
                const savedValue = localStorage.getItem(input.name);
                if (savedValue) {
                    if (input.type === 'radio' && input.value === savedValue) {
                        input.checked = true;
                        // Trigger onchange event
                        input.dispatchEvent(new Event('change'));
                    } else if (input.tagName === 'TEXTAREA') {
                        input.value = savedValue;
                    }
                }
                
                // Save on change
                input.addEventListener('change', function() {
                    localStorage.setItem(this.name, this.value);
                });
            });
            
            // Clear localStorage on successful submit
            form.addEventListener('submit', function() {
                // Will be cleared after redirect
                setTimeout(function() {
                    localStorage.clear();
                }, 1000);
            });
        }
    </script>
</body>
</html>
