<?php

namespace App\Http\Controllers\Admin;

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
        $vaksins = Vaksin::orderBy('urutan')->orderBy('nama_vaksin')->get();
        return view('admin.vaksin.index', compact('vaksins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.vaksin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_vaksin' => 'required|string|max:100|unique:vaksins,nama_vaksin',
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

        return redirect()->route('admin.vaksin.index')
            ->with('success', 'Vaksin berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vaksin $vaksin)
    {
        return view('admin.vaksin.edit', compact('vaksin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vaksin $vaksin)
    {
        $validated = $request->validate([
            'nama_vaksin' => 'required|string|max:100|unique:vaksins,nama_vaksin,' . $vaksin->id,
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

        return redirect()->route('admin.vaksin.index')
            ->with('success', 'Vaksin berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vaksin $vaksin)
    {
        $vaksin->delete();

        return redirect()->route('admin.vaksin.index')
            ->with('success', 'Vaksin berhasil dihapus!');
    }
}
