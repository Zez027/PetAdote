<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::group(['prefix'=>'usuarios'], function(){
    Route::get('/', [UserController::class, 'render']);
    Route::get('/id/{id}', [UserController::class, 'getUserById']);
});


