<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateProduto;
use App\Services\ProdutoService;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
   
    protected $ProdutoService;
    public function __construct(ProdutoService $produtoService)
    {   
        $this->ProdutoService = $produtoService;
    }

    public function index()
    {
        $produtos = $this->ProdutoService->getProdutos();

        return view('Produtos/index', compact('produtos'));
    }

    public function store(StoreUpdateProduto $request)
    {
        $this->ProdutoService->createNewProduto($request->validated());
      
        return redirect()->route('produtos.index')->with('success', 'Produto cadastrado com sucesso!');
    }

    public function show($id)
    {
        $produto = $this->ProdutoService->showProduto($id);

        return response()->json($produto);
    }


    public function update(StoreUpdateProduto $request, $id)
    {
        $this->ProdutoService->updateProduto($request->validated(), $id);

        return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso!');
    }


    public function destroy(string $id)
    {
        $this->ProdutoService->destroyProduto($id);

    }
}
