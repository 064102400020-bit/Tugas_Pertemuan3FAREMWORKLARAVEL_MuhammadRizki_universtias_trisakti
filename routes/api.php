<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;

/*
|--------------------------------------------------------------------------
| API Routes - booksales-api
|--------------------------------------------------------------------------
*/

Route::apiResource('authors', AuthorController::class);
Route::apiResource('books', BookController::class);