@extends('adminlte::page')

@section('title', 'Cadastro')

@section('content_header')
    <h1>Cadastrar nova entrada no Estoque</h1>
@stop

@section('content')

<form action="{{route('estoque.index')}}" method="get">
    @csrf
    <button class="btn btn-success" data-toggle="modal" id="btnCadastrar" data-target="#cadastrar">Estoque</button>
</form>

<div class="mb-3">

    @if(session('success'))
        <div style="margin-top: 10px;"> <!-- Adicione a margem superior aqui -->
            <div class="alert alert-success" id="success-message">
                {{ session('success') }}
            </div>

            <script>
                setTimeout(function() {
                    $('#success-message').fadeOut('fast');
                }, 2000); // 2000 milissegundos = 2 segundos
            </script>
        </div>
    @endif
  
</div>

@php

$heads = [
    'Número',
    'Produto',
    'Quantidade',
    'Saldo',
];

@endphp
<x-adminlte-datatable id="table3" :heads="$heads" head-theme="dark" theme="light" striped hoverable>

<form action="{{route('estoque.store')}}" method="post">
    @csrf

    @foreach ($produtos as $produto)
        <tr>
            <td>{{ $produto->id }}</td>
            <td>{{ $produto->nome }}</td>
            <td><input style="width: 80px;" type="text" class="form-control" name="quantidade[{{$produto->id}}]"></td>
            <td> 
                @if ($produto->estoque && $produto->estoque->quantidade)
                    {{ $produto->estoque->quantidade }}
                @else
                0
                @endif
            </td>
        </tr>
    @endforeach
  
    </x-adminlte-datatable>

    <div class="mb-3">
        <label for="exampleTextarea">Observação</label>
        <textarea class="form-control" name="observacao" id="observacao" rows="2"></textarea>
    </div>
    

    @if(isset($search))
    {{ $produtos->appends(['search' => $search])->links('pagination::bootstrap-5') }}
    @else
    {{ $produtos->links('pagination::bootstrap-5') }}
    @endif



    <button class="btn btn-primary" data-toggle="modal" id="btnCadastrar" data-target="#cadastrar">Cadastrar</button>
</form>


@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    
@stop 