<?php


use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PedidoNovoController;
use App\Http\Controllers\UserPedidoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::get('/', function(){
        return redirect('/home');
    });
    //deslogar
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    
    //pagina gerenciadora de pedidos
    Route::get('/home', function () {
        return view('layouts.app');        
    })->name('home');
    
    //adicionar novo pedido na mao
    Route::post('/novo', [PedidoNovoController::class, 'postPedido'])->name('adicionarPedido');

    //pagina de configuracoes de produtos
    Route::get('/config', function () {
        return view('layouts.app');        
    })->name('config.get');

    //pagina de registrar usuario
    Route::get('/registrar-user', function () {
        return view('layouts.app');        
    })->name('user.get');

    //baixar programa de impressao
    Route::get('/download', function () {
        $file = storage_path('download/impressao_automatica.exe');
        if(file_exists($file)){
            return response()->download($file);        
        } 
        return redirect('/home');
    })->name('download')
    ;
    //post pra criar novo usuario
    Route::post('/registrar-user', [UserPedidoController::class, 'newUser'])->name('user.post');


});

Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/authenticate', [LoginController::class, 'authenticate']);

// Route::get('/createNewUser', function(){
//     $token = Str::random(60);
 

//     $user = User::create([
//         'name' => 'teste2',
//         'email' => 'teste2@teste.com',
//         'password' => bcrypt('Password123') 
//     ]);
//     return $user->createToken($token)->plainTextToken;
// });

  // ->withHeaders([
        //     'Acess-Control-Allow-Origin' => 'http://localhost:8090',
        //     'Acess-Control-Allow-Methods' => 'POST,GET',
        //     'Acess-Control-Allow-Headers' => 'Content-Type, Authorization',
        //     'Acess-Control-Max-Age' => '86400',
        // ])
