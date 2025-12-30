<?php

use App\Http\Controllers\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/videos/webhook', [VideoController::class, 'handleWebhook'])
    ->name('videos.webhook');
