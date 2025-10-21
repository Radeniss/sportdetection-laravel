<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\YoloExplorerController;

Route::get('/', [YoloExplorerController::class, 'index'])->middleware('auth');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store']); 
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/submit-video', [AuthController::class, 'submitVideo'])->name('submit-video');
