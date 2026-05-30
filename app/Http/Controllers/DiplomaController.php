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

        // Calcular puntaje total
        $totalScore = UserScore::where('user_id', $user->id)->sum('score');

        $pdf = Pdf::loadView('diploma', ['user' => $user, 'totalScore' => $totalScore]);

        return $pdf->download('Diploma_Ecotop_'.$user->name.'.pdf');
    }
}
