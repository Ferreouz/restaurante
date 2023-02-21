<div class="row">

<div class="col-6">
    {{-- LISTA DISPONIVEIS --}}
<div class="card text-bg-light mb-3" style="max-width: 30rem;">
    <form>

        <div class="card-header d-flex justify-content-between"> 
            <h5 class="card-title">LISTA PEDIDOS</h5>
            </div>
        <div class="card-body">
            <div class="row">

           
            <div class="form-floating col-8">
                <select class="form-select" 
                wire:model="currentLista"
                aria-label="Floating label select example">
                <option onclick="location.reload()" value="" selected>-Escolha uma opção-</option>
                @foreach($listaProdutos as $index => $lista)
                  <option
                  value="{{$lista['id']}}"> {{$lista['nome']}} @if($lista['ativado_hoje'] === 1)  ATIVADO  @endif</option>
                @endforeach
                </select>
                <label>Tipo</label>
            </div>
            <div class="form-floating col-4">
                <button type="button" class="btn btn-success" 
                wire:click="updateLista"
                > Ativar essa Hoje</button>
            </div>

        </div>
        </div>
    </form>
</div>
{{-- END LISTA DISPONIVEIS --}}
{{-- PHOTO UPLOAD AND PREVIEW --}}
@if($currentLista !== NULL)
<div class="card text-bg-light mb-3" style="width: 18rem;">
<img class="card-img-top" width="180" height="400"
src="
    @if($photo)
        @php
            try {
               $url = $photo->temporaryUrl();
               $photoStatus = true;
            }catch (RuntimeException $exception){
                $photoStatus =  false;
            }
        @endphp
        @if($photoStatus)
            {{ $url }}
        @endif
    
    @else
        @foreach($listaProdutos as $lista)
            @if($lista['id'] ==$currentLista)
            {{$lista['imagem']}}
            @endif
        @endforeach
    @endif
"
 alt="Cardapio">
 <div class="card-body">
    <form wire:submit.prevent="save">
        <div class="mb-3">
            <label for="formFile" class="form-label">Default file input example</label>
            <input class="form-control" type="file" wire:model="photo" accept="image/*">
          </div>          
    @error('photo') <span class="error">{{ $message }}</span> @enderror
    
   
    <button type="submit" class="btn btn-primary" >Salvar Cardapio</button>
</form>
  </div>

  
</div> 
@endif
{{-- END PHOTO UPLOAD AND PREVIEW --}}

{{-- Produtos --}}
<div class="card text-bg-light mb-3" style="max-width: 50rem;">
    <form>
        @csrf
        <div class="card-header d-flex justify-content-between"> 
            <h5 class="card-title">LISTA tal</h5>
            
            <button type="button"
            
            
             wire:click="addProduto"
                class="btn btn-secondary @if($currentLista === NULL) disabled @endif">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"></path>
                </svg>
            </button>
            </div>
        <div class="card-body">
            <div class="row">
            @foreach($produtos as $index => $produto)
            @if($produto['lista_id'] == $currentLista)
                <div class="form-floating col-4">
                    <select required class="form-select" 
                    wire:model="produtos.{{$index}}.tipo"
                    wire:change="botaoSalvarProdutos({{$index}})"
                    aria-label="Floating label select example">
                        <option @if($produto['tipo'] == "") selected @endif value="">-Escolha uma opção-</option>
                      <option @if($produto['tipo'] == "Marmitex") selected @endif value="Marmitex">Marmitex</option>
                      <option @if($produto['tipo'] == "Barca") selected @endif value="Barca">Barca</option>
                      <option @if($produto['tipo'] == "Hamburguer") selected @endif value="Hamburguer">Hamburguer</option>
                      <option @if($produto['tipo'] == "Comida") selected @endif value="Comida">Comida</option>

                    </select>
                    <label>Tipo</label>
                </div>
            <div class="form-floating col-8">
                <input type="text" class="form-control" 
                id="floatingDetalhe{{$index}}" 
                wire:model.lazy="produtos.{{$index}}.detalhes"
                wire:change="botaoSalvarProdutos({{$index}})"
                >
                <label for="floatingDetalhe{{$index}}">Detalhes</label>
            </div>
            <div class="form-floating col-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="flexCheckProduto{{$index}}" 
                    wire:model="produtos.{{$index}}.ativado_hoje"
                    wire:change="botaoSalvarProdutos({{$index}})"
                    @if($produto['ativado_hoje']) checked @endif>
                    <label class="form-check-label" for="flexCheckProduto{{$index}}">
                    Disponivel
                    </label>
                </div>
            </div>
            
            <div class="form-floating col-4">
                <input 
                wire:model.lazy="produtos.{{$index}}.valor"
                type="text" class="form-control" 
                id="floatingPreco.{{$index}}"
                wire:change="botaoSalvarProdutos({{$index}})" 
                >
                
                <label for="floatingPreco.{{$index}}">Valor</label>
            </div>

            <a  
            wire:click="salvarProdutos({{$index}})"
             class="btn link-success col-2 @if($produto['botaoSalvar'] == 0) disabled @endif"
            style="border: none"  href="#">Salvar</a>
           <a style="border: none" href="#" class="btn link-danger col-2"
           onclick="confirm('Tem certeza que deseja deletar esse Produto?') || event.stopImmediatePropagation()"
           wire:click="deleteProduto({{$index}})"
           >Deletar</a>

            <div class="p-3"></div> 
        @endif
        @endforeach
        </div>
        </div>
     
    </form>
