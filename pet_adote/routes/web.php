<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AdoptionController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Rotas Públicas (Abertas para todos)
|--------------------------------------------------------------------------
*/
Route::get('/', [PetController::class, 'index'])->name('home');
Route::get('/pets/{pet}', [PetController::class, 'show'])->name('pets.show');

/*
|--------------------------------------------------------------------------
| Rotas para Visitantes (Apenas usuários NÃO logados)
|--------------------------------------------------------------------------
*/
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

/*
|--------------------------------------------------------------------------
| Rotas para Usuários Autenticados
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |----------------------------------------------------------------------
    | 1. Fluxo de Verificação de E-mail
    | (Estas rotas NÃO podem ter o middleware 'verified', senão gera loop)
    |----------------------------------------------------------------------
    */
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('home')->with('success', 'E-mail verificado com sucesso!');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Link de verificação reenviado!');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // Logout e Perfil Básico (Acessível sem verificação para permitir que o user saia ou corrija dados)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/perfil/edit', [AuthController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil/update', [AuthController::class, 'update'])->name('perfil.update');

    /*
    |----------------------------------------------------------------------
    | 2. Rotas Bloqueadas (Exigem E-mail Verificado)
    | O usuário só entra aqui se clicar no link do e-mail!
    |----------------------------------------------------------------------
    */
    Route::middleware(['verified'])->group(function () {
        
        // Alterar senha
        Route::get('/perfil/senha', [AuthController::class, 'editPassword'])->name('perfil.password.edit');
        Route::put('/perfil/senha', [AuthController::class, 'updatePassword'])->name('perfil.password.update');
        
        // Gerenciamento de Pets (Doador)
        Route::get('/pets/create', [PetController::class, 'create'])->name('pets.create');
        Route::post('/pets', [PetController::class, 'store'])->name('pets.store');
        Route::get('/pets/{pet}/edit', [PetController::class, 'edit'])->name('pets.edit');
        Route::put('/pets/{pet}', [PetController::class, 'update'])->name('pets.update');
        Route::delete('/pets/{pet}', [PetController::class, 'destroy'])->name('pets.destroy');

        // Fotos e Favoritos
        Route::post('/pets/photo/{photo}/main', [PetController::class, 'setMainPhoto'])->name('pets.photo.main');
        Route::delete('/pets/photo/{photo}', [PetController::class, 'deletePhoto'])->name('pets.photo.delete');
        Route::get('/meus-favoritos', [PetController::class, 'favoritos'])->name('pets.favoritos');
        Route::post('/pets/{id}/favorite', [FavoriteController::class, 'toggle'])->name('pets.favorite');

        // Fluxo de Adoção
        Route::post('/pets/{id}/adopt', [AdoptionController::class, 'store'])->name('adocoes.store');
        Route::get('/solicitacoes', [AdoptionController::class, 'index'])->name('adoptions.index');
        Route::post('/solicitacoes/{id}/aprovar', [AdoptionController::class, 'approve'])->name('adoptions.approve');
        Route::post('/solicitacoes/{id}/rejeitar', [AdoptionController::class, 'reject'])->name('adoptions.reject');
        Route::get('/meus-pedidos', [AdoptionController::class, 'meusPedidos'])->name('adoptions.meus_pedidos');
        Route::get('/meus-pets', [PetController::class, 'meusPets'])->name('pets.meus');
    });
});