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

        // Registrar el tiempo de inicio en la sesión del servidor (blindaje anti-trampas)
        $request->session()->put('quiz_start_' . $ecosystem->id, now()->timestamp);

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

            // Verificar si el sistema está cerrado
            $closureTimeStr = \App\Models\Setting::where('key', 'system_closure_time')->value('value');
            if ($closureTimeStr) {
                $closureTime = \Carbon\Carbon::parse($closureTimeStr);
                if (\Carbon\Carbon::now()->greaterThanOrEqualTo($closureTime)) {
                    if ($request->expectsJson()) {
                        return response()->json(['error' => 'El tiempo para responder ha terminado.'], 403);
                    }
                    return redirect()->route('dashboard')->with('error', 'El tiempo para responder ha terminado.');
                }
            }
        }

        $answers = $request->input('answers', []);
        
        // Calcular tiempo desde el servidor (ignorar por completo al cliente)
        $sessionKey = 'quiz_start_' . $ecosystem->id;
        $startTime = $request->session()->get($sessionKey);
        
        if ($startTime) {
            $time_elapsed = max(0, now()->timestamp - $startTime);
            // Limpiamos la sesión para que si intenta reenviar no recicle el tiempo
            $request->session()->forget($sessionKey); 
        } else {
            // Si llega directo al POST sin pasar por el GET (bot), o la sesión caducó, 
            // asumimos 60 segundos (lo que da 0 de bono).
            $time_elapsed = 60;
        }

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
                        'base_score' => $baseScore,
                        'time_bonus' => $timeBonus,
                        'early_bird_bonus' => $earlyBirdBonus,
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

        // Si es el último ecosistema (Día 5), redirigimos a la escena post-créditos
        if ($ecosystem->day_number == 5) {
            return redirect()->route('quiz.post_credits', $ecosystem->id);
        }

        return redirect()->route('dashboard');
    }

    public function postCredits(Ecosystem $ecosystem, Request $request)
    {
        $user = $request->user();

        // Verificar que el usuario realmente haya completado este ecosistema
        if (!UserScore::where('user_id', $user->id)->where('ecosystem_id', $ecosystem->id)->exists()) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Quiz/PostCredits', [
            'ecosystem' => $ecosystem,
        ]);
    }
}
