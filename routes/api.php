<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseTopicController;

Route::get('/test', function () {
    return 'test laravel api';
});

// Route::apiResource('courses', CourseController::class);

// Public routes - anyone can view courses
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{course}', [CourseController::class, 'show']);

// Public routes for course topics
Route::get('/courses/{course}/topics', [CourseTopicController::class, 'index']);
Route::get('/courses/{course}/topics/{topic}', [CourseTopicController::class, 'show']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Admin-only routes for course management
    Route::post('/courses', [CourseController::class, 'store']);
    Route::put('/courses/{course}', [CourseController::class, 'update']);
    Route::delete('/courses/{course}', [CourseController::class, 'destroy']);
    
    // Admin-only routes for course topics
    Route::post('/courses/{course}/topics', [CourseTopicController::class, 'store']);
    Route::put('/courses/{course}/topics/{topic}', [CourseTopicController::class, 'update']);
    Route::delete('/courses/{course}/topics/{topic}', [CourseTopicController::class, 'destroy']);
    
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/sessions', [AuthController::class, 'sessions']);
    Route::delete('/sessions/{tokenId}', [AuthController::class, 'revokeSession']);
});
