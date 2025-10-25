<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Berhasil Dikirim</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-50 via-white to-blue-50 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Success Card -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <!-- Header with Icon -->
                <div class="bg-gradient-to-r from-green-500 to-blue-500 text-white px-8 py-12 text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full mb-4">
                        <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold mb-2">Permohonan Berhasil Dikirim!</h1>
                    <p class="text-green-100">Data Anda telah tersimpan di sistem kami</p>
                </div>

                <!-- Content -->
                <div class="p-8">
                    @if(session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                        <p class="text-green-700">{{ session('success') }}</p>
                    </div>
                    @endif

                    <div class="space-y-4 text-gray-700">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-blue-500 mt-1 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold mb-1">Langkah Selanjutnya:</h3>
                                <p class="text-sm">Tim kami akan meninjau permohonan Anda dan menghubungi melalui nomor telepon yang telah didaftarkan dalam 1-2 hari kerja.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-blue-500 mt-1 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold mb-1">Pastikan Nomor Aktif:</h3>
                                <p class="text-sm">Mohon pastikan nomor telepon Anda aktif agar kami dapat menghubungi untuk konfirmasi jadwal vaksinasi.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-blue-500 mt-1 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold mb-1">Persiapkan Dokumen:</h3>
                                <p class="text-sm">Siapkan KTP/Paspor, buku vaksinasi (jika ada), dan dokumen perjalanan lainnya saat datang untuk vaksinasi.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="mt-8 p-4 bg-blue-50 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-2">Butuh Bantuan?</h3>
                        <p class="text-sm text-gray-600 mb-2">Hubungi kami:</p>
                        <div class="space-y-1 text-sm">
                            <p class="flex items-center text-gray-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                Telepon: 0821-xxxx-xxxx
                            </p>
                            <p class="flex items-center text-gray-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Email: info@hospital.com
                            </p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex justify-center space-x-4">
                        <a href="{{ route('permohonan.create') }}" class="px-6 py-3 bg-white border-2 border-blue-500 text-blue-600 hover:bg-blue-50 font-semibold rounded-lg transition">
                            Daftar Lagi
                        </a>
                        <a href="{{ url('/') }}" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-green-600 hover:from-blue-700 hover:to-green-700 text-white font-semibold rounded-lg transition">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="mt-6 text-center text-gray-600 text-sm">
                <p>Terima kasih telah mempercayai layanan kami</p>
            </div>
        </div>
    </div>
</body>
</html>
