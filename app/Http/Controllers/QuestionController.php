<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Ecosystem;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Questions/Index', [
            'questions' => Question::with('ecosystem')->get(),
            'ecosystems' => Ecosystem::withCount('questions')->orderBy('day_number')->get(),
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('Admin/Questions/Create', [
            'ecosystems' => Ecosystem::all(),
            'selected_ecosystem_id' => $request->query('ecosystem_id') ? (int) $request->query('ecosystem_id') : null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ecosystem_id' => 'required|exists:ecosystems,id',
            'question_text' => 'required|string|max:255',
            'options' => 'required|array|min:4|max:4',
            'correct_option_index' => 'required|integer|min:0|max:3',
            'image_file' => 'nullable|image|max:2048', // 2MB max
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('questions', 'public');
            $validated['image_url'] = '/storage/' . $path;
        }

        Question::create($validated);
        return redirect()->route('admin.questions.index')->with('success', 'Pregunta creada.');
    }

    public function edit(Question $question)
    {
        return Inertia::render('Admin/Questions/Edit', [
            'question' => $question,
            'ecosystems' => Ecosystem::all()
        ]);
    }

    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'ecosystem_id' => 'required|exists:ecosystems,id',
            'question_text' => 'required|string|max:255',
            'options' => 'required|array|min:4|max:4',
            'correct_option_index' => 'required|integer|min:0|max:3',
            'image_file' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image_file')) {
            // Eliminar imagen anterior si existe
            if ($question->image_url) {
                $oldPath = str_replace('/storage/', '', $question->image_url);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image_file')->store('questions', 'public');
            $validated['image_url'] = '/storage/' . $path;
        }

        $question->update($validated);
        return redirect()->route('admin.questions.index')->with('success', 'Pregunta actualizada.');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('admin.questions.index')->with('success', 'Pregunta eliminada.');
    }
}
