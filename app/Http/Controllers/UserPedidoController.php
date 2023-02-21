<?php

namespace App\Http\Controllers;

use App\Helpers\MyHelpers;
use Illuminate\Http\Request;
use App\Models\UsuariosPedidos;

class UserPedidoController extends Controller
{
    public function newUser(Request $request){

        $request->validate([
            'nome' => 'required',
            'nome_completo' => 'required',
            'endereco' => 'required',
            'whatsapp' => ['integer','digits:8'],
        ],
        //mensagens:
        [
            'nome.required' => 'Nome é obrigatório!',
            'endereco.required' => 'Endereco é obrigatório!',
            'nome_completo.required' => 'Nome completo é obrigatório!',
            'whatsapp.integer' => 'Telefone só aceita numeros!',
            'whatsapp.digits' => 'Telefone precisa apenas de 8 digitos!',

        ]);
        $user_id = auth()->user()->id;
        //limpar onde form e null
        $collection = collect(request()->all())->filter()->all();
        $collection['user_id'] =  $user_id;

        if($collection['whatsapp']){
     
            $collection['whatsapp'] = MyHelpers::formatarWhatsapp($collection['whatsapp']); 
          
            $user = UsuariosPedidos::updateOrCreate([
               'whatsapp' => $collection['whatsapp'], 'user_id' =>  $user_id,
            ]
            , $collection);
            return back()->with('message', 'Editado com sucesso!');
            
        }else{
            $user = UsuariosPedidos::create($collection); 
        } 

        return back()->with('message', 'Cadastrado com sucesso!');
    }

}
