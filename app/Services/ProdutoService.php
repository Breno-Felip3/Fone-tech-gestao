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

    public function getProdutos( $dadosRequisicao)
    {
        $dadosRequisicao =  $dadosRequisicao;
        return $this->repositorio->getProdutos( $dadosRequisicao);
    }

    public function createNewProduto(array $dados)
    {
        return $this->repositorio->create($dados);
    }

    public function showProduto($id)
    {
        return $this->repositorio->getProduto($id);
    }

    public function updateProduto($dados, $id)
    {
        return $this->repositorio->update($dados, $id);
    }

    public function destroyProduto($id)
    {
        return $this->repositorio->destroy($id);
    }
}