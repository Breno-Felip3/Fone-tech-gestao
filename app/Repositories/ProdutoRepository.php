<?php

namespace App\Repositories;

use App\Models\Produto;

class ProdutoRepository
{
    protected $produtoModel;
    public function __construct(Produto $produto)
    {   
        $this->produtoModel = $produto; 
    }

    public function getProdutos( $dadosRequisicao)
    {
        // if ($dadosRequisicao == null) {
        //     return $this->produtoModel->with('estoque')->whereNull('deleted_at')->paginate(10);
        // }

        $dadosRequisicao =  $dadosRequisicao;

        $produtos = $this->produtoModel->query()
        ->select('produtos.id', 'nome', 'preco_custo', 'preco_venda', 'tempo_garantia', 'descricao', 'produtos.created_at', 'quantidade')
        ->join('estoque_saldos', 'estoque_saldos.produto_id', 'produtos.id');
            
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
            'null',
            'quantidade',
            'null',
            
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
        });
      
        return response()->json([
            'data' => $produtos->getCollection(),
            'recordsTotal' => count($produtos), 
            'recordsFiltered' => $produtos->total(),
        ]);
        
    }

    public function create(array $dados)
    {
        // Formatar o valor substituindo vírgulas por pontos
        $dados['preco_custo']  = str_replace(",",".", $dados['preco_custo']);
        $dados['preco_venda']  = str_replace(",",".", $dados['preco_venda']);
        
        return $this->produtoModel->create($dados);
    }

    public function getProduto($id)
    {
        return $this->produtoModel->findOrFail($id);
    }

    public function update($dados, $id)
    {
        $produto = $this->getProduto($id);

        $dados['preco_custo'] = str_replace(',','.',str_replace('.','',$dados['preco_custo']));
        $dados['preco_venda'] = str_replace(',','.',str_replace('.','',$dados['preco_venda']));
     
        
        return $produto->update($dados);
    }

    public function destroy($id)
    {
        $produto = $this->getProduto($id);

        return $produto->delete();
    }
}