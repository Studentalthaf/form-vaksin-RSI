<!-- Tabel Permohonan PENDING - Modern & Responsive -->
<div class="overflow-x-auto">
    <table class="w-full min-w-full divide-y divide-gray-200">
        <thead class="bg-gradient-to-r from-yellow-500 to-orange-500">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">No</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">SIM RS</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Nama Pasien</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">No. Telepon</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Jenis Vaksin</th>
                <th class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($permohonan as $index => $item)
            <tr class="hover:bg-yellow-50 transition-colors duration-150">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-bold text-gray-900">{{ $permohonan->firstItem() + $index }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div>
                            <div class="text-sm font-semibold text-gray-900">{{ $item->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $item->created_at->format('H:i') }} WIB</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-mono font-bold text-blue-700">{{ $item->pasien->sim_rs }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div>
                            <div class="text-sm font-bold text-gray-900">{{ $item->pasien->nama }}</div>
                            <div class="flex items-center mt-1">
                                @if($item->is_perjalanan)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Perjalanan LN
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-700 border border-gray-200">
                                    💉 Vaksin Umum
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900 font-medium">{{ $item->pasien->no_telp }}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm font-semibold text-gray-900">{{ $item->jenis_vaksin }}</div>
                    @if($item->is_perjalanan && $item->negara_tujuan)
                    <div class="text-xs text-gray-500 mt-1 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $item->negara_tujuan }}
                    </div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="flex items-center justify-center space-x-2">
                        <a href="{{ route('admin.permohonan.show', $item) }}" 
                           class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-bold shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all"
                           title="Lihat Detail & Beri Nilai">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Detail
                        </a>
                        
                        <form action="{{ route('admin.permohonan.destroy', $item) }}" method="POST" 
                              onsubmit="return confirm('⚠️ PERHATIAN!\n\nAnda yakin ingin menghapus permohonan pasien {{ $item->pasien->nama }}?\n\nSemua data akan dihapus:\n- Data permohonan\n- Data screening\n- Foto KTP\n- Foto Paspor\n- Tanda tangan pasien\n- Tanda tangan dokter\n\nTindakan ini TIDAK BISA dibatalkan!')"
                              class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-bold shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all"
                                    title="Hapus Permohonan & Semua Data">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center">
                        <svg class="w-20 h-20 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-500 font-bold text-lg">Tidak ada permohonan pending</p>
                        <p class="text-gray-400 text-sm mt-2">Semua permohonan sudah diproses</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($permohonan->hasPages())
<div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
    {{ $permohonan->appends(request()->except('pending_page'))->links() }}
</div>
@endif
