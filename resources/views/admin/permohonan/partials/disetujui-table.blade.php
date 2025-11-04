<!-- Tabel Permohonan DISETUJUI -->
<table class="w-full">
    <thead class="bg-green-600 text-white">
        <tr>
            <th class="px-4 py-4 text-left text-sm font-semibold">No</th>
            <th class="px-4 py-4 text-left text-sm font-semibold">Tanggal Pengajuan</th>
            <th class="px-4 py-4 text-left text-sm font-semibold">Nama Pasien</th>
            <th class="px-4 py-4 text-left text-sm font-semibold">SIM-RS</th>
            <th class="px-4 py-4 text-left text-sm font-semibold">No. Telepon</th>
            <th class="px-4 py-4 text-left text-sm font-semibold">Negara Tujuan</th>
            <th class="px-4 py-4 text-left text-sm font-semibold">Status</th>
            <th class="px-4 py-4 text-center text-sm font-semibold">Aksi</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-200">
        @forelse($permohonan as $index => $item)
        <tr class="hover:bg-green-50 transition">
            <td class="px-4 py-4 text-sm">{{ $permohonan->firstItem() + $index }}</td>
            <td class="px-4 py-4 text-sm whitespace-nowrap">
                <div class="font-medium text-gray-900">{{ $item->created_at->format('d/m/Y') }}</div>
                <div class="text-xs text-gray-500">{{ $item->created_at->format('H:i') }}</div>
            </td>
            <td class="px-4 py-4">
                <div class="font-semibold text-gray-900">{{ $item->pasien->nama }}</div>
                @if($item->is_perjalanan)
                <span class="inline-block mt-1 px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs font-bold">
                    ✈️ Perjalanan
                </span>
                @else
                <span class="inline-block mt-1 px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-xs font-bold">
                    💉 Vaksin Biasa
                </span>
                @endif
            </td>
            <td class="px-4 py-4 text-sm text-gray-600">{{ $item->pasien->sim_rs }}</td>
            <td class="px-4 py-4 text-sm text-gray-600">{{ $item->pasien->no_telp }}</td>
            <td class="px-4 py-4 text-sm">
                <div class="font-medium">{{ $item->negara_tujuan ?? '-' }}</div>
                @if($item->tanggal_berangkat)
                <div class="text-xs text-gray-500">{{ $item->tanggal_berangkat->format('d/m/Y') }}</div>
                @endif
            </td>
            <td class="px-4 py-4">
                <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Disetujui
                </span>
            </td>
            <td class="px-4 py-4">
                <div class="flex items-center justify-center space-x-2">
                    <a href="{{ route('admin.permohonan.show', $item) }}" 
                       class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-xs font-medium transition"
                       title="Detail">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('admin.permohonan.destroy', $item) }}" class="inline" onsubmit="return confirm('Yakin hapus permohonan ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" 
                                class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-medium transition"
                                title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="px-6 py-12 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-500 font-semibold">Belum ada permohonan yang disetujui</p>
                <p class="text-gray-400 text-sm mt-1">Permohonan yang sudah disetujui akan muncul di sini</p>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<!-- Pagination -->
@if($permohonan->hasPages())
<div class="px-4 sm:px-6 py-4 border-t bg-gray-50">
    {{ $permohonan->appends(request()->except('disetujui_page'))->links() }}
</div>
@endif
