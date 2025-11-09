<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gradient-to-r from-red-600 to-pink-600">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">No</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Nama Pasien</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">NIK</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Jenis Vaksin</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($permohonan as $index => $item)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $permohonan->firstItem() + $index }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-red-400 to-pink-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($item->pasien->nama, 0, 1)) }}
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-semibold text-gray-900">{{ $item->pasien->nama }}</div>
                            <div class="text-xs text-gray-500">{{ $item->pasien->no_telp }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $item->pasien->nik ?? '-' }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">
                    @if(is_array($item->jenis_vaksin))
                        <div class="space-y-1">
                            @foreach($item->jenis_vaksin as $vaksin)
                            <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">{{ $vaksin }}</span>
                            @endforeach
                        </div>
                    @else
                        <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">{{ $item->jenis_vaksin }}</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ $item->created_at->format('d/m/Y H:i') }}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    @if(!$item->screening || !$item->screening->nilaiScreening)
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Belum Dicek
                        </span>
                    @else
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Sudah Dicek
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="flex items-center justify-center space-x-2">
                        <a href="{{ route('admin.permohonan.show', $item) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-bold shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all"
                           title="Lihat Detail & Beri Nilai">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Detail
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center">
                        <svg class="w-20 h-20 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500 font-bold text-lg">Tidak ada permohonan</p>
                        <p class="text-gray-400 text-sm mt-2">Belum ada data permohonan vaksinasi</p>
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
    {{ $permohonan->links() }}
</div>
@endif
