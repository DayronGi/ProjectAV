<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ObligationController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::controller(TaskController::class)->middleware('auth:sanctum')->group(function() {
    Route::get('/tasks', 'list');
    Route::post('/tasks', 'store');
    Route::get('/tasks/{task_id}', 'view');
    Route::put('/tasks/{task_id}/update', 'update');
    Route::put('/tasks/{task_id}/delete', 'delete');
});

Route::controller(MeetingController::class)->middleware('auth:sanctum')->group(function() {
    Route::get('/meetings', 'list');
    Route::post('/meetings', 'store');
    Route::get('/meetings/{meeting_id}', 'view');
    Route::put('/meetings/{meeting_id}/update', 'update');
    Route::put('/meetings/{meeting_id}/delete', 'delete');
});

Route::controller(ObligationController::class)->middleware('auth:sanctum')->group(function() {
    Route::get('/obligations', 'list');
    Route::post('/obligations', 'store');
    Route::get('/obligations/{obligation_id}', 'view');
    Route::put('/obligations/{obligation_id}/update', 'update');
    Route::put('/obligations/{obligation_id}/delete', 'delete');
});

Route::controller(StateController::class)->middleware('auth:sanctum')->group(function() {
    Route::get('/status/tasks', 'tasks');
    Route::get('/status/meetings', 'meetings');
    Route::get('/status/obligations', 'obligations');
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
