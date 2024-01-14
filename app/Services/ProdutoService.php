<?php

namespace App\Services;

use App\Repositories\ProdutoRepository;

class ProdutoService
{
    protected $repositorio;
    public function __construct(ProdutoRepository $produtoRepository)
    {   
        $this->repositorio = $produtoRepository;
    }

    public function getProdutos()
    {
        return $this->repositorio->getAllProdutos();
    }

    public function createNewProduto(array $dados)
    {
        return $this->repositorio->create($dados);
    }

    public function showProduto($id)
    {
        return $this->repositorio->show($id);
    }
}