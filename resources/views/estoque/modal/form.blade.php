<select name="produto_id" class="form-select form-control mb-3" aria-label="Default select example">
  <option selected>Selecione o produto</option>
  @foreach ($produtos as $produto )
    <option value="{{$produto->id}}">{{$produto->nome}}</option>
  @endforeach
</select>


<div class="mb-3">
    <label for="formGroupExampleInput2" class="form-label">Quantidade de itens</label>
    <input type="text" class="form-control" id="quantidade_inicial" name="quantidade_inicial">
</div>

<div class="mb-3">
    <label for="formGroupExampleInput2" class="form-label">Preço Custo</label>
    <input type="text" class="form-control" id="preco_custo" name="preco_custo">
</div>


  