<div class="modal fade" id="detalhes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="myModalLabel">Detalhes da entrada</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>

          @php
          $heads = [
              'Numero',
              'Produto',
              'Quantidade',
              'Custo Unitário',
              'Custo Total',
              'Total Entrada',
              'Data Cadastro',
              'Observação',
              'Ações'
          ];

          @endphp

          <x-adminlte-datatable id="table3" :heads="$heads" head-theme="dark" theme="light" striped hoverable>
              {{-- Geração dinâmica de linhas --}}
              @foreach($entradas as $entrada)
                  <tr>
                      <th>{{$entrada->id}}</th>
                      <th>{{$entrada->itens_count}}</th>
                      <th>{{ 'R$ ' . number_format($entrada->total_entrada, 2, ',', '.') }}</th>
                      <th>{{$entrada->created_at}}</th>
                      <th>{{$entrada->observacao}}</th>
                    <th>
                      

                      <button class="btn btn-xs btn-default text-primary mx-1 shadow detalhes" data-toggle="modal" data-target="#detalhes">
                          <i class="fa fa-lg fa-fw  fa-eye"></i>
                      </button>

                      <button class="btn btn-xs btn-default text-danger mx-1 shadow deletar"  title="Apagar">
                          <i class="fa fa-lg fa-fw fa-trash"></i>
                      </button>
                    </th>
                  </tr>
              @endforeach 

          </x-adminlte-datatable>

      

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
          </div>
      </div>
  </div>
</div>
