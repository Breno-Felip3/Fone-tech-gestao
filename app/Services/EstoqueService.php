<?php

namespace App\Services;

use App\Repositories\EstoqueRepository;

class EstoqueService
{
    protected $repositorio;
    public function __construct(EstoqueRepository $estoqueRepository)
    {
        $this->repositorio = $estoqueRepository;
    }

    public function getEstoques()
    {
        return $this->repositorio->getAllEstoques();
    }

    public function createNewEstoque($dados)
    { 
        return $this->repositorio->store($dados);
    }
}