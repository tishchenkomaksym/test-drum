<?php


use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\Auth\LoginController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('register', [ RegisterController::class, 'register']);
Route::post('login', [ LoginController::class, 'login']);

Route::group([
    'middleware' => ['auth:sanctum']
], function () {
    Route::get('tasks', [ TaskController::class, 'index']);
    Route::get('task/{task}', [ TaskController::class, 'show']);
    Route::post('task', [ TaskController::class, 'store']);
    Route::post('task/sort', [ TaskController::class, 'sortTask']);
    Route::put('task/{task}', [ TaskController::class, 'update']);
    Route::patch('task/{task}/complete', [ TaskController::class, 'completeTask']);
    Route::delete('task/{task}', [ TaskController::class, 'destroy']);
});
