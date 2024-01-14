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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateProduto $request)
    {
        $this->ProdutoService->createNewProduto($request->validated());
      
        return redirect()->route('produtos.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $this->ProdutoService->showProduto($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
