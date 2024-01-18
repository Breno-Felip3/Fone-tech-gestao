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
        <div style="margin-top: 10px;"> 
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

 {{-- Setup data for datatables  --}}
@php

    $heads = [
        'Número Produto',
        'Nome Produto',
        'Preço Custo (R$)',
        'Preço Venda (R$)',
        'Tempo Garantia',
        'Quantidade Estoque',
        'Ações'
    ];

    $btnEdit = '<button class="btn btn-xs editar text-primary mx-1 shadow" title="Edit">' .
                '<i class="fa fa-lg fa-fw fa-pen"></i>' .
                '</button>';
    $btnDelete = '<button class="btn btn-xs deletar text-danger mx-1 shadow" title="Delete">
                    <i class="fa fa-lg fa-fw fa-trash"></i>
                </button>';
    // $btnDetails = '<button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details">
    //                 <i class="fa fa-lg fa-fw fa-eye"></i>
    //             </button>';

    $config = [
        'processing' => true,
        'serverSide' => true,
        'order' => [0, 'asc'],
        'columns' => [
            ['data' => 'id'],
            ['data' => 'nome'],
            ['data' => 'preco_custo'],
            ['data' => 'preco_venda'],
            ['data' => 'tempo_garantia', 'orderable' => false],
            ['data' => 'estoque.quantidade', 

                'defaultContent' => isset('data'['estoque']['quantidade']) ? 'data'['estoque']['quantidade'] : 0
            ],

            [
                'data' => null,
                'defaultContent' => '<nobr class="btn-column">'.$btnEdit.$btnDelete.'</nobr>',
            ],            
        ],

        'ajax' => [
            'url' => '/produtos/show',
            'method' => 'GET',
        ],
        'language' => [
            'url' => asset('json/traducao_datatables.json'),
        ],
    ];

@endphp

{{-- Compressed with style options / fill data using the plugin config --}}
<x-adminlte-datatable id="produtos" :heads="$heads" head-theme="dark" :config="$config"
    striped hoverable bordered compressed ajax-url="{{ $config['ajax']['url'] }}" ajax-method="{{ $config['ajax']['method'] }}"
    server-side processing/>

<!-- Modal de Cadastro -->
@include('produto/Modal/confirmacaoExclusao')
@include('produto/Modal/cadastrar')
@include('produto/Modal/editar')

@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script>

        $(document).ready(function () {

            var dataTable = $('#produtos').DataTable();
            // Captura o clique nos botões dentro da coluna de ações


            // Captura o clique nos botões dentro da coluna de ações
            $('#produtos').on('click', '.btn-column button', function () {
                // Encontra a linha (row) correspondente ao botão clicado
                var row = dataTable.row($(this).parents('tr'));

                // Obtém os dados da linha
                var rowData = row.data();

                // Extrai o ID do objeto de dados
                var produto_id = rowData.id;

                // Verifica se a classe 'edit' está presente no botão clicado
                if ($(this).hasClass('editar')) {
                    // Código para editar
                    abrirModalEdicao(produto_id);
                } else if ($(this).hasClass('deletar')){
                    // Ação desconhecida ou nenhum botão identificado
                    abrirModalConfirmacao(produto_id);
                }
            });

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

            function abrirModalConfirmacao(produto_id)
            {
                $('#confirmacaoModal').modal('show');
                $('#confirmarExclusao').data('id', produto_id);
            }

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
        
            //Edição do Produto
            function abrirModalEdicao(produto_id) {
                $.ajax({
                    url: "/produtos/show/" + produto_id,
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $(' #nome').val(data.nome);

                        var preco_venda = data.preco_venda.toFixed(2).replace('.', ',');
                        var preco_custo = data.preco_custo.toFixed(2).replace('.', ',');

                        $(' #preco_custo').val(preco_venda).attr('readonly', true);
                        $(' #preco_venda').val(preco_venda).attr('readonly', true);
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
            };
        });
    </script>
@stop