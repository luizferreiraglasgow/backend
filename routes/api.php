<?php
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers'], function () {
    // Categories endpoints
    Route::get('/categories', 'CategoryController@index');
    Route::post('/categories', 'CategoryController@store');
    Route::get('/categories/{id}', 'CategoryController@show');
    Route::put('/categories/{id}', 'CategoryController@update');
    Route::delete('/categories/{id}', 'CategoryController@destroy');
    Route::get('categories/{id}/posts', [CategoryController::class, 'showPosts']);

    // Users endpoints
    Route::get('/users', 'UserController@index');
    Route::post('/users', 'UserController@store');
    Route::get('/users/{id}', 'UserController@show');
    Route::put('/users/{id}', 'UserController@update');
    Route::delete('/users/{id}', 'UserController@destroy');

    Route::post('/login', [LoginController::class, 'login']);

    // Posts endpoints
    Route::get('/posts', 'PostController@index');
    Route::post('/posts', 'PostController@store');
    Route::get('/posts/{id}', 'PostController@show');
    Route::put('/posts/{id}', 'PostController@update');
    Route::delete('/posts/{id}', 'PostController@destroy');
    // Posts endpoints
    Route::get('/users/{userId}/posts', [PostController::class, 'postsForUser']);

    // Comments endpoints
    Route::get('/comments', 'CommentController@index');
    Route::post('/comments', 'CommentController@store');
    Route::get('/comments/{id}', 'CommentController@show');
    Route::put('/comments/{id}', 'CommentController@update');
    Route::delete('/comments/{id}', 'CommentController@destroy');
    Route::get('/posts/{postId}/comments', [CommentController::class, 'commentsForPost']);

});