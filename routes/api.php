<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RecipeController;
use App\Http\Controllers\Api\AuthController;

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
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');

// Get all the recipes
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes');
Route::get('/recipes/{id}', [RecipeController::class, 'show']);

// Recipe routes (protected with auth:api middleware)
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/recipes', [RecipeController::class, 'store']);
    Route::put('/recipes/{id}', [RecipeController::class, 'update']);
    Route::delete('/recipes/{id}', [RecipeController::class, 'destroy']);

    // Additional optional routes
//     Route::get('/recipes/search', [RecipeController::class, 'search']);
//     Route::get('/recipes/categories', [RecipeCategoryController::class, 'index']);
//     Route::get('/recipes/categories/{id}', [RecipeCategoryController::class, 'show']);
//     Route::post('/recipes/{id}/favorite', [RecipeFavoriteController::class, 'store']);
//     Route::delete('/recipes/{id}/favorite', [RecipeFavoriteController::class, 'destroy']);
//     Route::post('/recipes/{id}/rate', [RecipeRatingController::class, 'store']);

});