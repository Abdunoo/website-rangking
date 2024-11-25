<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('welcome'); // Or your Vue.js entry point
})->where('any', '.*');
