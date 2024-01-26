<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'Login'])->name("login");
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('logout', [AuthController::class, 'logout']);
    });
});

Route::group(['prefix' => 'task', 'middleware' => 'auth:sanctum'], function () {
    Route::get('get-tasks', [TaskController::class, 'getTask'])->name("task.get");
    Route::post('store-tasks', [TaskController::class, 'storeTask'])->name("task.store");
    Route::put('update-task/{id}', [TaskController::class, 'updateTask'])->name("task.update");
    Route::put('update-status/{id}', [TaskController::class, 'updateStatus'])->name("task.status");
    Route::delete('remove/{id}', [TaskController::class, 'removeTask'])->name("task.remove");
});
