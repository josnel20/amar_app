<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [App\Http\Controllers\LoginController::class, 'store']);
Route::get('/user', [App\Http\Controllers\UserController::class, 'index']);
Route::post('/upload-img', [App\Http\Controllers\UploadController::class, 'upload']);

Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/me', function (Request $request) {
        return response()->json([
            'user' => $request->user()
        ]);
    });

    Route::controller(App\Http\Controllers\ProdutoController::class)->prefix('produtos')->group(function () {
        Route::get('/', 'index');
        Route::get('/pesquisa/{id}', 'show');
        Route::post('/criar', 'create');
        Route::patch('/edit/{id}', 'update');
        Route::put('/inativar/{id}', 'destroy');
        Route::controller(App\Http\Controllers\ProdutoImagemController::class)->prefix('imagem')->group(function () {
            Route::get('/{id}', 'show');
            Route::post('/criar', 'create');
            Route::delete('/apagar/{id}', 'destroy');
        });
    });
});

Route::middleware('auth:sanctum')->post('/logout', [App\Http\Controllers\LoginController::class, 'logout']);
