<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EcosystemController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\DiplomaController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\SettingsController;
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
    Route::get('/quiz/{ecosystem}/post-credits', [QuizController::class, 'postCredits'])->name('quiz.post_credits');

    Route::get('/api/leaderboard', [LeaderboardController::class, 'getTop10'])->name('api.leaderboard');
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
    
    Route::get('/diploma', [DiplomaController::class, 'show'])->name('diploma.download');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
});

// Rutas de Administrador
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('ecosystems', EcosystemController::class);
    Route::resource('questions', QuestionController::class);
    Route::get('export-podium', [LeaderboardController::class, 'export'])->name('export.podium');
    Route::get('feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
