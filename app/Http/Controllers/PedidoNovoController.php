<?php

namespace App\Http\Controllers;

use App\Helpers\MyHelpers;
use Illuminate\Http\Request;
use App\Models\UsuariosPedidos;
use Illuminate\Support\Facades\DB;

class PedidoNovoController extends Controller
{
    public function postPedido(Request $request){
        //conta logada
        $user_id = auth()->user()->id;
        
        if(!isset($request->produtos)){
            return redirect()->back()->with('message', 'Erro ao adicionar Pedido!');   
        }


        //caso nao esteja selecionado um usuario cadastrado
        if($request->user_cadastrado === NULL){

            $data = [
                'user_id' => $user_id,
                'nome' => $request->nome,
            ];
    
            if($request->endereco){
                $data['endereco'] = $request->endereco;
            }

            if($request->whatsapp){
                $data['whatsapp'] = MyHelpers::formatarWhatsapp($request->whatsapp); 

                $user = UsuariosPedidos::firstOrCreate([
                   'whatsapp' => $data['whatsapp'], 'user_id' => $user_id 
                ]
                , 
                $data);            
            }else{
                $user = UsuariosPedidos::create($data);  
            } 
            $usuarios_pedidos_id = $user->id;

        } else {
            //caso esteja selecionado um usuario cadastrado pegar id 
            $usuarios_pedidos_id = $request->user_cadastrado;
        }
    

        try {
            DB::beginTransaction();

            $pedido = MyHelpers::formatarPostPedido($request);

            $idPedido =  DB::table('pedidos')->insertGetId(
            [
                'user_id' => $user_id,
                "usuarios_pedidos_id" => $usuarios_pedidos_id, 
                "endereco"=>$pedido['endereco'],
                "retirar_entregar"=> $pedido["retirar_entregar"],
                "troco"=>$pedido['troco'],
                "forma_pagamento"=>$pedido['formaPagamento'],
                "frete"=>$pedido['frete'],
                "valor_total"=>$pedido['total'],
                "observacao"=>$pedido['observacao'],
                "created_at" =>  \Carbon\Carbon::now(),
                "updated_at" => \Carbon\Carbon::now(),

            ]);

            foreach($request->produtos as $index =>  $produto){
                $todas_guarnicoes = "";
                if(array_key_exists('guarnicoes',$produto) && $produto["tipo"] != "Bebida"){
                    foreach($produto['guarnicoes'] as $guarnicao){
                        if($guarnicao == "")
                            continue;
                        $todas_guarnicoes .= $guarnicao . "|";
                    }
                }
                DB::insert('insert into produtos_pedidos (user_id,pedidos_id,tipo,opcao,quantidade,valor,guarnicoes) 
                values (?, ?, ?, ?, ?, ?, ?)', 
                [
                    $user_id,
                    $idPedido, 
                    $produto["tipo"],
                    $produto["opcao"],
                    $produto["quantidade"],   
                    MyHelpers::formatPreco($produto["valorUnitario"]),
                    $todas_guarnicoes,
                ]);
            }
          

            DB::commit();

            return redirect()->back()->with('message', 'Pedido adicionado com sucesso!');   

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', 'Erro ao adicionar Pedido!');   
        }
    }   

    
}
