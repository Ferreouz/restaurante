<div  class="modal fade" id="Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="true">
  <form action="{{ route('adicionarPedido') }}" method="POST">
    @csrf
    <!-- modal-lg -->
    <div class="modal-dialog modal-fullscreen modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Adicionar Pedido</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
           @livewire('form-novo')
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-info" >Salvar</button>
          {{-- <button type="button" class="btn btn-primary">Salvar e imprimir</button> --}}
        </div>
      </div>
    </div>
  </form>
</div>