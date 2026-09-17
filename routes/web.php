<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PhraseSearchController;
use App\Http\Controllers\PhraseController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/search', [PhraseSearchController::class, 'index'])->name('phrases.search');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/write', [PhraseController::class, 'write'])->name('write');
    Route::post('/write', [PhraseController::class, 'store'])->name('write.store');
});

require __DIR__.'/auth.php';


use App\Http\Controllers\UserFrasearioController;

Route::get('/{username}', [UserFrasearioController::class, 'show'])->name('frasear.show')->where('username', '[A-Za-z0-9_.-]+');