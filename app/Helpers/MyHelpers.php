<?php

namespace App\Helpers;

class MyHelpers {
    //Formatar values para mandar para impressora automatica.exe
    public static function formatarImpressao($pedido){
        $ListaPedido = "";
        foreach($pedido->produtos_pedidos as $produto){
            $ListaPedido .= $produto->quantidade . "X ";
            $ListaPedido .= $produto->tipo ." ". $produto->opcao."\n";
            $ListaPedido .= "                 Valor Unitario: R$ " . number_format($produto->valor/ 100, 2, ',','');
            $ListaPedido .= "\n";
        }
        if($pedido->observacao){
            $ListaPedido .= "Observacao: ".$pedido->observacao;
            $ListaPedido .= "\n";
        }
        if($pedido->troco){
            $troco = "R$ ". number_format($pedido->troco/ 100, 2, ',','');
        }else $troco = "-";

        if($pedido->endereco){
            $endereco = $pedido->endereco;
        }else $endereco = $pedido->usuarios_pedidos->endereco;
    

        return [
        'id'=>$pedido->id,
        'nome' => $pedido->usuarios_pedidos->nome_completo ?? $pedido->usuarios_pedidos->nome,
        'data' => \Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y H:i'),
        'whatsapp' => $pedido->usuarios_pedidos->whatsapp,
        'endereco'=>$endereco,
        'retirar_entregar'=>$pedido->retirar_entregar,
        'ListaPedido'=>$ListaPedido,
        'ValorTotalcomFrete'=>"R$".number_format($pedido->valor_total / 100, 2, ',',''),
        'FormaPgto'=>$pedido->forma_pagamento,
        'troco' => $troco,
        ];
    }
    //funcao de formatar o preco dependendo do que vem
    public static function formatPreco($preco){
        
        $preco = preg_replace('/[^0-9\.,]/', '', $preco);
        $preco = preg_replace('/ /', '', $preco);
        $preco = preg_replace('/\./', ',', $preco);
        
        if($decimal = strstr($preco, ',')){
            if(strlen($decimal) == 2){
                $preco = $preco . "0";
            }
            if(strlen($decimal) > 3){
                $preco = substr($preco, 0, (strlen($decimal)-3) * -1 );
            }
           
        }else {
            $preco = $preco . "00";
        } 

        $preco = preg_replace('/,/', '', $preco);

        if(!is_numeric($preco)){
            $preco = 0;
        }
       
        return $preco;
    }
    public static function formatarWhatsapp($whatsapp){
        $whatsapp = str_replace(' ','',$whatsapp);
        return  "+5534".$whatsapp;
    }
    public static function formatarPostPedido($request){

        if(isset($request->endereco)){
            $endereco = $request->endereco;
        }else $endereco = "";

        if(isset($request->observacao)){
            $observacao = $request->observacao;
        }else $observacao = "";

        if(isset($request->formaPagamento)){
            $formaPagamento = $request->formaPagamento;
        }else $formaPagamento = "Nao especificado";

        if(isset($request->retirar_entregar)){
            $retirar_entregar = $request->retirar_entregar;
        }else $retirar_entregar = "Nao especificado";

        $total = 0;
        foreach($request->produtos as $index => $produto){
            if($produto['valorUnitario'] != ""){
                $v = $produto['valorUnitario'];
            } else $v = 0;

            if($produto['quantidade'] != ""){
                $q = $produto['quantidade'];
            } else $q = 0;
            
            $total = self::formatPreco($v) * $q + $total;
        }

        
        if($request->frete != ""){
            $frete = self::formatPreco($request->frete);
        }else $frete = 000;
        
        if($request->troco != ""){
            $troco = self::formatPreco($request->troco);
        }else $troco = 000;
        
        
        $total = $total + $frete;

        return [
            "endereco"=>$endereco,
            "troco"=>$troco,
            "retirar_entregar"=> $retirar_entregar,
            "formaPagamento"=>$formaPagamento,
            "frete"=>$frete,
            "total"=>$total,
            "observacao"=>$observacao,
        ];

    }
}