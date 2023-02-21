<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <title>Document</title>
</head>
<body>
    <!-- Login -->
 
    <section class="vh-100">
        <div class="container py-5 h-100">
          <div class="row d-flex align-items-center justify-content-center h-100">
            <div class="col-md-8 col-lg-7 col-xl-6">
              <img src="restaurant.jpg"
                class="img-fluid" alt="Phone image">
            </div>
            <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                
                  
              <form method="POST" action="/authenticate">
                @csrf
               @foreach ($errors->all() as $message)
                    <div class="alert alert-danger" role="alert">
                        {{$message}}
                    </div>
              
               @endforeach
                <!-- Email input -->
                <div class="form-outline mb-4">
                  <input name="email" type="email" id="form1Example13" class="form-control form-control-lg" value="{{old('email')}}"/>
                  <label class="form-label" for="form1Example13" >Email</label>
                </div>
      
                <!-- Password input -->
                <div class="form-outline mb-4">
                  <input name="password"  type="password" id="form1Example23" class="form-control form-control-lg" />
                  <label class="form-label" for="form1Example23">Senha</label>
                </div>
      
                <div class="d-flex justify-content-around align-items-center mb-4">
                  <!-- Checkbox -->
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="form1Example3" checked />
                    <label class="form-check-label" for="form1Example3"> Lembrar de mim</label>
                  </div>
                  {{-- <a href="#!">Esqueceu a senha?</a> --}}
                 </div>
      
                <!-- Submit button -->
                <button type="submit" class="btn btn-primary btn-lg btn-block">Logar</button>
      
            
      
      
              </form>
            </div>
          </div>
        </div>
      </section>

       <!-- END Login -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>