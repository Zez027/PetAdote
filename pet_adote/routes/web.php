<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AdoptionController;

// Rota principal (Home)
Route::get('/', [PetController::class, 'index'])->name('home');

// Rotas para visitantes (não logados)
Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Recuperação de senha
    Route::get('/password/reset', [PasswordResetController::class, 'requestForm'])->name('password.request');
    Route::post('/password/email', [PasswordResetController::class, 'sendResetEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
    Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.update');
});

// Rotas para usuários logados
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/perfil/edit', [AuthController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil/update', [AuthController::class, 'update'])->name('perfil.update');

    // --- Rotas de Pets ---
    Route::get('/meus-pets', [PetController::class, 'meusPets'])->name('pets.meus');
    Route::get('/meus-favoritos', [PetController::class, 'favoritos'])->name('pets.favoritos'); // <--- Nova rota
    
    Route::get('/pets/create', [PetController::class, 'create'])->name('pets.create');
    Route::post('/pets', [PetController::class, 'store'])->name('pets.store');
    Route::get('/pets/{pet}', [PetController::class, 'show'])->name('pets.show');
    Route::get('/pets/{pet}/edit', [PetController::class, 'edit'])->name('pets.edit');
    Route::put('/pets/{pet}', [PetController::class, 'update'])->name('pets.update');
    Route::delete('/pets/{pet}', [PetController::class, 'destroy'])->name('pets.destroy');

    // Rotas de foto do pet
    Route::post('/pets/photo/{photo}/main', [PetController::class, 'setMainPhoto'])->name('pets.photo.main');
    Route::delete('/pets/photo/{photo}', [PetController::class, 'deletePhoto'])->name('pets.photo.delete');

    // --- Rotas de Ações (Favoritar e Adotar) ---
    Route::post('/pets/{id}/favorite', [FavoriteController::class, 'toggle'])->name('pets.favorite');
    Route::post('/pets/{id}/adopt', [AdoptionController::class, 'store'])->name('pets.adopt');

    // --- Painel de Solicitações ---
    Route::get('/solicitacoes', [AdoptionController::class, 'index'])->name('adoptions.index');
    Route::post('/solicitacoes/{id}/aprovar', [AdoptionController::class, 'approve'])->name('adoptions.approve');
    Route::post('/solicitacoes/{id}/rejeitar', [AdoptionController::class, 'reject'])->name('adoptions.reject');
});