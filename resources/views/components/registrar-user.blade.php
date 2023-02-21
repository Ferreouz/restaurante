<div class="row">
    <div class="card col-12">
        <form action="{{route('user.post')}}" method="POST">
        @csrf
        <div class="card-header">
            <h3>
                Cadastrar Usuario
            </h3>
        </div>
        <div class="card-body">
            @foreach ($errors->all() as $message)
            <div class="alert alert-danger" role="alert">
                {{$message}}
            </div>
      
       @endforeach
              <div class="row">
            <div class="form-floating col-3">
                <input type="text" class="form-control" id="floatingNomeCompleto" name="nome_completo" placeholder=""  value="{{old('nome_completo')}}">
                <label for="floatingNomeCompleto">Nome Completo</label>
            </div>
            <div class="form-floating col-3">
                <input type="text" class="form-control" id="floatingNome" name="nome" placeholder=""  value="{{old('nome')}}">
                <label for="floatingNome">Nome p/ Exibicao</label>
            </div>
            <div class="form-floating col-3">
                <input type="text" class="form-control" id="floatingEndereco" name="endereco" placeholder=""  value="{{old('endereco')}}">
                <label for="floatingEndereco">Endereco</label>
            </div>
    
            <div class="form-floating col-3">
              <input maxlength="8" type="text" class="form-control" id="floatingTelefone" name="whatsapp" placeholder=""  value="{{old('whatsapp')}}">
              <label for="floatingTelefone">Numero de telefone (88758888)</label>
            </div>

            <div class="p-3"></div>

            <div class="form-floating col-3">
                <input maxlength="15" type="text" class="form-control" id="floatingCpf" name="cpf" placeholder=""  value="{{old('cpf')}}">
                <label for="floatingCpf">CPF</label>
              </div>
            

             <div class="form-floating col-3">
                <input type="date" class="form-control" id="floatingNascimento" name="nascimento" placeholder="" >
                <label for="floatingNascimento">Data Nascimento</label>
              </div>

              <button type="submit" class="btn btn-success col-2">Salvar</button>
              
             </div>

        </div>        
    </form>
      </div>
</div>