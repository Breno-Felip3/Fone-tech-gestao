<?php

use App\Http\Controllers\{
    EstoqueController,
    ProdutoController
};

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('/produtos')->group(function(){
    Route::any('/index', [ProdutoController::class, 'index'])->name('produto.index');
    Route::get('/show', [ProdutoController::class, 'showAllProdutos']);
    Route::post('/salvar', [ProdutoController::class, 'store']);
    Route::get('/show/{id}', [ProdutoController::class, 'show']);
    Route::put('/atualizar/{id}', [ProdutoController::class, 'update'])->name('produto.update');
    Route::delete('/deletar/{id}', [ProdutoController::class, 'destroy'])->name('produto.delete');
});

Route::prefix('/estoques')->group(function(){
    Route::get('/index', [EstoqueController::class, 'index'])->name('estoque.index');
    Route::get('/show', [EstoqueController::class, 'showEstoques'])->name('estoque.show');
    Route::any('/cadastro', [EstoqueController::class, 'create'])->name('estoque.create');
    Route::post('/salvar', [EstoqueController::class, 'store'])->name('estoque.store');
    // Route::get('/show/{id}', [EstoqueController::class, 'show']);
    // Route::put('/atualizar/{id}', [EstoqueController::class, 'update'])->name('produtos.update');
    // Route::delete('/deletar/{id}', [EstoqueController::class, 'destroy'])->name('produtos.delete');
});

