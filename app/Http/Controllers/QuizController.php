<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Ecosystem;
use App\Models\UserScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function show(Ecosystem $ecosystem, Request $request)
    {
        $user = $request->user();

        // Verificar si ya tiene score (bloquear reintentos) - Omitir si es admin
        if ($user->role !== 'admin') {
            $existingScore = UserScore::where('user_id', $user->id)
                                       ->where('ecosystem_id', $ecosystem->id)
                                       ->first();

            if ($existingScore) {
                return redirect()->route('ecosystem.show', $ecosystem->id)
                                 ->with('error', 'Ya has completado este quiz.');
            }
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

        // Verificar intento inicial rápido - Omitir si es admin
        if ($user->role !== 'admin') {
            if (UserScore::where('user_id', $user->id)->where('ecosystem_id', $ecosystem->id)->exists()) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Ya has completado este quiz.'], 403);
                }
                return redirect()->route('ecosystem.show', $ecosystem->id)->with('error', 'Ya has completado este quiz.');
            }
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
        // NUEVA REGLA: Solo se otorga el bono de tiempo si el usuario realiza la prueba 
        // en el mismo día que se habilitó el ecosistema (available_from).
        $timeBonus = 0;
        if ($correctAnswers > 0) {
            $isSameDay = true;
            if ($ecosystem->available_from) {
                $isSameDay = now()->isSameDay($ecosystem->available_from);
            }
            
            if ($isSameDay) {
                $timeBonus = max(0, 60 - $time_elapsed);
            }
        }

        try {
            DB::transaction(function () use ($user, $ecosystem, $baseScore, $timeBonus, $request) {
                // Bloqueamos el registro del ecosistema para serializar los envíos de este módulo
                Ecosystem::where('id', $ecosystem->id)->lockForUpdate()->first();

                // Verificar intento de nuevo dentro del bloqueo
                if ($user->role !== 'admin') {
                    if (UserScore::where('user_id', $user->id)->where('ecosystem_id', $ecosystem->id)->exists()) {
                        throw new \Exception('Ya has completado este quiz.', 403);
                    }
                }

                // Calcular el bono por orden de llegada (Early Bird Bonus)
                // Solo se otorga si el usuario actual es un usuario común (no admin ni tester)
                $earlyBirdBonus = 0;
                if ($user->role !== 'admin' && $user->role !== 'tester') {
                    $previousCompletions = UserScore::where('ecosystem_id', $ecosystem->id)
                        ->whereHas('user', function ($query) {
                            $query->whereNotIn('role', ['admin', 'tester']);
                        })
                        ->count();

                    if ($previousCompletions === 0) {
                        $earlyBirdBonus = 30; // 1er Lugar
                    } elseif ($previousCompletions === 1) {
                        $earlyBirdBonus = 20; // 2do Lugar
                    } elseif ($previousCompletions === 2) {
                        $earlyBirdBonus = 10; // 3er Lugar
                    }
                }

                $totalScore = $baseScore + $timeBonus + $earlyBirdBonus;

                UserScore::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'ecosystem_id' => $ecosystem->id,
                    ],
                    [
                        'score' => $totalScore,
                    ]
                );
            });
        } catch (\Exception $e) {
            if ($e->getCode() === 403) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => $e->getMessage()], 403);
                }
                return redirect()->route('ecosystem.show', $ecosystem->id)->with('error', $e->getMessage());
            }
            throw $e;
        }

        return redirect()->route('dashboard');
    }
}
