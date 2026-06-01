<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Ecosystem;
use App\Models\UserScore;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function show(Ecosystem $ecosystem, Request $request)
    {
        $user = $request->user();

        // Verificar si ya tiene score (bloquear reintentos)
        $existingScore = UserScore::where('user_id', $user->id)
                                  ->where('ecosystem_id', $ecosystem->id)
                                  ->first();

        if ($existingScore) {
            return redirect()->route('ecosystem.show', $ecosystem->id)
                             ->with('error', 'Ya has completado este quiz.');
        }

        // Obtener preguntas (sin la respuesta correcta) y mezclar opciones
        $questions = $ecosystem->questions()->select('id', 'question_text', 'options', 'image_url')->inRandomOrder()->get();

        $questions->transform(function ($question) {
            $shuffledOptions = [];
            foreach ($question->options as $index => $text) {
                $shuffledOptions[] = ['id' => $index, 'text' => $text];
            }
            shuffle($shuffledOptions);
            
            $question->options = $shuffledOptions;
            return $question;
        });

        return Inertia::render('Quiz/Show', [
            'ecosystem' => $ecosystem,
            'questions' => $questions,
        ]);
    }

    public function submit(Ecosystem $ecosystem, Request $request)
    {
        $user = $request->user();

        // Verificar intento
        if (UserScore::where('user_id', $user->id)->where('ecosystem_id', $ecosystem->id)->exists()) {
            return response()->json(['error' => 'Ya has completado este quiz.'], 403);
        }

        $answers = $request->input('answers', []);
        $time_elapsed = (int) $request->input('time_elapsed', 60); // Segundos que tardó, default 60
        $questions = $ecosystem->questions()->get();
        
        $baseScore = 0;
        $correctAnswers = 0;

        foreach ($questions as $question) {
            if (isset($answers[$question->id]) && (int)$answers[$question->id] === (int)$question->correct_option_index) {
                $baseScore += 20; // 20 pts por respuesta correcta
                $correctAnswers++;
            }
        }

        // Sistema equilibrado de tiempo: 
        // Solo recibe bono si tuvo al menos 1 respuesta correcta.
        // Mientras menos tiempo (segundos), más bono. Máximo 60 de bono.
        $timeBonus = 0;
        if ($correctAnswers > 0) {
            $timeBonus = max(0, 60 - $time_elapsed);
        }

        $totalScore = $baseScore + $timeBonus;

        UserScore::create([
            'user_id' => $user->id,
            'ecosystem_id' => $ecosystem->id,
            'score' => $totalScore,
        ]);

        return redirect()->route('dashboard');
    }
}
