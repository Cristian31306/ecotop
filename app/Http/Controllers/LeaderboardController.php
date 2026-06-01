<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Rap2hpoutre\FastExcel\FastExcel;

class LeaderboardController extends Controller
{
    public function index()
    {
        $users = User::select('users.id', 'users.name', 'users.email', DB::raw('COALESCE(SUM(user_scores.score), 0) as total_score'))
            ->leftJoin('user_scores', 'users.id', '=', 'user_scores.user_id')
            ->whereNotIn('users.role', ['admin', 'tester'])
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_score')
            ->orderBy('users.name')
            ->with(['scores.ecosystem' => function ($query) {
                $query->select('id', 'title', 'day_number');
            }])
            ->get();

        return Inertia::render('Leaderboard/Index', [
            'users' => $users
        ]);
    }

    public function getTop10()
    {
        $topUsers = UserScore::select('user_scores.user_id', DB::raw('SUM(user_scores.score) as total_score'))
            ->join('users', 'user_scores.user_id', '=', 'users.id')
            ->whereNotIn('users.role', ['admin', 'tester'])
            ->groupBy('user_scores.user_id', 'users.name')
            ->orderByDesc('total_score')
            ->orderBy('users.name')
            ->with('user:id,name') // Carga la relación con el nombre
            ->take(10)
            ->get();

        return response()->json($topUsers);
    }

    public function export()
    {
        $ecosystems = \App\Models\Ecosystem::orderBy('day_number')->get();
        
        $users = User::select('users.id', 'users.name', 'users.email', DB::raw('COALESCE(SUM(user_scores.score), 0) as total_score'))
            ->leftJoin('user_scores', 'users.id', '=', 'user_scores.user_id')
            ->whereNotIn('users.role', ['admin', 'tester'])
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_score')
            ->orderBy('users.name')
            ->with(['scores.ecosystem'])
            ->get();

        return (new FastExcel($users))->download('podio.xlsx', function ($user) use ($ecosystems) {
            $row = [
                'Nombre' => $user->name,
                'Correo' => $user->email,
            ];
            
            foreach ($ecosystems as $ecosystem) {
                $score = $user->scores->firstWhere('ecosystem_id', $ecosystem->id);
                $row['Día ' . $ecosystem->day_number . ' - ' . $ecosystem->title] = $score ? $score->score : 0;
            }
            
            $row['Puntaje Total'] = $user->total_score;
            
            return $row;
        });
    }
}
