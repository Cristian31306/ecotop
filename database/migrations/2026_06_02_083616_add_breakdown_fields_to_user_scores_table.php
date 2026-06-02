<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Agregar las nuevas columnas de forma segura
        Schema::table('user_scores', function (Blueprint $table) {
            $table->integer('base_score')->default(0)->after('score');
            $table->integer('time_bonus')->default(0)->after('base_score');
            $table->integer('early_bird_bonus')->default(0)->after('time_bonus');
        });

        // 2. Calcular de forma retroactiva las bonificaciones para la data existente
        $ecosystemIds = DB::table('user_scores')
            ->distinct()
            ->pluck('ecosystem_id');

        foreach ($ecosystemIds as $ecosystemId) {
            // Obtener cuántas preguntas tiene el ecosistema para saber el puntaje máximo base
            $numQuestions = DB::table('questions')
                ->where('ecosystem_id', $ecosystemId)
                ->count();
            $maxBaseScore = $numQuestions * 20;

            // Obtener todos los puntajes del ecosistema ordenados por fecha de creación (los más antiguos primero)
            // Cargar también el rol del usuario para aplicar la regla de early bird solo a participantes válidos
            $userScores = DB::table('user_scores')
                ->join('users', 'user_scores.user_id', '=', 'users.id')
                ->select('user_scores.id', 'user_scores.score', 'users.role', 'user_scores.created_at')
                ->where('user_scores.ecosystem_id', $ecosystemId)
                ->orderBy('user_scores.created_at', 'asc')
                ->get();

            // Contador de finalizaciones válidas para el bono de orden de llegada (solo no-admin y no-tester)
            $validCompletionIndex = 0;

            foreach ($userScores as $us) {
                $earlyBirdBonus = 0;

                // Solo participan por bono de orden de llegada los usuarios normales (exploradores)
                if ($us->role !== 'admin' && $us->role !== 'tester') {
                    if ($validCompletionIndex === 0) {
                        $earlyBirdBonus = 30; // 1ro
                    } elseif ($validCompletionIndex === 1) {
                        $earlyBirdBonus = 20; // 2do
                    } elseif ($validCompletionIndex === 2) {
                        $earlyBirdBonus = 10; // 3ro
                    }
                    $validCompletionIndex++;
                }

                // Desglosar el remanente en base score y bono de tiempo
                $remanente = $us->score - $earlyBirdBonus;

                if ($remanente < 0) {
                    $baseScore = 0;
                    $timeBonus = 0;
                    // Si el remanente es negativo, significa que el score total era menor que el early_bird_bonus teórico
                    // en ese caso, ajustamos el early bird para no dar más puntos de los que realmente tiene el score
                    $earlyBirdBonus = $us->score;
                } elseif ($remanente > $maxBaseScore) {
                    $baseScore = $maxBaseScore;
                    $timeBonus = $remanente - $baseScore;
                } else {
                    $baseScore = (int) (floor($remanente / 20) * 20);
                    $timeBonus = $remanente - $baseScore;
                }

                // Actualizar el registro en la base de datos
                DB::table('user_scores')
                    ->where('id', $us->id)
                    ->update([
                        'base_score' => $baseScore,
                        'time_bonus' => $timeBonus,
                        'early_bird_bonus' => $earlyBirdBonus
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_scores', function (Blueprint $table) {
            $table->dropColumn(['base_score', 'time_bonus', 'early_bird_bonus']);
        });
    }
};
