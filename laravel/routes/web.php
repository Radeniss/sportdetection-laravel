<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ========== LOGIN DENGAN GOOGLE ==========
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// ========== HALAMAN UTAMA ==========
Route::get('/', function () {
    return view('yolo-explorer');
})->middleware('auth');

// ========== LOGIN & REGISTER ==========
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
    
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store']);
});

// ========== LOGOUT ==========
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ========== OPSIONAL: Redirect jika sudah login ==========
Route::get('/home', function () {
    return redirect('/');
});
