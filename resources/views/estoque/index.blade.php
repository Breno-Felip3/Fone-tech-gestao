@extends('adminlte::page')

@section('title', 'Produtos')

@section('content_header')
    <h1>Estoque de Produtos</h1>
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
    'Produto',
    'Qtde Adicionada',
    'Preço Custo Unitario',
    'Quantidade Estoque',
    'Data Cadastro',
    'Ações'
];

@endphp

<x-adminlte-datatable id="table5" :heads="$heads" theme="light" striped hoverable>
    {{-- Geração dinâmica de linhas --}}
     @foreach($estoques as $estoque)
        <tr>
            <th>{{$estoque->produto->nome}}</th>
            <th>{{$estoque->quantidade_inicial}}</th>
            <th>{{ 'R$ ' . number_format($estoque->preco_custo, 2, ',', '.') }}</th>
            <th>{{$estoque->quantidade_disponivel}}</th>
            <th>{{$estoque->created_at}}</th>
           <th>
            
            <button class="btn btn-xs btn-default text-primary mx-1 shadow editar"  title="Editar">
                <i class="fa fa-lg fa-fw fa-pen"></i>
            </button>

            <button class="btn btn-xs btn-default text-danger mx-1 shadow deletar"  title="Apagar">
                <i class="fa fa-lg fa-fw fa-trash"></i>
            </button>
           </th>
        </tr>
    @endforeach 

</x-adminlte-datatable>

<!-- Modal de Cadastro -->
{{-- @include('estoque/modal/confirmacaoExclusao') --}}
@include('estoque/modal/cadastrar')
@include('estoque/modal/editar')

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    {{-- <script>

         $(document).ready(function () {

            // Limpar campos ao clicar no botão Cadastrar
            $('#btnCadastrar').click(function() {
                // Limpar os campos
                $('#nome').val('');
                $('#preco_venda').val('');
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
                        alert('Ocorreu um erro ao excluir o vendedor: ' + error);
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