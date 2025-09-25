<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthenticationController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
    Route::get('/me', 'me')->middleware('auth:sanctum');
});

Route::group(['middleware' => ['auth:sanctum','userLog']], function () {
    // Projects
    Route::apiResource('projects', ProjectController::class);

    //Tasks
    Route::get('/projects/{project_id}/tasks', [TaskController::class, 'index']);
    Route::post('/projects/{project_id}/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{task_id}', [TaskController::class, 'show']);
    Route::put('/tasks/{task_id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task_id}', [TaskController::class, 'destroy']);

    // Comment
    Route::post('tasks/{task_id}/comments', [TaskController::class, 'addComment']);
    Route::get('tasks/{task_id}/comments', [TaskController::class, 'getComments']);

});
