<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\SprintController;
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

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::middleware(['auth:sanctum','verified'])->get('board', [BoardController::class, 'list']);
Route::middleware(['auth:sanctum','verified'])->get('board/{id}', [BoardController::class, 'get']);
Route::middleware(['auth:sanctum','verified'])->get('board/{boardId}/sprint', [SprintController::class, 'list']);
Route::middleware(['auth:sanctum','verified'])->get('board/{boardId}/sprint/{sprintId}', [SprintController::class, 'get']);
