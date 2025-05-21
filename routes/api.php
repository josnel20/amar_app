<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [App\Http\Controllers\LoginController::class, 'store']);
Route::get('/user', [App\Http\Controllers\UserController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::controller(App\Http\Controllers\ProdutoController::class)->prefix('produtos')->group(function () {
        Route::get('/', 'index');
        Route::get('/create', 'create');
        Route::get('/{id}/edit', 'edit');
        Route::put('/{id}', 'update');
        Route::put('/{id}/inativar', 'inativar');
    });
});
