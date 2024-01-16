<?php
namespace App\Repositories;

use App\Models\EstoqueEntrada;
use App\Models\EstoqueEntradaItem;
use App\Models\EstoqueSaldo;
use App\Models\Produto;

class EstoqueRepository
{
    protected $entidadeEstoqueSaldo, $entidadeEntrada, $entidadeEntradaItens, $entidadeProduto;

    public function __construct(
            EstoqueSaldo $entidadeEstoqueSaldo, 
            EstoqueEntrada $entidadeEntrada, 
            EstoqueEntradaItem $estoqueEntradaItem,
            Produto $produto
        )
    {
        $this->entidadeEstoqueSaldo = $entidadeEstoqueSaldo;
        $this->entidadeEntrada = $entidadeEntrada;
        $this->entidadeEntradaItens = $estoqueEntradaItem;
        $this->entidadeProduto = $produto;
    }

    public function getAllEstoques()
    {
        return $this->entidadeEntrada->with('itens')->withCount('itens')->get();
    }

    public function store($dados)
    {
        //Itera sobre o campo quantidade recebido 
        foreach ($dados['quantidade'] as $produto_id => $quantidade){

            if($quantidade > 0){
                $item = $this->entidadeProduto::find($produto_id); 
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

        $entrada = $this->entidadeEntrada::create([
            'observacao' => $dados['observacao'],
            'total_entrada' => $valorTotalEntrada
        ]);

        foreach ($itens as $item) {
            $entradaItem = $entrada->itens()->create($item);
            $saldoEstoque = $this->entidadeEstoqueSaldo::where('produto_id', $item['produto_id'])->first();
            $novoSaldoEstoque = $item['quantidade'] + $saldoEstoque->quantidade;
           
            // Atualiza ou cria o registro no EstoqueSaldo
            $estoqueSaldo = $this->entidadeEstoqueSaldo::updateOrCreate(
                ['produto_id' => $item['produto_id']],
                ['quantidade' => $novoSaldoEstoque]
            );
    
            // Associa o EstoqueSaldo ao EstoqueEntradaItem
            $entradaItem->estoqueSaldo()->save($estoqueSaldo);
        }
        
    }

}