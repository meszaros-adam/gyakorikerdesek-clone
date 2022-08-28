<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminCheck;
use App\Http\Middleware\LoginCheck;

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

Route::prefix('/auth')->group(function () {
    Route::post('/register', [App\Http\Controllers\UserController::class, 'registration']);
    Route::post('/login', [App\Http\Controllers\UserController::class, 'login']);
    Route::get('/logout', [App\Http\Controllers\UserController::class, 'logout']);
});

//Questions
Route::post('/create_question', [App\Http\Controllers\QuestionController::class, 'add'])->middleware(LoginCheck::class);
Route::get('/get_last_five_questions', [App\Http\Controllers\QuestionController::class, 'getLastFive']);
Route::get('get_single_question', [App\Http\Controllers\QuestionController::class, 'getSingle']);

//Answers
Route::post('/create_answer', [App\Http\Controllers\AnswerController::class, 'add'])->middleware(LoginCheck::class);

//Category
Route::post('/create_category', [App\Http\Controllers\CategoryController::class, 'add'])->middleware(AdminCheck::class);
Route::get('/get_categories', [App\Http\Controllers\CategoryController::class, 'get']);
Route::get('/get_all_categories', [App\Http\Controllers\CategoryController::class, 'getAll']);
Route::post('/edit_category', [App\Http\Controllers\CategoryController::class, 'edit'])->middleware(AdminCheck::class);
Route::post('/delete_category', [App\Http\Controllers\CategoryController::class, 'delete'])->middleware(AdminCheck::class);

//Message
Route::post('/create_message', [App\Http\Controllers\MessageController::class, 'add'])->middleware(LoginCheck::class);
Route::get('/incoming_messages', [App\Http\Controllers\MessageController::class, 'getIncoming'])->middleware(LoginCheck::class);
Route::get('/sended_messages', [App\Http\Controllers\MessageController::class, 'getSended'])->middleware(LoginCheck::class);


Route::fallback(function () {
    return view('welcome');
});
