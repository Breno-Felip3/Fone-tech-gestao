<div class="modal fade" id="cadastrar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Cadastrar novo Produto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formulario">
                <div class="modal-body">
                    @include('produto/modal/form')
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary salvar">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/js/jquery-3.7.1.min.js"></script>
<script>

    $(document).ready(function(){
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
                var dataTable = $('#produtos').DataTable();
                dataTable.ajax.reload();
            },
            error: function(xhr, status, error) {
                console.error("Erro na requisição Ajax:", xhr, status, error);
            }
        });
    });
    });

</script>
