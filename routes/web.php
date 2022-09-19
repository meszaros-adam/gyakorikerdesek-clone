<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminCheck;
use App\Http\Middleware\LoginCheck;
use App\Http\Middleware\QuestionPermissionCheck;

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
Route::get('/get_questions', [App\Http\Controllers\QuestionController::class, 'get']);
Route::post('/edit_question', [App\Http\Controllers\QuestionController::class, 'edit'])->middleware(AdminCheck::class);
Route::post('/delete_question', [App\Http\Controllers\QuestionController::class, 'delete'])->middleware(QuestionPermissionCheck::class);
Route::get('/get_my_questions', [App\Http\Controllers\QuestionController::class, 'getMyQuestions'])->middleware(LoginCheck::class);


//Answers
Route::post('/create_answer', [App\Http\Controllers\AnswerController::class, 'add'])->middleware(LoginCheck::class);
Route::post('/get_answers', [App\Http\Controllers\AnswerController::class, 'get'])->middleware(AdminCheck::class);

//Category
Route::post('/create_category', [App\Http\Controllers\CategoryController::class, 'add'])->middleware(AdminCheck::class);
Route::get('/get_categories', [App\Http\Controllers\CategoryController::class, 'get']);
Route::get('/get_all_categories', [App\Http\Controllers\CategoryController::class, 'getAll']);
Route::post('/edit_category', [App\Http\Controllers\CategoryController::class, 'edit'])->middleware(AdminCheck::class);
Route::post('/delete_category', [App\Http\Controllers\CategoryController::class, 'delete'])->middleware(AdminCheck::class);

//Tag
Route::post('/create_tag', [App\Http\Controllers\TagController::class, 'add'])->middleware(AdminCheck::class);
Route::get('/get_tags', [App\Http\Controllers\TagController::class, 'get']);
Route::post('/edit_tag', [App\Http\Controllers\TagController::class, 'edit'])->middleware(AdminCheck::class);
Route::post('/delete_tag', [App\Http\Controllers\TagController::class, 'delete'])->middleware(AdminCheck::class);

//Message
Route::post('/create_message', [App\Http\Controllers\MessageController::class, 'add'])->middleware(LoginCheck::class);
Route::get('/incoming_messages', [App\Http\Controllers\MessageController::class, 'getIncoming'])->middleware(LoginCheck::class);
Route::get('/sended_messages', [App\Http\Controllers\MessageController::class, 'getSended'])->middleware(LoginCheck::class);
Route::get('/unreaded_messages_count', [App\Http\Controllers\MessageController::class, 'getUnreadedCount'])->middleware(LoginCheck::class);
Route::post('/set_messages_to_readed', [App\Http\Controllers\MessageController::class, 'setMessagesTopReaded'])->middleware(LoginCheck::class);

//User
Route::get('get_users', [App\Http\Controllers\UserController::class, 'get'])->middleware(AdminCheck::class);
Route::post('edit_user', [App\Http\Controllers\UserController::class, 'edit'])->middleware(AdminCheck::class);
Route::post('delete_user', [App\Http\Controllers\UserController::class, 'delete'])->middleware(AdminCheck::class);


Route::fallback(function () {
    return view('welcome');
});
