<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;


// Login routes
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Website routes
Route::get('/websites', [WebsiteController::class, 'index']);
Route::get('/websites/{website}', [WebsiteController::class, 'show']);
Route::get('/websiteByName/{website}', [WebsiteController::class, 'byName']);

Route::middleware('auth:sanctum')->group(function () {
    // Credit routes
    Route::get('/credits', [CreditController::class, 'index']);
    Route::post('/credits/add/{user}', [CreditController::class, 'addCredits']);
    Route::post('/credits/deduct/{user}', [CreditController::class, 'deductCredits']);

    // Website routes
    Route::put('/websites/{website}', [WebsiteController::class, 'update']);
    Route::delete('/websites/{website}', [WebsiteController::class, 'destroy']);

    // Contact routes
    Route::get('/websites/{website}/contacts', [ContactController::class, 'index']);
    Route::post('/websites/{website}/contacts', [ContactController::class, 'store']);
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy']);

});
