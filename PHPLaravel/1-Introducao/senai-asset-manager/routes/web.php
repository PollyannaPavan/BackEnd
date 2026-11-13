<?php

use App\Http\Controllers\AtivoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProdutoController;
use App\Models\Cliente;
use Illuminate\Support\Facades\Route;

// Rota Principal da Minha aplicação: Dashboard de Ativos Fabris
Route::get("/", [AtivoController::class, "index"])->name("ativos.index");

// Rota de Infraestrutura para exibir o status em um json
Route::get("/status", [AtivoController::class, "status"])->name("ativos.status");

// Rota de Clientes
Route::resource("clientes", ClienteController::class)->whereNumber("clientes");

// Rota Customizada para a busca por setor
Route::get("/clientes/setor/{setor?}", [ClienteController::class, "filtrarPorSetor"])
    ->name("clientes.setor")
    ->whereAlphaNumeric("setor");

// Rota de Produtos
Route::resource("produtos", ProdutoController::class)->whereNumber("produtos");
