<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/admin/login', [AuthController::class, 'loginAsAdmin'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/public/websites', [WebsiteController::class, 'getLstWebsites']);
Route::get('/public/categories', [CategoryController::class, 'listCategories']);
Route::get('/public/websites/{website}', [WebsiteController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me'])->name('me');
    Route::put('/auth/profile', [AuthController::class, 'updateProfile']);
    Route::delete('/auth/profile/photo', [AuthController::class, 'removeProfilePhoto']);
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change.password');
    Route::get('/websiteByName/{website}', [WebsiteController::class, 'byName']);
    Route::post('/credits/purchase-credits', [CreditController::class, 'addCredits']);
    Route::get('/credits/purchase-history', [CreditController::class, 'getHistoryPayment']);
    Route::post('/credits/deduct', [CreditController::class, 'deductCredits']);
    Route::put('/websites/{website}', [WebsiteController::class, 'update']);
    Route::delete('/websites/{website}', [WebsiteController::class, 'destroy']);
    Route::get('/websites/{website}/contacts', [ContactController::class, 'index']);
    Route::post('/websites/{website}/contacts', [ContactController::class, 'store']);
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy']);
    Route::apiResource('/categories', CategoryController::class);


    // Review Routes
    //  Route::get('/websites/{websiteId}/reviews', [ReviewController::class, 'index']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{reviewId}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{reviewId}', [ReviewController::class, 'destroy']);
    Route::get('/my-reviews', [ReviewController::class, 'userReviews']);

    Route::get('/websites/{websiteId}/review-stats', [ReviewController::class, 'reviewStats']);

    // Admin Routes
    //  Route::middleware(['can:admin,App\Models\Review'])->group(function () {
    //      Route::post('/reviews/{reviewId}/approve', [ReviewController::class, 'approveReview']);
    //  });


    Route::middleware(['can:admin'])->group(function () {
        Route::get('/admin/websites/update-rankings', [WebsiteController::class, 'updateRankings']);

        Route::get('/admin/website-rankings', [AdminController::class, 'getWebsiteRankings']);
        Route::get('/admin/stats', [AdminController::class, 'getStats']);

        Route::get('/admin/users/search', [UserController::class, 'searchUser']);


        Route::apiResource('/admin/websites', WebsiteController::class);
        Route::apiResource('/admin/categories', CategoryController::class);
        Route::apiResource('/admin/credits', CreditController::class);
        Route::apiResource('/admin/reviews', ReviewController::class);
        Route::apiResource('/admin/users', UserController::class);


        Route::get('/admin/reviews/update-all-website-ratings', [ReviewController::class, 'updateAllWebsiteRatings']);
        Route::post('/admin/reviews/update-all', [ReviewController::class, 'updateAllReview']);
        Route::get('/admin/reviews/{reviewId}/approve', [ReviewController::class, 'approveReview']);
        Route::get('/admin/reviews/{reviewId}/reject', [ReviewController::class, 'rejectReview']);
        Route::get('/admin/credits/{creditId}/approve', [CreditController::class, 'approveCredit']);
        Route::post('/admin/credits/add', [CreditController::class, 'addCredit']);

    });
});
