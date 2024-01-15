<?php
namespace App\Repositories;

use App\Models\Estoque;

class EstoqueRepository
{
    protected $entidade;
    public function __construct(Estoque $estoque)
    {
        $this->entidade = $estoque;
    }

    public function create($dados)
    {
        return $this->entidade->create($dados);
    }
}