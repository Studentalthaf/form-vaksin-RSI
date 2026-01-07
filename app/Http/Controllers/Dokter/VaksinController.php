<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Vaksin;
use Illuminate\Http\Request;

class VaksinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Tampilkan semua vaksin termasuk yang sudah dihapus (soft deleted)
        $vaksins = Vaksin::withTrashed()
            ->with(['creator', 'updater', 'deleter'])
            ->orderBy('urutan')
            ->orderBy('nama_vaksin')
            ->get();
        return view('dokter.vaksin.index', compact('vaksins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dokter.vaksin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_vaksin' => [
                'required',
                'string',
                'max:100',
                // Unique hanya untuk data yang belum dihapus
                \Illuminate\Validation\Rule::unique('vaksins', 'nama_vaksin')->whereNull('deleted_at')
            ],
            'deskripsi' => 'nullable|string',
            'urutan' => 'nullable|integer|min:0',
            'aktif' => 'nullable|boolean',
        ], [
            'nama_vaksin.required' => 'Nama vaksin wajib diisi.',
            'nama_vaksin.unique' => 'Nama vaksin sudah ada.',
        ]);

        // Handle checkbox aktif (jika tidak dicentang, set ke false)
        $validated['aktif'] = $request->has('aktif') ? (bool)$request->aktif : false;

        Vaksin::create($validated);

        return redirect()->route('dokter.vaksin.index')
            ->with('success', 'Vaksin berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vaksin $vaksin)
    {
        return view('dokter.vaksin.edit', compact('vaksin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vaksin $vaksin)
    {
        $validated = $request->validate([
            'nama_vaksin' => [
                'required',
                'string',
                'max:100',
                // Unique hanya untuk data yang belum dihapus, kecuali ID yang sedang diedit
                \Illuminate\Validation\Rule::unique('vaksins', 'nama_vaksin')
                    ->ignore($vaksin->id)
                    ->whereNull('deleted_at')
            ],
            'deskripsi' => 'nullable|string',
            'urutan' => 'nullable|integer|min:0',
            'aktif' => 'nullable|boolean',
        ], [
            'nama_vaksin.required' => 'Nama vaksin wajib diisi.',
            'nama_vaksin.unique' => 'Nama vaksin sudah ada.',
        ]);

        // Handle checkbox aktif (jika tidak dicentang, set ke false)
        $validated['aktif'] = $request->has('aktif') ? (bool)$request->aktif : false;

        $vaksin->update($validated);

        return redirect()->route('dokter.vaksin.index')
            ->with('success', 'Vaksin berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vaksin $vaksin)
    {
        $vaksin->delete();

        return redirect()->route('dokter.vaksin.index')
            ->with('success', 'Vaksin berhasil dihapus!');
    }
}
