<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [LogoutController::class, 'logout']);
    Route::get('/user', [UserController::class, 'user']);
    Route::put('/user', [UserController::class, 'update']);

    Route::middleware('role:author')->prefix('author')->group(function () {
        Route::put('/books/{book}', [\App\Http\Controllers\Author\BookController::class, 'update']);
        Route::delete('/books/{book}', [\App\Http\Controllers\Author\BookController::class, 'destroy']);
    });

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::apiResource('books', \App\Http\Controllers\Admin\BookController::class);
        Route::apiResource('authors', \App\Http\Controllers\Admin\AuthorController::class);
        Route::apiResource('genres', \App\Http\Controllers\Admin\GenreController::class);
    });
});

Route::get('/books', [\App\Http\Controllers\Public\BookController::class, 'index']);
Route::get('/books/{book}', [\App\Http\Controllers\Public\BookController::class, 'show']);
Route::get('/authors', [\App\Http\Controllers\Public\AuthorController::class, 'index']);
Route::get('/authors/{author}', [\App\Http\Controllers\Public\AuthorController::class, 'show']);
Route::get('/genres', [\App\Http\Controllers\Public\GenreController::class, 'index']);
