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
        return view('produto/index');
    }

    public function showAllProdutos(Request $request)
    {
        $dadosRequisicao = $request;

        return $this->produtoService->getProdutos( $dadosRequisicao);
    }

    public function store(StoreUpdateProduto $request)
    {
       return $this->produtoService->createNewProduto($request->validated());
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
