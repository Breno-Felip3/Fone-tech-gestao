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
        return $this->entidade->with('estoque')->paginate(8);
    }

    public function create(array $dados)
    {
        // Formatar o valor substituindo vírgulas por pontos
        $dados['preco_custo'] = str_replace(',', '.', $dados['preco_custo']);
        $dados['preco_venda'] = str_replace(',', '.', $dados['preco_venda']);
        
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