<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


use App\Http\Controllers\UserController;

Route::get('/api/users', [UserController::class, 'index']);
Route::post('/api/users', [UserController::class, 'store']);
Route::post('/api/login', [UserController::class, 'login']);
Route::post('/api/users/update-name', [UserController::class, 'updateName']);
