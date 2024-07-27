<?php

use App\Http\Controllers\Api\CadastroClienteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CadastroUsuarioController;

Route::post('/cadastro_usuario', [CadastroUsuarioController::class, 'store']);
Route::post('/login', [CadastroUsuarioController::class, 'login']);

Route::post('/cadastro_cliente', [CadastroClienteController::class, 'store']);
Route::post('/busca_clientes', [CadastroClienteController::class, 'getClientes']);
Route::delete('/excluir_clientes/{id}', [CadastroClienteController::class, 'destroy']);

Route::get('/busca_cliente_id/{id}', [CadastroClienteController::class, 'show']);
Route::post('/edita_cliente/{id}', [CadastroClienteController::class, 'update']);

Route::get('/teste_api', function () {
    return 'teste';
});
