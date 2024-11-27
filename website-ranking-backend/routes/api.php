<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/websites', [WebsiteController::class, 'index']);
Route::get('/websites/{website}', [WebsiteController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me'])->name('me');
    Route::put('/auth/profile', [AuthController::class, 'updateProfile']);
    Route::delete('/auth/profile/photo', [AuthController::class, 'removeProfilePhoto']);
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change.password');
    Route::get('/websiteByName/{website}', [WebsiteController::class, 'byName']);
    Route::get('/credits', [CreditController::class, 'index']);
    Route::post('/credits/purchase-credits', [CreditController::class, 'addCredits']);
    Route::get('/credits/purchase-history', [CreditController::class, 'getHistoryPayment']);
    Route::post('/credits/deduct', [CreditController::class, 'deductCredits']);
    Route::put('/websites/{website}', [WebsiteController::class, 'update']);
    Route::delete('/websites/{website}', [WebsiteController::class, 'destroy']);
    Route::get('/websites/{website}/contacts', [ContactController::class, 'index']);
    Route::post('/websites/{website}/contacts', [ContactController::class, 'store']);
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy']);


     // Review Routes
     Route::get('/websites/{websiteId}/reviews', [ReviewController::class, 'index']);
     Route::post('/reviews', [ReviewController::class, 'store']);
     Route::put('/reviews/{reviewId}', [ReviewController::class, 'update']);
     Route::delete('/reviews/{reviewId}', [ReviewController::class, 'destroy']);
     Route::get('/my-reviews', [ReviewController::class, 'userReviews']);

     // Admin Routes
     Route::middleware(['can:admin,App\Models\Review'])->group(function () {
         Route::post('/reviews/{reviewId}/approve', [ReviewController::class, 'approveReview']);
         Route::get('/websites/{websiteId}/review-stats', [ReviewController::class, 'reviewStats']);
     });

});
