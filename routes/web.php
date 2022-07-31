<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Auth

Route::prefix('/auth')->group(function(){
    Route::post('/register', [App\Http\Controllers\UserController::class, 'registration']);
    Route::post('/login', [App\Http\Controllers\UserController::class, 'login']);
    Route::get('/logout', [App\Http\Controllers\UserController::class, 'logout']);
});

//Questions
Route::post('/create_question', [App\Http\Controllers\QuestionController::class, 'add']);

//Category
Route::post('/create_category', [App\Http\Controllers\CategoryController::class, 'add']);
Route::get('/get_categories', [App\Http\Controllers\CategoryController::class, 'get']);


Route::get('/', function () {
    return view('welcome');
});


Route::fallback( function () {
    return view('welcome');
});
