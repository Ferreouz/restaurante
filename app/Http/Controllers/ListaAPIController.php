<?php

namespace App\Http\Controllers;

use App\Models\Bebidas;
use App\Models\Produtos;
use App\Helpers\MyHelpers;
use Illuminate\Http\Request;
use App\Models\ListaProdutos;
use App\Models\UsuariosPedidos;
use Illuminate\Support\Facades\DB;

class ListaAPIController extends Controller
{
   public $erro = ['erro' => 1];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //user logado
        $user_id = auth()->user()->id;

        $lista = ListaProdutos::with(['produtos' => function($d){
            $d->where('ativado_hoje', 1);
        }])
        ->where('ativado_hoje', 1)
        ->first();

        $data = "";
        foreach($lista->produtos as $produto){
            //$data .= $produto->id . " - ";
            $data .= $produto->tipo . " ". $produto->detalhes . " ";
            $data .= number_format( $produto->valor / 100, 2, ',','');
            $data .= "\n";
        }

        $bebidas =  Bebidas::where('ativado_hoje', 1)
        ->get(['id','nome','valor']);
        
        $holder = "";
        foreach($bebidas as $bebida){
          // $holder .= $bebida->id . " - ";
           $holder .= $bebida->nome . " ";
           $holder .= number_format( $bebida->valor / 100, 2, ',','');
           $holder .= "\n";
        }
        if($lista->imagem != ""){
            $cardapio = env('APP_URL'). "/" .$lista->imagem;
        } else  $cardapio = env('APP_URL') . "/restaurant.jpg";
    
        $imagemBebidas = env('APP_URL') .  "/" . 'storage/bebidas_'. $user_id  . '.png';
        return ['data' => $data, 'bebidas'=> $holder, 'imagem' => $cardapio, 'imagem_bebidas' => $imagemBebidas] ;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function buscarProduto($id){
        if ( filter_var($id, FILTER_VALIDATE_INT) === false ) {
            // return ['mensagem' => 'Produto não encontrado!'];
            return $this->erro;
        }
        $user_id = auth()->user()->id;
        try{
            $lista_ativada = ListaProdutos::where('ativado_hoje', 1)
            ->first();

            $numero = $id - 1;
            $produto = Produtos::where('lista_id', $lista_ativada->id)->skip($numero)->first();

            if(!$produto){
                // return ['mensagem' => 'Produto não encontrado!'];
                return $this->erro;
            }
            if($produto->ativado_hoje === 0){
                // return ['mensagem' => 'Esse produto esta indisponível no momento!'];
                return $this->erro;
            }
            $valor = (float) number_format( $produto->valor/ 100, 2, '.');
            $reposta = [
                'id' => $produto->id,
                'detalhes' =>  $produto->tipo . " " . $produto->detalhes,
                'valor' => $valor,
            ];
            return $reposta;
        }catch (\Exception $e){
            // return ['erro' => 'Ocorreu um erro ao buscar esse produto'];
            return $this->erro;
        }

       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function buscarBebida($id){
        if ( filter_var($id, FILTER_VALIDATE_INT) === false ) {
            // return ['mensagem' => 'Bebida não encontrada!'];
            return $this->erro;
        }
        $user_id = auth()->user()->id;
        try{
            $numero = $id - 1;
            $bebida =  Bebidas::skip($numero)
            ->first();
            $reposta = "";

            if(!$bebida){
                // return ['mensagem' => 'Bebida não encontrada!'];
                return $this->erro;
            }
            if($bebida->ativado_hoje === 0){
                // return ['mensagem' => 'Essa bebida esta indisponível no momento!'];
                return $this->erro;
            }

            $valor = (float) number_format( $bebida->valor/ 100, 2, '.');

            $reposta = [
                'id' => $bebida->id,
                'detalhes' =>  $bebida->nome,
                'valor' => $valor,
            ];
            return $reposta;

        }catch (\Exception $e){
            // return ['erro' => 'Ocorreu um erro ao buscar essa bebida'];
            return $this->erro;
        }

    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        // $user = UsuariosPedidos::firstOrCreate(
        //     ['whatsapp' =>  $request->telefone],
        //     [
        //     'id_bot' => $request->id_bot,
        //     'nome' => $request->nome,
        //     'whatsapp' =>  $request->telefone
        //     ]
        // );
        $user_id = auth()->user()->id;

        $data = [
            'user_id' => $user_id ,
            'id_bot' => $request->id_bot,
            'nome' => $request->nome,
            'whatsapp' =>  $request->telefone,
        ];

        if($request->endereco){
            $data['endereco'] = $request->endereco;
        }
        //criar usuario ou buscar ja criado
        $user = UsuariosPedidos::firstOrCreate(
            [
                'whatsapp' =>  $request->telefone,
                'user_id' => $user_id ,
            ],
            $data
        );

        try{
            DB::beginTransaction();

            $idPedido =  DB::table('pedidos')->insertGetId(
                [
                    'user_id' => $user_id ,
                    "usuarios_pedidos_id" => $user->id, 
                    "endereco" => $request->endereco,
                    "retirar_entregar" => $request->entregar_retirar,
                    "troco"=>  MyHelpers::formatPreco($request->troco),
                    "forma_pagamento"=> $request->pagamento,
                    "frete"=> MyHelpers::formatPreco($request->frete),
                    "valor_total"=>  MyHelpers::formatPreco($request->valor),
                    "observacao"=> $request->observacao,
                    "created_at" =>  \Carbon\Carbon::now(),
                    "updated_at" => \Carbon\Carbon::now(),
                ]);
                //remover ultimo character
             

              if($request->comida){
                $request->comida = substr($request->comida, 0, -1) ;
                $comidas = explode(";", $request->comida);
                foreach($comidas as $comida){
                    $comidaS = explode("x", $comida);

                    $produto = Produtos::where('id', $comidaS[1])
                    ->first(['tipo','detalhes','valor']);

                    if(!$produto){
                        continue;
                    }

                    DB::insert('insert into produtos_pedidos (user_id,pedidos_id,tipo,opcao,quantidade,valor) 
                    values (?, ?, ?, ?, ?, ?)', 
                    [
                        $user_id ,
                        $idPedido, 
                        $produto->tipo,
                        $produto->detalhes,
                        $comidaS[0],
                        $produto->valor,
                    ]);
                }
              }

              if($request->bebida){
                $request->bebida = substr($request->bebida, 0, -1) ;
                $bebidas = explode(";", $request->bebida);
                foreach($bebidas as $bebida){
                    $comidaS = explode("x", $bebida);
                    
                    $produto = Bebidas::where('id', $comidaS[1])
                    ->first(['nome','valor']);
                    if(!$produto){
                        continue;
                    }

                    DB::insert('insert into produtos_pedidos (user_id,pedidos_id,tipo,opcao,quantidade,valor) 
                    values (?, ?, ?, ?, ?, ?)', 
                    [
                        $user_id ,
                        $idPedido, 
                        'Bebida',
                        $produto->nome,
                        $comidaS[0],
                        $produto->valor,
                    ]);
                }
            }
            DB::commit();

            return ['numero'=>$idPedido];
            
        }catch (\Exception $e) {
            DB::rollBack();
            // return ['mensagem' => 'erro ao fazer pedido'];  
            return $this->erro; 
        }
     

    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show($id)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, $id)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy($id)
    // {
    //     //
    // }
}
