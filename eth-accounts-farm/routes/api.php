<?php

use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\WorkerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['api'])->group(function () {
    Route::get('/worker/register', [WorkerController::class, 'register']);
    Route::get('/worker/list', [WorkerController::class, 'list']);
    Route::get('/worker/disable', [WorkerController::class, 'disable']);
    Route::get('/worker/enable', [WorkerController::class, 'enable']);
    Route::get('/task/create', [TaskController::class, 'create']);
});

