<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\WebsiteController;

// API routes with rate limiting (60 requests per minute)
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/clients', [ClientController::class, 'index']);
    Route::get('/clients/{client:uuid}/websites', [WebsiteController::class, 'index']);
});
