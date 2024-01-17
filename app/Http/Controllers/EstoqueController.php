<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateEstoque;
use App\Services\{
    EstoqueService,
    ProdutoService
};
use Illuminate\Http\Request;

class EstoqueController extends Controller
{
    protected $estoqueServico;
    protected $produtoService;
    public function __construct(EstoqueService $estoqueServico, ProdutoService $produtoService)
    {
        $this->estoqueServico = $estoqueServico;
        $this->produtoService = $produtoService;
    }

    public function index()
    {
        return view('estoque/index');
    }

    public function showEstoques(Request $request)
    {
        $dadosRequisicao = $request;

        return $this->estoqueServico->getEstoques($dadosRequisicao);

    }

    public function create()
    {
        $produtos = $this->produtoService->getProdutos(null);
        return view('estoque/cadastrar', compact('produtos'));
    }

    public function store(StoreUpdateEstoque $dados)
    {
        $this->estoqueServico->createNewEstoque($dados->all());

        return redirect()->route('estoque.index')->with('success', 'Novo estoque cadastrado com sucesso!');
    }
}
