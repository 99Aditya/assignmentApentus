<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskController;
use App\Models\Category;

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
// ----------------- User Controller route start -----------------
Route::post('/registration', [UserController::class,'register']);
Route::post('/login', [UserController::class,'login']);
// ----------------- User Controller route end -----------------


Route::middleware('auth:sanctum')->group(function () {
    // ----------------- Category Controller route start -----------------
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class,'index']);
        Route::post('/save', [CategoryController::class,'save']);
        Route::put('/update/{id}', [CategoryController::class,'update']);
        Route::delete('/delete/{id}', [CategoryController::class,'delete']);
    });
    // ----------------- Category Controller route end -----------------

    // ----------------- Task Controller route start -----------------
    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class,'index']);
        Route::post('/save', [TaskController::class,'save']);
        Route::put('/update/{id}', [TaskController::class,'update']);
        Route::get('/detail/{id}', [TaskController::class,'detail']);
        Route::delete('/delete/{id}', [TaskController::class,'delete']);
    });
    // ----------------- Task Controller route end -----------------

});