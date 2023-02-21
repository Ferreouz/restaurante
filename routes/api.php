<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListaAPIController;
use App\Http\Controllers\UsersAPIController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::get('/user/id_bot/{id}',[UsersAPIController::class, 'show']);
// Route::get('/user/cpf/{cpf}',[UsersAPIController::class, 'get_cpf']);
// Fazer update user by id_bot

Route::group(['middleware' => ['auth:sanctum']],function(){
    Route::post('/user',[UsersAPIController::class, 'update']);
    Route::get('/lista',[ListaAPIController::class, 'index']);
    Route::get('/produto/{id}',[ListaAPIController::class, 'buscarProduto']);
    Route::get('/bebida/{id}',[ListaAPIController::class, 'buscarBebida']);
    Route::post('/pedido',[ListaAPIController::class, 'store']);
});

// Route::get('create');
// Route::post('/tokens/create', function (Request $request) {
//     $token = $request->user()->createToken($request->token_name);
 
//     return ['token' => $token->plainTextToken];
// });


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/user', function(){
//         return json_encode(['message'=>"ok"]);
//     });
// });
