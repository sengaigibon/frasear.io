<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PhraseSearchController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/search', [PhraseSearchController::class, 'index'])->name('phrases.search');

// Personal fraseario page (domain.com/username) — not built yet, this is
// a placeholder route so home.blade.php's "saved by" links resolve
// without erroring. Replace with the real controller in the next pass.
Route::get('/{username}', function (string $username) {
    abort(404);
})->name('frasear.show')->where('username', '[A-Za-z0-9_-]+');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';