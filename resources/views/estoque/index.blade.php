@extends('adminlte::page')

@section('title', 'Estoques')

@section('content_header')
    <h1>Entrada de Estoque</h1>
@stop

@section('content')

<div class="mb-3">

    <form action="{{route('estoque.create')}}" method="post">
        @csrf
        <button class="btn btn-success" data-toggle="modal" id="btnCadastrar" data-target="#cadastrar">Cadastrar</button>
    </form>
    

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
        'Numero',
        'Quantidade de Produtos',
        'Total Entrada (R$)',
        'Data Cadastro',
        'Observação',
        'Ações'
    ];

    
    $config = [
        'processing' => true,
        'serverSide' => true,
        'order' => [0, 'asc'],
        'columns' => [
            ['data' => 'id'],
            ['data' => 'quantidade_produtos'],
            ['data' => 'total_entrada'],
            ['data' => 'created_at'],
            ['data' => 'observacao', 'orderable' => false],
            [ 'data' => null, 'orderable' => false],
                        
        ],
        'ajax' => [
            'url' => '/estoques/show',
            'method' => 'GET',
        ],
        'language' => [
            'url' => asset('json/traducao_datatables.json'),
        ],
        
    ];

@endphp

{{-- Compressed with style options / fill data using the plugin config --}}
<x-adminlte-datatable id="estoques" :heads="$heads" head-theme="dark" :config="$config"
    striped hoverable bordered compressed ajax-url="{{ $config['ajax']['url'] }}" ajax-method="{{ $config['ajax']['method'] }}"
    server-side processing/>


<!-- Modal de detalhes -->
{{-- @include('estoque/modal/detalhes') --}}


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