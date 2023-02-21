<div class="modal fade" id="ModalPedido" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-right modal-dialog-scrollable">
      <div class="modal-content">
        
        <div class="modal-header">
            <h1 class="modal-title fs-5 text-success" id="exampleModalLabel">Pedido #{{$pedido->id ?? ""}} </h1>
            {{-- <h5 class="modal-title text-primary" >-- Nome: {{$pedido->primeiro_nome ?? ""}}</h5> --}}
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body"  >
          <p>**************************************</p>

          <p>Data: @if(isset($pedido->created_at)) @php(\Carbon\Carbon::setLocale('pt_BR')){{ \Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y H:i')}} @endif</p>

          <p>Nome: @if(isset($pedido->usuarios_pedidos->nome))
             {{$pedido->usuarios_pedidos->nome}}
             @else
             {{$pedido->usuarios_pedidos->nome_completo ?? ""}}
            @endif</p>

          <p>Endereco: 
            @if(isset($pedido->endereco))
             {{$pedido->endereco}}
             @else
             {{$pedido->usuarios_pedidos->endereco ?? ""}}
            @endif
              </p>
          <p>Numero de telefone: {{$pedido->usuarios_pedidos->whatsapp ?? ""}}</p>
          <p>Retirar/Entregar: {{$pedido->retirar_entregar?? ""}}</p>
          <br>

          <p><strong> Detalhes:</strong></p>
          @if(isset($pedido->produtos_pedidos))
            @foreach ($pedido->produtos_pedidos as $produto)
              {{$produto->quantidade . "X ". $produto->tipo . " " .$produto->opcao}}
              <br>
              <p class="float-end"> Valor Unitario: R$ {{number_format($produto->valor/ 100, 2, ',','')}}</p>
              <br>
            @endforeach
          @endif
          <br>

          @if(isset($pedido->observacao))
              <p> <strong>Observacao: </strong>  {{$pedido->observacao}}</p>
          @endif
          <br>

          <p class="float-end"> Valor Total com Frete: R$ @if(isset($pedido->valor_total)) {{ number_format( $pedido->valor_total/ 100, 2, ',','')}} @endif</p>
          <br>
          @if(isset($pedido->troco) )
            <p class="float-end">Troco: R$ @if(isset($pedido->troco)){{number_format($pedido->troco/ 100, 2, ',','')}} @endif</p>
          @endif
          <p class="float-end">Forma de Pagamento: {{$pedido->forma_pagamento ?? ""}}</p>
          
          <p>**************************************</p>

          
      


        </div>
          <div class="modal-footer">
            <button data-bs-dismiss="modal" aria-label="Close" wire:click="imprimirNovamente" type="button" class="btn btn-primary">Imprimir Novamente</button>
          </div>
      </div>
    </div>
</div>  