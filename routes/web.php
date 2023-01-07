<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminCheck;
use App\Http\Middleware\LoginCheck;
use App\Http\Middleware\QuestionPermissionCheck;
use Illuminate\Auth\Events\Login;

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
Route::get('/get_my_answered_questions', [App\Http\Controllers\QuestionController::class, 'getMyAnsweredQuestions'])->middleware(LoginCheck::class);
Route::get('/get_questions_by_category', [App\Http\Controllers\QuestionController::class, 'getByCategory']);
Route::get('/get_questions_by_tag', [App\Http\Controllers\QuestionController::class, 'getByTag']);
Route::get('/get_all_questions', [App\Http\Controllers\QuestionController::class, 'getAll']);
Route::get('/get_questions_by_watched_tags', [App\Http\Controllers\QuestionController::class, 'getByWatchedTags']);
Route::get('/search_question', [App\Http\Controllers\QuestionController::class, 'search']);

//Answers
Route::post('/create_answer', [App\Http\Controllers\AnswerController::class, 'add'])->middleware(LoginCheck::class);
Route::post('/edit_answer', [App\Http\Controllers\AnswerController::class, 'edit'])->middleware(AdminCheck::class);
Route::post('/delete_answer', [App\Http\Controllers\AnswerController::class, 'delete'])->middleware(AdminCheck::class);
Route::post('/rate_answer', [App\Http\Controllers\AnswerController::class, 'rate'])->middleware(LoginCheck::class);


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
Route::get('/get_popular_tags', [App\Http\Controllers\TagController::class, 'getPopular']);
Route::post('/add_tag_to_watchlist', [App\Http\Controllers\TagController::class, 'addToWatchlist'])->middleware(LoginCheck::class);
Route::post('/remove_tag_from_watchlist', [App\Http\Controllers\TagController::class, 'removeFromWatchlist'])->middleware(LoginCheck::class);
Route::get('/get_watched_tags', [App\Http\Controllers\TagController::class, 'getWatched'])->middleware(LoginCheck::class);
Route::post('delete_watched_tag', [App\Http\Controllers\TagController::class, 'deleteWatched'])->middleware(LoginCheck::class);


//Message
Route::post('/create_message', [App\Http\Controllers\MessageController::class, 'add'])->middleware(LoginCheck::class);
Route::get('/incoming_messages', [App\Http\Controllers\MessageController::class, 'getIncoming'])->middleware(LoginCheck::class);
Route::get('/sended_messages', [App\Http\Controllers\MessageController::class, 'getSended'])->middleware(LoginCheck::class);
Route::get('/unreaded_messages_count', [App\Http\Controllers\MessageController::class, 'getUnreadedCount'])->middleware(LoginCheck::class);
Route::post('/set_message_to_readed', [App\Http\Controllers\MessageController::class, 'setMessageToReaded'])->middleware(LoginCheck::class);

//User
Route::get('/get_users', [App\Http\Controllers\UserController::class, 'get'])->middleware(AdminCheck::class);
Route::post('/edit_user', [App\Http\Controllers\UserController::class, 'edit'])->middleware(AdminCheck::class);
Route::post('/edit_my_profile', [App\Http\Controllers\UserController::class, 'editMyProfile'])->middleware(LoginCheck::class);
Route::post('/delete_user', [App\Http\Controllers\UserController::class, 'delete'])->middleware(AdminCheck::class);
Route::get('/get_user_data', [App\Http\Controllers\UserController::class, 'getUserData'])->middleware(LoginCheck::class);
Route::post('/change_password', [App\Http\Controllers\UserController::class, 'changePassword'])->middleware(LoginCheck::class);

Route::fallback(function () {
    return view('welcome');
});
