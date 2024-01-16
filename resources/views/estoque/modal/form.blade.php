@php

$heads = [
    'Número',
    'Produto',
    'Quantidade',
    'Saldo',
];

@endphp
<x-adminlte-datatable id="table3" :heads="$heads" theme="light" striped hoverable>

    @foreach ($produtos as $produto)
        <tr>
            <td>{{ $produto->id }}</td>
            <td>{{ $produto->nome }}</td>
            <td><input type="text" class="form-control" id="quantidade_inicial" name="quantidade_inicial"></td>
        </tr>
    @endforeach
  
</x-adminlte-datatable>

@if(isset($search))
  {{ $produtos->appends(['search' => $search])->links('pagination::bootstrap-5') }}
@else
  {{ $produtos->links('pagination::bootstrap-5') }}
@endif

<div class="mb-3">
  <label for="formGroupExampleInput2" class="form-label">Observação</label>
  <div class="col-12">
    <textarea class="form-control" name="Observacao" rows="3"></textarea>
  </div>
</div>




  