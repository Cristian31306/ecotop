<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Ecosystem;
use App\Models\UserScore;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $isAdmin = $user->role === 'admin';
        $ecosystems = Ecosystem::orderBy('day_number')->get();
        $userScores = UserScore::where('user_id', $user->id)->pluck('score', 'ecosystem_id')->toArray();

        // Determinar estado de cada ecosistema (bloqueado/desbloqueado) para TODOS (incluyendo admin visualmente)
        $ecosystemsData = $ecosystems->map(function ($eco, $index) use ($userScores, $ecosystems) {
            $isLocked = false;
            $availableDate = null;
            
            // 1. Bloqueo por Progreso (Día anterior no completado)
            if ($index > 0) {
                $prevEcoId = $ecosystems[$index - 1]->id;
                if (!isset($userScores[$prevEcoId])) {
                    $isLocked = true;
                }
            }

            // 2. Bloqueo por Fecha Programada
            if ($eco->available_from && now() < $eco->available_from) {
                $isLocked = true;
                // Retornar la fecha formateada amigable
                $availableDate = $eco->available_from->isoFormat('D [de] MMMM [a las] h:mm A');
            }

            return [
                'id' => $eco->id,
                'day_number' => $eco->day_number,
                'title' => $eco->title,
                'is_locked' => $isLocked,
                'available_date' => $availableDate,
                'score' => $userScores[$eco->id] ?? null,
            ];
        });

        return Inertia::render('Dashboard', [
            'ecosystems' => $ecosystemsData,
            'userScoresCount' => count($userScores),
            'isAdmin' => $isAdmin,
        ]);
    }

    public function show(Ecosystem $ecosystem, Request $request)
    {
        $user = $request->user();
        $isAdmin = $user->role === 'admin';
        $userScores = UserScore::where('user_id', $user->id)->pluck('score', 'ecosystem_id')->toArray();

        // Verificar que pueda acceder si no es admin
        if (!$isAdmin) {
            // Verificar bloqueo por día anterior
            if ($ecosystem->day_number > 1) {
                $prevEco = Ecosystem::where('day_number', $ecosystem->day_number - 1)->first();
                if ($prevEco && !isset($userScores[$prevEco->id])) {
                    abort(403, 'Ecosistema bloqueado. Completa el día anterior.');
                }
            }
            
            // Verificar bloqueo por fecha
            if ($ecosystem->available_from && now() < $ecosystem->available_from) {
                abort(403, 'Este ecosistema aún no está disponible. Se abrirá el ' . $ecosystem->available_from->isoFormat('D [de] MMMM [a las] h:mm A'));
            }
        }

        $hasCompleted = isset($userScores[$ecosystem->id]);

        return Inertia::render('Ecosystem/Show', [
            'ecosystem' => $ecosystem,
            'hasCompleted' => $hasCompleted,
            'score' => $userScores[$ecosystem->id] ?? null,
            'canRetry' => $isAdmin,
        ]);
    }
}
