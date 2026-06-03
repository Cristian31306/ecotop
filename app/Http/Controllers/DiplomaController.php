<?php

namespace App\Http\Controllers;

use App\Models\Ecosystem;
use App\Models\UserScore;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DiplomaController extends Controller
{
    public function download(Request $request)
    {
        $user = $request->user();

        // Verificar si completó el ecosistema 5
        $day5Ecosystem = Ecosystem::where('day_number', 5)->first();
        
        if (!$day5Ecosystem) {
            abort(404, 'Ecosistema final no encontrado.');
        }

        $completedDay5 = UserScore::where('user_id', $user->id)
                                  ->where('ecosystem_id', $day5Ecosystem->id)
                                  ->exists();

        if (!$completedDay5) {
            abort(403, 'Debes completar todos los ecosistemas para obtener tu diploma.');
        }

        // Calcular puntaje total y bonos para el título
        $scores = UserScore::where('user_id', $user->id)->get();
        $totalScore = $scores->sum('score');
        $totalEarlyBird = $scores->sum('early_bird_bonus');
        $totalTimeBonus = $scores->sum('time_bonus');

        $title = 'Guardián del Ecosistema 🛡️';
        if ($totalEarlyBird > 50) {
            $title = 'El Madrugador Extremo 🦇';
        } elseif ($totalTimeBonus > 200) {
            $title = 'Flash Ecológico ⚡';
        } elseif ($totalScore >= 900) {
            $title = 'Sabio de la Naturaleza 🌿';
        }

        $pdf = Pdf::loadView('diploma', [
            'user' => $user, 
            'totalScore' => $totalScore,
            'title' => $title
        ]);

        return $pdf->download('Diploma_Ecotop_'.$user->name.'.pdf');
    }
}
