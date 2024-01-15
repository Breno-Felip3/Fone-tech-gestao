<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateProduto;
use App\Services\ProdutoService;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
   
    protected $produtoService;
    public function __construct(ProdutoService $produtoService)
    {   
        $this->produtoService = $produtoService;
    }

    public function index()
    {
        $produtos = $this->produtoService->getProdutos();

        return view('produto/index', compact('produtos'));
    }

    public function store(StoreUpdateProduto $request)
    {
        $this->produtoService->createNewProduto($request->validated());
      
        return redirect()->route('produto.index')->with('success', 'Produto cadastrado com sucesso!');
    }

    public function show($id)
    {
        $produto = $this->produtoService->showProduto($id);

        return response()->json($produto);
    }


    public function update(StoreUpdateProduto $request, $id)
    {
        $this->produtoService->updateProduto($request->validated(), $id);

        return redirect()->route('produto.index')->with('success', 'Produto atualizado com sucesso!');
    }


    public function destroy(string $id)
    {
        $this->produtoService->destroyProduto($id);

    }
}
