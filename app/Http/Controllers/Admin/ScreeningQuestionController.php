<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScreeningQuestion;
use App\Models\ScreeningQuestionCategory;
use Illuminate\Http\Request;

class ScreeningQuestionController extends Controller
{
    public function index()
    {
        $questions = ScreeningQuestion::with('category')
            ->orderBy('urutan')
            ->get();
        return view('admin.screening.questions.index', compact('questions'));
    }

    public function create()
    {
        $categories = ScreeningQuestionCategory::where('aktif', true)
            ->orderBy('urutan')
            ->get();
        return view('admin.screening.questions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:screening_question_categories,id',
            'pertanyaan' => 'required|string',
            'tipe_jawaban' => 'required|in:ya_tidak,pilihan_ganda,text',
            'pilihan_jawaban' => 'nullable|array',
            'urutan' => 'nullable|integer|min:0',
            'wajib' => 'nullable|boolean',
            'aktif' => 'nullable|boolean',
        ]);

        // Convert textarea lines to array for pilihan_jawaban
        if ($request->tipe_jawaban === 'pilihan_ganda' && isset($validated['pilihan_jawaban'][0])) {
            $pilihan = explode("\n", $validated['pilihan_jawaban'][0]);
            $validated['pilihan_jawaban'] = array_filter(array_map('trim', $pilihan));
        }

        $validated['wajib'] = $request->has('wajib');
        $validated['aktif'] = $request->has('aktif');

        ScreeningQuestion::create($validated);

        return redirect()->route('admin.screening.questions.index')
            ->with('success', 'Pertanyaan berhasil ditambahkan');
    }

    public function edit(ScreeningQuestion $question)
    {
        $categories = ScreeningQuestionCategory::where('aktif', true)
            ->orderBy('urutan')
            ->get();
        return view('admin.screening.questions.edit', compact('question', 'categories'));
    }

    public function update(Request $request, ScreeningQuestion $question)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:screening_question_categories,id',
            'pertanyaan' => 'required|string',
            'tipe_jawaban' => 'required|in:ya_tidak,pilihan_ganda,text',
            'pilihan_jawaban' => 'nullable|array',
            'urutan' => 'nullable|integer|min:0',
            'wajib' => 'nullable|boolean',
            'aktif' => 'nullable|boolean',
        ]);

        // Convert textarea lines to array for pilihan_jawaban
        if ($request->tipe_jawaban === 'pilihan_ganda' && isset($validated['pilihan_jawaban'][0])) {
            $pilihan = explode("\n", $validated['pilihan_jawaban'][0]);
            $validated['pilihan_jawaban'] = array_filter(array_map('trim', $pilihan));
        }

        $validated['wajib'] = $request->has('wajib');
        $validated['aktif'] = $request->has('aktif');

        $question->update($validated);

        return redirect()->route('admin.screening.questions.index')
            ->with('success', 'Pertanyaan berhasil diupdate');
    }

    public function destroy(ScreeningQuestion $question)
    {
        $question->delete();

        return redirect()->route('admin.screening.questions.index')
            ->with('success', 'Pertanyaan screening berhasil dihapus!');
    }
}
