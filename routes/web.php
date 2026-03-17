<?php

use App\Http\Controllers\WishController;
use Illuminate\Support\Facades\Route;

// Home — redirect ke wall of wishes
Route::get('/', function () {
    return redirect()->route('wishes.index');
});

// Wall of wishes
Route::get('/wishes', [WishController::class, 'index'])->name('wishes.index');

// Form submit ucapan
Route::get('/wishes/create', [WishController::class, 'create'])->name('wishes.create');
Route::post('/wishes', [WishController::class, 'store'])->name('wishes.store');

// Admin: Roll & Reset
Route::post('/wishes/roll', [WishController::class, 'roll'])->name('wishes.roll');
Route::post('/wishes/reset', [WishController::class, 'reset'])->name('wishes.reset');