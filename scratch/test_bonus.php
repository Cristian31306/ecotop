<?php

// Inicializar Laravel de manera externa
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

use Illuminate\Contracts\Console\Kernel;
$app->make(Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Ecosystem;
use App\Models\UserScore;
use Illuminate\Support\Facades\DB;

// Iniciar una transacción para no dejar basura en la BD
DB::beginTransaction();

try {
    echo "=== Iniciando Pruebas de Bono por Orden de Llegada ===\n";

    // 1. Obtener o crear un ecosistema de prueba
    $ecosystem = Ecosystem::firstOrCreate(
        ['day_number' => 99],
        ['title' => 'Ecosistema de Prueba', 'content' => 'Contenido de prueba', 'is_active' => true]
    );
    echo "Ecosistema ID: {$ecosystem->id} creado/obtenido.\n\n";

    // 2. Crear usuarios de prueba
    $u1 = User::create(['name' => 'User 1', 'email' => 'u1@test.com', 'password' => bcrypt('password'), 'role' => 'user']);
    $u2 = User::create(['name' => 'User 2', 'email' => 'u2@test.com', 'password' => bcrypt('password'), 'role' => 'user']);
    $u3 = User::create(['name' => 'User 3', 'email' => 'u3@test.com', 'password' => bcrypt('password'), 'role' => 'user']);
    $u4 = User::create(['name' => 'User 4', 'email' => 'u4@test.com', 'password' => bcrypt('password'), 'role' => 'user']);
    
    $admin = User::create(['name' => 'Admin Test', 'email' => 'admin_t@test.com', 'password' => bcrypt('password'), 'role' => 'admin']);
    $tester = User::create(['name' => 'Tester Test', 'email' => 'tester_t@test.com', 'password' => bcrypt('password'), 'role' => 'tester']);

    echo "Usuarios creados con éxito.\n\n";

    // 3. Simular que responden en orden y calcular bonos
    $usersToTest = [
        ['user' => $u1, 'expected_bonus' => 30, 'name' => 'Usuario 1 (Primero)'],
        ['user' => $admin, 'expected_bonus' => 0, 'name' => 'Administrador (Excluido de bonos)'],
        ['user' => $u2, 'expected_bonus' => 20, 'name' => 'Usuario 2 (Segundo)'],
        ['user' => $tester, 'expected_bonus' => 0, 'name' => 'Tester (Excluido de bonos)'],
        ['user' => $u3, 'expected_bonus' => 10, 'name' => 'Usuario 3 (Tercero)'],
        ['user' => $u4, 'expected_bonus' => 0, 'name' => 'Usuario 4 (Cuarto)']
    ];

    foreach ($usersToTest as $case) {
        $currentUser = $case['user'];
        $expected = $case['expected_bonus'];
        $caseName = $case['name'];

        // Lógica del bono copiada exactamente de QuizController:
        $earlyBirdBonus = 0;
        if ($currentUser->role !== 'admin' && $currentUser->role !== 'tester') {
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

        // Guardar el score en la base de datos para la siguiente iteración
        UserScore::create([
            'user_id' => $currentUser->id,
            'ecosystem_id' => $ecosystem->id,
            'score' => 100 + $earlyBirdBonus // Base 100 + bono
        ]);

        echo "Resultado para {$caseName}:\n";
        echo "  - Bono obtenido: {$earlyBirdBonus} pts\n";
        echo "  - Bono esperado: {$expected} pts\n";
        
        if ($earlyBirdBonus === $expected) {
            echo "  [OK] ¡Prueba superada!\n\n";
        } else {
            echo "  [ERROR] El bono no coincide con lo esperado.\n\n";
            throw new Exception("Prueba fallida para {$caseName}");
        }
    }

    echo "=== Todas las pruebas locales finalizaron con ÉXITO ===\n";

} catch (Exception $e) {
    echo "\nError durante la prueba: " . $e->getMessage() . "\n";
} finally {
    // Revertir todo para no ensuciar la base de datos local
    DB::rollBack();
    echo "Base de datos restablecida (Rollback completado).\n";
}
