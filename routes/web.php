<?php

use App\Http\Controllers\ProdutoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('/produtos')->group(function(){
    Route::get('/index', [ProdutoController::class, 'index'])->name('produtos.index');
    Route::post('/salvar', [ProdutoController::class, 'store'])->name('produtos.store');
    Route::get('/show/{id}', [ProdutoController::class, 'show']);
    Route::put('/atualizar/{id}', [ProdutoController::class, 'update'])->name('produtos.update');
    Route::delete('/deletar/{id}', [ProdutoController::class, 'destroy'])->name('produtos.delete');
});
