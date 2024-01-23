<?php
namespace App\Repositories;

use App\Models\{
    EstoqueEntrada,
    EstoqueEntradaItem,
    EstoqueSaldo,
    Produto
};

class EstoqueRepository
{
    protected $modelEstoqueSaldo, $modelEstoqueEntrada, $modelEstoqueEntradaItens, $modelProduto;

    public function __construct(
            EstoqueSaldo $modelEstoqueSaldo, 
            EstoqueEntrada $modelEstoqueEntrada, 
            EstoqueEntradaItem $modelEstoqueEntradaItens,
            Produto $produto
        )
    {
        $this->modelEstoqueSaldo = $modelEstoqueSaldo;
        $this->modelEstoqueEntrada = $modelEstoqueEntrada;
        $this->modelEstoqueEntradaItens = $modelEstoqueEntradaItens;
        $this->modelProduto = $produto;
    }

    public function getEstoques($dadosRequisicao)
    {
        $dadosRequisicao = $dadosRequisicao;

        $estoques = $this->modelEstoqueEntrada->whereNull('deleted_at')->select('id', 'total_entrada', 'observacao', 'created_at')
            ->whereNull('deleted_at')->with(['itens' => function ($query) {
                    $query->select('estoque_entrada_id', 'produto_id', 'quantidade');
                    }])->withCount('itens as quantidade_produtos');        
        
        if(isset($dadosRequisicao['searc']['value'])){
            $termoPesquisa = '%' . $dadosRequisicao['searc']['value'] . '%';
            $estoques->where(function($query) use ($termoPesquisa){
                $query  ->where('id', 'LIKE', $termoPesquisa)
                        ->orWhere('observacao', 'LIKE', $termoPesquisa)
                        ->orWhere('quantidade', 'LIKE', $termoPesquisa);
            });
        };

        $colunaOrdenar = [
            'id',
            'quantidade_produtos',
            'total_entrada',
            'created_at',
           
        ];

        if(isset($dadosRequisicao['order'])){
            $colunaOrdenar = $colunaOrdenar[$dadosRequisicao['order'][0]['column']] ?? 'id';
            $direcaoOrdenar = $dadosRequisicao['order'][0]['dir'] ?? 'asc';
        }
     
        if(isset( $dadosRequisicao['start'])){
            $paginacao = $dadosRequisicao['start'] / $dadosRequisicao['length'] + 1;
            $estoques = $estoques->orderBy($colunaOrdenar, $direcaoOrdenar)->paginate($dadosRequisicao['length'], ['*'], 'page', $paginacao);
        }

        return response()->json([
            'data' => $estoques->getCollection(),
            'recordsTotal' => count($estoques),
            'recordsFiltered' => $estoques->total(),
        ]);

    }

    public function store($dados)
    {
        //Itera sobre a quantidade recebido e seleciona o produto com quantidade adicionada
        foreach ($dados['quantidade'] as $produto_id => $quantidade){

            if($quantidade > 0){
                $item = $this->modelProduto::find($produto_id); 
                $valorUnitario = $item->preco_custo;
                $total_entrada = $quantidade * $valorUnitario;

                $itens[] = [
                    'produto_id' => $produto_id,
                    'quantidade' => $quantidade,
                    'total_entrada' => $total_entrada,
                ];

            }
        }

        $valorTotalEntrada = array_sum(array_column($itens, 'total_entrada'));

        $entrada = $this->modelEstoqueEntrada::create([
            'observacao' => $dados['observacao'],
            'total_entrada' => $valorTotalEntrada
        ]);

        foreach ($itens as $item) {
            $entradaItem = $entrada->itens()->create($item);
            $saldoEstoque = $this->modelEstoqueSaldo::where('produto_id', $item['produto_id'])->first();
            if(isset($saldoEstoque->quantidade)){
                $novoSaldoEstoque = $item['quantidade'] + $saldoEstoque->quantidade;
            };
           
            // Atualiza ou cria o registro no EstoqueSaldo
            $estoqueSaldo = $this->modelEstoqueSaldo::updateOrCreate(
                ['produto_id' => $item['produto_id']],
                ['quantidade' => isset($novoSaldoEstoque) ? $novoSaldoEstoque : $item['quantidade']]
            );
    
            // Associa o EstoqueSaldo ao modelEstoqueEntradaItens
            $entradaItem->estoqueSaldo()->save($estoqueSaldo);
        }
        
    }

}