<div>

  {{-- @if($guarnicoes_option)
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag/dist/css/multi-select-tag.css">
    <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag/dist/js/multi-select-tag.js"></script>
  @endif --}}

    <div class="row">
      

      <div class="form-floating col-3">
        <select class="form-select" id="user_cadastrado" 
        name="user_cadastrado" aria-label="Floating label select example">
          <option value="" selected>-Escolha uma opção-</option>
          @foreach ($usuarios as $index => $usuario)
          {{-- INDEX: id usuario -> $usuario[0] EXIBICAO --}}
          <option value="{{$index}}">{{$usuario[0]}}</option>
          @endforeach
        </select>
        <label for="user_cadastrado">Usarios Ja cadastrados</label>
      </div>

    <div class="col-1">OU NOVO:</div>

        <div class="form-floating col-2">
            <input type="text" class="form-control" id="floatingNome" name="nome" placeholder="" >
            <label for="floatingNome">Nome</label>
        </div>
        <div class="form-floating col-3">
          <input maxlength="8" type="text" class="form-control" id="floatingTelefone" name="whatsapp" placeholder="">
          <label for="floatingTelefone">Numero de telefone (99998888)</label>
        </div>

        <div class="p-3"></div>
        <div class="form-floating col-3">
          <input type="text" class="form-control" id="floatingEndereco" name="endereco" placeholder="">
          <label for="floatingEndereco">Endereco</label>
      </div>

        <div class="form-floating col-3">
          <select required class="form-select" id="floatingPagamento" 
          name="formaPagamento" aria-label="Floating label select example">
            <option value="" selected>-Escolha uma opção-</option>
            <option value="Dinheiro">Dinheiro</option>
            <option value="Cartão de crédito">Cartão de crédito</option>
            <option value="Cartão de débito">Cartão de débito</option>
            <option value="Voucher refeição">Voucher refeição</option>
            <option value="Voucher alimentação">Voucher alimentação</option>
            <option value="Pix">PIX</option>
          </select>
          <label for="floatingPagamento">Forma de Pagamento</label>
        </div>

        <div class="p-3"></div>

        <div class="form-floating col-3">
            <input wire:model.lazy="frete" type="text" class="form-control" id="floatingFrete" name="frete" wire:change="calcularTotal">
            <label for="floatingFrete">Frete</label>
        </div>

        <div class="form-floating col-3">
          <select required class="form-select" id="floatingEntrega" name="retirar_entregar"
           aria-label="Floating label select example">
            <option value="" selected>-Escolha uma opção-</option>
            <option value="Retirar">Retirar</option>
            <option value="Entrega">Entrega</option>
          </select>
          <label for="floatingEntrega">Retirar/Entregar</label>
        </div>

        <div class="form-floating col-3">
          <input type="text" class="form-control" id="floatingTroco" name="troco" placeholder="" >
          <label for="floatingTroco">Troco</label>
        </div>
      
      <div class="form-floating col-3">
        <input type="text" class="form-control" id="floatingObservacao" name="observacao" >
        <label for="floatingObservacao">Observação Geral</label>
    </div>
       
          <div class="p-3"></div> 
       
          <div class="col-12">
            <p>Detalhes:</p>
          </div>

{{-- Produto  --}}
      @foreach($produtos as $index => $produto)

            <div class="form-floating col-2">
              <select required class="form-select" id="floatingTipo[{{$index}}]" 
              name="produtos[{{$index}}][tipo]" 
              wire:model="produtos.{{$index}}.tipo"
              wire:change="mudarOpcao({{$index}})"
              aria-label="Floating label select example">

                <option value="" selected>-Escolha uma opção-</option>
                <option value="Marmitex">Marmitex</option>
                <option value="Barca">Barca</option>
                <option value="Hamburguer">Hamburguer</option>
                <option  value="Comida">Comida</option>
                <option  value="Bebida">Bebida</option>

              </select>
              <label for="floatingTipo[{{$index}}]">Tipo</label>
            </div>


            <div class="form-floating col-3">
              <select required class="form-select" id="floatingOpcao[{{$index}}]" 
              name="produtos[{{$index}}][opcao]"
              wire:model="produtos.{{$index}}.opcao"
              wire:change="mudarDetalhe({{$index}})"
              aria-label="Floating label select example">

                <option value="" selected>-Escolha uma opção-</option>
                @if($opcao[$index])

                  @if($opcao[$index] == "Bebida")
                    @foreach($listaBebidas as $bebida)
                      <option value="{{$bebida['nome']}}">{{$bebida['nome']}}</option>
                    @endforeach

                  @else

                  @foreach($listaProdutos->produtos as $produtoLista)
                    @if($produtoLista->tipo == $opcao[$index])
                      <option value="{{$produtoLista->detalhes}}">{{$produtoLista->detalhes}}</option>
                    @endif
                  @endforeach

                  @endif

              @endif
              </select>
              <label for="floatingOpcao[{{$index}}]">Detalhes</label>
            </div>

            
              @if($guarnicoes_option)
               <div class="form-floating col-3">
                      <select 
                      {{-- class="form-select"  --}}
                      {{-- style="overflow-y: auto" --}}
                      id="floatingGuarnicao[{{$index}}]" 
                      name="produtos[{{$index}}][guarnicoes][]"
                      multiple
                      {{-- wire:change="mudarDetalhe({{$index}})" --}}
                      {{-- aria-label="Floating label select example" --}}
                      >
                      <option value="" selected>-Escolha pelo menos uma Guarnicoes-</option>

                      @foreach($guarnicoes as $guarnicao)
                          <option value="{{$guarnicao['nome']}}">{{$guarnicao['nome']}}</option>
                      @endforeach

                      </select>
                  </div>
                @endif
          <div class="form-floating col-2">
              <input type="text" class="form-control" id="floatingValor[{{$index}}]"
               name="produtos[{{$index}}][valorUnitario]" 
               wire:model.lazy="produtos.{{$index}}.valorUnitario"
               wire:change="calcularTotal"
               {{-- @if($detalhe[$index])
                  @foreach($listaProdutos[0]->produtos as $produtoLista)
                    @if($produtoLista->tipo == $opcao[$index] && $produtoLista->detalhes == $detalhe[$index])
                    value="{{$produtoLista->valor}}"
                    @endif
                  @endforeach
                @endif --}}
               >
              <label for="floatingValor[{{$index}}]">Valor Unitario</label>
          </div>

          <div class="form-floating col-1">
            <input type="number" class="form-control" id="floatingQTD[{{$index}}]" 
            name="produtos[{{$index}}][quantidade]" 
            wire:model.lazy="produtos.{{$index}}.quantidade"
            wire:change="calcularTotal">
            <label for="floatingQTD[{{$index}}]">Quantidade</label>
        </div>
        <div class="col-1">
            <button class="btn btn-danger" wire:click.prevent="removeProduto({{$index}})" type="button"> Deletar </button>
        </div>

        <div class="p-3"></div> 
      @endforeach
{{-- END Produto  --}}
    </div>
    <div class="form-floating float-start">
      <input type="text" class="form-control" id="floatingTotal" name="total" readonly value="{{number_format( $total/ 100, 2, ',','')}}">
      <label class="text-danger" for="floatingTotal">Valor Total</label>
    </div>

    <button type="button" class="btn btn-success float-end" wire:click.prevent="addProduto">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
      <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"></path>
      </svg>
    </button>
    {{-- <button wire:click.prevent="calcularTotal" type="button"> Calc Total</button> --}}
</div>
