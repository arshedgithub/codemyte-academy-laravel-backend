<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;

Route::get('/test', function () {
    return 'test laravel api';
});

Route::apiResource('courses', CourseController::class);

// Route::post('register',  )
