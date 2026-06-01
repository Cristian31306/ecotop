<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EcosystemController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\DiplomaController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/ecosystems/{ecosystem}', [DashboardController::class, 'show'])->name('ecosystem.show');
    
    Route::get('/quiz/{ecosystem}', [QuizController::class, 'show'])->name('quiz.show');
    Route::post('/quiz/{ecosystem}', [QuizController::class, 'submit'])->name('quiz.submit');

    Route::get('/api/leaderboard', [LeaderboardController::class, 'getTop10'])->name('api.leaderboard');
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
    
    Route::get('/diploma/download', [DiplomaController::class, 'download'])->name('diploma.download');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas de Administrador
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('ecosystems', EcosystemController::class);
    Route::resource('questions', QuestionController::class);
});

require __DIR__.'/auth.php';