</div>

</div>
{{-- SALVAR LISTA --}}

{{--BEBIDAS --}}

<div class="col-6">
    <div class="card text-bg-light mb-3" style="max-width: 60rem;">
        <form>
            <div class="card-header d-flex justify-content-between"> 
                 <h5 class="card-title">LISTA BEBIDAS</h5>
                        
                <button type="button"
                wire:click="addBebida"
                 class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"></path>
                    </svg>
                </button></div>

            <div class="card-body">
                <div class="row">
                    @foreach($bebidas as $index => $bebida)
                        <div class="form-floating col-4">
                            <input type="text" class="form-control" 
                            id="floatingNome{{$index}}" name="nomeBebida"  
                            wire:change="botaoSalvarBebidas({{$index}})" 
                            wire:model.lazy="bebidas.{{$index}}.nome"
                            >
                            <label for="floatingNome{{$index}}">Nome</label>
                        </div>
            
                        <div class="form-floating col-2">
                            <input type="text" class="form-control" 
                            id="floatingPreco{{$index}}" name="precoBebida" 
                            wire:change="botaoSalvarBebidas({{$index}})" 
                            wire:model.lazy="bebidas.{{$index}}.valor"
                            >
                            <label for="floatingPreco{{$index}}">Preco</label>
                        </div>
            
                        <div class="form-floating col-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="flexCheckBebidaDisponivel{{$index}}" 
                                wire:change="botaoSalvarBebidas({{$index}})" 
                                wire:model="bebidas.{{$index}}.ativado_hoje"
                                @if($bebida['ativado_hoje']) checked @endif>
                                <label class="form-check-label" for="flexCheckBebidaDisponivel{{$index}}">
                                Disponivel Hoje
                                </label>
                            </div>
                        </div>
                            <a  wire:click="salvarBebidas({{$index}})" class="btn link-success col-2 @if($bebida['botaoSalvar'] == 0) disabled @endif"
                             style="border: none"  href="#">Salvar</a>
                            <a style="border: none" href="#" class="btn link-danger col-2"
                            onclick="confirm('Tem certeza que deseja deletar essa Bebida?') || event.stopImmediatePropagation()"
                            wire:click="deleteBebida({{$index}})"
                            >Deletar</a>

                        <div class="p-3"></div> 


                @endforeach

            </div>
            </div>
        </form>
        
    </div>
    <div class="card text-bg-light mb-3" style="width: 18rem;">
        <img class="card-img-top" width="180" height="400"
        src="{{$bebidaPath}}"
         alt="Cardapio Bebidas">
         <div class="card-body">
            <form wire:submit.prevent="saveImageBebida">
                <div class="mb-3">
                    <input class="form-control" type="file" wire:model="photoBebida" accept="image/*">
                  </div>          
            @error('photoBebida') <span class="error">{{ $message }}</span> @enderror
            
           
            <button type="submit" class="btn btn-primary" >Salvar Cardapio Bebidas</button>
        </form>
       </div>
    </div> 
</div>

{{-- END BEBIDAS  --}}
</div>
