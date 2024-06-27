<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('programs.index');
});

Route::get('/programs', [ProgramController::class, 'index']);
Route::get('/programs/data', [ProgramController::class, 'data'])->name('programs.data');
Route::post('/programs', [ProgramController::class, 'store']);
Route::get('/programs/{program}', [ProgramController::class, 'show']);
Route::put('/programs/{program}', [ProgramController::class, 'update']);
Route::delete('/programs/{program}', [ProgramController::class, 'destroy']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route::get('/programs', [ProgramController::class, 'index']);
    // Route::get('/programs/data', [ProgramController::class, 'data'])->name('programs.data');
    // Route::post('/programs', [ProgramController::class, 'store']);
    // Route::get('/programs/{program}', [ProgramController::class, 'show']);
    // Route::put('/programs/{program}', [ProgramController::class, 'update']);
    // Route::delete('/programs/{program}', [ProgramController::class, 'destroy']);
});

require __DIR__ . '/auth.php';
