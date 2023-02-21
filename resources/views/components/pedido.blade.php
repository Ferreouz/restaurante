<div onclick="updateModal({{$pedido->id}})" id="{{$pedido->id}}" ondragstart="dragging(this)" ondragend="draggend(this)" class="card text-white bg-success mb-3 draggable" draggable="true" style="max-width: 18rem;">
    <div class="card-header"> 
      Pedido #{{$pedido->id}}
      {{$pedido->usuarios_pedidos->nome}}
      <br>
      <small class="text-info">Feito   {{ \Carbon\Carbon::parse($pedido->created_at)->diffForHumans()}}</small>
  </div>

    
</div>
{{-- <div onclick="updateModal({{$pedido->id}})" id="{{$pedido->id}}" ondragstart="dragging(this)" ondragend="draggend(this)" class="card text-white bg-success mb-3 draggable" draggable="true" style="max-width: 18rem;">
  <div class="card-header">Pedido #{{$pedido->id}}</div>
  <div class="card-body">
    <p class="card-text">
      @foreach ($pedido->produtos_pedidos as $produto)
        {{ $produto->quantidade . "X "}}
        {{$produto->tipo ." ". $produto->opcao}} <br>
      @endforeach
      </p>
      @if($pedido->observacao)
      <p> <strong>Obs:</strong> {{$pedido->observacao}}</p>
      @endif
  </div>
  <div class="card-footer">
      <small class="text-white">Feito   {{ \Carbon\Carbon::parse($pedido->created_at)->diffForHumans()}}</small>
  </div>
</div> --}}
