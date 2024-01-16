@extends('adminlte::page')

@section('title', 'Produtos')

@section('content_header')
    <h1>Produtos</h1>
@stop

@section('content')

<div class="mb-3">
    <button class="btn btn-success" data-toggle="modal" id="btnCadastrar" data-target="#cadastrar">Cadastrar</button>

    <div id="mensagemConfirmaExclusao" class="alert alert-success" style="margin-top: 10px; display: none;"> Produto excluído com sucesso! </div>

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
    'Número Produto',
    'Nome',
    'Preço Custo',
    'Preço Venda',
    'Tempo Garantia (Meses)',
    'Quantidade Estoque',
    'Descrição',
    'Ações'
];

@endphp

<x-adminlte-datatable id="table3" :heads="$heads" head-theme="dark" theme="light" striped hoverable>
    {{-- Geração dinâmica de linhas --}}
    @foreach($produtos as $produto)
        <tr>
           <th>{{$produto->id}}</th>
           <th>{{$produto->nome}}</th>
           <th>{{ 'R$ ' . number_format($produto->preco_custo, 2, ',', '.') }}</th>
           <th>{{ 'R$ ' . number_format($produto->preco_venda, 2, ',', '.') }}</th>
           <th>{{$produto->tempo_garantia}}</th>
           <th>
                @if ($produto->estoque && $produto->estoque->quantidade)
                    {{ $produto->estoque->quantidade }}
                @else
                    0
                @endif
            </th>
        
            </th>
           <th>{{$produto->descricao}}</th>
           <th>
            
            <button class="btn btn-xs btn-default text-primary mx-1 shadow editar"  data-id="{{ $produto->id }}" title="Editar">
                <i class="fa fa-lg fa-fw fa-pen"></i>
            </button>

            <button class="btn btn-xs btn-default text-danger mx-1 shadow deletar"  data-id="{{ $produto->id }}" title="Apagar">
                <i class="fa fa-lg fa-fw fa-trash"></i>
            </button>
           </th>
        </tr>
    @endforeach

</x-adminlte-datatable>

@if(isset($search))
    {{ $produtos->appends(['search' => $search])->links('pagination::bootstrap-5') }}
@else
    {{ $produtos->links('pagination::bootstrap-5') }}
@endif

<!-- Modal de Cadastro -->
@include('produto/Modal/confirmacaoExclusao')
@include('produto/Modal/cadastrar')
@include('produto/Modal/editar')

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>

        $(document).ready(function () {

            // Limpar campos ao clicar no botão Cadastrar
            $('#btnCadastrar').click(function() {
                // Limpar os campos
                $('#nome').val('');
                $('#preco_venda').val('');
                $('#preco_custo').val('');
                $('#preco_custo').removeAttr('readonly');
                $('#preco_venda').removeAttr('readonly');
                $('#tempo_garantia').val('');
                $('#descricao').val('');
            });

            function abrirModalConfirmacao(id)
            {
                $('#confirmacaoModal').modal('show');
                $('#confirmarExclusao').data('id', id);
            }
            
            $('.deletar').click(function() {
                var id = $(this).data('id');
                abrirModalConfirmacao(id);
            });

            $('#confirmarExclusao').click(function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "/produtos/deletar/" + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        location.reload();
                        $('#mensagemConfirmaExclusao').fadeIn().delay(1000).fadeOut();
                        $('#confirmacaoModal').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        alert('Ocorreu um erro ao excluir o produto: ' + error);
                    }
                });
            });
        
            $('.editar').click(function () {
                var produto_id = $(this).data('id');

                $.ajax({
                    url: "/produtos/show/" + produto_id,
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $(' #nome').val(data.nome);
                        $(' #preco_venda').val(data.preco_venda);
                        $(' #preco_custo').val(data.preco_custo);
                        $(' #preco_custo').val(data.preco_custo).attr('readonly', true);
                        $(' #preco_venda').val(data.preco_venda).attr('readonly', true);
                        $(' #tempo_garantia').val(data.tempo_garantia);
                        $(' #descricao').val(data.descricao);
                    
                        // Defina a ação do formulário dinamicamente
                        $('#FormEditar').attr('action', '/produtos/atualizar/' + produto_id);

                        $('#editar').modal('show');
                    },
                    
                    error: function (xhr, status, error) {
                        console.error("Erro na requisição Ajax:", xhr, status, error);
                    }
                });
            });
        });
    </script>
@stop