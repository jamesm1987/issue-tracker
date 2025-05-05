<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\UserProjectController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{project}', [ProjectController::class, 'show']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::put('/projects/{project}', [ProjectController::class, 'update']);
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);
    
    Route::post('/projects/{project}/issues', [IssueController::class, 'store']);
    Route::get('/projects/{project}/issues/{issue}', [IssueController::class, 'show']);

    Route::post('/users/{user}/projects', [UserProjectController::class, 'store']);
    Route::put('/users/{user}/projects', [UserProjectController::class, 'update']);
    

    // Route::post('/register', [AuthController::class, 'register']);

    Route::post('/invites', [InviteController::class, 'store']);
    Route::get('/invites/{token}', [InviteController::class, 'show']);
    Route::post('/invitates/accept', [InviteController::class, 'accept']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/login', [AuthController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
