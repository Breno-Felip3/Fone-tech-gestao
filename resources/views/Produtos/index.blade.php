@extends('adminlte::page')

@section('title', 'Produtos')

@section('content_header')
    <h1>Produtos</h1>
@stop

@section('content')

<div class="mb-3">
    <button class="btn btn-success" data-toggle="modal" data-target="#cadastrar">Cadastrar</button>
</div>

@php

$heads = [
    'ID',
    'Nome',
    'Preço Custo',
    'Preço Venda',
    'Tempo Garantia',
    'Quantidade Estoque',
    'Ações'
];

@endphp

<x-adminlte-datatable id="table5" :heads="$heads" theme="light" striped hoverable>
    {{-- Geração dinâmica de linhas --}}
    @foreach($produtos as $produto)
        <tr>
           <th>{{$produto->id}}</th>
           <th>{{$produto->nome}}</th>
           <th>{{ 'R$ ' . number_format($produto->preco_compra, 2, ',', '.') }}</th>
           <th>{{ 'R$ ' . number_format($produto->preco_venda, 2, ',', '.') }}</th>
           <th>{{$produto->tempo_garantia}}</th>
           <th>1</th>
           <th>
       
            
            <button class="btn btn-xs btn-default text-primary mx-1 shadow editar" data-toggle="modal" data-target="#editar" data-id="{{ $produto->id }}" title="Edit">
                <i class="fa fa-lg fa-fw fa-pen"></i>
            </button>

            <button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
                <i class="fa fa-lg fa-fw fa-trash"></i>
            </button>
           </th>
        </tr>
    @endforeach
</x-adminlte-datatable>

<!-- Modal de Cadastro -->
@include('Produtos/Modal/cadastrar')
@include('Produtos/Modal/editar')

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    $(document).ready(function () {
        $('.editar').click(function () {
            var produto_id = $(this).data('id');

            $('#produto_id').val(produto_id);
            $.ajax({
                url: "/produtos/show/" + produto_id,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    console.log("Resposta do Servidor:", data);
                    // Preencher os campos do formulário de edição com os dados do produto
                    $('#nome').val(data.nome);
                    $('#preco').val(data.preco);
                    // ... outros campos ...
                },

                error: function (xhr, status, error) {
                    console.error("Erro na requisição Ajax:", xhr, status, error);
                    console.log("Resposta do servidor:", xhr.responseText);
                }


            });
        });
    });
</script>

@stop