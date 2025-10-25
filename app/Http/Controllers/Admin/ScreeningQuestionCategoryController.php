<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScreeningQuestionCategory;
use Illuminate\Http\Request;

class ScreeningQuestionCategoryController extends Controller
{
    public function index()
    {
        $categories = ScreeningQuestionCategory::withCount('questions')
            ->orderBy('urutan')
            ->get();
        return view('admin.screening.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.screening.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'urutan' => 'nullable|integer|min:0',
            'aktif' => 'boolean',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
        ]);

        ScreeningQuestionCategory::create($validated);

        return redirect()->route('admin.screening.categories.index')
            ->with('success', 'Kategori pertanyaan berhasil ditambahkan!');
    }

    public function edit(ScreeningQuestionCategory $category)
    {
        return view('admin.screening.categories.edit', compact('category'));
    }

    public function update(Request $request, ScreeningQuestionCategory $category)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'urutan' => 'nullable|integer|min:0',
            'aktif' => 'boolean',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
        ]);

        $category->update($validated);

        return redirect()->route('admin.screening.categories.index')
            ->with('success', 'Kategori pertanyaan berhasil diupdate!');
    }

    public function destroy(ScreeningQuestionCategory $category)
    {
        // Cek apakah kategori memiliki pertanyaan
        if ($category->questions()->count() > 0) {
            return redirect()->route('admin.screening.categories.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih memiliki pertanyaan!');
        }

        $category->delete();

        return redirect()->route('admin.screening.categories.index')
            ->with('success', 'Kategori pertanyaan berhasil dihapus!');
    }
}
