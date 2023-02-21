<?php

namespace App\Http\Controllers;

use App\Models\ConfigUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Poster extends Controller
{

    public static function mudarSituacao($nome, $situacao,$id_bot,$entrega){
        $situacao = self::formatarSituacao($situacao,$entrega,$nome);

        if(!$situacao == ""){
            $config = ConfigUser::where('chave', 'poster_url')->firstOrFail(['valor']);

            $response = Http::post($config->valor, [
                'id' => $id_bot,
                'situacao' => $situacao,
            ]);
        }
    }
    public static function formatarSituacao($situacao,$entrega,$nome){
        $retorno = "";
        switch($situacao){
            case 1:
                $retorno = "Seu pedido está sendo preparado...🔥";
                break;
            case 2:
                if($entrega == "Entrega"){
                    $retorno = $nome . ", seu pedido saiu para entrega 🛵";
                }else{
                    $retorno = $nome .", seu pedido já pode ser retirado";
                }
                break;
            case 3:
                $retorno = "Pedido Cancelado";
                break;
            //case 4:
              //  $retorno = "Pedido Finalizado, Obrigado por comprar com a gente! ";
                //break;
            default:
                break;
        }
        return $retorno;

    }
}
