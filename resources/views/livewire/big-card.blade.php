<div >
    <div class="container" >
        <div class="row overflow-scroll" >
            <div class="d-flex justify-content-evenly">
                <!-- CARD NOVO -->
                <!-- <div class="card-footer bg-transparent border-primary">Footer</div> -->
    
                <div class="card border-primary big-card" >
                    <div class="card-header d-flex justify-content-between text-primary">
                         <h5 wire:click="maisPedido" class="card-title">NOVO</h5>
                        
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#Modal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"></path>
                            </svg>
                        </button>
                    </div>
                    {{-- wire:poll="buscarPedidos" --}}
                    {{-- wire:poll.3000ms --}}
                    <div class="card-body container-drag" wire:poll.6000ms>
                        <!-- EXEMPLO PEDIDO -->
                        @if($pedidos)
                          @foreach ($pedidos as $pedido)
                              @if($pedido->situacao === NULL)
                                @include('components.pedido')
                              @endif
                          @endforeach
                        @endif
                         <!-- END EXEMPLO PEDIDO -->
                        
                    </div>
                    
                  </div>
                <!-- FIM CARD NOVO -->
    
                  <div class="p-2"></div>
                <!-- CARD PREPARO -->
    
                  <div class="card border-success big-card" >
                    <div class="card-header text-success">
                        <h5 class="card-title">EM PREPARO</h5>
                        </div>
                    <div id="container.preparo" class="card-body text-sucess container-drag">
                    @if($pedidos)
                      @foreach ($pedidos as $pedido)
                        @if($pedido->situacao === 1)
                          @include('components.pedido')
                        @endif
                      @endforeach
                    @endif
                    </div>
                  </div>
                <!-- FIM CARD PREPARO -->
    
                  <div class="p-2"></div>
                <!-- CARD PRONTO -->
                  <div class="card border-dark big-card" >
                    <div class="card-header text-dark"> <h5 class="card-title">ENTREGA/<br>RETIRADA</h5></div>
                    <div id="container.entrega" class="card-body text-dark container-drag">

                    @if($pedidos)
                      @foreach ($pedidos as $pedido)
                      @if($pedido->situacao === 2)
                        @include('components.pedido')
                      @endif
                      @endforeach
                    @endif

                    </div>
                  </div>
                <!-- FIM CARD PRONTO -->

                <div class="p-2"></div>
                <!-- CARD ENTREGUE -->
                <div class="card border-warning big-card" >
                 <div class="card-header text-warning"> <h5 class="card-title">Finalizado</h5></div>
                 <div id="container.finalizado" class="card-body text-warning container-drag">

                   @if($pedidos)
                   @foreach ($pedidos as $pedido)
                     @if($pedido->situacao === 4)
                       @include('components.pedido')
                    @endif
                   @endforeach
                 @endif
                 
                 </div>
               </div>
               <!--FIM CARD ENTREGUE -->

                  <div class="p-2"></div>
                  <!-- CARD CANCELADO -->
                  <div class="card border-danger big-card" >
                    <div class="card-header text-danger"> <h5 class="card-title">CANCELADO</h5></div>
                    <div id="container.cancelado" class="card-body text-danger container-drag">

                      @if($pedidos)
                      @foreach ($pedidos as $pedido)
                        @if($pedido->situacao === 3)
                          @include('components.pedido')
                       @endif
                      @endforeach
                    @endif
                    
                    </div>
                  </div>
                  <!--FIM CARD CANCELADO -->

                 

                
                 
                </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
const containers = document.querySelectorAll('.container-drag')
containers.forEach(container =>{
    container.addEventListener('dragover',evt =>{
        evt.preventDefault()
        const draggable = document.querySelector('.dragging')
        
        container.appendChild(draggable)

        draggable.ondragend = e=>{
          e.stopImmediatePropagation()
          mudarSituacaoPedido(draggable.id, container.id)
        }
        // draggable.addEventListener('dragend', e=>{
        //   console.log("dps FIROU")
        //   e.stopImmediatePropagation()
        //   console.log("dps1 FIROU")


        //   mudarSituacaoPedido(draggable.id, container.id)
        //   // console.log(draggable.id,container.id)
        // })
    })
})
function mudarSituacaoPedido(pedidoID, containerID){
  var situacao;
  switch(containerID){
    case 'container.preparo':
      situacao = 1;
      break;
    case 'container.entrega':
      situacao = 2;
      break;
    case 'container.cancelado':
      situacao = 3;
      break;
    case 'container.finalizado':
      situacao = 4;
      break;
    default:
       situacao = 0;
  }
  if(situacao != 0 ){
    Livewire.emit('mudarSituacaoPedido', pedidoID,situacao)
  }
}
function dragging(element){
  element.classList.add('dragging')
}
function draggend(element){
  element.classList.remove('dragging')
}
//ao receber o evento de pedido novo
window.addEventListener('pedidoAdicionado', event => {
  imprimirTermica(event.detail.arrayPedido)
}, false);

//variavel de controle para mandar imprimir uma por vez
var printCONTROL = 1;


//funcao pra mandar imprimir
function imprimirTermica(arrayPedido){

  // console.log("Pedido mandado")
  const pedido = JSON.stringify({'pedido':arrayPedido})
    if(printCONTROL === 1){
      printable = 0;
      fetch('http://localhost:8090/', {method: 'POST',headers:{
      'Accept': 'application/json',
      'Content-Type':'application/json'
    } 
    ,body: pedido}).then(resposta => resposta.json()).then(json =>{
      if(json.statusImpressao == 'sucesso'){
        Livewire.emit('impressaoOK', arrayPedido.numero)
        // console.log("impressaoOK")
      }else console.log(json.statusImpressao)
      printCONTROL = 1;
    }).catch(err =>{
      console.error(err)
      printCONTROL = 1;
    })
  }

}
    function updateModal(arg){
        
      Livewire.emit('ModalPedido:updateModal', arg)
        // spinner.classList.remove('d-none')
        // bg.classList.remove('d-none')

    }
    const ModalPedido = document.getElementById('ModalPedido')
    function abrirModal(){
        const modal = bootstrap.Modal.getOrCreateInstance(ModalPedido)
        // spinner.classList.add('d-none')
        // bg.classList.add('d-none')
        modal.show()
    }
    window.addEventListener('modal-loaded', abrirModal)
    </script>
@endpush
