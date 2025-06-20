<?php

use App\Http\Controllers\PetController;
use App\Http\Controllers\AuthController;

Route::get('/', [PetController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/perfil/edit', [AuthController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil/update', [AuthController::class, 'update'])->name('perfil.update');

    Route::get('/meus-pets', [PetController::class, 'meusPets'])->name('pets.meus');

    Route::get('/pets/create', [PetController::class, 'create'])->name('pets.create');
    Route::post('/pets', [PetController::class, 'store'])->name('pets.store');
    Route::get('/pets/{pet}', [PetController::class, 'show'])->name('pets.show');
    Route::get('/pets/{pet}/edit', [PetController::class, 'edit'])->name('pets.edit');
    Route::put('/pets/{pet}', [PetController::class, 'update'])->name('pets.update');
    Route::delete('/pets/{pet}', [PetController::class, 'destroy'])->name('pets.destroy');
});

