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

Route::get('board', [BoardController::class, 'list']);
Route::get('board/{id}', [BoardController::class, 'get']);
Route::get('board/{boardId}/sprint', [SprintController::class, 'list']);
Route::get('board/{boardId}/sprint/{sprintId}', [SprintController::class, 'get']);