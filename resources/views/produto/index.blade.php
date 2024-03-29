@extends('adminlte::page')

@section('title', 'Produtos')

@section('content_header')
    <h1>Produtos</h1>
@stop

@section('content')

<div class="mb-3">
    <button class="btn btn-success" data-toggle="modal" id="btnCadastrar" data-target="#cadastrar">Cadastrar</button>

    <div id="mensagemConfirmaExclusao" class="alert alert-success" style="margin-top: 10px; display: none;"> Produto excluído com sucesso! </div>
    <div id="mensagemConfirmaCadastro" class="alert alert-success" style="margin-top: 10px; display: none;"> Produto cadastrado com sucesso! </div>
    <div id="mensagemConfirmaEdicao" class="alert alert-success" style="margin-top: 10px; display: none;"> Produto atualizado com sucesso! </div>
  
</div>

 {{-- Datatables  --}}
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
                '<i class="fa fa-fw fa-pen-to-square"></i>' .
                '</button>';
    $btnDelete = '<button class="btn btn-xs deletar text-danger mx-1 shadow" title="Delete">
                    <i class="fa fa-fw fa-trash-can"></i>
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
            ['data' => 'quantidade', 

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
    <link rel="stylesheet" href="{{ asset('/css/bootstrap/ajax_bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/bootstrap/maxcdn.bootstrap.min.css') }}">
@stop

@section('js')
    <script src="{{ asset('js/icones/fontawesome.js') }}"></script>
    <script>

        $(document).ready(function () {
            //Define o Datatables
            var dataTable = $('#produtos').DataTable();

            //Cria um novo produto
            $('.salvar').click(function(){
                var dadosFormulario = $('#formulario').serialize();
                $.ajax({
                    url: "/produtos/salvar",
                    method: 'POST',
                    data: dadosFormulario,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#cadastrar').modal('hide');
                        $('#mensagemConfirmaCadastro').fadeIn().delay(1000).fadeOut();

                        // Recarrega o DataTable após o sucesso
                        dataTable.ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error("Erro na requisição Ajax:", xhr, status, error);
                    }
                });
            });
           
            // Captura o clique nos botões dentro da coluna de ações
            $('#produtos').on('click', '.btn-column button', function () {
                // Encontra a linha (row) correspondente ao botão clicado
                var row = dataTable.row($(this).parents('tr'));
                // Obtém os dados da linha
                var rowData = row.data();
                // Extrai o ID do objeto de dados
                var produto_id = rowData.id;

                // aciona as ações desejadas
                if ($(this).hasClass('editar')) {
                    // Código para editar
                    abrirModalEdicao(produto_id);
                } else if ($(this).hasClass('deletar')){
                    // Ação desconhecida ou nenhum botão identificado
                    modalConfirmacaoExclusao(produto_id);
                }
            });

            // Limpa os campos ao clicar no botão Cadastrar
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

            //Configura a exclusão do produto
            function modalConfirmacaoExclusao(produto_id)
            {
                //Abre o modal para exclusão
                $('#confirmacaoModal').modal('show');

                //Confirma a exlusão do produto
                $('#confirmaExclusao').click(function() {
                    $.ajax({
                        url: "/produtos/deletar/" + produto_id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {

                            // Recarrega o DataTable após o sucesso
                            dataTable.ajax.reload();
                            $('#mensagemConfirmaExclusao').fadeIn().delay(1000).fadeOut();
                            $('#confirmacaoModal').modal('hide');
                        },
                        error: function(xhr, status, error) {
                            alert('Ocorreu um erro ao excluir o produto: ' + error);
                        }
                    });
                });
            }

            //Edição do Produto
            function abrirModalEdicao(produto_id) {
                $.ajax({
                    url: "/produtos/show/" + produto_id,
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        //Atribui os dados aos campos do formulário 
                        $(' #nome').val(data.nome);
                        var preco_custo = data.preco_custo.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                        var preco_venda = data.preco_venda.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                        $(' #preco_custo').val(preco_custo).attr('readonly', true);
                        $(' #preco_venda').val(preco_venda).attr('readonly', true);
                        $(' #tempo_garantia').val(data.tempo_garantia);
                        $(' #descricao').val(data.descricao);

                        //Abre o formulário
                        $('#editar').modal('show');

                        $('#formularioEditar').off('submit').on('submit', function (event) {
                            event.preventDefault(); 
                            var dadosFormulario = $(this).serialize();
                            $.ajax({
                                url: "/produtos/atualizar/" + produto_id,
                                data: dadosFormulario,
                                type: 'PUT',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    $('#editar').modal('hide');

                                    // Recarrega o DataTable após o sucesso
                                    dataTable.ajax.reload();
                                    $('#mensagemConfirmaEdicao').fadeIn().delay(1300).fadeOut();
                                },
                                error: function(xhr, status, error) {
                                    alert('Ocorreu um erro ao atualizar o produto: ' + error);
                                }
                            });
                        });
                    },
                    
                    error: function (xhr, status, error) {
                        console.error("Erro na requisição Ajax:", xhr, status, error);
                    }
                });
            };

        });
    </script>
@stop