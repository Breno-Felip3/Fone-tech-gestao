<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateEstoque;
use App\Models\Estoque;
use App\Models\Produto;
use App\Services\EstoqueService;
use Illuminate\Http\Request;

class EstoqueController extends Controller
{
    protected $estoqueServico;
    public function __construct(EstoqueService $estoqueServico)
    {
        $this->estoqueServico = $estoqueServico;
    }

    public function index()
    {
        $produtos = Produto::all();
        $estoques = Estoque::with('produto')->get();

        return view('estoque/index', compact('produtos', 'estoques'));
    }

    public function store(StoreUpdateEstoque $dados)
    {
        $this->estoqueServico->createNewEstoque($dados->validated());

        return redirect()->route('estoque.index')->with('success', 'Novo estoque cadastrado com sucesso!');
    }
}
