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

    public function getAllProdutos( $dadosRequisicao)
    {
        if($dadosRequisicao == null){
            return $this->entidade->with('estoque')->paginate(10);
        }

        $dadosRequisicao =  $dadosRequisicao;

        $produtos = $this->entidade->with('estoque')->select('id', 'nome', 'preco_custo', 'preco_venda', 'tempo_garantia');
            
        if (isset($dadosRequisicao['search']['value'])){
            $termoPesquisa = '%' . $dadosRequisicao['search']['value'] . '%';
            $produtos->where(function($query) use ($termoPesquisa){
                $query  ->where('id', 'LIKE', $termoPesquisa)
                        ->orWhere('nome', 'LIKE', $termoPesquisa);
            });
        }

        $colunaOrdenar = [
            'id',
            'nome',
            'preco_custo',
            'preco_venda',
            'quantidade',
            
        ];

        if(isset($dadosRequisicao['order'])){
            $colunaOrdenar = $colunaOrdenar[$dadosRequisicao['order'][0]['column']] ?? 'id';
            $direcaoOrdenar = $dadosRequisicao['order'][0]['dir'] ?? 'asc';
        };
        
        if(isset($dadosRequisicao['start'] )){
            $paginacao = $dadosRequisicao['start'] / $dadosRequisicao['length'] + 1;
            $produtos = $produtos->orderBy($colunaOrdenar, $direcaoOrdenar)->paginate($dadosRequisicao['length'], ['*'], 'page', $paginacao);
        }

            //Formata o retorno dos valores para BR
            $produtos->getCollection()->map(function ($produto) {
            $produto->preco_custo = number_format($produto->preco_custo, 2, ',', '.');
            $produto->preco_venda = number_format($produto->preco_venda, 2, ',', '.');
            return $produto;
        });
        
      
        return response()->json([
            'data' => $produtos->getCollection(),
            'recordsTotal' => count($produtos), // Total de registros antes da filtragem
            'recordsFiltered' => $produtos->total(),
        ]);
        
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