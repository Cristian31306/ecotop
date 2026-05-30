<?php

namespace App\Http\Controllers;

use App\Models\UserScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function getTop10()
    {
        $topUsers = UserScore::select('user_id', DB::raw('SUM(score) as total_score'))
            ->groupBy('user_id')
            ->orderByDesc('total_score')
            ->with('user:id,name') // Carga la relación con el nombre
            ->take(10)
            ->get();

        return response()->json($topUsers);
    }
}
