<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AutenticacaoController;
use App\Http\Controllers\Api\ProdutoController;

Route::get('/ping', function () {
    return response()->json([
        'api' => 'BipVendas',
        'status' => 'online'
    ]);
});



Route::post('/register', [AutenticacaoController::class, 'register']);
Route::post('/login', [AutenticacaoController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AutenticacaoController::class, 'me']);
    Route::post('/logout', [AutenticacaoController::class, 'logout']);
});

Route::get('/produtos', [ProdutoController::class, 'index']);
Route::post('/produtos', [ProdutoController::class, 'store']);
Route::get('/produtos/{codigo}', [ProdutoController::class, 'buscarPorCodigo']);
Route::put('/produtos/{id}', [ProdutoController::class, 'update']);
