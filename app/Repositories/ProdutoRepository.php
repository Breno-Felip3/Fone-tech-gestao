<?php

namespace App\Repositories;

use App\Models\Produto;

class ProdutoRepository
{
    protected $entidade;
    public function __construct(Produto $produto)
    {   
        $this->entidade = $produto; 
    }

    public function getAllProdutos()
    {
        return $this->entidade->get();
    }

    public function create(array $dados)
    {
        return $this->entidade->create($dados);
    }

    public function show($id)
    {
        $produto = $this->entidade->find($id);
        dd($produto);
        return response()->json($produto);
    }
}