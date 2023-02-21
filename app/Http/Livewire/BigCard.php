<?php

namespace App\Http\Livewire;


use App\Models\Pedido;
use Livewire\Component;
use App\Helpers\MyHelpers;
use App\Http\Controllers\Poster;

class BigCard extends Component
{
    public $pedidos = [];
    public $today;
    public $yesterday;


    protected $listeners = ['impressaoOK' => 'impressaoOK', 'mudarSituacaoPedido'=>'mudarSituacaoPedido'];

    public function mount(){
        $this->today = \Carbon\Carbon::today();
        $this->yesterday = \Carbon\Carbon::yesterday();
    }
    public function render()
    {
        $ys = $this->yesterday;

        /* OLD MOSTRANDO OS DE HOJE
        // $this->pedidos = Pedido::whereDate('created_at', $today)
        // ->orderBy('created_at','desc')
        // ->get();
        */


        // CARD PEQUENO
        $this->pedidos = Pedido::whereDate('created_at', $this->today)
        ->orWhere(function ($q) use($ys){
            $q->whereNotIn('situacao', [3,4])
            ->whereDate('created_at', $ys);
        })
        ->orderBy('created_at','desc')
        ->get();
         
        \Carbon\Carbon::setLocale('pt_BR');

        // ,'usuarios_pedidos' 
        //CARD GRANDE COM DETALHES
        // $this->pedidos = Pedido::with(['produtos_pedidos'])
        // ->whereDate('created_at', $this->today)
        // ->orWhere(function ($q) use($ys){
        //         $q->whereNotIn('situacao', [3,4])
        //         ->whereDate('created_at', $ys);
        // })
        // ->orderBy('created_at','desc')
        // ->get();

        $this->checkPedidosNaoImpressos();
        // dd($pedidos);
        return view('livewire.big-card');
    }

    public function checkPedidosNaoImpressos()
    {

        foreach($this->pedidos as $pedido){
            if($pedido->situacaoImpressao === NULL){
                $this->novoPedido($pedido);
            }
        }
    }
    public function novoPedido($pedido)
    {
        $lista = MyHelpers::formatarImpressao($pedido);
        
        $this->dispatchBrowserEvent('pedidoAdicionado',['arrayPedido' => [
            'numero'=>$lista['id'],
            'nome' => $lista['nome'],
            'data' => $lista['data'],
            'whatsapp' => $lista['whatsapp'],
            'endereco'=>$lista['endereco'],
            'retirar_entregar'=>$lista['retirar_entregar'],
            'ListaPedido'=>$lista['ListaPedido'],
            'ValorTotalcomFrete'=>$lista['ValorTotalcomFrete'],
            'FormaPgto'=> $lista['FormaPgto'],
            'troco' => $lista['troco'],
            ]]);
    }

    public function impressaoOK(Int $pedidoID)
    {
       $y = Pedido::where('id',$pedidoID)->update(['situacaoImpressao'=> 1 ]);
    }

    public function mudarSituacaoPedido(Int $pedidoID, Int $situacao)
    {

        $pedido = Pedido::with('usuarios_pedidos')->where('id', $pedidoID)->firstOrFail();


        //se pedido cancelado nao pode mudar
        if($pedido->situacao === 3){
            exit;
        }
        
        if($situacao > $pedido->situacao || $pedido->situacao === NULL){
            Pedido::where('id',$pedidoID)->update(['situacao'=>$situacao]);
            if(isset($pedido->usuarios_pedidos->id_bot)){
                Poster::mudarSituacao(
                    $pedido->usuarios_pedidos->nome ?? $pedido->usuarios_pedidos->nome_completo,
                    $situacao,
                    $pedido->usuarios_pedidos->id_bot,
                    $pedido->retirar_entregar
                );
            }
        }
      
    }
    public function qualEndereco($pedido)
    {
        if($pedido->endereco){
            $endereco = $pedido->endereco;
        }else $endereco =$pedido->usuarios_pedidos->endereco;
        return $endereco;
    }
}
