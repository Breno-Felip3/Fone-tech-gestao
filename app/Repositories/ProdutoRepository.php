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
        return $this->entidade->find($id);
    }

    public function update($dados, $id)
    {
        $produto = $this->show($id);
        
        return $produto->update($dados);
    }

    public function destroy($id)
    {
        $produto = $this->show($id);

        return $produto->delete();
    }
}