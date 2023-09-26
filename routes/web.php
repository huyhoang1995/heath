<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Rest\HealthController;
use App\Http\Controllers\Rest\PostController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authen
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::prefix('health')->group(function () {
    // Diary routes
    Route::get('/list-diary', [HealthController::class, 'getListDiary']);
    Route::post('/create-diary', [HealthController::class, 'createDiary']);
    Route::put('/update-diary/{id}', [HealthController::class, 'updateDiary']);
    Route::delete('/delete-diary/{id}', [HealthController::class, 'deleteDiary']);

    // MealHistory routes
    Route::get('/list-history-meal', [HealthController::class, 'getListHistoryMeal']);
    Route::post('/create-history-meal', [HealthController::class, 'createHistoryMeal']);
    Route::put('/update-history-meal/{id}', [HealthController::class, 'updateHistoryMeal']);
    Route::delete('/delete-history-meal/{id}', [HealthController::class, 'deleteHistoryMeal']);

    // ExerciseRecord routes
    Route::get('/list-exercise-record', [HealthController::class, 'getListExerciseRecord']);
    Route::post('/create-exercise-record', [HealthController::class, 'createExerciseRecord']);
    Route::put('/update-exercise-record/{id}', [HealthController::class, 'updateExerciseRecord']);
    Route::delete('/delete-exercise-record/{id}', [HealthController::class, 'deleteExerciseRecord']);
});

Route::prefix('posts')->group(function () {
    Route::post('/create', [PostController::class, 'createPost']);
    Route::put('/update/{id}', [PostController::class, 'updatePost']);
    Route::delete('/delete/{id}', [PostController::class, 'deletePost']);
    Route::get('/all', [PostController::class, 'getAllPosts']);
    Route::get('/detail/{id}', [PostController::class, 'detailPost']);
});