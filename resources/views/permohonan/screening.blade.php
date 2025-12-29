<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Screening Kesehatan - Form Vaksinasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Fix untuk iPhone/Safari - Signature Pad */
        #signaturePad, #signaturePadKeluarga {
            touch-action: none !important;
            -webkit-touch-callout: none !important;
            -webkit-user-select: none !important;
            user-select: none !important;
            -webkit-tap-highlight-color: transparent !important;
            pointer-events: auto !important;
        }
        /* Prevent iOS Safari from zooming on double tap */
        body {
            touch-action: pan-x pan-y;
        }
    </style>
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

                        <div class="bg-white border-2 border-green-300 rounded-lg overflow-hidden" style="touch-action: none; -webkit-touch-callout: none;">
                            <canvas id="signaturePad" style="width: 100%; height: 200px; touch-action: none !important; -webkit-touch-callout: none; -webkit-user-select: none; user-select: none; cursor: crosshair; display: block; position: relative; background: white;"></canvas>
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

                    <!-- Checkbox untuk Tanda Tangan Keluarga -->
                    <div class="mt-6 p-4 border-2 border-gray-200 rounded-lg bg-gray-50">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="perlu_tanda_tangan_keluarga" id="perluTandaTanganKeluarga" 
                                value="1" 
                                class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500"
                                onchange="toggleTandaTanganKeluarga()">
                            <span class="ml-3 text-gray-700 font-medium">
                                Pasien tidak dapat menandatangani sendiri, perlu tanda tangan keluarga/pendamping
                            </span>
                        </label>
                    </div>

                    <!-- Tanda Tangan Keluarga (Opsional) - Hidden by default -->
                    <div id="tandaTanganKeluargaSection" class="mt-6 p-6 border-2 border-blue-300 rounded-lg bg-blue-50 hidden">
                        <div class="mb-4">
                            <h3 class="text-lg font-bold text-blue-900 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                                Tanda Tangan Keluarga/Pendamping
                            </h3>
                            <p class="text-sm text-blue-700 mt-1">
                                Silakan isi nama dan tanda tangan keluarga/pendamping yang mewakili pasien.
                            </p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Keluarga/Pendamping <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_keluarga" id="namaKeluargaInput" 
                                value="{{ old('nama_keluarga') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Nama lengkap keluarga/pendamping">
                        </div>

                        <div class="bg-white border-2 border-blue-300 rounded-lg overflow-hidden" style="touch-action: none; -webkit-touch-callout: none;">
                            <canvas id="signaturePadKeluarga" style="width: 100%; height: 200px; touch-action: none !important; -webkit-touch-callout: none; -webkit-user-select: none; user-select: none; cursor: crosshair; display: block; position: relative; background: white;"></canvas>
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
        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
        // ========== SIGNATURE PAD - Fixed for iPhone/Safari ==========
        const canvas = document.getElementById('signaturePad');
        if (canvas) {
            // Fix canvas size for high DPI displays (iPhone/Safari)
            function setupCanvas(canvasElement) {
                const rect = canvasElement.getBoundingClientRect();
                const dpr = window.devicePixelRatio || 1;
                
                // Set actual size in memory (scaled for DPI)
                canvasElement.width = rect.width * dpr;
                canvasElement.height = rect.height * dpr;
                
                // Scale the drawing context so everything draws at the correct size
                const ctx = canvasElement.getContext('2d');
                ctx.scale(dpr, dpr);
                
                // Set CSS size to maintain visual size
                canvasElement.style.width = rect.width + 'px';
                canvasElement.style.height = rect.height + 'px';
                
                return ctx;
            }
            
            // Setup canvas after a small delay to ensure layout is complete
            setTimeout(function() {
                const ctx = setupCanvas(canvas);
                let isDrawing = false;
                let lastPos = { x: 0, y: 0 };

                // Setup canvas context
                ctx.strokeStyle = '#000000'; // Black
                ctx.lineWidth = 3;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';

                function getPosition(e) {
                    const rect = canvas.getBoundingClientRect();
                    
                    let clientX, clientY;
                    
                    // Priority: pointer events > touches > changedTouches > mouse
                    if (e.pointerType !== undefined) {
                        // Pointer Events API (modern, works on iPhone)
                        clientX = e.clientX;
                        clientY = e.clientY;
                    } else if (e.touches && e.touches.length > 0) {
                        clientX = e.touches[0].clientX;
                        clientY = e.touches[0].clientY;
                    } else if (e.changedTouches && e.changedTouches.length > 0) {
                        clientX = e.changedTouches[0].clientX;
                        clientY = e.changedTouches[0].clientY;
                    } else {
                        clientX = e.clientX;
                        clientY = e.clientY;
                    }
                    
                    return {
                        x: clientX - rect.left,
                        y: clientY - rect.top
                    };
                }

                function startDrawing(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    isDrawing = true;
                    lastPos = getPosition(e);
                    ctx.beginPath();
                    ctx.moveTo(lastPos.x, lastPos.y);
                }

                function draw(e) {
                    if (!isDrawing) return;
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const pos = getPosition(e);
                    
                    // Draw smooth line
                    ctx.beginPath();
                    ctx.moveTo(lastPos.x, lastPos.y);
                    ctx.lineTo(pos.x, pos.y);
                    ctx.stroke();
                    
                    lastPos = pos;
                }

                function stopDrawing(e) {
                    if (e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    isDrawing = false;
                    ctx.beginPath();
                }

                // Pointer Events API - Modern approach, works better on iPhone/Safari
                if (canvas.setPointerCapture) {
                    canvas.addEventListener('pointerdown', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        canvas.setPointerCapture(e.pointerId);
                        startDrawing(e);
                    });
                    
                    canvas.addEventListener('pointermove', function(e) {
                        if (isDrawing) {
                            e.preventDefault();
                            e.stopPropagation();
                            draw(e);
                        }
                    });
                    
                    canvas.addEventListener('pointerup', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        canvas.releasePointerCapture(e.pointerId);
                        stopDrawing(e);
                    });
                    
                    canvas.addEventListener('pointercancel', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        canvas.releasePointerCapture(e.pointerId);
                        stopDrawing(e);
                    });
                }

                // Mouse events (fallback for desktop)
                canvas.addEventListener('mousedown', startDrawing);
                canvas.addEventListener('mousemove', draw);
                canvas.addEventListener('mouseup', stopDrawing);
                canvas.addEventListener('mouseout', stopDrawing);
                canvas.addEventListener('mouseleave', stopDrawing);

                // Touch events - Enhanced for iPhone/iOS/Safari (fallback)
                canvas.addEventListener('touchstart', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    startDrawing(e);
                }, { passive: false });
                
                canvas.addEventListener('touchmove', function(e) {
                    if (isDrawing) {
                        e.preventDefault();
                        e.stopPropagation();
                        draw(e);
                    }
                }, { passive: false });
                
                canvas.addEventListener('touchend', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    stopDrawing(e);
                }, { passive: false });
                
                canvas.addEventListener('touchcancel', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    stopDrawing(e);
                }, { passive: false });

                // Clear signature
                window.clearSignature = function() {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    document.getElementById('tandaTanganInput').value = '';
                }

                // Check if canvas is blank
                window.isCanvasBlank = function(canvasElement) {
                    const blank = document.createElement('canvas');
                    blank.width = canvasElement.width;
                    blank.height = canvasElement.height;
                    return canvasElement.toDataURL() === blank.toDataURL();
                }

                // Form submission validation
                const form = document.getElementById('screeningForm');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        // Validate signature
                        if (window.isCanvasBlank && window.isCanvasBlank(canvas)) {
                    alert('❌ Tanda tangan belum dibuat!\n\nSilakan tanda tangan terlebih dahulu di kotak yang tersedia sebagai tanda persetujuan Anda.');
                    canvas.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }

                // Save signature as base64
                const signatureData = canvas.toDataURL('image/png');
                document.getElementById('tandaTanganInput').value = signatureData;

                        // Save family signature if checkbox is checked and signature exists
                        const perluTandaTanganKeluarga = document.getElementById('perluTandaTanganKeluarga').checked;
                        if (perluTandaTanganKeluarga && canvasKeluarga && window.isCanvasBlank && !window.isCanvasBlank(canvasKeluarga)) {
                    const signatureDataKeluarga = canvasKeluarga.toDataURL('image/png');
                    document.getElementById('tandaTanganKeluargaInput').value = signatureDataKeluarga;
                    
                    // Validate nama keluarga
                    const namaKeluarga = document.getElementById('namaKeluargaInput').value.trim();
                    if (!namaKeluarga) {
                        alert('❌ Nama keluarga/pendamping wajib diisi jika memilih tanda tangan keluarga!');
                        document.getElementById('namaKeluargaInput').focus();
                        return false;
                    }
                        } else if (perluTandaTanganKeluarga && (!canvasKeluarga || (window.isCanvasBlank && window.isCanvasBlank(canvasKeluarga)))) {
                    alert('❌ Tanda tangan keluarga wajib dibuat jika memilih opsi ini!');
                    if (canvasKeluarga) {
                        canvasKeluarga.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    return false;
                }

                        // Confirm before submit
                        if (confirm('Apakah Anda yakin data yang Anda isi sudah benar?\n\n✓ Semua pertanyaan sudah dijawab\n✓ Tanda tangan sudah dibuat\n\nData tidak dapat diubah setelah dikirim.')) {
                            // Clear localStorage
                            localStorage.clear();
                            this.submit();
                        }
                    });
                }
            }, 100); // Small delay to ensure layout is complete
        }

        // Toggle tanda tangan keluarga
        function toggleTandaTanganKeluarga() {
            const checkbox = document.getElementById('perluTandaTanganKeluarga');
            const section = document.getElementById('tandaTanganKeluargaSection');
            const namaInput = document.getElementById('namaKeluargaInput');
            
            if (checkbox.checked) {
                section.classList.remove('hidden');
                namaInput.required = true;
            } else {
                section.classList.add('hidden');
                namaInput.required = false;
                // Clear values
                namaInput.value = '';
                if (window.clearSignatureKeluarga) {
                    clearSignatureKeluarga();
                }
            }
        }

        // ========== SIGNATURE PAD KELUARGA - Fixed for iPhone/Safari ==========
        const canvasKeluarga = document.getElementById('signaturePadKeluarga');
        if (canvasKeluarga) {
            // Fix canvas size for high DPI displays (iPhone/Safari)
            function setupCanvasKeluarga(canvasElement) {
                const rect = canvasElement.getBoundingClientRect();
                const dpr = window.devicePixelRatio || 1;
                
                // Set actual size in memory (scaled for DPI)
                canvasElement.width = rect.width * dpr;
                canvasElement.height = rect.height * dpr;
                
                // Scale the drawing context so everything draws at the correct size
                const ctx = canvasElement.getContext('2d');
                ctx.scale(dpr, dpr);
                
                // Set CSS size to maintain visual size
                canvasElement.style.width = rect.width + 'px';
                canvasElement.style.height = rect.height + 'px';
                
                return ctx;
            }
            
            // Setup canvas after a small delay to ensure layout is complete
            setTimeout(function() {
                const ctxKeluarga = setupCanvasKeluarga(canvasKeluarga);
                let isDrawingKeluarga = false;
                let lastPosKeluarga = { x: 0, y: 0 };

                // Setup canvas context
                ctxKeluarga.strokeStyle = '#000000'; // Black
                ctxKeluarga.lineWidth = 3;
                ctxKeluarga.lineCap = 'round';
                ctxKeluarga.lineJoin = 'round';

                function getPositionKeluarga(e) {
                    const rect = canvasKeluarga.getBoundingClientRect();
                    
                    let clientX, clientY;
                    
                    // Priority: pointer events > touches > changedTouches > mouse
                    if (e.pointerType !== undefined) {
                        clientX = e.clientX;
                        clientY = e.clientY;
                    } else if (e.touches && e.touches.length > 0) {
                        clientX = e.touches[0].clientX;
                        clientY = e.touches[0].clientY;
                    } else if (e.changedTouches && e.changedTouches.length > 0) {
                        clientX = e.changedTouches[0].clientX;
                        clientY = e.changedTouches[0].clientY;
                    } else {
                        clientX = e.clientX;
                        clientY = e.clientY;
                    }
                    
                    return {
                        x: clientX - rect.left,
                        y: clientY - rect.top
                    };
                }

                function startDrawingKeluarga(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    isDrawingKeluarga = true;
                    lastPosKeluarga = getPositionKeluarga(e);
                    ctxKeluarga.beginPath();
                    ctxKeluarga.moveTo(lastPosKeluarga.x, lastPosKeluarga.y);
                }

                function drawKeluarga(e) {
                    if (!isDrawingKeluarga) return;
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const pos = getPositionKeluarga(e);
                    
                    // Draw smooth line
                    ctxKeluarga.beginPath();
                    ctxKeluarga.moveTo(lastPosKeluarga.x, lastPosKeluarga.y);
                    ctxKeluarga.lineTo(pos.x, pos.y);
                    ctxKeluarga.stroke();
                    
                    lastPosKeluarga = pos;
                }

                function stopDrawingKeluarga(e) {
                    if (e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    isDrawingKeluarga = false;
                    ctxKeluarga.beginPath();
                }

                // Pointer Events API - Modern approach, works better on iPhone/Safari
                if (canvasKeluarga.setPointerCapture) {
                    canvasKeluarga.addEventListener('pointerdown', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        canvasKeluarga.setPointerCapture(e.pointerId);
                        startDrawingKeluarga(e);
                    });
                    
                    canvasKeluarga.addEventListener('pointermove', function(e) {
                        if (isDrawingKeluarga) {
                            e.preventDefault();
                            e.stopPropagation();
                            drawKeluarga(e);
                        }
                    });
                    
                    canvasKeluarga.addEventListener('pointerup', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        canvasKeluarga.releasePointerCapture(e.pointerId);
                        stopDrawingKeluarga(e);
                    });
                    
                    canvasKeluarga.addEventListener('pointercancel', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        canvasKeluarga.releasePointerCapture(e.pointerId);
                        stopDrawingKeluarga(e);
                    });
                }

                // Mouse events (fallback for desktop)
                canvasKeluarga.addEventListener('mousedown', startDrawingKeluarga);
                canvasKeluarga.addEventListener('mousemove', drawKeluarga);
                canvasKeluarga.addEventListener('mouseup', stopDrawingKeluarga);
                canvasKeluarga.addEventListener('mouseout', stopDrawingKeluarga);
                canvasKeluarga.addEventListener('mouseleave', stopDrawingKeluarga);

                // Touch events - Enhanced for iPhone/iOS/Safari (fallback)
                canvasKeluarga.addEventListener('touchstart', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    startDrawingKeluarga(e);
                }, { passive: false });
                
                canvasKeluarga.addEventListener('touchmove', function(e) {
                    if (isDrawingKeluarga) {
                        e.preventDefault();
                        e.stopPropagation();
                        drawKeluarga(e);
                    }
                }, { passive: false });
                
                canvasKeluarga.addEventListener('touchend', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    stopDrawingKeluarga(e);
                }, { passive: false });
                
                canvasKeluarga.addEventListener('touchcancel', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    stopDrawingKeluarga(e);
                }, { passive: false });
            
                // Clear signature
                window.clearSignatureKeluarga = function() {
                    ctxKeluarga.clearRect(0, 0, canvasKeluarga.width, canvasKeluarga.height);
                    document.getElementById('tandaTanganKeluargaInput').value = '';
                }
            }, 100); // Small delay to ensure layout is complete
        }


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
        }); // End DOMContentLoaded
    </script>
</body>
</html>
