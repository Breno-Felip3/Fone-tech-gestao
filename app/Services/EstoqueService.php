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

    public function createNewEstoque($dados)
    {
        $dados['quantidade_disponivel'] = $dados['quantidade_inicial'];

        return $this->repositorio->create($dados);
    }
}